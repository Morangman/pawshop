<?php

declare(strict_types = 1);

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use McMatters\LaravelRoles\Models\Permission;
use McMatters\LaravelRoles\Models\Role;

/**
 * Class UserRoleSeeder
 */
class UserRoleSeeder extends Seeder
{
    /**
     * @return void
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     */
    public function run(): void
    {
        $i = 0;
        $phone = '12345678900';
        $password = 'pass';
        $now = Carbon::now();

        $this->truncateOld();

        foreach (Config::get('roles.roles_permissions', []) as $role => $permissions) {
            /** @var \McMatters\LaravelRoles\Models\Role $roleModel */
            $roleModel = Role::query()->create([
                'name' => $role,
                'level' => ++$i,
            ]);

            foreach ($permissions as $permission) {
                /** @var \McMatters\LaravelRoles\Models\Permission $permissionModel */
                $permissionModel = Permission::query()->create(['name' => $permission,]);
                $roleModel->attachPermission($permissionModel);
            }

            /** @var \App\User $user */
            $user = User::query()->create([
                'name' => $role,
                'email' => "{$role}@example.com",
                'phone' => $phone,
                'email_verified_at' => $now,
                'password' => $password,
            ]);

            $user->attachRole($roleModel);
        }
    }

    /**
     * @return void
     */
    protected function truncateOld(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach (Config::get('roles.tables') as $table) {
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();
    }
}
