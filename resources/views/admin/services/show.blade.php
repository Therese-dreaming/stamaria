@extends('layouts.admin')

@section('title', 'Service Details')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Service Details</h1>
            <p class="text-gray-600 mt-2">View and manage service information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.services.form-fields', $service) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-list-alt mr-2"></i>Form Fields
            </a>
            <a href="{{ route('admin.services.edit', $service->id) }}" 
               class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Service
            </a>
            <a href="{{ route('admin.services') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Services
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Service Overview Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-primary/10 rounded-xl flex items-center justify-center mr-4">
                    <i class="{{ $service->icon ?? 'fas fa-concierge-bell' }} text-primary text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h2>
                    <p class="text-gray-500">Service ID: {{ $service->id }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                @if($service->is_active)
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>Active
                    </span>
                @else
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                        <i class="fas fa-times-circle mr-1"></i>Inactive
                    </span>
                @endif
            </div>
        </div>

        <!-- Service Information Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Description -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-align-left mr-2 text-indigo-500"></i>
                        Description
                    </h4>
                    <p class="text-gray-600">{{ $service->description }}</p>
                </div>

                <!-- Price -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-peso-sign mr-2 text-indigo-500"></i>
                        Price
                    </h4>
                    @if(isset($service->price))
                        @if($service->price > 0)
                            <span class="text-2xl font-bold text-primary">{{ $service->formatted_price ?? ('₱' . number_format($service->price, 2)) }}</span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">Free of Charge</span>
                        @endif
                    @else
                        <span class="text-gray-400">Not set</span>
                    @endif
                </div>

                <!-- Duration -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-clock mr-2 text-indigo-500"></i>
                        Duration
                    </h4>
                    @if(isset($service->duration_minutes) && $service->duration_minutes > 0)
                        <span class="text-gray-600">{{ $service->formatted_duration }}</span>
                    @else
                        <span class="text-gray-400">Not specified</span>
                    @endif
                </div>

                <!-- Slots -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-users mr-2 text-indigo-500"></i>
                        Available Slots
                    </h4>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-users mr-1"></i>{{ $service->slots ?? 1 }} slot{{ $service->slots != 1 ? 's' : '' }} per time slot
                    </span>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Schedule Information -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-indigo-500"></i>
                        Schedule
                    </h4>
                    @if($service->schedules && count($service->schedules) > 0)
                        @foreach($service->schedules as $schedule)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-4">
                                @if(isset($schedule['preset']) && $schedule['preset'] !== 'custom')
                                    <div class="text-sm text-gray-600 mb-2">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $schedule['preset'])) }}
                                    </div>
                                @endif
                                
                                @if(isset($schedule['days']) && is_array($schedule['days']) && count($schedule['days']) > 0)
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        @foreach($schedule['days'] as $day)
                                            <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded text-xs font-medium">
                                                {{ ucfirst($day) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                @if(isset($schedule['primary_time']))
                                    <div class="text-sm text-gray-600 mb-2">
                                        <i class="fas fa-clock mr-1"></i>
                                        Primary Time: {{ $schedule['primary_time'] }}
                                    </div>
                                @endif
                                
                                @if(isset($schedule['additional_times']) && count($schedule['additional_times']) > 0)
                                    <div class="text-sm text-gray-600 mb-2">
                                        <i class="fas fa-plus mr-1"></i>
                                        Additional Times: {{ implode(', ', $schedule['additional_times']) }}
                                    </div>
                                @endif

                                <!-- Day-Specific Time Slots -->
                                @if(isset($schedule['day_specific_times']) && !empty($schedule['day_specific_times']))
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <h5 class="text-xs font-semibold text-gray-700 mb-2">Day-Specific Time Slots:</h5>
                                        <div class="space-y-2">
                                            @foreach($schedule['day_specific_times'] as $day => $times)
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs font-medium text-gray-600 capitalize">{{ $day }}:</span>
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($times as $time)
                                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">
                                                                {{ $time }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                @if(isset($schedule['description']))
                                    <div class="text-sm text-gray-600 mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ $schedule['description'] }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <span class="text-gray-400">No schedule information available</span>
                    @endif
                </div>

                <!-- Requirements -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-list-check mr-2 text-indigo-500"></i>
                        Requirements
                    </h4>
                    @if($service->requirements)
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="text-sm text-gray-600 whitespace-pre-line">{{ $service->requirements }}</div>
                        </div>
                    @else
                        <span class="text-gray-400">No requirements specified</span>
                    @endif
                </div>

                <!-- Additional Notes -->
                @if($service->additional_notes)
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-sticky-note mr-2 text-indigo-500"></i>
                        Additional Notes
                    </h4>
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <div class="text-sm text-gray-600">{{ $service->additional_notes }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Service Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-900">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Available Slots</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $service->slots ?? 1 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-star text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Rating</p>
                    <p class="text-2xl font-bold text-gray-900">N/A</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.services.edit', $service->id) }}" 
               class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Service
            </a>
            
            @if($service->is_active)
                <form action="{{ route('admin.services.update', $service->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="is_active" value="0">
                    <button type="submit" 
                            class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-pause mr-2"></i>Deactivate
                    </button>
                </form>
            @else
                <form action="{{ route('admin.services.update', $service->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="is_active" value="1">
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-play mr-2"></i>Activate
                    </button>
                </form>
            @endif
            
            <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this service? This action cannot be undone.');" 
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-trash mr-2"></i>Delete Service
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Any additional JavaScript can go here
</script>
@endsection