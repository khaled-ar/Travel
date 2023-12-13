<?php

namespace App\Models\Api;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Api\Payments;

class User extends Authenticatable implements MustVerifyEmail {
    use HasApiTokens, HasFactory, Notifiable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone',
        'terms',
    ];

    /**
    * The attributes that should be hidden for serialization.
    *
    * @var array<int, string>
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
    * The attributes that should be cast.
    *
    * @var array<string, string>
    */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function setPasswordAttribute( $value ) {
        $this->attributes[ 'password' ] = Hash::make( $value );
    }

    public function books() {
        return $this->hasMany( Book::class );
    }

    public function payments() {
        return $this->hasMany( Payments::class );
    }
}
