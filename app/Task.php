<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class Task
 *
 * @package App
 */
class Task extends Model implements HasMedia
{
    use HasMediaTrait;

    public const MEDIA_COLLECTION_TASK = 'task';

    public const STATUS_NEW = 1;
    public const STATUS_IN_PROCESS = 2;
    public const STATUS_COMPLETED = 3;
    public const STATUS_POSTPONED = 4;

    /**
     * @var string
     */
    protected $table = 'tasks';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'text',
        'notes',
        'task_status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'name' => 'name',
        'text' => 'string',
        'notes' => 'string',
        'task_status' => 'int',
    ];

    /**
     * @return array
     */
    public function getTaskVideosAttribute(): array
    {
        return $this->getMedia(static::MEDIA_COLLECTION_TASK)
            ->map(static function (Media $media) {
                return [
                    'id' => $media->getKey(),
                    'url' => $media->getUrl(),
                ];
            })
            ->toArray();
    }
}
