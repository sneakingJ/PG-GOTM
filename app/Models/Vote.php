<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = ['month_id', 'discord_id', 'short', 'created_at', 'updated_at'];

    public function rankings()
    {
        return $this->hasMany(Ranking::class);
    }
}
