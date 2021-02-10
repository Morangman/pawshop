<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SuspectIp
 *
 * @package App
 */
class SuspectIp extends Model
{
    /**
     * @var string
     */
    protected $table = 'suspect_ips';

    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'ip_address',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'order_id' => 'int',
        'ip_address' => 'string',
    ];
}
