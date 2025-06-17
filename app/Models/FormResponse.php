<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'responses',
    ];

    protected $casts = [
        'responses' => 'array',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}