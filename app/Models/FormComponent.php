<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'page',
        'type',
        'label',
        'order',
        'properties',
        'validation',
        'required',
    ];

    protected $casts = [
        'properties' => 'array',
        'validation' => 'array',
        'required' => 'boolean',
    ];

    /**
     * Get the form that owns the component.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}