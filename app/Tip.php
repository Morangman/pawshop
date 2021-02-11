<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tip
 *
 * @package App
 */
class Tip extends Model
{
    /**
     * @var string
     */
    protected $table = 'tips';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'text',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'name' => 'name',
        'text' => 'string',
    ];
}
