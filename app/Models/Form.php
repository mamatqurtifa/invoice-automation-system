<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'is_template',
        'is_active',
    ];

    protected $casts = [
        'is_template' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the project that owns the form.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get all components for the form.
     */
    public function components()
    {
        return $this->hasMany(FormComponent::class)->orderBy('order');
    }

    /**
     * Get all responses for the form.
     */
    public function responses()
    {
        return $this->hasMany(FormResponse::class);
    }
}