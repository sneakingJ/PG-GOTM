<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomination extends Model
{
    use HasFactory;

    public function pitches(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Pitch::class);
    }
}
