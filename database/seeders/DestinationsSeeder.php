<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DestinationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $json = [];
        try {
            $jsonContent = File::get(base_path('database/datas/destinations.json'));
            $json = json_decode($jsonContent, true);
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->command->error($e->getMessage());
        }

        DB::table('destinations')->truncate();

        foreach ($json as $item) {
            \App\Models\Destination::firstOrCreate($item);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info(count($json).' destinations importées !');
    }
}
