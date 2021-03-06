<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StbCountriesTableSeeder::class);

        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(StbMessagesTableSeeder::class);

        $this->call(StbReportsCatTableSeeder::class);
        $this->call(StbReportsTableSeeder::class);

        $this->call(StbThemesTableSeeder::class);
        $this->call(StbTypesTableSeeder::class);

        $this->call(StbStreamsTableSeeder::class);
        $this->call(StbViewersTableSeeder::class);
        $this->call(StbChatsTableSeeder::class);
    }
}
/*
composer update
composer dump-autoload
php artisan migrate:fresh --seed
php artisan voyager:install
*/