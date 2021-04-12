<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Callback
 *
 * @package App
 */
class Callback extends Model
{
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
    ];

    /**
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'text' => 'string',
    ];
}
