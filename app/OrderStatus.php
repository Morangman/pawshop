<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderStatus
 *
 * @package App
 */
class OrderStatus extends Model
{
    /**
     * @var string
     */
    protected $table = 'order_status';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'color',
        'fedex_status',
        'order',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'color' => 'string',
        'fedex_status' => 'string',
        'order' => 'int',
    ];
}
