<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'name_id',
        'slug',
        'attribute',
        'value',
        'decryption',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'name_id' => 'int',
        'slug' => 'string',
        'attribute' => 'string',
        'value' => 'string',
        'decryption' => 'string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stepName(): BelongsTo
    {
        return $this->belongsTo(
            StepName::class,
            'name_id',
            'id',
            'stepName'
        );
    }
}
