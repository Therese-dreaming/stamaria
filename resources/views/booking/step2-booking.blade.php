@extends('layouts.main')

@section('title', 'Additional Information - ' . $service->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-yellow-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#0d5c2f] mb-2">Additional Information</h1>
            <p class="text-gray-600">Please provide the required information for {{ $service->name }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Service Information -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-[#0d5c2f]/10 rounded-xl flex items-center justify-center mr-4">
                        <i class="{{ $service->icon }} text-[#0d5c2f] text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h2>
                        <p class="text-gray-500">Service ID: {{ $service->id }}</p>
                    </div>
                </div>

                <!-- Progress Indicator -->
                <div class="mb-6">
                    <div class="flex items-center justify-center">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-[#0d5c2f] text-white rounded-full flex items-center justify-center text-sm font-medium">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="w-16 h-1 bg-[#0d5c2f]"></div>
                            <div class="w-8 h-8 bg-[#0d5c2f] text-white rounded-full flex items-center justify-center text-sm font-medium">
                                2
                            </div>
                            <div class="w-16 h-1 bg-gray-300"></div>
                            <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">
                                3
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center mt-2 text-sm text-gray-600">
                        <span class="mr-8">Basic Info</span>
                        <span class="mr-8">Additional Info</span>
                        <span>Review</span>
                    </div>
                </div>
            </div>

            <!-- Dynamic Form Fields -->
            <div class="p-6">
                @if($service->formFields->count() > 0)
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Service-Specific Information</h3>
                    
                    <form action="{{ route('booking.step3') }}" method="POST" class="space-y-6" id="dynamicForm">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                        
                        @foreach($service->formFields as $field)
                            <div class="form-field-container" 
                                 @if($field->is_conditional)
                                     data-condition-field="{{ $field->condition_field }}"
                                     data-condition-value="{{ $field->condition_value }}"
                                     style="display: none;"
                                 @endif>
                                
                                <label for="custom_field_{{ $field->field_name }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $field->label }}
                                    @if($field->required)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>

                                @switch($field->field_type)
                                    @case('textarea')
                                        <textarea 
                                            id="custom_field_{{ $field->field_name }}"
                                            name="custom_field_{{ $field->field_name }}"
                                            rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent"
                                            placeholder="{{ $field->placeholder }}"
                                            @if($field->required) required @endif
                                        >{{ old('custom_field_' . $field->field_name) }}</textarea>
                                        @break

                                    @case('select')
                                        <select 
                                            id="custom_field_{{ $field->field_name }}"
                                            name="custom_field_{{ $field->field_name }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent"
                                            @if($field->required) required @endif
                                        >
                                            <option value="">Select {{ $field->label }}</option>
                                            @if($field->options)
                                                @foreach($field->options as $option)
                                                    <option value="{{ $option }}" {{ old('custom_field_' . $field->field_name) == $option ? 'selected' : '' }}>
                                                        {{ $option }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @break

                                    @case('checkbox')
                                        <div class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                id="custom_field_{{ $field->field_name }}"
                                                name="custom_field_{{ $field->field_name }}"
                                                value="1"
                                                class="h-4 w-4 rounded border-gray-300 text-[#0d5c2f] focus:ring-[#0d5c2f]"
                                                @if($field->required) required @endif
                                                {{ old('custom_field_' . $field->field_name) ? 'checked' : '' }}
                                            >
                                            <label for="custom_field_{{ $field->field_name }}" class="ml-2 text-sm text-gray-700">
                                                {{ $field->label }}
                                            </label>
                                        </div>
                                        @break

                                    @case('radio')
                                        <div class="space-y-2">
                                            @if($field->options)
                                                @foreach($field->options as $option)
                                                    <div class="flex items-center">
                                                        <input 
                                                            type="radio" 
                                                            id="custom_field_{{ $field->field_name }}_{{ $loop->index }}"
                                                            name="custom_field_{{ $field->field_name }}"
                                                            value="{{ $option }}"
                                                            class="h-4 w-4 border-gray-300 text-[#0d5c2f] focus:ring-[#0d5c2f]"
                                                            @if($field->required) required @endif
                                                            {{ old('custom_field_' . $field->field_name) == $option ? 'checked' : '' }}
                                                        >
                                                        <label for="custom_field_{{ $field->field_name }}_{{ $loop->index }}" class="ml-2 text-sm text-gray-700">
                                                            {{ $option }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        @break

                                    @case('file')
                                        <input 
                                            type="file" 
                                            id="custom_field_{{ $field->field_name }}"
                                            name="custom_field_{{ $field->field_name }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent"
                                            @if($field->required) required @endif
                                        >
                                        @break

                                    @default
                                        <input 
                                            type="{{ $field->field_type }}" 
                                            id="custom_field_{{ $field->field_name }}"
                                            name="custom_field_{{ $field->field_name }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent"
                                            placeholder="{{ $field->placeholder }}"
                                            @if($field->required) required @endif
                                            value="{{ old('custom_field_' . $field->field_name) }}"
                                        >
                                @endswitch

                                @if($field->help_text)
                                    <p class="text-xs text-gray-500 mt-1">{{ $field->help_text }}</p>
                                @endif

                                @error('custom_field_' . $field->field_name)
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach

                        <!-- Submit Buttons -->
                        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                            <a href="{{ route('booking.step1', $service) }}" 
                               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Back
                            </a>
                            <button type="submit" 
                                    class="bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                Continue to Review <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-info-circle text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Additional Information Required</h3>
                        <p class="text-gray-600 mb-6">This service doesn't require any additional information.</p>
                        
                        <div class="flex justify-between items-center">
                            <a href="{{ route('booking.step1', $service) }}" 
                               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Back
                            </a>
                            <a href="{{ route('booking.step3') }}" 
                               class="bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                Continue to Review <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($service->formFields->where('is_conditional', true)->count() > 0)
    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle conditional fields
            const conditionalFields = document.querySelectorAll('[data-condition-field]');
            
            conditionalFields.forEach(field => {
                const conditionField = field.getAttribute('data-condition-field');
                const conditionValue = field.getAttribute('data-condition-value');
                
                // Skip if condition field is not specified
                if (!conditionField || conditionField === 'null') {
                    console.warn('Conditional field missing condition_field:', field);
                    return;
                }
                
                const triggerField = document.querySelector(`[name="custom_field_${conditionField}"]`);
                
                // Handle radio buttons - they have multiple elements with the same name
                let triggerFields = [];
                if (!triggerField) {
                    triggerFields = document.querySelectorAll(`[name="custom_field_${conditionField}"]`);
                } else {
                    triggerFields = [triggerField];
                }
                
                if (triggerFields.length > 0) {
                    // Initial check
                    checkCondition(field, triggerFields, conditionValue);
                    
                    // Listen for changes on all trigger fields (important for radio buttons)
                    triggerFields.forEach(trigger => {
                        trigger.addEventListener('change', function() {
                            checkCondition(field, triggerFields, conditionValue);
                        });
                        
                        // Also listen for input events for text fields
                        if (trigger.type === 'text' || trigger.type === 'email' || trigger.type === 'number') {
                            trigger.addEventListener('input', function() {
                                checkCondition(field, triggerFields, conditionValue);
                            });
                        }
                    });
                }
            });
            
            function checkCondition(field, triggerFields, conditionValue) {
                let triggerValue = '';
                
                // Get the current value based on field type
                if (triggerFields.length === 1) {
                    const trigger = triggerFields[0];
                    if (trigger.type === 'checkbox') {
                        triggerValue = trigger.checked ? trigger.value : '';
                    } else {
                        triggerValue = trigger.value;
                    }
                } else {
                    // Handle radio buttons - find the checked one
                    const checkedRadio = Array.from(triggerFields).find(radio => radio.checked);
                    triggerValue = checkedRadio ? checkedRadio.value : '';
                }
                
                // Check if condition is met
                const shouldShow = (triggerValue == conditionValue);
                
                if (shouldShow) {
                    field.style.display = 'block';
                    // Re-enable required validation for visible fields
                    const inputs = field.querySelectorAll('input, select, textarea');
                    inputs.forEach(input => {
                        input.disabled = false;
                        // Add back required attribute if the field was originally required
                        const label = field.querySelector('label');
                        if (label && label.innerHTML.includes('*')) {
                            input.setAttribute('required', 'required');
                        }
                    });
                } else {
                    field.style.display = 'none';
                    // Disable validation for hidden fields
                    const inputs = field.querySelectorAll('input, select, textarea');
                    inputs.forEach(input => {
                        input.disabled = true;
                        input.removeAttribute('required');
                        // Clear values of hidden fields to prevent submission issues
                        if (input.type === 'checkbox' || input.type === 'radio') {
                            input.checked = false;
                        } else {
                            input.value = '';
                        }
                    });
                }
            }
        });
    </script>
    @endsection
@endif
@endsection 