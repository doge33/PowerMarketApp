<?php

namespace App\Console;

use App\Geopoint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //fix geocoding errors
        $schedule->call(function () {
            //regions that need fixing
            $region_ids = [];

            $geopoints = Geopoint::whereIn('region_id', $region_ids)->get();
            $key = config('services.google_maps.key');
            $cases = [];
            $ids = [];
            $values = [];
            foreach ($geopoints as $geopoint) {
                $lat = $geopoint->latLon->getLat();
                $lon = $geopoint->latLon->getLng();
                try {
                    $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lon . '&key=' . $key);
                    $address = $response['results'][0]['formatted_address'];
                    $cases[] = "WHEN {$geopoint->id} then ?";
                    $values[] = $address;
                    $ids[] = $geopoint->id;
                } catch (\Exception $e) {
                    $address = null;
                    Log::channel('geocodinglog')->critical($e->getMessage());
                }
            }
            $ids = implode(',', $ids);
            $cases = implode(' ', $cases);

            if (!empty($ids)) {
                DB::update("UPDATE geopoints SET `address` = CASE `id` {$cases} END
            WHERE `id` in ({$ids})", $values);
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
