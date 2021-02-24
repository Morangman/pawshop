<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Task
 *
 * @package App
 */
class Task extends Model
{
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
}
