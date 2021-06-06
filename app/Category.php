<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class Category
 *
 * @package App
 */
class Category extends Model implements HasMedia
{
    use HasMediaTrait;

    public const MEDIA_COLLECTION_CATEGORY = 'category';

    public const TAB_PRODUCT = 1;
    public const TAB_CATEGORY = 2;

    public const IS_PARSED = 1;

    /**
     * @var string
     */
    protected $table = 'categories';

    /**
     * @var array
     */
    protected $fillable = [
        'subcategory_id',
        'user_id',
        'faq_id',
        'name',
        'slug',
        'image',
        'compressed_image',
        'custom_text',
        'premium_price',
        'price_for_broken',
        'is_hidden',
        'is_parsed',
        'is_recycle',
        'icloud_locked',
        'google_locked',
        'box_count',
        'view_count',
        'prod_year',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'subcategory_id' => 'int',
        'user_id' => 'int',
        'faq_id' => 'int',
        'name' => 'string',
        'slug' => 'string',
        'image' => 'string',
        'compressed_image' => 'string',
        'custom_text' => 'string',
        'premium_price' => 'string',
        'price_for_broken' => 'string',
        'is_hidden' => 'bool',
        'is_parsed' => 'bool',
        'is_recycle' => 'bool',
        'icloud_locked' => 'bool',
        'google_locked' => 'bool',
        'box_count' => 'int',
        'view_count' => 'int',
        'prod_year' => 'int',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    /**
     * Get orders by device id
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(
            Order::class,
            'order_device',
            'category_id',
            'id'
        );
    }

    public function getUrl()
    { 
        $url = $this->slug;
    
        $category = $this;

        $urlArr = [];

        while ($category = $category->subcategory) {
            //$url = $category->slug.'/'.$url;

            $urlArr[] = $category->slug;
        }
    
        return !empty($urlArr) ? end($urlArr).'/'.$url : $url;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function steps(): BelongsToMany
    {
        return $this->belongsToMany(Step::class, CategoryStep::class)->withPivot('sort_order');
    }

    /**
     * @return void
     */
    public function registerMediaConversions(Media $media = null): void
    {
       $this->addMediaConversion('thumb')
            ->format('webp')
            ->width(260)
            ->height(260);
    }
}
