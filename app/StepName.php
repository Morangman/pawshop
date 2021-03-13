<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StepName
 *
 * @package App
 */
class StepName extends Model
{
    /**
     * @var string
     */
    protected $table = 'step_names';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'tip_id',
        'title',
        'status',
        'is_condition',
        'is_checkbox',
        'is_functional',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'tip_id' => 'int',
        'name' => 'string',
        'title' => 'string',
        'status' => 'int',
        'is_condition' => 'bool',
        'is_checkbox' => 'bool',
        'is_functional' => 'bool',
    ];
}
