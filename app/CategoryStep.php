<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CategoryStep
 *
 * @package App
 */
class CategoryStep extends Model
{
    /**
     * @var string
     */
    protected $table = 'category_step';

    /**
     * @var array
     */
    protected $fillable = [
        'category_id',
        'step_id',
        'sort_order',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'category_id' => 'int',
        'step_id' => 'int',
        'sort_order' => 'int',
    ];
}
