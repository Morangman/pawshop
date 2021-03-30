<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class Order
 *
 * @package App
 */
class Order extends Model implements HasMedia
{
    use HasMediaTrait;

    public const MEDIA_COLLECTION_FEDEX = 'fedex';
    public const MEDIA_COLLECTION_UPS = 'ups';

    public const STATUS_NEW = 1;

    /**
     * @var string
     */
    protected $table = 'orders';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'tracking_number',
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
        'tracking_number' => 'int',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderStatus(): BelongsTo
    {
        return $this->belongsTo(
            OrderStatus::class,
            'ordered_status',
            'id',
            'orderStatus'
        );
    }

    /**
     * @return array
     */
    public function getFedexLableAttribute(): array
    {
        return $this->getMedia(static::MEDIA_COLLECTION_FEDEX)
            ->map(static function (Media $media) {
                return [
                    'id' => $media->getKey(),
                    'url' => $media->getUrl(),
                ];
            })
            ->toArray();
    }

    /**
     * @return array
     */
    public function getUpsLableAttribute(): array
    {
        return $this->getMedia(static::MEDIA_COLLECTION_UPS)
            ->map(static function (Media $media) {
                return [
                    'id' => $media->getKey(),
                    'url' => $media->getUrl(),
                ];
            })
            ->toArray();
    }
}
