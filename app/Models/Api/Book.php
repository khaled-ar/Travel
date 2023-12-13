<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model {
    use HasFactory;

    public function user() {
        return $this->belongsTo( User::class );
    }
}
