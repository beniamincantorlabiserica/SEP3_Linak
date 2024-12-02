<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DeskUsageLog;
use App\Models\UserNotification;
use Carbon\Carbon;

class DeskController extends Controller
{
    protected $deskId;
    protected $heightRanges = [
        152 => ['sitting' => 57, 'standing' => 93],
        155 => ['sitting' => 58, 'standing' => 94],
        157 => ['sitting' => 58, 'standing' => 95],
        160 => ['sitting' => 60, 'standing' => 97],
        163 => ['sitting' => 61, 'standing' => 99],
        165 => ['sitting' => 62, 'standing' => 100],
        168 => ['sitting' => 64, 'standing' => 103],
        170 => ['sitting' => 64, 'standing' => 104],
        173 => ['sitting' => 65, 'standing' => 105],
        175 => ['sitting' => 66, 'standing' => 108],
        178 => ['sitting' => 67, 'standing' => 109],
        180 => ['sitting' => 69, 'standing' => 111],
        183 => ['sitting' => 69, 'standing' => 112],
        185 => ['sitting' => 70, 'standing' => 113],
        188 => ['sitting' => 71, 'standing' => 113],
        191 => ['sitting' => 72, 'standing' => 117],
        193 => ['sitting' => 72, 'standing' => 119],
        196 => ['sitting' => 74, 'standing' => 121],
    ];

    public function __construct()
    {
        $this->deskId = DB::table('desk_users')
            ->where('user_id', Auth::id())
            ->value('desk_id');
    }

    public function index()
    {
        $deskData = null;
        if ($this->deskId) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, env('DESK_API_ENDPOINT') . "/E9Y2LxT4g1hQZ7aD8nR3mWx5P0qK6pV7/desks/{$this->deskId}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $deskData = json_decode($response, true);
            curl_close($ch);
        }

        $savedPositions = DB::table('desk_positions')
            ->where('user_id', Auth::id())
            ->get();
        
        return view('dashboard')->with([
            'deskId' => $this->deskId,
            'deskData' => $deskData,
            'savedPositions' => $savedPositions
        ]);
    }

    protected function determinePositionType($height_cm)
    {
        $userHeight = Auth::user()->height ?? 170; // Default height if not set
        
        // Find the closest height in our range
        $closest = array_reduce(array_keys($this->heightRanges), function($a, $b) use ($userHeight) {
            return abs($b - $userHeight) < abs($a - $userHeight) ? $b : $a;
        }, array_key_first($this->heightRanges));
        
        $ranges = $this->heightRanges[$closest];
        
        // Determine if it's sitting or standing
        $midPoint = ($ranges['sitting'] + $ranges['standing']) / 2;
        return $height_cm < $midPoint ? 'sitting' : 'standing';
    }

    public function savePosition(Request $request)
    {
        $positionType = $this->determinePositionType($request->position_height);
        
        DB::table('desk_positions')->insert([
            'user_id' => Auth::id(),
            'desk_id' => $this->deskId,
            'name' => $request->position_name,
            'position_mm' => $request->position_height,
            'position_type' => $positionType,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Position saved successfully');
    }

    public function deletePosition($id)
    {
        DB::table('desk_positions')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json(['success' => true]);
    }

    public function getSavedPositions()
    {
        $positions = DB::table('desk_positions')
            ->where('user_id', Auth::id())
            ->get();
            
        return response()->json($positions);
    }
    protected function logPositionChange($position_mm)
    {
        // End the previous log entry
        DeskUsageLog::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->update(['ended_at' => now()]);

        // Create new log entry
        DeskUsageLog::create([
            'user_id' => Auth::id(),
            'desk_id' => $this->deskId,
            'position_mm' => $position_mm,
            'position_type' => $this->determinePositionType($position_mm / 10), // Convert mm to cm for position type
            'started_at' => now(),
        ]);
    }

    public function getUsageStats()
{
    try {
        $now = Carbon::now();
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();

        // Get current session
        $currentSession = DeskUsageLog::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->first();

        $currentDuration = $currentSession 
            ? (int)$currentSession->started_at->diffInMinutes($now) 
            : 0;

        // Calculate ratios for different periods
        $calculateRatio = function($sittingMinutes, $standingMinutes) {
            $total = $sittingMinutes + $standingMinutes;
            if ($total === 0) return [0, 0];
            
            return [
                round(($sittingMinutes / $total) * 100, 1),
                round(($standingMinutes / $total) * 100, 1)
            ];
        };

        // Get period statistics
        $periods = [
            'daily' => $this->getPeriodStats($today, $now),
            'weekly' => $this->getPeriodStats($weekStart, $now),
            'monthly' => $this->getPeriodStats($monthStart, $now)
        ];

        // Prepare stats array with both usage and ratio data
        $stats = [
            'today' => [
                'sitting_minutes' => (int)$periods['daily']['sitting_minutes'],
                'standing_minutes' => (int)$periods['daily']['standing_minutes'],
                'total_minutes' => (int)($periods['daily']['sitting_minutes'] + $periods['daily']['standing_minutes'])
            ],
            'current_session' => [
                'duration' => $currentDuration,
                'position' => $currentSession ? $currentSession->position_type : null
            ],
            'position_changes' => DeskUsageLog::where('user_id', Auth::id())
                ->where('started_at', '>=', $today)
                ->count() - 1,
            'periods' => []
        ];

        // Add ratio data for each period
        foreach ($periods as $period => $data) {
            [$sittingPercent, $standingPercent] = $calculateRatio(
                $data['sitting_minutes'], 
                $data['standing_minutes']
            );
            
            $stats['periods'][$period] = [
                'sitting_minutes' => (int)$data['sitting_minutes'],
                'standing_minutes' => (int)$data['standing_minutes'],
                'total_minutes' => (int)($data['sitting_minutes'] + $data['standing_minutes']),
                'ratio' => [
                    'sitting' => $sittingPercent,
                    'standing' => $standingPercent,
                    'target' => 50
                ]
            ];
        }

        return response()->json($stats);
    } catch (\Exception $e) {
        \Log::error('Error in getUsageStats: ' . $e->getMessage());
        return response()->json(['error' => 'Internal server error'], 500);
    }
}

private function getPeriodStats($startDate, $endDate)
{
    $logs = DeskUsageLog::where('user_id', Auth::id())
        ->where('started_at', '>=', $startDate)
        ->where('started_at', '<=', $endDate)
        ->get();

    $sittingMinutes = 0;
    $standingMinutes = 0;

    foreach ($logs as $log) {
        $endTime = $log->ended_at ?? Carbon::now();
        $duration = $log->started_at->diffInMinutes($endTime);

        if ($log->position_type === 'sitting') {
            $sittingMinutes += $duration;
        } else {
            $standingMinutes += $duration;
        }
    }

    return [
        'sitting_minutes' => $sittingMinutes,
        'standing_minutes' => $standingMinutes
    ];
}

    private function calculateStats($startDate, $endDate)
    {
        $logs = DeskUsageLog::where('user_id', Auth::id())
            ->where('started_at', '>=', $startDate)
            ->where('started_at', '<=', $endDate)
            ->get();

        $sittingMinutes = 0;
        $standingMinutes = 0;

        foreach ($logs as $log) {
            $endTime = $log->ended_at ?? Carbon::now();
            $duration = $log->started_at->diffInMinutes($endTime);

            if ($log->position_type === 'sitting') {
                $sittingMinutes += $duration;
            } else {
                $standingMinutes += $duration;
            }
        }

        return [
            'sitting_minutes' => $sittingMinutes,
            'standing_minutes' => $standingMinutes,
            'total_minutes' => $sittingMinutes + $standingMinutes,
        ];
    }

    public function setPosition(Request $request)
    {
        if ($this->deskId) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, env('DESK_API_ENDPOINT') . "/E9Y2LxT4g1hQZ7aD8nR3mWx5P0qK6pV7/desks/{$this->deskId}/state");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['position_mm' => $request->height_cm * 10]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);

            // Log the position change
            $this->logPositionChange($request->height_cm * 10);
        }
        
        return back()->with('success', 'Desk position updated');
    }

    public function checkNotification()
{
    $user = auth()->user();
    $lastLog = DeskUsageLog::where('user_id', $user->id)
        ->whereNull('ended_at')
        ->first();

    if (!$lastLog) {
        return response()->json(['shouldNotify' => false]);
    }

    $duration = now()->diffInMinutes($lastLog->started_at);
    
    if ($duration >= 60) {
        $newPosition = $lastLog->position_type === 'sitting' ? 'standing' : 'sitting';
        
        // Get saved position if exists
        $savedPosition = DeskPosition::where('user_id', $user->id)
            ->where('position_type', $newPosition)
            ->first();

        // Create notification record
        $notification = UserNotification::create([
            'user_id' => $user->id,
            'type' => 'position_change',
            'message' => "Time to change to $newPosition position",
            'position_type' => $newPosition,
            'status' => 'pending'
        ]);

        return response()->json([
            'shouldNotify' => true,
            'notification_id' => $notification->id,
            'type' => $newPosition,
            'savedPosition' => $savedPosition
        ]);
    }

    return response()->json(['shouldNotify' => false]);
}

public function handleNotification(Request $request)
{
    $user = auth()->user();
    
    if ($request->notification_id === 'test') {
        $notification = UserNotification::create([
            'user_id' => $user->id,
            'type' => 'position_change',
            'message' => 'Test position change',
            'position_type' => $request->accepted ? 'standing' : 'sitting',
            'status' => $request->accepted ? 'accepted' : 'declined',
            'points' => $request->accepted ? 10 : 0
        ]);
    } else {
        $notification = UserNotification::findOrFail($request->notification_id);
        $notification->update([
            'status' => $request->accepted ? 'accepted' : 'declined',
            'points' => $request->accepted ? 10 : 0
        ]);
    }

    if ($request->accepted && $request->height && $this->deskId) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('DESK_API_ENDPOINT') . "/E9Y2LxT4g1hQZ7aD8nR3mWx5P0qK6pV7/desks/{$this->deskId}/state");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['position_mm' => intval($request->height) * 10]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            $this->logPositionChange($request->height * 10);
        }
    }

    return response()->json([
        'success' => true,
        'points' => $notification->points,
        'message' => $request->accepted ? 
            'Position change accepted! +10 points' : 
            'Position change declined'
    ]);
}
}