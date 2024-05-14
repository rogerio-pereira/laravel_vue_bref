<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'picture',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's first name.
     */
    protected function picture(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $value ? Storage::url($value) : null ,
        );
    }

    public function deleteTokens() : void 
    {
        $this->tokens()->delete();
    }

    public function newToken(string $name = 'frontend') : string 
    {
        return $this->createToken($name)->plainTextToken;
    }

    public function regenerateToken(string $name = 'frontend')
    {
        $this->deleteTokens();
        return $this->newToken($name);
    }
}
