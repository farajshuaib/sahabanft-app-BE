<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'notifiable_type', 'notifiable_id', 'data', 'user_id', 'read_at'];

    protected $dates = ['read_at'];


    public function notifiable()
    {
        $this->morphTo();
    }
}