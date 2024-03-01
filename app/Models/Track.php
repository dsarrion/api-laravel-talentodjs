<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'dj',
        'description',
        'url'
    ];

    //Relación de uno a mucho
    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    //Relación de uno a mucho
    public function likes() : HasMany
    {
        return $this->hasMany(Like::class);
    }

    //Relación de Muchos a Uno
    public function category() :BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
