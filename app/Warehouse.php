<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class Warehouse
 *
 * @package App
 */
class Warehouse extends Model implements HasMedia
{
    use HasMediaTrait;

    public const MEDIA_COLLECTION_WAREHOUSE = 'warehouse';


    public const STATUS_PAID = 1;
    public const STATUS_IN_REPAIR = 2;
    public const STATUS_FOR_SALE = 3;
    public const STATUS_SALED = 4;

    /**
     * @var string
     */
    protected $table = 'warehouses';

    /**
     * @var array
     */
    protected $fillable = [
        'category_id',
        'order_id',
        'status',
        'product_name',
        'imei',
        'serial_number',
        'price',
        'clear_price',
        'is_locked',
        'delivery_price',
        'repair_price',
        'sell_price',
        'steps',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'category_id' => 'int',
        'order_id' => 'int',
        'status' => 'int',
        'product_name' => 'string',
        'imei' => 'string',
        'serial_number' => 'string',
        'price' => 'float',
        'clear_price' => 'float',
        'is_locked' => 'bool',
        'delivery_price' => 'float',
        'repair_price' => 'float',
        'sell_price' => 'float',
        'steps' => 'array',
    ];

    /**
     * @return array
     */
    public function getWarehouseImagesAttribute(): array
    {
        return $this->getMedia(static::MEDIA_COLLECTION_WAREHOUSE)
            ->map(static function (Media $media) {
                return [
                    'id' => $media->getKey(),
                    'url' => $media->getUrl(),
                ];
            })
            ->toArray();
    }
}
