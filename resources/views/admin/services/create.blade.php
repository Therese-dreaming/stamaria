@extends('layouts.admin')

@section('title', 'Create Service')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create New Service</h1>
            <p class="text-gray-600 mt-2">Add a new service with schedule and pricing information</p>
        </div>
        <a href="{{ route('admin.services') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Services
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.services.store') }}" method="POST" id="serviceForm" class="space-y-8">
        @csrf
        
        <!-- Basic Service Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Basic Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Service Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Service Name *</label>
                    <input type="text" id="name" name="name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="{{ old('name') }}" placeholder="e.g., Wedding Ceremony">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icon (FontAwesome class)</label>
                    <input type="text" id="icon" name="icon" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="{{ old('icon') }}" placeholder="fas fa-heart">
                    @error('icon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea id="description" name="description" rows="4" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                          placeholder="Describe the service in detail...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Requirements -->
            <div class="mt-6">
                <div class="flex justify-between items-center mb-4">
                    <label class="block text-sm font-medium text-gray-700">Requirements</label>
                    <div class="flex space-x-2">
                        <button type="button" id="addRequirement" 
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                            <i class="fas fa-plus mr-1"></i>Add Requirement
                        </button>
                        <button type="button" id="addConditionalRequirement" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                            <i class="fas fa-question-circle mr-1"></i>Add Conditional Note
                        </button>
                    </div>
                </div>
                
                <div id="requirementsContainer" class="space-y-3">
                    <!-- Requirements will be added dynamically -->
                </div>
                
                <!-- Separator for conditional requirements -->
                <div id="requirementsSeparator" class="hidden my-4 border-t border-gray-200">
                    <div class="flex items-center justify-center">
                        <span class="bg-white px-3 text-xs text-gray-500">Conditional Notes</span>
                    </div>
                </div>
                
                <div class="mt-2 text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Add file upload requirements. All requirements are automatically file uploads. Use conditional notes for special instructions like "Upload Marriage Certificate if parents are married".
                </div>
                
                @error('requirements')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('requirements.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Additional Notes -->
            <div class="mt-6">
                <label for="additional_notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                <textarea id="additional_notes" name="additional_notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                          placeholder="Any additional information...">{{ old('additional_notes') }}</textarea>
                @error('additional_notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Service Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Service Information</h2>
            
            <!-- Price, Duration & Slots -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (₱) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="{{ old('price') }}" placeholder="0.00">
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes) *</label>
                    <input type="number" id="duration_minutes" name="duration_minutes" min="0" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="{{ old('duration_minutes') }}" placeholder="60">
                    @error('duration_minutes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="slots" class="block text-sm font-medium text-gray-700 mb-2">Available Slots *</label>
                    <input type="number" id="slots" name="slots" min="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="{{ old('slots', 1) }}" placeholder="1">
                    <p class="text-xs text-gray-500 mt-1">Number of bookings allowed per time slot</p>
                    @error('slots')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Schedule Section -->
            <h4 class="text-md font-medium text-gray-800 mb-4">Schedule Information</h4>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Preset</label>
                <select name="schedule_preset" id="schedule_preset" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Select a preset...</option>
                    <option value="daily">Daily</option>
                    <option value="weekdays">Weekdays (Mon-Fri)</option>
                    <option value="weekends">Weekends (Sat-Sun)</option>
                    <option value="sundays">Sundays Only</option>
                    <option value="saturdays">Saturdays Only</option>
                    <option value="wed_sat">Wednesdays & Saturdays</option>
                    <option value="custom">Custom Schedule</option>
                </select>
            </div>

            <div class="mb-4" id="primaryServiceTimeSection">
                <label class="block text-sm font-medium text-gray-700 mb-2">Primary Service Time</label>
                <input type="time" name="service_time" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Default time for all days (only shown when no specific schedule is selected)</p>
            </div>

            <div id="customScheduleOptions" class="hidden">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Custom Days</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach(['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                        <label class="flex items-center">
                            <input type="checkbox" name="custom_days[]" value="{{ $day }}" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm capitalize">{{ $day }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Times</label>
                    <div id="additionalTimesContainer" class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <input type="time" name="custom_times[]" 
                                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <button type="button" id="addTimeSlot" class="text-green-600 hover:text-green-800">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Time Setting -->
            <div id="bulkTimeSettingSection" class="mb-4 hidden">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h5 class="text-sm font-medium text-blue-800 mb-3">Bulk Time Setting</h5>
                    <p class="text-xs text-blue-600 mb-3">Set the same time slots for multiple days at once</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-blue-700 mb-2">Apply to:</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="bulk_apply_to" value="all" class="rounded border-blue-300 text-blue-600 focus:ring-blue-600" checked>
                                    <span class="ml-2 text-xs text-blue-700">All days in preset</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="bulk_apply_to" value="selected" class="rounded border-blue-300 text-blue-600 focus:ring-blue-600">
                                    <span class="ml-2 text-xs text-blue-700">Selected days only</span>
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-blue-700 mb-2">Time Slots:</label>
                            <div id="bulkTimeSlotsContainer" class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <input type="time" name="bulk_times[]" 
                                           class="px-2 py-1 border border-blue-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    <button type="button" id="addBulkTimeSlot" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" id="applyBulkTimes" 
                                    class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                Apply to Selected Days
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Day-Specific Time Slots -->
            <div id="daySpecificTimesSection" class="mb-4 hidden">
                <div class="flex justify-between items-center mb-3">
                    <h5 class="text-sm font-medium text-gray-700">Day-Specific Time Slots (Optional)</h5>
                    <button type="button" id="toggleBulkSetting" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                        <i class="fas fa-magic mr-1"></i>Bulk Time Setting
                    </button>
                </div>
                <p class="text-xs text-gray-500 mb-3">Override the primary time for specific days. Leave empty to use the primary time.</p>
                
                <div id="daySpecificTimesContainer" class="space-y-4">
                    <!-- Day-specific time slots will be added dynamically -->
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Description (Optional)</label>
                <textarea name="schedule_description" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                          placeholder="Additional schedule information..."></textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.services') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="bg-primary hover:bg-primary-dark text-white px-6 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-save mr-2"></i>Create Service
            </button>
        </div>
    </form>
</div>

<!-- Requirement Template -->
<template id="requirementTemplate">
    <div class="requirement-item flex items-start space-x-3 p-4 border border-gray-200 rounded-lg bg-gray-50">
        <div class="flex-1">
            <input type="text" name="requirements[]" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                   placeholder="e.g., Valid ID, Birth Certificate, etc.">
            <p class="text-xs text-gray-500 mt-1">This will be a file upload field</p>
        </div>
        <button type="button" class="remove-requirement text-red-500 hover:text-red-700 p-2">
            <i class="fas fa-times"></i>
        </button>
    </div>
</template>

<!-- Conditional Requirement Template -->
<template id="conditionalRequirementTemplate">
    <div class="conditional-requirement-item border border-blue-200 rounded-lg bg-blue-50 p-4 mb-3">
        <div class="flex items-center justify-between mb-3">
            <h6 class="text-sm font-medium text-blue-800 flex items-center">
                <i class="fas fa-question-circle mr-2 text-blue-600"></i>
                Conditional Note
            </h6>
            <button type="button" class="remove-conditional-requirement text-red-500 hover:text-red-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Condition -->
        <div class="mb-3">
            <label class="block text-xs font-medium text-blue-700 mb-1">Condition</label>
            <input type="text" name="requirement_conditions[]" 
                   class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                   placeholder="e.g., If parents are married">
        </div>
        
        <!-- Primary Requirement -->
        <div class="mb-3">
            <label class="block text-xs font-medium text-blue-700 mb-1">File Upload Requirement</label>
            <input type="text" name="requirements[]" 
                   class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                   placeholder="e.g., Marriage Certificate">
        </div>
        
        <!-- Alternative Requirements -->
        <div class="mb-3">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-xs font-medium text-blue-700">Alternative File Uploads</label>
                <button type="button" class="add-alternative-requirement text-blue-600 hover:text-blue-800 text-xs">
                    <i class="fas fa-plus mr-1"></i>Add Alternative
                </button>
            </div>
            <div class="alternative-requirements-container space-y-2">
                <div class="flex items-center space-x-2">
                    <input type="text" name="alternative_requirements[]" 
                           class="flex-1 px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="e.g., Birth Certificate">
                    <button type="button" class="remove-alternative-requirement text-red-500 hover:text-red-700 text-sm">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <p class="text-xs text-blue-600 mt-1">
                <i class="fas fa-info-circle mr-1"></i>
                These file uploads apply when the condition is not met
            </p>
        </div>
    </div>
</template>

<!-- Alternative Requirement Template -->
<template id="alternativeRequirementTemplate">
    <div class="flex items-center space-x-2">
        <input type="text" name="alternative_requirements[]" 
               class="flex-1 px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
               placeholder="e.g., Birth Certificate">
        <button type="button" class="remove-alternative-requirement text-red-500 hover:text-red-700 text-sm">
            <i class="fas fa-times"></i>
        </button>
    </div>
</template>

<!-- Day-Specific Time Slot Template -->
<template id="dayTimeSlotTemplate">
    <div class="day-time-slot bg-gray-50 rounded-lg p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center space-x-3">
                <label class="flex items-center">
                    <input type="checkbox" name="selected_days[]" value="__DAY__" 
                           class="rounded border-gray-300 text-primary focus:ring-primary day-checkbox">
                    <span class="ml-2 font-medium text-gray-800 capitalize day-name"></span>
                </label>
            </div>
            <button type="button" class="remove-day-time-slot text-red-500 hover:text-red-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="space-y-2">
            <div class="flex items-center space-x-2">
                <input type="time" name="day_specific_times[__DAY__][]" 
                       class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                <button type="button" class="add-day-time-slot text-green-600 hover:text-green-800">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
</template>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Requirements management
    const requirementsContainer = document.getElementById('requirementsContainer');
    const requirementTemplate = document.getElementById('requirementTemplate');
    const conditionalRequirementTemplate = document.getElementById('conditionalRequirementTemplate');
    const alternativeRequirementTemplate = document.getElementById('alternativeRequirementTemplate');
    const addRequirementButton = document.getElementById('addRequirement');
    const addConditionalRequirementButton = document.getElementById('addConditionalRequirement');
    const requirementsSeparator = document.getElementById('requirementsSeparator');
    
    // Schedule management
    const schedulePreset = document.getElementById('schedule_preset');
    const customOptions = document.getElementById('customScheduleOptions');
    const daySpecificSection = document.getElementById('daySpecificTimesSection');
    const daySpecificContainer = document.getElementById('daySpecificTimesContainer');
    const bulkTimeSection = document.getElementById('bulkTimeSettingSection');
    const toggleBulkBtn = document.getElementById('toggleBulkSetting');
    const addTimeBtn = document.getElementById('addTimeSlot');
    const timesContainer = document.getElementById('additionalTimesContainer');
    const primaryServiceTimeSection = document.getElementById('primaryServiceTimeSection');
    
    // Bulk time management
    const addBulkTimeBtn = document.getElementById('addBulkTimeSlot');
    const bulkTimeContainer = document.getElementById('bulkTimeSlotsContainer');
    const applyBulkBtn = document.getElementById('applyBulkTimes');
    
    // Add first requirement by default
    addRequirement();
    
    // Add requirement button click
    addRequirementButton.addEventListener('click', addRequirement);
    addConditionalRequirementButton.addEventListener('click', addConditionalRequirement);
    
    // Schedule preset functionality
    schedulePreset.addEventListener('change', function() {
        if (this.value === 'custom') {
            customOptions.classList.remove('hidden');
            daySpecificSection.classList.remove('hidden');
            primaryServiceTimeSection.classList.add('hidden');
            bulkTimeSection.classList.add('hidden');
        } else if (this.value && this.value !== '') {
            customOptions.classList.add('hidden');
            daySpecificSection.classList.remove('hidden');
            primaryServiceTimeSection.classList.add('hidden');
            bulkTimeSection.classList.add('hidden');
            updateDaySpecificTimes();
        } else {
            customOptions.classList.add('hidden');
            daySpecificSection.classList.add('hidden');
            primaryServiceTimeSection.classList.remove('hidden');
            bulkTimeSection.classList.add('hidden');
        }
    });
    
    // Toggle bulk time setting
    toggleBulkBtn.addEventListener('click', function() {
        if (bulkTimeSection.classList.contains('hidden')) {
            bulkTimeSection.classList.remove('hidden');
            toggleBulkBtn.innerHTML = '<i class="fas fa-times mr-1"></i>Hide Bulk Setting';
        } else {
            bulkTimeSection.classList.add('hidden');
            toggleBulkBtn.innerHTML = '<i class="fas fa-magic mr-1"></i>Bulk Time Setting';
        }
    });
    
    // Add bulk time slot
    addBulkTimeBtn.addEventListener('click', function() {
        const newTimeSlot = document.createElement('div');
        newTimeSlot.className = 'flex items-center space-x-2';
        newTimeSlot.innerHTML = `
            <input type="time" name="bulk_times[]" 
                   class="px-2 py-1 border border-blue-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            <button type="button" class="remove-bulk-time-slot text-red-600 hover:text-red-800 text-sm">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        bulkTimeContainer.appendChild(newTimeSlot);
        
        // Add remove functionality
        newTimeSlot.querySelector('.remove-bulk-time-slot').addEventListener('click', function() {
            if (bulkTimeContainer.children.length > 1) {
                newTimeSlot.remove();
            }
        });
    });
    
    // Apply bulk times
    applyBulkBtn.addEventListener('click', function() {
        const applyTo = document.querySelector('input[name="bulk_apply_to"]:checked').value;
        const bulkTimes = Array.from(bulkTimeContainer.querySelectorAll('input[name="bulk_times[]"]'))
            .map(input => input.value)
            .filter(time => time !== '');
        
        if (bulkTimes.length === 0) {
            alert('Please add at least one time slot');
            return;
        }
        
        if (applyTo === 'all') {
            // Apply to all days in the preset
            const daySlots = daySpecificContainer.querySelectorAll('.day-time-slot');
            daySlots.forEach(daySlot => {
                const timeContainer = daySlot.querySelector('.space-y-2');
                timeContainer.innerHTML = ''; // Clear existing times
                
                bulkTimes.forEach(time => {
                    const timeInput = document.createElement('div');
                    timeInput.className = 'flex items-center space-x-2';
                    timeInput.innerHTML = `
                        <input type="time" name="day_specific_times[${daySlot.querySelector('.day-name').textContent.toLowerCase()}][]" 
                               value="${time}"
                               class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <button type="button" class="remove-day-time-slot text-red-600 hover:text-red-800">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    timeContainer.appendChild(timeInput);
                });
            });
        } else {
            // Apply to selected days only
            const selectedDays = Array.from(daySpecificContainer.querySelectorAll('.day-checkbox:checked'))
                .map(checkbox => checkbox.value);
            
            if (selectedDays.length === 0) {
                alert('Please select at least one day');
                return;
            }
            
            selectedDays.forEach(day => {
                const daySlot = daySpecificContainer.querySelector(`[data-day="${day}"]`);
                if (daySlot) {
                    const timeContainer = daySlot.querySelector('.space-y-2');
                    timeContainer.innerHTML = ''; // Clear existing times
                    
                    bulkTimes.forEach(time => {
                        const timeInput = document.createElement('div');
                        timeInput.className = 'flex items-center space-x-2';
                        timeInput.innerHTML = `
                            <input type="time" name="day_specific_times[${day}][]" 
                                   value="${time}"
                                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <button type="button" class="remove-day-time-slot text-red-600 hover:text-red-800">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        timeContainer.appendChild(timeInput);
                    });
                }
            });
        }
        
        // Hide bulk section after applying
        bulkTimeSection.classList.add('hidden');
        toggleBulkBtn.innerHTML = '<i class="fas fa-magic mr-1"></i>Bulk Time Setting';
    });
    
    // Add time slot functionality
    addTimeBtn.addEventListener('click', function() {
        const newTimeSlot = document.createElement('div');
        newTimeSlot.className = 'flex items-center space-x-2';
        newTimeSlot.innerHTML = `
            <input type="time" name="custom_times[]" 
                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            <button type="button" class="remove-time-slot text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        timesContainer.appendChild(newTimeSlot);
        
        // Add remove functionality to the new time slot
        newTimeSlot.querySelector('.remove-time-slot').addEventListener('click', function() {
            newTimeSlot.remove();
        });
    });
    
    // Remove time slot functionality for existing slots
    timesContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-time-slot') || e.target.parentElement.classList.contains('remove-time-slot')) {
            const timeSlot = e.target.closest('.flex');
            if (timeSlot && timesContainer.children.length > 1) {
                timeSlot.remove();
            }
        }
    });
    
    function updateDaySpecificTimes() {
        // Clear existing day-specific times
        daySpecificContainer.innerHTML = '';
        
        const preset = schedulePreset.value;
        let days = [];
        
        // Get days based on preset
        switch (preset) {
            case 'daily':
                days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                break;
            case 'weekdays':
                days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                break;
            case 'weekends':
                days = ['saturday', 'sunday'];
                break;
            case 'sundays':
                days = ['sunday'];
                break;
            case 'saturdays':
                days = ['saturday'];
                break;
            case 'wed_sat':
                days = ['wednesday', 'saturday'];
                break;
        }
        
        // Add day-specific time slots for each day
        days.forEach(day => {
            addDayTimeSlot(day);
        });
        
        // Update remove button visibility based on preset type
        updateRemoveButtonVisibility();
    }
    
    function updateRemoveButtonVisibility() {
        const preset = schedulePreset.value;
        const removeButtons = daySpecificContainer.querySelectorAll('.remove-day-time-slot');
        
        removeButtons.forEach(button => {
            if (preset === 'custom') {
                button.style.display = 'block';
            } else {
                button.style.display = 'none';
            }
        });
    }
    
    function addDayTimeSlot(day) {
        const template = document.getElementById('dayTimeSlotTemplate');
        const clone = template.content.cloneNode(true);
        
        // Replace placeholders
        const dayName = clone.querySelector('.day-name');
        const timeInput = clone.querySelector('input[name*="__DAY__"]');
        const checkbox = clone.querySelector('input[name="selected_days[]"]');
        
        dayName.textContent = day;
        timeInput.name = timeInput.name.replace('__DAY__', day);
        checkbox.value = day;
        
        const daySlot = clone.firstElementChild;
        daySlot.setAttribute('data-day', day);
        daySpecificContainer.appendChild(daySlot);
        
        // Add functionality for adding more time slots to this day
        const addBtn = daySlot.querySelector('.add-day-time-slot');
        const timeContainer = daySlot.querySelector('.space-y-2');
        
        addBtn.addEventListener('click', function() {
            const newTimeSlot = document.createElement('div');
            newTimeSlot.className = 'flex items-center space-x-2';
            newTimeSlot.innerHTML = `
                <input type="time" name="day_specific_times[${day}][]" 
                       class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                <button type="button" class="remove-day-time-slot text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            timeContainer.appendChild(newTimeSlot);
            
            // Add remove functionality
            newTimeSlot.querySelector('.remove-day-time-slot').addEventListener('click', function() {
                if (timeContainer.children.length > 1) {
                    newTimeSlot.remove();
                }
            });
        });
        
        // Add remove functionality for day slots (only for custom schedules)
        const removeDayBtn = daySlot.querySelector('.remove-day-time-slot');
        if (schedulePreset.value === 'custom') {
            removeDayBtn.addEventListener('click', function() {
                if (daySpecificContainer.children.length > 1) {
                    daySlot.remove();
                }
            });
        } else {
            // Hide remove button for preset schedules
            removeDayBtn.style.display = 'none';
        }
    }
    
    function addRequirement() {
        const clone = requirementTemplate.content.cloneNode(true);
        const newRequirement = clone.firstElementChild;
        
        requirementsContainer.appendChild(newRequirement);
        
        // Add remove functionality
        const removeBtn = newRequirement.querySelector('.remove-requirement');
        removeBtn.addEventListener('click', function() {
            if (requirementsContainer.children.length > 1) {
                newRequirement.remove();
            } else {
                // Clear the input instead of removing if it's the last one
                const input = newRequirement.querySelector('input');
                input.value = '';
            }
        });
        
        // Hide remove button for first requirement if it's the only one
        updateRequirementRemoveButtons();
    }

    function addConditionalRequirement() {
        const clone = conditionalRequirementTemplate.content.cloneNode(true);
        const newConditionalRequirement = clone.firstElementChild;
        
        requirementsContainer.appendChild(newConditionalRequirement);

        // Add remove functionality
        const removeBtn = newConditionalRequirement.querySelector('.remove-conditional-requirement');
        removeBtn.addEventListener('click', function() {
            if (requirementsContainer.children.length > 1) {
                newConditionalRequirement.remove();
            } else {
                // Clear the input instead of removing if it's the last one
                const inputs = newConditionalRequirement.querySelectorAll('input');
                inputs.forEach(input => input.value = '');
            }
        });

        // Add alternative requirement functionality
        const addAlternativeBtn = newConditionalRequirement.querySelector('.add-alternative-requirement');
        const alternativeContainer = newConditionalRequirement.querySelector('.alternative-requirements-container');
        
        addAlternativeBtn.addEventListener('click', function() {
            const alternativeTemplate = document.getElementById('alternativeRequirementTemplate');
            const newAlternative = alternativeTemplate.content.cloneNode(true);
            
            // Add remove functionality to the new alternative
            const removeAlternativeBtn = newAlternative.querySelector('.remove-alternative-requirement');
            removeAlternativeBtn.addEventListener('click', function() {
                if (alternativeContainer.children.length > 1) {
                    newAlternative.firstElementChild.remove();
                }
            });
            
            alternativeContainer.appendChild(newAlternative);
        });

        // Add remove functionality to existing alternative requirements
        const existingRemoveBtns = newConditionalRequirement.querySelectorAll('.remove-alternative-requirement');
        existingRemoveBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                if (alternativeContainer.children.length > 1) {
                    btn.closest('.flex').remove();
                }
            });
        });

        // Hide remove button for first conditional requirement if it's the only one
        updateConditionalRequirementRemoveButtons();
    }

    function updateRequirementRemoveButtons() {
        const requirements = requirementsContainer.querySelectorAll('.requirement-item');
        requirements.forEach((req, index) => {
            const removeBtn = req.querySelector('.remove-requirement');
            if (requirements.length === 1) {
                removeBtn.style.display = 'none';
            } else {
                removeBtn.style.display = 'block';
            }
        });
        
        // Update separator visibility
        updateRequirementsSeparator();
    }

    function updateConditionalRequirementRemoveButtons() {
        const conditionalRequirements = requirementsContainer.querySelectorAll('.conditional-requirement-item');
        conditionalRequirements.forEach((req, index) => {
            const removeBtn = req.querySelector('.remove-conditional-requirement');
            if (conditionalRequirements.length === 1) {
                removeBtn.style.display = 'none';
            } else {
                removeBtn.style.display = 'block';
            }
        });
        
        // Update separator visibility
        updateRequirementsSeparator();
    }
    
    function updateRequirementsSeparator() {
        const regularRequirements = requirementsContainer.querySelectorAll('.requirement-item');
        const conditionalRequirements = requirementsContainer.querySelectorAll('.conditional-requirement-item');
        
        if (regularRequirements.length > 0 && conditionalRequirements.length > 0) {
            requirementsSeparator.classList.remove('hidden');
        } else {
            requirementsSeparator.classList.add('hidden');
        }
    }
});
</script>
@endsection
