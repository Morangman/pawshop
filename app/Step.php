<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Step
 *
 * @package App
 */
class Step extends Model
{
    /**
     * @var string
     */
    protected $table = 'steps';

    /**
     * @var array
     */
    protected $fillable = [
        'tip_id',
        'name',
        'items',
        'is_condition',
        'is_checkboxes',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'tip_id' => 'int',
        'name' => 'string',
        'items' => 'array',
        'is_condition' => 'bool',
        'is_checkboxes' => 'bool',
    ];
}
