<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AllDailyStatistic
 *
 * @package App
 */
class AllDailyStatistic extends Model
{
   /**
     * @var string
     */
    protected $table = 'all_daily_statistics';

    /**
     * @var array
     */
    protected $fillable = [
        'steps_view_count',
        'steps_coefficient',
        'category_id',
        'title',
        'image',
        'price',
        'date',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'steps_view_count' => 'int',
        'steps_coefficient' => 'float',
        'category_id' => 'int',
        'title' => 'string',
        'image' => 'string',
        'price' => 'float',
        'date' => 'date',
    ];
}