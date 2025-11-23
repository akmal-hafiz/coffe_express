<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Menu;
use App\Models\Order;

class ImportBackupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Importing backup data...');

        // Find latest backup files
        $backupDir = database_path('backups');
        
        if (!File::exists($backupDir)) {
            $this->command->error('❌ Backup directory not found!');
            $this->command->warn('Please run backup script first: .\backup-sqlite-data.ps1');
            return;
        }

        // Get latest backup files
        $files = File::files($backupDir);
        $latestUsers = collect($files)->filter(fn($f) => str_contains($f->getFilename(), 'users_'))->sortByDesc('mtime')->first();
        $latestMenus = collect($files)->filter(fn($f) => str_contains($f->getFilename(), 'menus_'))->sortByDesc('mtime')->first();
        $latestOrders = collect($files)->filter(fn($f) => str_contains($f->getFilename(), 'orders_'))->sortByDesc('mtime')->first();

        // Import Users
        if ($latestUsers) {
            $this->command->info('📥 Importing users...');
            $users = json_decode(File::get($latestUsers->getPathname()), true);
            
            foreach ($users as $user) {
                User::create([
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'email_verified_at' => $user['email_verified_at'],
                    'password' => $user['password'],
                    'remember_token' => $user['remember_token'],
                    'created_at' => $user['created_at'],
                    'updated_at' => $user['updated_at'],
                ]);
            }
            
            $this->command->info("✅ Imported " . count($users) . " users");
        }

        // Import Menus
        if ($latestMenus) {
            $this->command->info('📥 Importing menus...');
            $menus = json_decode(File::get($latestMenus->getPathname()), true);
            
            foreach ($menus as $menu) {
                Menu::create([
                    'id' => $menu['id'],
                    'name' => $menu['name'],
                    'description' => $menu['description'] ?? null,
                    'price' => $menu['price'],
                    'category' => $menu['category'] ?? null,
                    'image' => $menu['image'] ?? null,
                    'is_available' => $menu['is_available'] ?? true,
                    'created_at' => $menu['created_at'],
                    'updated_at' => $menu['updated_at'],
                ]);
            }
            
            $this->command->info("✅ Imported " . count($menus) . " menus");
        }

        // Import Orders
        if ($latestOrders) {
            $this->command->info('📥 Importing orders...');
            $orders = json_decode(File::get($latestOrders->getPathname()), true);
            
            foreach ($orders as $order) {
                Order::create([
                    'id' => $order['id'],
                    'order_number' => $order['order_number'],
                    'customer_name' => $order['customer_name'] ?? null,
                    'table_number' => $order['table_number'] ?? null,
                    'items' => $order['items'],
                    'total_price' => $order['total_price'],
                    'status' => $order['status'],
                    'notes' => $order['notes'] ?? null,
                    'estimated_time' => $order['estimated_time'] ?? null,
                    'created_at' => $order['created_at'],
                    'updated_at' => $order['updated_at'],
                ]);
            }
            
            $this->command->info("✅ Imported " . count($orders) . " orders");
        }

        // Reset sequences for PostgreSQL
        if (config('database.default') === 'pgsql') {
            $this->command->info('🔧 Resetting PostgreSQL sequences...');
            
            DB::statement("SELECT setval('users_id_seq', (SELECT MAX(id) FROM users))");
            DB::statement("SELECT setval('menus_id_seq', (SELECT MAX(id) FROM menus))");
            DB::statement("SELECT setval('orders_id_seq', (SELECT MAX(id) FROM orders))");
            
            $this->command->info('✅ Sequences reset');
        }

        $this->command->info('');
        $this->command->info('🎉 Import completed successfully!');
    }
}
