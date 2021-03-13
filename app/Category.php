<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Category
 *
 * @package App
 */
class Category extends Model implements HasMedia
{
    use HasMediaTrait;

    public const MEDIA_COLLECTION_CATEGORY = 'category';

    /**
     * @var string
     */
    protected $table = 'categories';

    /**
     * @var array
     */
    protected $fillable = [
        'subcategory_id',
        'faq_id',
        'name',
        'slug',
        'image',
        'custom_text',
        'is_hidden',
        'is_parsed',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'subcategory_id' => 'int',
        'faq_id' => 'int',
        'name' => 'string',
        'slug' => 'string',
        'image' => 'string',
        'custom_text' => 'string',
        'is_hidden' => 'bool',
        'is_parsed' => 'bool',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'subcategory_id',
            $this->primaryKey
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function steps(): BelongsToMany
    {
        return $this->belongsToMany(Step::class, CategoryStep::class)->withPivot('sort_order');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
