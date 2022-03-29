<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Lang;
use Spatie\MediaLibrary\Models\Media;
use Str;

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
    public const STATUS_CANCELLED = 2;
    public const STATUS_TRANSIT = 3;
    public const STATUS_ORDER_DELIVERED = 4;
    public const STATUS_RECEIVED = 5;
    public const STATUS_PAID = 6;
    public const STATUS_RESTORED = 7;
    // public const STATUS_SOLD = 8;

    public const STATUS_CHANGED = 1;
    public const STATUS_NOT_CONFIRMED = 1;
    public const STATUS_CONFIRMED = 2;
    public const STATUS_DECLINED = 3;

    public const STATUS_SHIPMENT_CREATED = 'OC';
    public const STATUS_SHIPMENT_SCHEDULED = 'SS';
    public const STATUS_PICKED_UP = 'PU';
    public const STATUS_IN_TRANSIT = 'IT';
    public const STATUS_ARRIVED = 'AR';
    public const STATUS_AT_FEDEX_FACILITY = 'AF';
    public const STATUS_AT_SORT_FACILITY = 'SF';
    public const STATUS_ON_FEDEX_VEHICLE = 'OD';
    public const STATUS_ON_ORIGIN_FACILITY = 'OF';
    public const STATUS_LEFT_ORIGIN = 'LO';
    public const STATUS_CLEARANCE_DELAY = 'CD';
    public const STATUS_ON_FEDEX_FACILITY = 'FD';
    public const STATUS_DELIVERED = 'DL';

    /**
     * @var string
     */
    protected $table = 'orders';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_cancelled',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'tracking_number',
        'user_email',
        'orders',
        'total_summ',
        'payment',
        'address',
        'comment',
        'exp_service',
        'insurance',
        'notes',
        'ordered_status',
        'fedex_status',
        'ip_address',
        'estimate_date',
        'delivered_date',
        'delivery_price',
        'paid_date',
        'received_date',
        'cancelled_date',
        'is_transit_notify',
        'is_received_notify',
        'send_ctn',
        'is_label_trouble',
        'is_restored',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'uuid' => 'string',
        'user_id' => 'int',
        'tracking_number' => 'int',
        'user_email' => 'string',
        'orders' => 'array',
        'total_summ' => 'string',
        'payment' => 'array',
        'address' => 'array',
        'comment' => 'string',
        'exp_service' => 'string',
        'insurance' => 'string',
        'notes' => 'string',
        'ordered_status' => 'int',
        'fedex_status' => 'string',
        'ip_address' => 'string',
        'estimate_date' => 'datetime',
        'delivered_date' => 'datetime',
        'delivery_price' => 'float',
        'paid_date' => 'datetime',
        'received_date' => 'datetime',
        'cancelled_date' => 'datetime',
        'is_transit_notify' => 'int',
        'is_received_notify' => 'int',
        'send_ctn' => 'int',
        'is_label_trouble' => 'bool',
        'is_restored' => 'bool',
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

    /**
     * Get categories by order id
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'order_device',
            'order_id',
            'id'
        );
    }

    /**
     * @return bool
     */
    public function getIsCancelledAttribute(): bool
    {
        return $this->ordered_status === $this::STATUS_CANCELLED;
    }

    /**
     * @return float
     */
    public function getTotalSummAttribute($value)
    {
        return number_format((float) $value, 2, '.', '');
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

    /**
     * @return array
     */
    public static function getStatusesOptions(): array
    {
        return [
            [
                'label' => Lang::get(
                    'admin/order.fedex_statuses.'.static::STATUS_SHIPMENT_CREATED
                ),
                'value' => static::STATUS_SHIPMENT_CREATED,
            ],
            [
                'label' => Lang::get(
                    'admin/order.fedex_statuses.'.static::STATUS_SHIPMENT_SCHEDULED
                ),
                'value' => static::STATUS_SHIPMENT_SCHEDULED,
            ],
            [
                'label' => Lang::get(
                    'admin/order.fedex_statuses.'.static::STATUS_PICKED_UP
                ),
                'value' => static::STATUS_PICKED_UP,
            ],
            [
                'label' => Lang::get(
                    'admin/order.fedex_statuses.'.static::STATUS_IN_TRANSIT
                ),
                'value' => static::STATUS_IN_TRANSIT,
            ],
            [
                'label' => Lang::get(
                    'admin/order.fedex_statuses.'.static::STATUS_DELIVERED
                ),
                'value' => static::STATUS_DELIVERED,
            ],
        ];
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    public static function isValidStatus(string $status): bool
    {
        return in_array($status, [
            static::STATUS_SHIPMENT_CREATED,
            static::STATUS_SHIPMENT_SCHEDULED,
            static::STATUS_PICKED_UP,
            static::STATUS_IN_TRANSIT,
            static::STATUS_DELIVERED,
            static::STATUS_ARRIVED,
            static::STATUS_ON_FEDEX_VEHICLE,
            static::STATUS_ON_FEDEX_FACILITY,
            static::STATUS_AT_FEDEX_FACILITY,
            static::STATUS_AT_SORT_FACILITY,
            static::STATUS_ON_ORIGIN_FACILITY,
            static::STATUS_LEFT_ORIGIN,
            static::STATUS_CLEARANCE_DELAY,
        ], true);
    }
}
