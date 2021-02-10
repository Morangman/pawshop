<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 *
 * @package App
 */
class Comment extends Model
{
    /**
     * @var string
     */
    protected $table = 'comments';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'text',
        'value',
        'is_hidden',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'text' => 'string',
        'value' => 'int',
        'is_hidden' => 'bool',
    ];
}
