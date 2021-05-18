<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReverseGeocoding implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $geopoints;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($geopoints)
    {
        $this->geopoints = $geopoints;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $key = config('services.google_maps.key');
        $cases = [];
        $ids = [];
        $values = [];
        foreach ($this->geopoints as $geopoint) {
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
    }
}
