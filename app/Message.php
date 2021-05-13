<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 *
 * @package App
 */
class Message extends Model
{
    /**
     * @var string
     */
    protected $table = 'messages';

    /**
     * @var array
     */
    protected $fillable = [
        'chat_id',
        'name',
        'email',
        'phone',
        'text',
        'sender',
        'viewed',
        'time',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'chat_id' => 'int',
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'text' => 'string',
        'sender' => 'int',
        'viewed' => 'int',
        'time' => 'datetime',
    ];
}
