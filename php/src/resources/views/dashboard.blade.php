<!-- resources/views/dashboard.blade.php -->
<x-app-layout>
    <!-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> -->

       <!-- Add this section to your dashboard.blade.php -->

       <div class="card bg-base-100 shadow-xl col-span-2">
       <!-- Add to dashboard.blade.php -->
<!-- Replace the existing notification modal in dashboard.blade.php -->
<dialog id="notification-modal" class="modal">
    <div class="modal-box max-w-2xl">
        <div class="flex items-center gap-4 mb-6">
            <div id="notification-icon" class="bg-primary/10 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" id="position-icon" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-lg" id="notification-title"></h3>
                <p class="text-sm opacity-75" id="notification-description"></p>
            </div>
        </div>

        <div id="positions-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4 my-6">
            <!-- Positions will be populated here -->
        </div>

        <div class="modal-action mt-6 border-t pt-4">
            <button onclick="handleNotification(false)" class="btn btn-ghost">
                Dismiss
            </button>
            <button onclick="handleNotification(true)" class="btn btn-primary" id="accept-btn">
                Accept
            </button>
        </div>
    </div>
</dialog>

<!-- Test buttons for development -->
<div class="flex gap-4 mt-4">
    <button onclick="testPosition('sitting')" class="btn btn-primary">
        Test Sitting Notification
    </button>
    <button onclick="testPosition('standing')" class="btn btn-primary">
        Test Standing Notification
    </button>
</div>



    <div class="card-body">
        <h2 class="card-title">Desk Usage Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4" id="usage-stats">
            <!-- Stats will be populated via JavaScript -->
        </div>
    </div>
</div>

<div class="card bg-base-100 shadow-xl col-span-2">
    <div class="card-body">
        <h2 class="card-title mb-4">Standing/Sitting Ratio Analysis</h2>
        <div class="tabs tabs-boxed mb-4">
            <a class="tab tab-active" onclick="switchPeriod('daily')" id="tab-daily">Daily</a>
            <a class="tab" onclick="switchPeriod('weekly')" id="tab-weekly">Weekly</a>
            <a class="tab" onclick="switchPeriod('monthly')" id="tab-monthly">Monthly</a>
        </div>
        <div class="h-64">
            <canvas id="ratioChart"></canvas>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-4">
            <div class="stat bg-base-200 rounded-box p-4">
                <div class="stat-title">Current Ratio</div>
                <div id="current-ratio" class="stat-value text-lg">Loading...</div>
            </div>
            <div class="stat bg-base-200 rounded-box p-4">
                <div class="stat-title">Target Ratio</div>
                <div class="stat-value text-lg">50% / 50%</div>
            </div>
        </div>
    </div>
</div>

    <div class="py-12">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if($deskId && $deskData)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Configuration Card -->
                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body">
                                <h2 class="card-title">Configuration</h2>
                                <p>Name: {{ $deskData['config']['name'] }}</p>
                                <p>Manufacturer: {{ $deskData['config']['manufacturer'] }}</p>
                            </div>
                        </div>

                        <!-- Current State Card -->
                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body">
                                <h2 class="card-title">Current State</h2>
                                <p>Position: {{ $deskData['state']['position_mm'] }}mm</p>
                                <p>Speed: {{ $deskData['state']['speed_mms'] }}mm/s</p>
                                <p>Status: {{ $deskData['state']['status'] }}</p>
                                @if($deskData['state']['isPositionLost'] || 
                                   $deskData['state']['isOverloadProtectionUp'] || 
                                   $deskData['state']['isOverloadProtectionDown'] || 
                                   $deskData['state']['isAntiCollision'])
                                    <div class="mt-2">
                                        @if($deskData['state']['isPositionLost'])
                                            <span class="badge badge-warning">Position Lost</span>
                                        @endif
                                        @if($deskData['state']['isOverloadProtectionUp'])
                                            <span class="badge badge-error">Overload Up</span>
                                        @endif
                                        @if($deskData['state']['isOverloadProtectionDown'])
                                            <span class="badge badge-error">Overload Down</span>
                                        @endif
                                        @if($deskData['state']['isAntiCollision'])
                                            <span class="badge badge-warning">Anti-Collision</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Usage Statistics Card -->
                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body">
                                <h2 class="card-title">Usage Statistics</h2>
                                <p>Total Activations: {{ $deskData['usage']['activationsCounter'] }}</p>
                                <p>Sit/Stand Changes: {{ $deskData['usage']['sitStandCounter'] }}</p>
                            </div>
                        </div>

                        <!-- Last Error Card -->
                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body">
                                <h2 class="card-title">Last Error</h2>
                                @if(!empty($deskData['lastErrors']))
                                    <p>Time: {{ $deskData['lastErrors'][0]['time_s'] }}s ago</p>
                                    <p>Error Code: {{ $deskData['lastErrors'][0]['errorCode'] }}</p>
                                @else
                                    <p>No recent errors</p>
                                @endif
                            </div>
                        </div>

                        <!-- Favorite Positions Card -->
                        <div class="card bg-base-100 shadow-xl col-span-2">
                            <div class="card-body">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="card-title">Favorite Positions</h2>
                                    <button onclick="document.getElementById('add-position-modal').showModal()" 
                                            class="btn btn-primary btn-sm">
                                        Add Position
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
    @foreach($savedPositions as $position)
        <div class="card bg-base-200">
            <div class="card-body p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold">{{ $position->name }}</h3>
                        <p>{{ $position->position_mm }} cm</p>
                        <span class="badge {{ $position->position_type === 'sitting' ? 'badge-primary' : 'badge-secondary' }}">
                            {{ ucfirst($position->position_type) }}
                        </span>
                    </div>
                    <button 
                        onclick="deletePosition({{ $position->id }})" 
                        class="btn btn-square btn-ghost btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('desk.position') }}" class="mt-2">
                    @csrf
                    <input type="hidden" name="height_cm" value="{{ $position->position_mm }}">
                    <button type="submit" class="btn btn-primary btn-sm w-full">
                        Move to Position
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Position Modal -->
                    <dialog id="add-position-modal" class="modal">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg">Add New Position</h3>
                            <form method="POST" action="{{ route('desk.save-position') }}" class="mt-4">
                                @csrf
                                <div class="form-control">
                                    <label class="label">Position Name</label>
                                    <input type="text" name="position_name" class="input input-bordered" required>
                                </div>
                                <div class="form-control mt-4">
                                    <label class="label">Height (cm)</label>
                                    <input type="number" name="position_height" class="input input-bordered" required>
                                </div>
                                <div class="modal-action">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="button" 
                                            onclick="document.getElementById('add-position-modal').close()" 
                                            class="btn">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </dialog>
                @else
                    <div class="alert alert-warning">
                        No desk assigned to your account.
                    </div>
                    <div>

                    {{ $deskId}}
                    
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
function deletePosition(id) {
    if (confirm('Are you sure you want to delete this position?')) {
        fetch(`/api/desk-positions/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }
}
function formatDuration(minutes) {
    if (!minutes) return '0h 0m';
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return `${hours}h ${mins}m`;
}

let ratioChart = null;
let currentPeriod = 'daily';

function updateUsageStats() {
    fetch('/api/desk-usage-stats')
        .then(response => response.json())
        .then(data => {
            // Update usage statistics
            const statsHtml = `
                <div class="stat bg-base-200 rounded-box p-4">
                    <div class="stat-title">Today's Usage</div>
                    <div class="stat-value text-2xl">${formatDuration(data.today.total_minutes)}</div>
                    <div class="stat-desc mt-2">
                        <div class="text-sm">Sitting: ${formatDuration(data.today.sitting_minutes)}</div>
                        <div class="text-sm">Standing: ${formatDuration(data.today.standing_minutes)}</div>
                    </div>
                </div>
                <div class="stat bg-base-200 rounded-box p-4">
                    <div class="stat-title">Current Session</div>
                    <div class="stat-value text-2xl">${formatDuration(data.current_session.duration)}</div>
                    <div class="stat-desc mt-2">
                        <div class="text-sm">Position: ${data.current_session.position || 'N/A'}</div>
                    </div>
                </div>
                <div class="stat bg-base-200 rounded-box p-4">
                    <div class="stat-title">Position Changes</div>
                    <div class="stat-value text-2xl">${data.position_changes}</div>
                    <div class="stat-desc mt-2">
                        <div class="text-sm">Today's changes</div>
                    </div>
                </div>
            `;
            
            const statsContainer = document.getElementById('usage-stats');
            if (statsContainer) {
                statsContainer.innerHTML = statsHtml;
            }

            // Update ratio chart
            const periodData = data.periods[currentPeriod];
            if (periodData && ratioChart) {
                ratioChart.data.datasets[0].data = [periodData.ratio.standing, 50];
                ratioChart.data.datasets[1].data = [periodData.ratio.sitting, 50];
                ratioChart.update();

                // Update current ratio display
                document.getElementById('current-ratio').textContent = 
                    `${periodData.ratio.standing.toFixed(1)}% / ${periodData.ratio.sitting.toFixed(1)}%`;
            }
        })
        .catch(error => {
            console.error('Error fetching stats:', error);
            const statsContainer = document.getElementById('usage-stats');
            if (statsContainer) {
                statsContainer.innerHTML = `
                    <div class="alert alert-error col-span-3">
                        <div class="flex items-center">
                            <span>Error loading statistics: ${error.message}</span>
                        </div>
                    </div>
                `;
            }
        });
}

function switchPeriod(period) {
    currentPeriod = period;
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('tab-active'));
    document.getElementById(`tab-${period}`).classList.add('tab-active');
    updateUsageStats();
}

// Initial load
document.addEventListener('DOMContentLoaded', () => {
    updateUsageStats();
    initializeRatioChart();
});

function initializeRatioChart() {
    const ctx = document.getElementById('ratioChart').getContext('2d');
    ratioChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Current', 'Target'],
            datasets: [
                {
                    label: 'Standing',
                    data: [0, 50],
                    backgroundColor: 'rgb(34, 197, 94)',
                    stack: 'Stack 0',
                },
                {
                    label: 'Sitting',
                    data: [0, 50],
                    backgroundColor: 'rgb(59, 130, 246)',
                    stack: 'Stack 0',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    stacked: true,
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                x: {
                    stacked: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw.toFixed(1) + '%';
                        }
                    }
                }
            }
        }
    });
}

// Update every minute
setInterval(updateUsageStats, 60000);

// ------------------------------------------------------------------------

let currentNotification = null;
let selectedPosition = null;

function showNotification() {
    const modal = document.getElementById('notification-modal');
    const title = document.getElementById('notification-title');
    const description = document.getElementById('notification-description');
    const positionsContainer = document.getElementById('positions-container');
    const acceptBtn = document.getElementById('accept-btn');
    const positionIcon = document.getElementById('position-icon');

    const positionType = currentNotification.type;
    
    // Update icon based on position type
    positionIcon.innerHTML = positionType === 'standing' 
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>` 
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>`;

    title.textContent = `Time to ${positionType === 'standing' ? 'Stand Up' : 'Sit Down'}`;
    description.textContent = `You've been ${positionType === 'standing' ? 'sitting' : 'standing'} for over an hour.`;

    // Fetch and display all available positions
    fetch('/desk-positions')
        .then(response => response.json())
        .then(positions => {
            const filteredPositions = positions.filter(p => p.position_type === positionType);
            
            if (filteredPositions.length === 0) {
                positionsContainer.innerHTML = `
                    <div class="col-span-2 text-center py-4 bg-base-200 rounded-lg">
                        <p class="text-sm opacity-75">No saved ${positionType} positions found.</p>
                        <button onclick="document.getElementById('add-position-modal').showModal()" 
                                class="btn btn-sm btn-ghost mt-2">
                            Add New Position
                        </button>
                    </div>`;
                document.getElementById('accept-btn').disabled = true;
                return;
            }

            positionsContainer.innerHTML = filteredPositions.map(position => `
                <button 
                    onclick="selectPosition(${position.id}, ${position.position_mm})"
                    class="btn btn-outline position-btn w-full justify-start font-normal normal-case"
                    id="position-${position.id}"
                >
                    <div class="flex flex-col items-start">
                        <span class="font-medium">${position.name}</span>
                        <span class="text-sm opacity-75">Height: ${position.position_mm/10} cm</span>
                    </div>
                </button>
            `).join('');

            // Select first position by default
            if (filteredPositions.length > 0) {
                const firstPosition = filteredPositions[0];
                selectPosition(firstPosition.id, firstPosition.position_mm);
            }
        });
    modal.showModal();
}

function selectPosition(id, height) {
    selectedPosition = { id, height };
    
    // Remove active class from all position buttons
    document.querySelectorAll('.position-btn').forEach(btn => {
        btn.classList.remove('btn-active', 'btn-primary');
        btn.classList.add('btn-outline');
    });
    
    // Add active class to selected button
    const selectedBtn = document.getElementById(`position-${id}`);
    if (selectedBtn) {
        selectedBtn.classList.remove('btn-outline');
        selectedBtn.classList.add('btn-active', 'btn-primary');
    }
    
    document.getElementById('accept-btn').disabled = false;
}

async function handleNotification(accepted) {
    try {
        const response = await fetch('/desk-notifications/respond', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                notification_id: currentNotification.notification_id,
                accepted,
                height: accepted ? selectedPosition.height : null
            })
        });

        const data = await response.json();

        // Show toast notification
        const toast = document.createElement('div');
        toast.className = `alert ${accepted ? 'alert-success' : 'alert-info'} fixed bottom-4 right-4 w-auto max-w-sm shadow-lg`;
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                ${accepted ? 
                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' : 
                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'}
                <span>${data.message}</span>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
        
        document.getElementById('notification-modal').close();
        currentNotification = null;
        selectedPosition = null;
    } catch (error) {
        console.error('Error handling notification:', error);
    }
}

// Test function
async function testPosition(position) {
    console.log('Simulating notification for:', position);
    currentNotification = {
        notification_id: 'test',
        type: position === 'sitting' ? 'standing' : 'sitting'
    };
    showNotification();
}

// Check every minute
checkNotification();
setInterval(checkNotification, 60000);
</script>
</x-app-layout>