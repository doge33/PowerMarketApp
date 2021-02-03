<?php

use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('regions')->delete();
        $json = File::get("database/data/Gloucestershire.json");
        $data = json_decode($json)['regions'];
        foreach ($data as $obj) {
            Region::create(array(
                'lat' => $obj->centre_lat,
                'lon' =>$obj->centre_lon
            ));

        }
    }
}
