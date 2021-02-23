<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 *
 * @package App
 */
class Order extends Model
{
    public const STATUS_NEW = 1;
    public const STATUS_IN_PROCESS = 2;
    public const STATUS_PAYMENT_EXPECTED = 3;
    public const STATUS_COMPLETED = 4;
    public const STATUS_POSTPONED = 5;

    /**
     * @var string
     */
    protected $table = 'orders';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'user_email',
        'orders',
        'total_summ',
        'payment',
        'address',
        'exp_service',
        'insurance',
        'notes',
        'ordered_status',
        'ip_address',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'user_id' => 'int',
        'user_email' => 'string',
        'orders' => 'array',
        'total_summ' => 'string',
        'payment' => 'array',
        'address' => 'array',
        'exp_service' => 'string',
        'insurance' => 'string',
        'notes' => 'string',
        'ordered_status' => 'int',
        'ip_address' => 'string',
    ];
}
