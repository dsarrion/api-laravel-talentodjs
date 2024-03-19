<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'track_id'
    ];

    //Desactivar $timestamp para evitar errores tener solo el metodo created_at y no updated_at
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        // Evento creating para darle valor a created_at
        static::creating(function ($like) {
            $like->created_at = now();
        });
    }

    //Relación Muchos a Uno
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //Relación Muchos a Uno
    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }
}
