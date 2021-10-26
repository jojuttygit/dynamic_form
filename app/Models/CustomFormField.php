<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomFormField extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'custom_form_field_id';

    /**
     * Get the field type that owns the fields.
     */
    public function fieldType()
    {
        return $this->belongsTo('FieldType', 'field_type_id', 'field_type_id');
    }

    /**
     * Get the form that owns the fields.
     */
    public function customForm()
    {
        return $this->belongsTo('CustomForm', 'custom_form_id', 'custom_form_id');
    }

    /**
     * Get the form field options for the field.
     */
    public function customFormFieldOptions()
    {
        return $this->hasMany('CustomFormFieldOption', 'custom_form_field_id', 'custom_form_field_id');
    }
}
