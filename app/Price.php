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
        'updated',
        'category_id',
        'steps_ids',
        'price',
        'custom_price',
        'is_parsed',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'updated' => 'int',
        'category_id' => 'int',
        'steps_ids' => 'array',
        'price' => 'string',
        'custom_price' => 'string',
        'is_parsed' => 'bool',
    ];
}
