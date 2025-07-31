<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceFormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'field_name',
        'label',
        'field_type',
        'options',
        'required',
        'placeholder',
        'help_text',
        'validation_rules',
        'order',
        'is_conditional',
        'condition_field',
        'condition_value',
    ];

    protected $casts = [
        'options' => 'array',
        'required' => 'boolean',
        'is_conditional' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getFieldTypeOptions()
    {
        return [
            'text' => 'Text Input',
            'email' => 'Email Input',
            'tel' => 'Phone Number',
            'date' => 'Date Picker',
            'time' => 'Time Picker',
            'textarea' => 'Text Area',
            'select' => 'Dropdown Select',
            'checkbox' => 'Checkbox',
            'radio' => 'Radio Buttons',
            'number' => 'Number Input',
            'file' => 'File Upload',
        ];
    }

    public function getValidationRulesArray()
    {
        if (!$this->validation_rules) {
            return [];
        }
        
        return explode('|', $this->validation_rules);
    }

    public function isSelectType()
    {
        return in_array($this->field_type, ['select', 'radio']);
    }

    public function isFileType()
    {
        return $this->field_type === 'file';
    }
}
