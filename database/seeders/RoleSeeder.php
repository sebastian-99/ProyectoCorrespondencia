<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rol1 = Role::create(['name' => 'Administrador']);
        $rol2 = Role::create(['name' => 'Usuario']);
        $rol3 = Role::create(['name' => 'Rector']);
        $rol4 = Role::create(['name' => 'Asistente']);

        Permission::create(['name' => 'Administracion'])->syncRoles($rol1);
        
        Permission::create(['name' => 'users.index'])->syncRoles($rol1);
        Permission::create(['name' => 'users.create'])->syncRoles($rol1);
        Permission::create(['name' => 'users.edit'])->syncRoles($rol1);
        Permission::create(['name' => 'users.destroy'])->syncRoles($rol1);

        Permission::create(['name' => 'areas.index'])->syncRoles($rol1);
        Permission::create(['name' => 'areas.create'])->syncRoles($rol1);
        Permission::create(['name' => 'areas.edit'])->syncRoles($rol1);
        Permission::create(['name' => 'areas.destroy'])->syncRoles($rol1);

        Permission::create(['name' => 'tipos-actividades.index'])->syncRoles($rol1);
        Permission::create(['name' => 'tipos-actividades.create'])->syncRoles($rol1);
        Permission::create(['name' => 'tipos-actividades.edit'])->syncRoles($rol1);
        Permission::create(['name' => 'tipos-actividades.destroy'])->syncRoles($rol1);

        Permission::create(['name' => 'ver-todas-actividades'])->syncRoles($rol1,$rol3);

        Permission::create(['name' => 'crear-actividades'])->syncRoles($rol2,$rol4,$rol3);
        Permission::create(['name' => 'ver-actividades-pendientes'])->syncRoles($rol2,$rol4,$rol3);
        Permission::create(['name' => 'ver-actividades-creadas'])->syncRoles($rol2,$rol4,$rol3);
        Permission::create(['name' => 'ver-actividades-asignadas'])->syncRoles($rol2,$rol4);

        Permission::create(['name' => 'ver-seguimientos'])->syncRoles($rol2,$rol4);
        
    }
}
