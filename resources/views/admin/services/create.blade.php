@extends('layouts.admin')

@section('title', 'Create Service')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create New Service</h1>
            <p class="text-gray-600 mt-2">Add a new service with multiple types, schedules, and pricing options</p>
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
                    <button type="button" id="addRequirement" 
                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                        <i class="fas fa-plus mr-1"></i>Add Requirement
                    </button>
                </div>
                
                <div id="requirementsContainer" class="space-y-3">
                    <!-- Requirements will be added dynamically -->
                </div>
                
                <div class="mt-2 text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Add specific requirements or documents needed for this service.
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

            <div id="generalServiceInfo" class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">General Service Information</h2>
                
                <!-- Message -->
                <div id="generalInfoMessage" class="mt-4 mb-6 text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Use these fields for services that do not have different types. This information will be used if no service types are added below.
                </div>
                
                <!-- Warning when service types exist -->
                <div id="generalInfoWarning" class="mt-4 mb-6 text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg p-3 hidden">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <strong>Note:</strong> General service information will be ignored because you have added service types below. Only the individual service type configurations will be used.
                </div>

                <!-- General Price & Duration -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="general_price" class="block text-sm font-medium text-gray-700 mb-2">Price (₱)</label>
                        <input type="number" id="general_price" name="general_price" step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               value="{{ old('general_price') }}" placeholder="0.00">
                        @error('general_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="general_duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                        <input type="number" id="general_duration_minutes" name="general_duration_minutes" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               value="{{ old('general_duration_minutes') }}" placeholder="60">
                        @error('general_duration_minutes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- General Schedule Section -->
                <h4 class="text-md font-medium text-gray-800 mb-4">Schedule Information</h4>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Preset</label>
                    <select name="general_schedule_preset" id="general_schedule_preset" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
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

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary Service Time</label>
                    <input type="time" name="general_service_time" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <div id="generalCustomScheduleOptions" class="hidden">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Custom Days</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @foreach(['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                            <label class="flex items-center">
                                <input type="checkbox" name="general_custom_days[]" value="{{ $day }}" class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="ml-2 text-sm capitalize">{{ $day }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Additional Times</label>
                        <div id="generalAdditionalTimesContainer" class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <input type="time" name="general_custom_times[]" 
                                       class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <button type="button" id="addGeneralTimeSlot" class="text-green-600 hover:text-green-800">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Description (Optional)</label>
                    <textarea name="general_schedule_description" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="Additional schedule information..."></textarea>
                </div>
            </div>

        <!-- Service Types Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Service Types <span class="text-sm font-normal text-gray-500">(Optional)</span></h2>
                <button type="button" id="addServiceType" 
                        class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Service Type
                </button>
            </div>

            <div id="serviceTypesContainer" class="space-y-6">
                <!-- Service types will be added dynamically when user clicks "Add Service Type" -->
            </div>

            <div id="noServiceTypesMessage" class="text-center py-8 text-gray-500">
                <i class="fas fa-info-circle text-2xl mb-2"></i>
                <p class="text-sm">No service types added yet.</p>
                <p class="text-xs mt-1">Click "Add Service Type" to create different variations of this service with their own schedules, duration, and pricing.</p>
            </div>

            <div class="mt-4 text-sm text-gray-600 hidden" id="serviceTypesInfo">
                <i class="fas fa-info-circle mr-1"></i>
                Add different types of this service with their own schedules, duration, and pricing.
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

<!-- Service Type Template -->
<template id="serviceTypeTemplate">
    <div class="service-type-card border-2 border-gray-200 rounded-lg p-6 relative">
        <button type="button" class="remove-service-type absolute top-3 right-3 text-red-500 hover:text-red-700 text-sm">
            <i class="fas fa-times"></i>
        </button>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Service Type Name -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Service Type Name *</label>
                <input type="text" name="service_types[__INDEX__][name]" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                       placeholder="e.g., Standard Wedding, Garden Wedding">
            </div>

            <!-- Price -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Price (₱)</label>
                <input type="number" name="service_types[__INDEX__][price]" step="0.01" min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                       placeholder="0.00">
            </div>

            <!-- Duration -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                <input type="number" name="service_types[__INDEX__][duration_minutes]" min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                       placeholder="60">
            </div>
        </div>

        <!-- Schedule Section -->
        <div class="mt-6">
            <h4 class="text-md font-medium text-gray-800 mb-4">Schedule Information</h4>
            
            <!-- Schedule Preset -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Preset</label>
                <select name="service_types[__INDEX__][schedule_preset]" class="schedule-preset w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
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

            <!-- Primary Service Time -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Primary Service Time</label>
                <input type="time" name="service_types[__INDEX__][service_time]" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <!-- Custom Schedule Options (hidden by default) -->
            <div class="custom-schedule-options hidden">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Custom Days</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="service_types[__INDEX__][custom_days][]" value="sunday" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm">Sunday</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="service_types[__INDEX__][custom_days][]" value="monday" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm">Monday</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="service_types[__INDEX__][custom_days][]" value="tuesday" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm">Tuesday</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="service_types[__INDEX__][custom_days][]" value="wednesday" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm">Wednesday</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="service_types[__INDEX__][custom_days][]" value="thursday" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm">Thursday</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="service_types[__INDEX__][custom_days][]" value="friday" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm">Friday</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="service_types[__INDEX__][custom_days][]" value="saturday" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm">Saturday</span>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Times</label>
                    <div class="additional-times-container space-y-2">
                        <div class="flex items-center space-x-2">
                            <input type="time" name="service_types[__INDEX__][custom_times][]" 
                                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <button type="button" class="add-time-slot text-green-600 hover:text-green-800">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule Description -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Description (Optional)</label>
                <textarea name="service_types[__INDEX__][schedule_description]" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                          placeholder="Additional schedule information..."></textarea>
            </div>
        </div>
    </div>
</template>

<!-- Requirement Template -->
<template id="requirementTemplate">
    <div class="requirement-item flex items-start space-x-3 p-4 border border-gray-200 rounded-lg bg-gray-50">
        <div class="flex-1">
            <input type="text" name="requirements[]" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                   placeholder="e.g., Valid ID, Birth Certificate, etc.">
        </div>
        <button type="button" class="remove-requirement text-red-500 hover:text-red-700 p-2">
            <i class="fas fa-times"></i>
        </button>
    </div>
</template>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let serviceTypeIndex = 0;
    const container = document.getElementById('serviceTypesContainer');
    const template = document.getElementById('serviceTypeTemplate');
    const addButton = document.getElementById('addServiceType');
    
    // Requirements management
    const requirementsContainer = document.getElementById('requirementsContainer');
    const requirementTemplate = document.getElementById('requirementTemplate');
    const addRequirementButton = document.getElementById('addRequirement');
    
    // General schedule management
    const generalSchedulePreset = document.getElementById('general_schedule_preset');
    const generalCustomOptions = document.getElementById('generalCustomScheduleOptions');
    const addGeneralTimeBtn = document.getElementById('addGeneralTimeSlot');
    const generalTimesContainer = document.getElementById('generalAdditionalTimesContainer');
    
    // Add first requirement by default
    addRequirement();
    
    // Add requirement button click
    addRequirementButton.addEventListener('click', addRequirement);
    
    // General schedule preset functionality
    generalSchedulePreset.addEventListener('change', function() {
        if (this.value === 'custom') {
            generalCustomOptions.classList.remove('hidden');
        } else {
            generalCustomOptions.classList.add('hidden');
        }
    });
    
    // General add time slot functionality
    addGeneralTimeBtn.addEventListener('click', function() {
        const newTimeSlot = document.createElement('div');
        newTimeSlot.className = 'flex items-center space-x-2';
        newTimeSlot.innerHTML = `
            <input type="time" name="general_custom_times[]" 
                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            <button type="button" class="remove-general-time-slot text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        generalTimesContainer.appendChild(newTimeSlot);
        
        // Add remove functionality to the new time slot
        newTimeSlot.querySelector('.remove-general-time-slot').addEventListener('click', function() {
            newTimeSlot.remove();
        });
    });
    
    // Remove general time slot functionality for existing slots
    generalTimesContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-general-time-slot') || e.target.parentElement.classList.contains('remove-general-time-slot')) {
            const timeSlot = e.target.closest('.flex');
            if (timeSlot && generalTimesContainer.children.length > 1) {
                timeSlot.remove();
            }
        }
    });
    
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
    }
    
    // Add service type button click
    addButton.addEventListener('click', function() {
        addServiceType();
        updateServiceTypesVisibility();
    });
    
    function addServiceType() {
        const clone = template.content.cloneNode(true);
        
        // Replace __INDEX__ with actual index
        const html = clone.firstElementChild.outerHTML.replace(/__INDEX__/g, serviceTypeIndex);
        
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html;
        const newCard = wrapper.firstElementChild;
        
        container.appendChild(newCard);
        
        // Add event listeners for this card
        setupServiceTypeCard(newCard, serviceTypeIndex);
        
        serviceTypeIndex++;
    }
    
    function updateServiceTypesVisibility() {
        const noMessage = document.getElementById('noServiceTypesMessage');
        const infoDiv = document.getElementById('serviceTypesInfo');
        const generalInfoMessage = document.getElementById('generalInfoMessage');
        const generalInfoWarning = document.getElementById('generalInfoWarning');
        
        if (container.children.length === 0) {
            noMessage.classList.remove('hidden');
            infoDiv.classList.add('hidden');
            // Show normal message, hide warning
            generalInfoMessage.classList.remove('hidden');
            generalInfoWarning.classList.add('hidden');
        } else {
            noMessage.classList.add('hidden');
            infoDiv.classList.remove('hidden');
            // Hide normal message, show warning
            generalInfoMessage.classList.add('hidden');
            generalInfoWarning.classList.remove('hidden');
        }
    }
    
    function setupServiceTypeCard(card, index) {
        // Remove button functionality
        const removeBtn = card.querySelector('.remove-service-type');
        removeBtn.addEventListener('click', function() {
            card.remove();
            updateServiceTypesVisibility();
        });
        
        // Schedule preset change handler
        const schedulePreset = card.querySelector('.schedule-preset');
        const customOptions = card.querySelector('.custom-schedule-options');
        
        schedulePreset.addEventListener('change', function() {
            if (this.value === 'custom') {
                customOptions.classList.remove('hidden');
            } else {
                customOptions.classList.add('hidden');
            }
        });
        
        // Add time slot functionality
        const addTimeBtn = card.querySelector('.add-time-slot');
        const timesContainer = card.querySelector('.additional-times-container');
        
        addTimeBtn.addEventListener('click', function() {
            const newTimeSlot = document.createElement('div');
            newTimeSlot.className = 'flex items-center space-x-2';
            newTimeSlot.innerHTML = `
                <input type="time" name="service_types[${index}][custom_times][]" 
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
        card.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-time-slot') || e.target.parentElement.classList.contains('remove-time-slot')) {
                const timeSlot = e.target.closest('.flex');
                if (timeSlot && timesContainer.children.length > 1) {
                    timeSlot.remove();
                }
            }
        });
        
        // All service types can now be removed since they're optional
    }
    
    // Form validation
    document.getElementById('serviceForm').addEventListener('submit', function(e) {
        const serviceTypes = container.querySelectorAll('.service-type-card');
        let isValid = true;
        
        serviceTypes.forEach(function(card, index) {
            const nameInput = card.querySelector('input[name*="[name]"]');
            if (!nameInput || !nameInput.value.trim()) {
                isValid = false;
                alert(`Service Type ${index + 1} name is required.`);
                nameInput.focus();
                return false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
