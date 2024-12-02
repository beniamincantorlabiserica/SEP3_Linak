<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DeskUsageLog;
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
}