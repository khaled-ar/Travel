<?php

namespace App\Models\Api;

use App\Models\Api\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model {
    use HasFactory;

    public function user() {
        return $this->belongsTo( User::class );
    }
}
