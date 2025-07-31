@extends('layouts.admin')

@section('title', 'Form Fields - ' . $service->name)

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Form Fields</h1>
            <p class="text-gray-600 mt-2">Manage custom form fields for {{ $service->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.services.show', $service) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-eye mr-2"></i>View Service
            </a>
            <button type="button" id="addFieldBtn" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>Add Field
            </button>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Service Information -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mr-4">
                <i class="{{ $service->icon }} text-primary text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $service->name }}</h2>
                <p class="text-gray-500">{{ $service->description }}</p>
            </div>
        </div>
    </div>

    <!-- Form Fields List -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Custom Form Fields</h3>
            <p class="text-sm text-gray-600 mt-1">These fields will appear in the booking form for this service.</p>
        </div>

        <div class="p-6">
            @if($service->formFields->count() > 0)
                <div class="space-y-4" id="formFieldsContainer">
                    @foreach($service->formFields as $field)
                        <div class="form-field-item border border-gray-200 rounded-lg p-4 bg-gray-50" data-field-id="{{ $field->id }}">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center">
                                        <i class="fas fa-grip-vertical text-primary text-sm"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $field->label }}</h4>
                                        <p class="text-sm text-gray-500">{{ $field->field_name }} ({{ $field->field_type }})</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($field->required)
                                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Required</span>
                                    @endif
                                    @if($field->is_conditional)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Conditional</span>
                                    @endif
                                    <button type="button" class="edit-field-btn text-blue-600 hover:text-blue-800 p-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="delete-field-btn text-red-600 hover:text-red-800 p-1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Type:</span>
                                    <span class="text-gray-900">{{ ucfirst($field->field_type) }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Order:</span>
                                    <span class="text-gray-900">{{ $field->order }}</span>
                                </div>
                                @if($field->placeholder)
                                    <div>
                                        <span class="font-medium text-gray-700">Placeholder:</span>
                                        <span class="text-gray-900">{{ $field->placeholder }}</span>
                                    </div>
                                @endif
                            </div>

                            @if($field->help_text)
                                <div class="mt-2 text-sm text-gray-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    {{ $field->help_text }}
                                </div>
                            @endif

                            @if($field->is_conditional)
                                <div class="mt-2 text-sm text-blue-600">
                                    <i class="fas fa-link mr-1"></i>
                                    Shows when {{ $field->condition_field }} = {{ $field->condition_value }}
                                    @if($field->required)
                                        <span class="text-red-600">(Required when shown)</span>
                                    @endif
                                </div>
                            @endif

                            @if($field->options && count($field->options) > 0)
                                <div class="mt-2">
                                    <span class="text-sm font-medium text-gray-700">Options:</span>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($field->options as $option)
                                            <span class="px-2 py-1 bg-gray-200 text-gray-700 text-xs rounded">{{ $option }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-list-alt text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Form Fields</h3>
                    <p class="text-gray-600 mb-4">This service doesn't have any custom form fields yet.</p>
                    <button type="button" id="addFirstFieldBtn" class="bg-primary hover:bg-primary-dark text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add First Field
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add/Edit Field Modal -->
<div id="fieldModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Add Form Field</h3>
                <button type="button" id="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="fieldForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="field_name" class="block text-sm font-medium text-gray-700 mb-1">Field Name *</label>
                            <input type="text" id="field_name" name="field_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="e.g., bride_name">
                            <p class="text-xs text-gray-500 mt-1">Only letters, numbers, and underscores allowed</p>
                        </div>
                        
                        <div>
                            <label for="label" class="block text-sm font-medium text-gray-700 mb-1">Display Label *</label>
                            <input type="text" id="label" name="label" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="e.g., Bride Name">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="field_type" class="block text-sm font-medium text-gray-700 mb-1">Field Type *</label>
                            <select id="field_type" name="field_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Select field type</option>
                                <option value="text">Text Input</option>
                                <option value="email">Email Input</option>
                                <option value="tel">Phone Number</option>
                                <option value="date">Date Picker</option>
                                <option value="time">Time Picker</option>
                                <option value="textarea">Text Area</option>
                                <option value="select">Dropdown Select</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="radio">Radio Buttons</option>
                                <option value="number">Number Input</option>
                                <option value="file">File Upload</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                            <input type="number" id="order" name="order" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="0">
                        </div>
                    </div>

                    <!-- Options for select/radio fields -->
                    <div id="optionsSection" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                        <div id="optionsContainer" class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <input type="text" name="options[]" 
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                       placeholder="Option 1">
                                <button type="button" class="add-option-btn text-green-600 hover:text-green-800">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Conditional Logic -->
                    <div class="border-t pt-4">
                        <div class="flex items-center mb-3">
                            <input type="hidden" name="is_conditional" value="0">
                            <input type="checkbox" id="is_conditional" name="is_conditional" value="1" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <label for="is_conditional" class="ml-2 text-sm font-medium text-gray-700">This field is conditional</label>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                            <p class="text-sm text-blue-800 mb-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Conditional Field:</strong> This field will only appear when another field meets a specific condition.
                            </p>
                            <p class="text-xs text-blue-600">
                                Example: Show "Marriage Certificate" field only when "Parents Married?" = "Yes"
                            </p>
                        </div>
                        
                        <div id="conditionalSection" class="hidden space-y-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="condition_field" class="block text-sm font-medium text-gray-700 mb-1">Depends on Field</label>
                                    <select id="condition_field" name="condition_field"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="">Select field</option>
                                        @foreach($service->formFields as $existingField)
                                            <option value="{{ $existingField->field_name }}">{{ $existingField->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="condition_value" class="block text-sm font-medium text-gray-700 mb-1">Condition Value</label>
                                    <input type="text" id="condition_value" name="condition_value"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                           placeholder="e.g., Yes, Married, etc.">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="placeholder" class="block text-sm font-medium text-gray-700 mb-1">Placeholder Text</label>
                            <input type="text" id="placeholder" name="placeholder"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Enter placeholder text">
                        </div>
                        
                        <div>
                            <label for="validation_rules" class="block text-sm font-medium text-gray-700 mb-1">Validation Rules</label>
                            <input type="text" id="validation_rules" name="validation_rules"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="e.g., required|min:3|max:50">
                            <p class="text-xs text-gray-500 mt-1">Use Laravel validation rules separated by |</p>
                        </div>
                    </div>

                    <div>
                        <label for="help_text" class="block text-sm font-medium text-gray-700 mb-1">Help Text</label>
                        <textarea id="help_text" name="help_text" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                  placeholder="Additional help text for users"></textarea>
                    </div>

                    <!-- Required Field Logic -->
                    <div class="border-t pt-4">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="hidden" name="required" value="0">
                                <input type="checkbox" id="required" name="required" value="1" class="rounded border-gray-300 text-primary focus:ring-primary">
                                <label for="required" class="ml-2 text-sm font-medium text-gray-700">This field is required</label>
                            </div>
                            
                            <div id="conditionalRequiredSection" class="hidden">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <p class="text-sm text-blue-800 mb-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        This field will be required only when the condition is met.
                                    </p>
                                    <p class="text-xs text-blue-600">
                                        Example: If "Parents Married" = "Yes", then "Marriage Certificate" becomes required.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelBtn" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-md">
                        Save Field
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('fieldModal');
    const addFieldBtn = document.getElementById('addFieldBtn');
    const addFirstFieldBtn = document.getElementById('addFirstFieldBtn');
    const closeModal = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const fieldForm = document.getElementById('fieldForm');
    const modalTitle = document.getElementById('modalTitle');
    const fieldType = document.getElementById('field_type');
    const optionsSection = document.getElementById('optionsSection');
    const isConditional = document.getElementById('is_conditional');
    const conditionalSection = document.getElementById('conditionalSection');
    const required = document.getElementById('required');
    const conditionalRequiredSection = document.getElementById('conditionalRequiredSection');

    // Show modal for adding new field
    function showAddModal() {
        modalTitle.textContent = 'Add Form Field';
        fieldForm.reset();
        fieldForm.action = "{{ route('admin.services.form-fields.store', $service) }}";
        fieldForm.method = 'POST';
        modal.classList.remove('hidden');
    }

    // Show modal for editing field
    function showEditModal(fieldId) {
        // Here you would fetch the field data and populate the form
        // For now, we'll just show the modal
        modalTitle.textContent = 'Edit Form Field';
        fieldForm.action = `/admin/services/{{ $service->id }}/form-fields/${fieldId}`;
        fieldForm.method = 'POST';
        fieldForm.innerHTML += '<input type="hidden" name="_method" value="PUT">';
        modal.classList.remove('hidden');
    }

    // Hide modal
    function hideModal() {
        modal.classList.add('hidden');
    }

    // Event listeners
    addFieldBtn.addEventListener('click', showAddModal);
    if (addFirstFieldBtn) {
        addFirstFieldBtn.addEventListener('click', showAddModal);
    }
    closeModal.addEventListener('click', hideModal);
    cancelBtn.addEventListener('click', hideModal);

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            hideModal();
        }
    });

    // Show/hide options section based on field type
    fieldType.addEventListener('change', function() {
        if (['select', 'radio'].includes(this.value)) {
            optionsSection.classList.remove('hidden');
        } else {
            optionsSection.classList.add('hidden');
        }
    });

    // Show/hide conditional section
    isConditional.addEventListener('change', function() {
        if (this.checked) {
            conditionalSection.classList.remove('hidden');
        } else {
            conditionalSection.classList.add('hidden');
            conditionalRequiredSection.classList.add('hidden');
        }
    });

    // Show/hide conditional required section
    required.addEventListener('change', function() {
        if (this.checked && isConditional.checked) {
            conditionalRequiredSection.classList.remove('hidden');
        } else {
            conditionalRequiredSection.classList.add('hidden');
        }
    });

    // Add option button
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-option-btn')) {
            const optionsContainer = document.getElementById('optionsContainer');
            const newOption = document.createElement('div');
            newOption.className = 'flex items-center space-x-2';
            newOption.innerHTML = `
                <input type="text" name="options[]" 
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                       placeholder="Option">
                <button type="button" class="remove-option-btn text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            `;
            optionsContainer.appendChild(newOption);
        }

        if (e.target.classList.contains('remove-option-btn')) {
            e.target.closest('.flex').remove();
        }
    });

    // Edit and delete field buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('edit-field-btn')) {
            const fieldItem = e.target.closest('.form-field-item');
            const fieldId = fieldItem.dataset.fieldId;
            showEditModal(fieldId);
        }

        if (e.target.classList.contains('delete-field-btn')) {
            const fieldItem = e.target.closest('.form-field-item');
            const fieldId = fieldItem.dataset.fieldId;
            
            if (confirm('Are you sure you want to delete this field?')) {
                fetch(`/admin/services/{{ $service->id }}/form-fields/${fieldId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(response => {
                    if (response.ok) {
                        fieldItem.remove();
                        if (document.querySelectorAll('.form-field-item').length === 0) {
                            location.reload();
                        }
                    }
                });
            }
        }
    });
});
</script>
@endsection 