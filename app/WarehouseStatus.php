<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WarehouseStatus
 *
 * @package App
 */
class WarehouseStatus extends Model
{
    /**
     * @var string
     */
    protected $table = 'warehouse_statuses';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'color',
        'order',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'color' => 'string',
        'order' => 'int',
    ];
}
