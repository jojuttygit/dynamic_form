<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FieldType extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'field_type_id';

    const TEXT = 1;
    const NUMBER = 2;
    const SELECT = 3;

    /**
     * Get the form fields for the field type.
     */
    public function customFormField()
    {
        return $this->hasMany('CustomFormField', 'field_type_id', 'field_type_id');
    }
}
