<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Parent Permissions
            ['name' => 'manage_roles', 'label' => 'Manage Roles', 'parent_id' => '', 'route_name' => 'roles.index', 'role_id' => '1'],

            ['name' => 'manage_permissions', 'label' => 'Manage Permissions', 'parent_id' => '', 'route_name' => 'permissions.index', 'role_id' => '1'],

            ['name' => 'manage_users', 'label' => 'Manage Users', 'parent_id' => '', 'route_name' => 'users'],

            ['name' => 'manage_packages', 'label' => 'Manage Packages', 'parent_id' => '', 'route_name' => 'packages'],

            ['name' => 'manage_features', 'label' => 'Manage Features', 'parent_id' => '', 'route_name' => 'features'],

            ['name' => 'manage_promocodes', 'label' => 'Manage PromoCodes', 'parent_id' => '', 'route_name' => 'promo-code'],

            ['name' => 'manage_orders', 'label' => 'Manage Orders', 'parent_id' => '', 'route_name' => 'orders'],

            ['name' => 'manage_transactions', 'label' => 'Manage Transactions', 'parent_id' => '', 'route_name' => 'transactions'],

            ['name' => 'manage_fields', 'label' => 'Manage Fields', 'parent_id' => '', 'route_name' => 'fields'],

            ['name' => 'manage_booked_services', 'label' => 'Manage Booked Services', 'parent_id' => '', 'route_name' => 'booked_service'],

            ['name' => 'manage_chats', 'label' => 'Manage Chats', 'parent_id' => '', 'route_name' => 'chats'],

            //Manage Roles
            ['name' => 'view_role', 'label' => 'View Role', 'parent_id' => 'manage_roles', 'route_name' => 'roles.index', 'role_id' => '1'],

            ['name' => 'add_role', 'label' => 'Add Role', 'parent_id' => 'manage_roles', 'route_name' => 'roles.create', 'role_id' => '1'],

            ['name' => 'edit_role', 'label' => 'Edit Role', 'parent_id' => 'manage_roles', 'route_name' => 'roles.edit', 'role_id' => '1'],

            ['name' => 'delete_role', 'label' => 'Delete Role', 'parent_id' => 'manage_roles', 'route_name' => 'roles.destory', 'role_id' => '1'],

            ['name' => 'assign_role_permissions', 'label' => 'Assign Role Permissions', 'parent_id' => 'manage_roles', 'route_name' => 'roles.assign-permissions', 'role_id' => '1'],

            //Manage Permissions
            ['name' => 'view_permission', 'label' => 'View Permission', 'parent_id' => 'manage_permissions', 'route_name' => 'permissions.index', 'role_id' => '1'],

            ['name' => 'add_permission', 'label' => 'Add Permission', 'parent_id' => 'manage_permissions', 'route_name' => 'permissions.create', 'role_id' => '1'],

            ['name' => 'edit_permission', 'label' => 'Edit Permission', 'parent_id' => 'manage_permissions', 'route_name' => 'permissions.edit', 'role_id' => '1'],

            ['name' => 'delete_permission', 'label' => 'Delete Permission', 'parent_id' => 'manage_permissions', 'route_name' => 'permissions.destroy', 'role_id' => '1'],

            //Manage Users
            ['name' => 'view_user', 'label' => 'View User', 'parent_id' => 'manage_users', 'route_name' => 'users.index'],

            ['name' => 'add_user', 'label' => 'Add User', 'parent_id' => 'manage_users', 'route_name' => 'users.create'],

            ['name' => 'edit_user', 'label' => 'Edit User', 'parent_id' => 'manage_users', 'route_name' => 'users.edit'],

            ['name' => 'delete_user', 'label' => 'Delete User', 'parent_id' => 'manage_users', 'route_name' => 'users.destroy'],

            //Manage Package
            ['name' => 'view_package', 'label' => 'View Package', 'parent_id' => 'manage_packages', 'route_name' => 'packages.index'],

            ['name' => 'add_package', 'label' => 'Add Package', 'parent_id' => 'manage_packages', 'route_name' => 'packages.create'],

            ['name' => 'edit_package', 'label' => 'Edit Package', 'parent_id' => 'manage_packages', 'route_name' => 'packages.edit'],

            ['name' => 'delete_package', 'label' => 'Delete Package', 'parent_id' => 'manage_packages', 'route_name' => 'packages.destroy'],

            //Manage Features
            ['name' => 'view_feature', 'label' => 'View Feature', 'parent_id' => 'manage_features', 'route_name' => 'features.index'],

            ['name' => 'add_feature', 'label' => 'Add Feature', 'parent_id' => 'manage_features', 'route_name' => 'features.create'],

            ['name' => 'edit_feature', 'label' => 'Edit Feature', 'parent_id' => 'manage_features', 'route_name' => 'features.edit'],

            ['name' => 'delete_feature', 'label' => 'Delete Feature', 'parent_id' => 'manage_features', 'route_name' => 'features.destroy'],

            // Manage Promo Codes
            ['name' => 'view_promocode', 'label' => 'View Promo Code', 'parent_id' => 'manage_promocodes', 'route_name' => 'promo-code.index'],

            ['name' => 'add_promocode', 'label' => 'Add Promo Code', 'parent_id' => 'manage_promocodes', 'route_name' => 'promo-code.create'],

            ['name' => 'edit_promocode', 'label' => 'Edit Promo Code', 'parent_id' => 'manage_promocodes', 'route_name' => 'promo-code.edit'],

            ['name' => 'delete_promocode', 'label' => 'Delete Promo Code', 'parent_id' => 'manage_promocodes', 'route_name' => 'promo-code.destroy'],

            // Manage Orders
            ['name' => 'view_orders', 'label' => 'View Orders', 'parent_id' => 'manage_orders', 'route_name' => 'orders.index'],

            ['name' => 'add_orders', 'label' => 'Add Orders', 'parent_id' => 'manage_orders', 'route_name' => 'orders.create'],

            ['name' => 'edit_orders', 'label' => 'Edit Orders', 'parent_id' => 'manage_orders', 'route_name' => 'orders.edit'],

            ['name' => 'delete_orders', 'label' => 'Delete Orders', 'parent_id' => 'manage_orders', 'route_name' => 'orders.destroy'],

            // Manage Transactions
            ['name' => 'view_transactions', 'label' => 'View Transactions', 'parent_id' => 'manage_transactions', 'route_name' => 'transactions.index'],

            ['name' => 'add_transactions', 'label' => 'Add Transactions', 'parent_id' => 'manage_transactions', 'route_name' => 'transactions.create'],

            ['name' => 'edit_transactions', 'label' => 'Edit Transactions', 'parent_id' => 'manage_transactions', 'route_name' => 'transactions.edit'],

            ['name' => 'delete_transactions', 'label' => 'Delete Transactions', 'parent_id' => 'manage_transactions', 'route_name' => 'transactions.destroy'],

            // Manage Fields
            ['name' => 'view_fields', 'label' => 'View Fields', 'parent_id' => 'manage_fields', 'route_name' => 'fields.index'],

            ['name' => 'add_fields', 'label' => 'Add Fields', 'parent_id' => 'manage_fields', 'route_name' => 'fields.create'],

            ['name' => 'edit_fields', 'label' => 'Edit Fields', 'parent_id' => 'manage_fields', 'route_name' => 'fields.edit'],

            ['name' => 'delete_fields', 'label' => 'Delete Fields', 'parent_id' => 'manage_fields', 'route_name' => 'fields.destroy'],

            // Manage Booked Services
            ['name' => 'view_booked_services', 'label' => 'View Booked Service', 'parent_id' => 'manage_booked_services', 'route_name' => 'booked-services.index'],

            ['name' => 'edit_booked_services', 'label' => 'Edit Booked Service', 'parent_id' => 'manage_booked_services', 'route_name' => 'booked-services.edit'],

            ['name' => 'delete_booked_services', 'label' => 'Delete Booked Service', 'parent_id' => 'manage_booked_services', 'route_name' => 'booked-services.destroy'],

            // Manage Chats
            ['name' => 'view_chats', 'label' => 'View Chat', 'parent_id' => 'manage_chats', 'route_name' => 'chats.index'],

            ['name' => 'add_chats', 'label' => 'Add Chat', 'parent_id' => 'manage_chats', 'route_name' => 'chats.create'],

            ['name' => 'edit_chats', 'label' => 'Edit Chat', 'parent_id' => 'manage_chats', 'route_name' => 'chats.edit'],

            ['name' => 'delete_chats', 'label' => 'Delete Chat', 'parent_id' => 'manage_chats', 'route_name' => 'chats.destroy'],

            ['name' => 'response_chat', 'label' => 'Response Chat', 'parent_id' => 'manage_chats', 'route_name' => 'chats.response'],



        ];
        try {
            foreach ($permissions as $permission) {
                if (!empty($permission['parent_id'])) {
                    $parent_id = Permission::where('name', $permission['parent_id'])->first();
                    if (empty($parent_id)) {
                        dd($permission);
                    }
                    $permission['parent_id'] = $parent_id->id;
                }
                $data = [
                    'label' => $permission['label'],
                    'name' => $permission['name'],
                    'guard_name' => 'web',
                    'parent_id' => $permission['parent_id'],
                    'route_name' => $permission['route_name'],
                    'role_id' => isset($permission['role_id']) ? $permission['role_id'] : '',
                    'created_by' => '1',
                ];
                Permission::updateOrCreate(['name' => $permission['name']], $data);
            }
            // set permissions to Super admin
            $super_admin_role = Role::where('name', 'Super Admin')->first();
            $get_all_permissions = Permission::get()->pluck('id')->toArray();
            $super_admin_role->syncPermissions($get_all_permissions);
            // set permissions to Super admin
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}
