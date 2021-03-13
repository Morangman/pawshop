<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Price
 *
 * @package App
 */
class Price extends Model
{
    /**
     * @var string
     */
    protected $table = 'prices';

    /**
     * @var array
     */
    protected $fillable = [
        'category_id',
        'steps_ids',
        'price',
        'is_parsed',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'category_id' => 'int',
        'steps_ids' => 'array',
        'price' => 'string',
        'is_parsed' => 'bool',
    ];
}
