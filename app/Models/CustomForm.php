<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'custom_form_id';

    /**
     * Get the form fields for the form.
     */
    public function customFormField()
    {
        return $this->hasMany('CustomFormField', 'custom_form_id', 'custom_form_id');
    }
}
