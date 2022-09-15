<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    public function followable()
    {
        return $this->morphTo();
    }
    public function userable()
    {
        return $this->morphTo();
    }
}
