<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'simple_text',
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
        'simple_text' => 'string',
        'sender' => 'int',
        'viewed' => 'int',
        'time' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(
            EmailFile::class,
            'message_id',
            'id',
            'files'
        );
    }
}
