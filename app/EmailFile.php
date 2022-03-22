<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailFile
 *
 * @package App
 */
class EmailFile extends Model
{
    /**
     * @var string
     */
    protected $table = 'email_files';

    /**
     * @var array
     */
    protected $fillable = [
        'chat_id',
        'message_id',
        'url',
        'mime',
        'type',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'chat_id' => 'int',
        'message_id' => 'int',
        'url' => 'string',
        'mime' => 'string',
        'type' => 'string',
    ];
}
