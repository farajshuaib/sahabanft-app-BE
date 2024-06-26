<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SocialLink extends Model
{
    protected $guarded = [];

    public function socialable(): MorphTo
    {
        return $this->morphTo();
    }
}
