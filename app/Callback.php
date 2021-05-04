<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Callback
 *
 * @package App
 */
class Callback extends Model
{
    public const SENDER_FROM = 1;
    public const SENDER_TO = 2;

    public const IS_VIEWED = 1;

    /**
     * @var string
     */
    protected $table = 'callbacks';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'text',
        'sender',
        'viewed',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'text' => 'string',
        'sender' => 'int',
        'viewed' => 'int',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(
            Message::class,
            'chat_id',
            'id',
            'messages'
        );
    }
}
