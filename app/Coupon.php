<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Coupon
 *
 * @package App
 */
class Coupon extends Model
{
    /**
     * @var string
     */
    protected $table = 'coupons';

    /**
     * @var array
     */
    protected $fillable = [
        'category_id',
        'name',
        'code',
        'percent_value',
        'text',
        'is_hidden',
        'start_date',
        'end_date',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'category_id' => 'int',
        'name' => 'string',
        'code' => 'string',
        'percent_value' => 'float',
        'text' => 'string',
        'is_hidden' => 'bool',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
