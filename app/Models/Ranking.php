<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    use HasFactory;

    protected $fillable = ['vote_id', 'nomination_id', 'rank', 'created_at', 'updated_at'];

    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }

    public function nomination()
    {
        return $this->belongsTo(Nomination::class);
    }
}
