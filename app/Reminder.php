<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Reminder
 *
 * @package App
 */
class Reminder extends Model
{
    /**
     * @var string
     */
    protected $table = 'reminders';

    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'email',
        'title',
        'text',
        'reminder_date',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'order_id' => 'string',
        'user_id' => 'string',
        'email' => 'string',
        'title' => 'string',
        'text' => 'string',
        'reminder_date' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(
            Order::class,
            'order_id',
            'id',
            'order'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id',
            'user'
        );
    }
}
