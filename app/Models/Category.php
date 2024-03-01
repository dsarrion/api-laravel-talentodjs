<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    //Relación de uno a mucho
    public function tracks() : HasMany
    {
        return $this->hasMany(Track::class);
    }

}
