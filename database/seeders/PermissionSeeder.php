<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $users_group = PermissionGroup::create(['name' => 'users permissions', 'title' => 'مدیریت کاربران']);
        $other_group = PermissionGroup::create(['name' => 'other permissions', 'title' => 'متفرقه']);
        $permissions_group = PermissionGroup::create(['name' => 'permissions management', 'title' => 'مدیریت دسترسی ها']);
        $product_category_permissions = PermissionGroup::create(['name' => 'product category permissions', 'title' => 'مدیریت دسته بندی محصولات']);
        $product_permissions = PermissionGroup::create(['name' => 'product permissions', 'title' => 'مدیریت محصولات']);
        $inventory_permissions = PermissionGroup::create(['name' => 'inventory permissions', 'title' => 'مدیریت انبارداری']);
        $productChanges_permissions = PermissionGroup::create(['name' => 'productChanges permissions', 'title' => 'مدیریت اسناد']);
        $factors_permissions = PermissionGroup::create(['name' => 'factors permissions', 'title' => 'مدیریت فاکتور ها']);
        $wallets_permissions = PermissionGroup::create(['name' => 'wallets permissions', 'title' => 'مدیریت صندوق ها']);
        $payments_permissions = PermissionGroup::create(['name' => 'payments permissions', 'title' => 'مدیریت پرداخت ها']);
        $reports_permissions = PermissionGroup::create(['name' => 'reports permissions', 'title' => 'مدیریت گزارشات ']);
        $customer_permissions = PermissionGroup::create(['name' => 'customers permissions', 'title' => 'مدیریت مشتریان ']);

        Permission::create(['name' => 'view panel', 'title' => 'مشاهده پنل', 'group_id' => $other_group->id]);

      
        Permission::create(['name' => 'create product', 'title' => 'ایجاد محصول', 'group_id' => $product_permissions->id]);
        Permission::create(['name' => 'view products', 'title' => 'مشاهده محصول', 'group_id' => $product_permissions->id]);
        Permission::create(['name' => 'edit product', 'title' => 'ویرایش محصول', 'group_id' => $product_permissions->id]);
        Permission::create(['name' => 'delete product', 'title' => 'حذف محصول', 'group_id' => $product_permissions->id]);
      
        Permission::create(['name' => 'create inventory', 'title' => 'ایجاد انبار داری', 'group_id' => $inventory_permissions->id]);
        Permission::create(['name' => 'view inventorys', 'title' => 'مشاهده انبار داری', 'group_id' => $inventory_permissions->id]);
        Permission::create(['name' => 'edit inventory', 'title' => 'ویرایش انبار داری', 'group_id' => $inventory_permissions->id]);
        Permission::create(['name' => 'delete inventory', 'title' => 'حذف انبار داری', 'group_id' => $inventory_permissions->id]);

        Permission::create(['name' => 'create productChange', 'title' => 'ایجاد سند', 'group_id' => $productChanges_permissions->id]);
        Permission::create(['name' => 'view productChanges', 'title' => 'مشاهده سند', 'group_id' => $productChanges_permissions->id]);
        Permission::create(['name' => 'edit productChange', 'title' => 'ویرایش سند', 'group_id' => $productChanges_permissions->id]);
        Permission::create(['name' => 'delete productChange', 'title' => 'حذف سند', 'group_id' => $productChanges_permissions->id]);

        Permission::create(['name' => 'create factor', 'title' => 'ایجاد فاکتور', 'group_id' => $factors_permissions->id]);
        Permission::create(['name' => 'view factors', 'title' => 'مشاهده فاکتور', 'group_id' => $factors_permissions->id]);
        Permission::create(['name' => 'edit factor', 'title' => 'ویرایش فاکتور', 'group_id' => $factors_permissions->id]);
        Permission::create(['name' => 'delete factor', 'title' => 'حذف فاکتور', 'group_id' => $factors_permissions->id]);

        Permission::create(['name' => 'create wallet', 'title' => 'ایجاد صندوق پول', 'group_id' => $wallets_permissions->id]);
        Permission::create(['name' => 'view wallets', 'title' => 'مشاهده صندوق پول', 'group_id' => $wallets_permissions->id]);
        Permission::create(['name' => 'edit wallet', 'title' => 'ویرایش صندوق پول', 'group_id' => $wallets_permissions->id]);
        Permission::create(['name' => 'delete wallet', 'title' => 'حذف صندوق پول', 'group_id' => $wallets_permissions->id]);

        Permission::create(['name' => 'create payment', 'title' => 'ایجاد پرداختی', 'group_id' => $payments_permissions->id]);
        Permission::create(['name' => 'view payments', 'title' => 'مشاهده پرداختی', 'group_id' => $payments_permissions->id]);
        Permission::create(['name' => 'edit payment', 'title' => 'ویرایش پرداختی', 'group_id' => $payments_permissions->id]);
        Permission::create(['name' => 'delete payment', 'title' => 'حذف پرداختی', 'group_id' => $payments_permissions->id]);

        Permission::create(['name' => 'create report', 'title' => 'ایجاد گزارشات', 'group_id' => $reports_permissions->id]);
        Permission::create(['name' => 'view reports', 'title' => 'مشاهده گزارشات', 'group_id' => $reports_permissions->id]);
        Permission::create(['name' => 'edit report', 'title' => 'ویرایش گزارشات', 'group_id' => $reports_permissions->id]);
        Permission::create(['name' => 'delete report', 'title' => 'حذف گزارشات', 'group_id' => $reports_permissions->id]);

        Permission::create(['name' => 'create customer', 'title' => 'ایجاد گزارشات', 'group_id' => $customer_permissions->id]);
        Permission::create(['name' => 'view customers', 'title' => 'مشاهده گزارشات', 'group_id' => $customer_permissions->id]);
        Permission::create(['name' => 'edit customer', 'title' => 'ویرایش گزارشات', 'group_id' => $customer_permissions->id]);
        Permission::create(['name' => 'delete customer', 'title' => 'حذف گزارشات', 'group_id' => $customer_permissions->id]);

     

        Permission::create(['name' => 'create category', 'title' => 'ایجاد دسته بندی محصول', 'group_id' => $product_category_permissions->id]);
        Permission::create(['name' => 'view category', 'title' => 'مشاهده دسته بندی محصول', 'group_id' => $product_category_permissions->id]);
        Permission::create(['name' => 'edit category', 'title' => 'ویرایش دسته بندی محصول', 'group_id' => $product_category_permissions->id]);
        Permission::create(['name' => 'delete category', 'title' => 'حذف دسته بندی محصول', 'group_id' => $product_category_permissions->id]);


        Permission::create(['name' => 'create user', 'title' => 'ایجاد کاربر', 'group_id' => $users_group->id]);
        Permission::create(['name' => 'view users', 'title' => 'مشاهده کاربر', 'group_id' => $users_group->id]);
        Permission::create(['name' => 'edit user', 'title' => 'ویرایش کاربر', 'group_id' => $users_group->id]);
        Permission::create(['name' => 'delete user', 'title' => 'حذف کاربر', 'group_id' => $users_group->id]);
        Permission::create(['name' => 'edit user permissions', 'title' => 'ویرایش دسترسی کاربران', 'group_id' => $users_group->id]);

        Permission::create(['name' => 'delete permission', 'title' => 'حذف دسترسی ها', 'group_id' => $permissions_group->id]);
        Permission::create(['name' => 'edit permission', 'title' => 'ویرایش دسترسی ها', 'group_id' => $permissions_group->id]);
        Permission::create(['name' => 'create permission', 'title' => 'ایجاد دسترسی ها', 'group_id' => $permissions_group->id]);
        Permission::create(['name' => 'view permissions', 'title' => 'مشاهده دسترسی ها', 'group_id' => $permissions_group->id]);
        Permission::create(['name' => 'delete permission group', 'title' => 'حذف گروه های دسترسی', 'group_id' => $permissions_group->id]);
        Permission::create(['name' => 'edit permission group', 'title' => 'ویرایش گروه های دسترسی', 'group_id' => $permissions_group->id]);
        Permission::create(['name' => 'create permission group', 'title' => 'ایجاد گروه های دسترسی', 'group_id' => $permissions_group->id]);
        Permission::create(['name' => 'view permission groups', 'title' => 'مشاهده گروه های دسترسی', 'group_id' => $permissions_group->id]);


        Permission::create(['name' => 'delete role', 'title' => 'حذف نقش ها', 'group_id' => $permissions_group->id]);
        Permission::create(['name' => 'edit role', 'title' => 'ویرایش نقش ها', 'group_id' => $permissions_group->id]);
        Permission::create(['name' => 'create role', 'title' => 'ایجاد نقش ها', 'group_id' => $permissions_group->id]);
        Permission::create(['name' => 'view roles', 'title' => 'مشاهده نقش ها', 'group_id' => $permissions_group->id]);
    }
}