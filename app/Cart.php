<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Cart
 *
 * @package App
 */
class Cart extends Model
{
   /**
     * @var string
     */
    protected $table = 'carts';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'orders',
        'send_ctn',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'user_id' => 'int',
        'orders' => 'array',
        'send_ctn' => 'int',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id',
            'user'
        );
    }
}
