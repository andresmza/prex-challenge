<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \App\Models\User factory(array $attributes = [])
 * @method static \App\Models\User firstOrCreate(array $attributes, array $values = [])
 * @method static \App\Models\User|null firstWhere(string $column, mixed $value)
 * @method static \App\Models\User updateOrCreate(array $attributes, array $values = [])
 * @method static \App\Models\User create(array $attributes = [])
 * @method static \App\Models\User make(array $attributes = [])
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @method \Illuminate\Database\Eloquent\Relations\HasMany tokens()
 * @method \Laravel\Passport\PersonalAccessTokenResult createToken(string $name, array $scopes = [])
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}
