<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRolesTable
 */
class CreateRolesTable extends Migration
{
    /**
     * @var string
     */
    protected $table;

    /**
     * CreateRolesTable constructor.
     */
    public function __construct()
    {
        $this->table = Config::get('roles.tables.roles');
    }

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->unsignedSmallInteger('level')->default(1);
            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
}
