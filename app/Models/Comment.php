<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content'
    ];

    //Relación Muchos a Uno
    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //Relación Muchos a Uno
    public function track() :BelongsTo
    {
        return $this->belongsTo(Track::class);
    }

}
