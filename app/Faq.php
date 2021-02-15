<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Faq
 *
 * @package App
 */
class Faq extends Model
{
    /**
     * @var string
     */
    protected $table = 'faqs';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'data',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'data' => 'array',
    ];
}
