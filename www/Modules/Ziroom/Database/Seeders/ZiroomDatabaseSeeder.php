<?php

namespace Modules\Ziroom\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class ZiroomDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(\Modules\Ziroom\Database\Seeders\BlogsTableSeeder::class);
        // $this->call("OthersTableSeeder");
    }
}

