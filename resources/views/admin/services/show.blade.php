@extends('layouts.admin')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold font-poppins text-gray-900">{{ $service->name }}</h1>
        <p class="text-gray-600 mt-1">View service details and information</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.services.edit', $service->id) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-colors duration-200">
            <i class="fas fa-edit mr-2"></i>
            Edit Service
        </a>
        <a href="{{ route('admin.services') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Services
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Service Details Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-8">
        <div class="px-8 py-6 bg-gradient-to-r from-indigo-500 to-purple-600">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-info-circle mr-3"></i>
                Service Details
            </h2>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Service Icon and Name -->
                    <div class="flex items-start">
                        <div class="h-16 w-16 bg-primary/10 rounded-xl flex items-center justify-center mr-4">
                            <i class="{{ $service->icon ?? 'fas fa-concierge-bell' }} text-primary text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h3>
                            @if(isset($service->type))
                                <p class="text-gray-500">{{ $service->type }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-align-left mr-2 text-indigo-500"></i>
                            Description
                        </h4>
                        <p class="text-gray-600">{{ $service->description }}</p>
                    </div>

                    <!-- Service Types & Pricing -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-peso-sign mr-2 text-indigo-500"></i>
                            @if(!empty($service->types) && count($service->types) > 0)
                                Service Types & Pricing
                            @else
                                Price
                            @endif
                        </h4>
                        @if(!empty($service->types) && count($service->types) > 0)
                            <div class="space-y-3">
                                @foreach($service->types as $type)
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-gray-800">{{ $type['name'] }}</span>
                                            @if(isset($type['price']) && $type['price'] > 0)
                                                <span class="text-lg font-bold text-primary">₱{{ number_format($type['price'], 2) }}</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Free</span>
                                            @endif
                                        </div>
                                        @if(isset($type['duration_minutes']) && $type['duration_minutes'] > 0)
                                            <div class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $type['duration_minutes'] }} minutes
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            @if(isset($service->price))
                                @if($service->price > 0)
                                    <span class="text-2xl font-bold text-primary">{{ $service->formatted_price ?? ('₱' . number_format($service->price, 2)) }}</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">Free of Charge</span>
                                @endif
                            @else
                                <span class="text-gray-400">Not set</span>
                            @endif
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
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Schedule Information -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-indigo-500"></i>
                            @if(!empty($service->types) && count($service->types) > 0)
                                Service Type Schedules
                            @else
                                Schedule
                            @endif
                        </h4>
                        @if(!empty($service->types) && count($service->types) > 0)
                            <div class="space-y-3">
                                @foreach($service->types as $type)
                                    @if(isset($type['schedule']) && !empty($type['schedule']))
                                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                            <div class="font-medium text-gray-800 mb-2">{{ $type['name'] }}</div>
                                            @if(isset($type['schedule']['preset']) && $type['schedule']['preset'] !== 'custom')
                                                <div class="text-sm text-gray-600">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $type['schedule']['preset'])) }}
                                                </div>
                                            @endif
                                            @if(isset($type['schedule']['days']) && is_array($type['schedule']['days']) && count($type['schedule']['days']) > 0)
                                                <div class="flex flex-wrap gap-1 mt-2">
                                                    @foreach($type['schedule']['days'] as $day)
                                                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded text-xs font-medium">
                                                            {{ ucfirst($day) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if(isset($type['schedule']['primary_time']) && $type['schedule']['primary_time'])
                                                <div class="text-sm text-gray-600 mt-1">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Primary: {{ \Carbon\Carbon::createFromFormat('H:i', $type['schedule']['primary_time'])->format('g:i A') }}
                                                </div>
                                            @endif
                                            @if(isset($type['schedule']['additional_times']) && is_array($type['schedule']['additional_times']) && count($type['schedule']['additional_times']) > 0)
                                                <div class="flex flex-wrap gap-1 mt-2">
                                                    <span class="text-xs text-gray-500 w-full mb-1">Additional Times:</span>
                                                    @foreach($type['schedule']['additional_times'] as $time)
                                                        @if($time)
                                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                                                                {{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('g:i A') }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if(isset($type['schedule']['description']) && $type['schedule']['description'])
                                                <div class="text-sm text-gray-600 mt-2 italic">
                                                    "{{ $type['schedule']['description'] }}"
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                            <div class="font-medium text-gray-800 mb-1">{{ $type['name'] }}</div>
                                            <div class="text-sm text-gray-500">No schedule configured</div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @elseif($service->schedules)
                            <div class="text-gray-600">
                                {{ $service->formatted_schedule }}
                            </div>
                            @if($service->schedule_days && count($service->schedule_days) > 0)
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($service->schedule_days as $day)
                                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-lg text-xs font-medium">
                                            {{ ucfirst($day) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <span class="text-gray-400">No schedule information</span>
                        @endif
                    </div>

                    <!-- Service Time -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-clock mr-2 text-indigo-500"></i>
                            Primary Service Time
                        </h4>
                        @if($service->primary_time)
                            <div class="text-gray-600">
                                {{ \Carbon\Carbon::createFromFormat('H:i', $service->primary_time)->format('g:i A') }}
                            </div>
                        @else
                            <span class="text-gray-400">No time specified</span>
                        @endif
                    </div>

                    <!-- Additional Times (if applicable) -->
                    @if($service->schedules && isset($service->schedules['additional_times']) && count($service->schedules['additional_times']) > 0)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-clock mr-2 text-indigo-500"></i>
                            Additional Times
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($service->schedules['additional_times'] as $time)
                                @if($time)
                                    <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-lg text-xs font-medium">
                                        {{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('g:i A') }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Created & Updated -->
                    <div class="pt-4 border-t border-gray-100">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 mb-1">Created</h4>
                                <p class="text-sm text-gray-600">{{ $service->created_at->format('M d, Y g:i A') }}</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 mb-1">Last Updated</h4>
                                <p class="text-sm text-gray-600">{{ $service->updated_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Statistics Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-8">
        <div class="px-8 py-6 bg-gradient-to-r from-blue-500 to-cyan-600">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-chart-line mr-3"></i>
                Booking Statistics
            </h2>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-blue-50 rounded-xl p-4 text-center">
                    <h3 class="text-sm font-semibold text-blue-700 mb-1">Total Bookings</h3>
                    <p class="text-3xl font-bold text-blue-800">{{ $service->bookings_count ?? 0 }}</p>
                </div>
                
                <div class="bg-green-50 rounded-xl p-4 text-center">
                    <h3 class="text-sm font-semibold text-green-700 mb-1">Completed</h3>
                    <p class="text-3xl font-bold text-green-800">{{ $service->completed_bookings_count ?? 0 }}</p>
                </div>
                
                <div class="bg-amber-50 rounded-xl p-4 text-center">
                    <h3 class="text-sm font-semibold text-amber-700 mb-1">Upcoming</h3>
                    <p class="text-3xl font-bold text-amber-800">{{ $service->upcoming_bookings_count ?? 0 }}</p>
                </div>
            </div>
            
            <div class="mt-6 text-center">
                <a href="" class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-calendar-check mr-2"></i>
                    View All Bookings for this Service
                </a>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between">
        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service? This action cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-trash-alt mr-2"></i>
                Delete Service
            </button>
        </form>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.services.edit', $service->id) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i>
                Edit Service
            </a>
            <a href="{{ route('admin.services') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Services
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Any additional JavaScript can go here
</script>
@endsection