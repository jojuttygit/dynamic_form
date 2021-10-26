<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomFormFieldOption extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'custom_form_field_option_id';

    /**
     * Get the form field that owns the form field options.
     */
    public function customFormField()
    {
        return $this->belongsTo('CustomFormField', 'custom_form_field_id', 'custom_form_field_id');
    }
}
