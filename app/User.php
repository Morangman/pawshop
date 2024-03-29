<?php

declare(strict_types = 1);

namespace App;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
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
        'name',
        'phone',
        'email',
        'addresses',
        'password',
        'email_verified_at',
        'register_code',
        'is_active',
        'is_blocked',
        'mail_subscription',
        'last_accessed_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'addresses' => 'array',
        'email_verified_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'register_code' => 'int',
        'is_active' => 'bool',
        'is_blocked' => 'bool',
        'mail_subscription' => 'bool',
        'password' => 'string',
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
    public function scopeAdmins(Builder $query): Builder
    {
        return $query->whereHas('roles', function (Builder $subQuery) {
            return $subQuery->where('roles.name', 'admin')
                ->orWhere('roles.name', 'superadmin')
                ->orWhere('roles.name', 'manager');
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsers(Builder $query): Builder
    {
        return $query->whereHas('roles', function (Builder $subQuery) {
            return $subQuery->where('roles.name', 'user');
        });
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

    /**
     * @param string|null $by
     * @param string|null $dir
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications(string $by = null, string $dir = null): MorphMany
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')
            ->orderBy($by ?? 'created_at', $dir ?? 'desc');
    }

    /**
     * @param mixed $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        //$this->notify(new ResetPasswordNotification($token));
    }
}
