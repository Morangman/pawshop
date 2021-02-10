<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use McMatters\LaravelRoles\Traits\HasPermission;
use McMatters\LaravelRoles\Traits\HasRole;

/**
 * Class User
 *
 * @package App
 */
class User extends Authenticatable
{
    use HasPermission;
    use HasRole;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param string $value
     *
     * @return void
     */
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotifiableUsers(Builder $query): Builder
    {
        return $query->whereHas('roles', function (Builder $subQuery) {
            return $subQuery->where('roles.name', 'admin')
                ->orWhere('roles.name', 'superadmin')
                ->orWhere('roles.name', 'manager');
        });
    }
}
