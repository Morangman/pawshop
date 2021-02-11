<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'name',
        'image',
        'custom_text',
        'is_hidden',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'subcategory_id' => 'int',
        'name' => 'string',
        'image' => 'string',
        'custom_text' => 'string',
        'is_hidden' => 'bool',
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
}
