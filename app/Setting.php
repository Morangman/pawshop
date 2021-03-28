<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Setting
 *
 * @package App
 */
class Setting extends Model implements HasMedia
{
    use HasMediaTrait;

    public const MEDIA_COLLECTION_SETTING = 'setting';

    /**
     * @var string
     */
    protected $table = 'settings';

    /**
     * @var array
     */
    protected $fillable = [
        'general_settings',
        'terms',
        'code_insert',
        'user_agreement',
        'law_enforcement',
        'privacy_policy',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'general_settings' => 'array',
        'terms' => 'string',
        'user_agreement' => 'string',
        'law_enforcement' => 'string',
        'privacy_policy' => 'string',
        'code_insert' => 'string',
    ];
}
