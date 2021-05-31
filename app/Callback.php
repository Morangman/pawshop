<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Message;

/**
 * Class Callback
 *
 * @package App
 */
class Callback extends Model
{
    public const SENDER_FROM = 1;
    public const SENDER_TO = 2;

    public const NOT_VIEWED = 1;
    public const IS_VIEWED = 2;

    /**
     * @var string
     */
    protected $table = 'callbacks';

     /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_answered',
    ];

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function newMessages(): HasMany
    {
        return $this->hasMany(
            Message::class,
            'chat_id',
            'id',
            'messages'
        )->where('viewed', '=', $this::NOT_VIEWED);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function message(): HasMany
    {
        return $this->hasMany(
            Message::class,
            'chat_id',
            'id',
            'messages'
        );
    }


    /**
     * @return bool
     */
    public function getIsAnsweredAttribute(): bool
    {
        $lastMsg = $this->messages()->orderBy('created_at', 'desc')->first();

        return $lastMsg ? $lastMsg->getAttribute('sender') == $this::SENDER_TO : false;
    }

    /**
     * Get count of new messages.
     *
     * @return int
     */
    public function messagesCount(): int
    {
        return $this->messages()->where('viewed', '=', $this::NOT_VIEWED)->count();
    }
}
