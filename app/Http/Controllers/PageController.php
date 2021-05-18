<?php

namespace App\Http\Controllers;

use App\Geopoint;
use App\Cluster;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

use Spatie\TemporaryDirectory\TemporaryDirectory;

class PageController extends Controller
{
    public static function randomString() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function index(string $page)
    {
        if (view()->exists("pages.{$page}")) {
            return view("pages.{$page}");
        }

        return abort(404);
    }


    private function getGeopointData($geopoints){

       //this function can be called by each of the four reporting functions to extract the geopoint data
       //return [1,2,3,4,5];
       $monthly_savings = array_fill(0, 12, 0); //starting at index 0, fill in "0" for 12 spots   [0,0,0,0,0,0,0,0,0,0,0,0]
       $monthly_exports = array_fill(0, 12, 0);
       $monthly_gen_captive = array_fill(0, 12, 0);
       $monthly_gen_exports = array_fill(0, 12, 0);
       $yearly_gen_captive = array_fill(0, 25, 0);
       $yearly_gen_exports = array_fill(0, 25, 0);
       $yearly_co2 = array_fill(0, 26, 0);

       foreach ($geopoints as $geopoint) {

           for ($i = 0; $i < 12; $i++) {
               $monthly_savings[$i] += $geopoint->monthly_gen_saving_value_GBP[$i]; //populate each value in the 12-item array with the value of the corresponding value in this param in the geopoint
               $monthly_exports[$i] += $geopoint->monthly_gen_export_value_GBP[$i]; //there should be  at least 12 values (one for each month) on those columns/params for each geopoint
           }

           for ($i = 0; $i < 26; $i++) {
               $yearly_co2[$i] += $geopoint->yearly_co2_saved_kg[$i]; //there should be at least 26 values in this param for each geopoint
           }

           //new params
           if($geopoint->monthly_gen_captive_kWh){
               for ($i = 0; $i < 12; $i++) {
                   $monthly_gen_captive[$i] += $geopoint->monthly_gen_captive_kWh[$i];
               }
           }

           if($geopoint->monthly_gen_export_kWh){
               for ($i = 0; $i < 12; $i++) {
                   $monthly_gen_exports[$i] += $geopoint->monthly_gen_export_kWh[$i];
               }

           }

           if($geopoint->yearly_gen_captive_kWh){
               for ($i = 0; $i < 25; $i++) {
                   $yearly_gen_captive[$i] += $geopoint->yearly_gen_captive_kWh[$i]; //verified that y-gen-cap and y-gen-exp both are arrays of 25 values
               }
           }
            if($geopoint->yearly_gen_export_kWh){
               for ($i = 0; $i < 25; $i++) {
                   $yearly_gen_exports[$i] += $geopoint->yearly_gen_export_kWh[$i];  //verified that y-gen-cap and y-gen-exp both are arrays of 25 values
               }
           }

       }
       return [
           'monthly_savings' => $monthly_savings,
           'monthly_exports' => $monthly_exports,
           'monthly_gen_captive' => $monthly_gen_captive,
           'monthly_gen_exports' => $monthly_gen_exports,
           'yearly_gen_captive' => $yearly_gen_captive,
           'yearly_gen_exports' => $yearly_gen_exports,
           'yearly_co2' => $yearly_co2
       ];
   }


    public function pdf(Request $request)
    {
        $geopoint = Geopoint::find($request->geopoint_id);
        if (is_null($geopoint)) {
            return view('pages.reporting');
        }
        $lat = $geopoint->latLon->getLat();
        $lon = $geopoint->latLon->getLng();
        $key = config('services.google_maps.key');
        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lon . '&key=' . $key);
            $address = $response['results'][0]['formatted_address'];
        } catch (\Exception $e) {
            $address = null;
        }
        $html = view('pages.pdf', [
            'size' => $geopoint->system_capacity_kWp,
            'cost' => $geopoint->system_cost_GBP,
            'savings' => $geopoint->lifetime_gen_GBP,
            'breakeven' => $geopoint->breakeven_years,
            'tons' => round($geopoint->annual_co2_saved_kg / 1000, 2),
            'cars' => round($geopoint->annual_gen_kWh * 0.00025, 2),
            'trees' => round($geopoint->annual_gen_kWh * 0.0117),
            'oil' => round($geopoint->annual_gen_kWh * 0.2174),
            'lat' => $lat,
            'lon' => $lon,
            'address' => $address,
            'geodata' => json_encode([$geopoint]),
            'monthly_savings' => json_encode($geopoint->monthly_gen_saving_value_GBP),
            'monthly_exports' => json_encode($geopoint->monthly_gen_export_value_GBP),
            'saved_co2' => json_encode($geopoint->yearly_co2_saved_kg),
            'monthly_gen_captive' => json_encode($geopoint->monthly_gen_captive_kWh),
            'monthly_gen_exports' => json_encode($geopoint->monthly_gen_export_kWh),
            'yearly_gen_captive' => json_encode($geopoint->yearly_gen_captive_kWh),
            'yearly_gen_exports' => json_encode($geopoint->yearly_gen_export_kWh),

        ]);
        $output = [];
        $tempDir = (new TemporaryDirectory())->create();
        $tempHtmlPath = $tempDir->path('index.html');
        file_put_contents($tempHtmlPath, $html);
        $tempPdfName = self::RandomString().".pdf";
        $command = 'node ../storage/app/pdfs/generate_pdf.js "file://' . $tempHtmlPath . '" "' . $tempPdfName .'"';
        exec($command, $output);
        $tempDir->delete();
        $pathToPdf = Storage::disk('local')->path('pdfs/'.$tempPdfName);
        return response()->file($pathToPdf, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'filename="' . $address . '.pdf"'
        ])->deleteFileAfterSend();
    }
    /**
     * Display the reporting page
     *
     * @return \Illuminate\View\View
     */
    public function reporting(Request $request)
    {
        $request->validate([
            'geopoint_id' => 'required|integer'
        ]);
        $geopoint = Geopoint::find($request->geopoint_id);
        if (is_null($geopoint)) {
            return view('pages.reporting');
        }
        $lat = $geopoint->latLon->getLat();
        $lon = $geopoint->latLon->getLng();
        $key = config('services.google_maps.key');
        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lon . '&key=' . $key);
            $address = $response['results'][0]['formatted_address'];
        } catch (\Exception $e) {
            $address = null;
        }

        return view('pages.reporting', [
            'id' => $geopoint->id,
            'size' => $geopoint->system_capacity_kWp,
            'cost' => $geopoint->system_cost_GBP,
            'savings' => $geopoint->lifetime_gen_GBP,
            'breakeven' => $geopoint->breakeven_years,
            'tons' => round($geopoint->annual_co2_saved_kg / 1000, 2),
            'cars' => round($geopoint->annual_gen_kWh * 0.00025, 2),
            'trees' => round($geopoint->annual_gen_kWh * 0.0117),
            'oil' => round($geopoint->annual_gen_kWh * 0.2174),
            'lat' => $lat,
            'lon' => $lon,
            'address' => $address,
            'geodata' => json_encode([$geopoint]),
            'monthly_savings' => json_encode($geopoint->monthly_gen_saving_value_GBP),
            'monthly_exports' => json_encode($geopoint->monthly_gen_export_value_GBP),
            'saved_co2' => json_encode($geopoint->yearly_co2_saved_kg),
            'monthly_gen_captive' => json_encode($geopoint->monthly_gen_captive_kWh),
            'monthly_gen_exports' => json_encode($geopoint->monthly_gen_export_kWh),
            'yearly_gen_captive' => json_encode($geopoint->yearly_gen_captive_kWh),
            'yearly_gen_exports' => json_encode($geopoint->yearly_gen_export_kWh),
        ]);
    }

    public function clusterPdf(Request $request, $cluster_name) {
        $user = $request->user();
        $cluster = Cluster::where([
            ['name', $cluster_name],
            ['user_id', $user->id]
        ])->first();

        // if (is_null($cluster)) {
        //     return view('pages.reporting');
        // }

        // temporary fix until cluster-user relations update properly: allows viewing on both own projects and shared projects
        if (is_null($cluster)) {
            $cluster = $user->clusters->where('name', $cluster_name)->first();
            if (is_null($cluster)) {
                return view('pages.reporting');
            }
        }

        $geopoints = $cluster->geopoints;
        $data_array= $this->getGeopointData($geopoints);
        $html = view('pages.cluster_pdf', [
            'project' => $cluster->name,
            'size' => $geopoints->sum('system_capacity_kWp'),
            'cost' => $geopoints->sum('system_cost_GBP'),
            'savings' => $geopoints->sum('lifetime_gen_GBP'),
            'breakeven' => $geopoints->avg('breakeven_years'),
            'tons' => round($geopoints->sum('annual_co2_saved_kg') / 1000, 2),
            'cars' => round($geopoints->sum('annual_gen_kWh') * 0.00025, 2),
            'trees' => round($geopoints->sum('annual_gen_kWh') * 0.0117),
            'oil' => round($geopoints->sum('annual_gen_kWh') * 0.2174),
            'geodata' => json_encode($geopoints),
            'monthly_savings' => json_encode($data_array['monthly_savings']),
            'monthly_exports' => json_encode($data_array['monthly_exports']),
            'monthly_gen_captive' => json_encode($data_array['monthly_gen_captive']),
            'monthly_gen_exports' => json_encode($data_array['monthly_gen_exports']),
            'yearly_gen_captive' => json_encode($data_array['yearly_gen_captive']),
            'yearly_gen_exports' => json_encode($data_array['yearly_gen_exports']),
            'saved_co2' => json_encode($data_array['yearly_co2']),
        ]);
        $output = [];
        $tempDir = (new TemporaryDirectory())->create();
        $tempHtmlPath = $tempDir->path('index.html');
        file_put_contents($tempHtmlPath, $html);
        $tempPdfName = self::RandomString().".pdf";
        $command = 'node ../storage/app/pdfs/generate_pdf.js "file://' . $tempHtmlPath . '" "' . $tempPdfName .'"';
        exec($command, $output);
        $tempDir->delete();
        $pathToPdf = Storage::disk('local')->path('pdfs/'.$tempPdfName);
        return response()->file($pathToPdf, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'filename="' . $cluster->name . '.pdf"'
        ])->deleteFileAfterSend();
    }

    public function clusterReporting(Request $request, $cluster_name)
    {
        $user = $request->user(); //logged-in user
        $cluster = Cluster::where([
            ['name', $cluster_name],
            ['user_id', $user->id]
        ])->first();

        // if (is_null($cluster)) {
        //     return view('pages.reporting');
        // }

        // temporary fix until cluster-user relations update properly: allows viewing on both own projects and shared projects
        if (is_null($cluster)) {
            $cluster = $user->clusters->where('name', $cluster_name)->first();
            if (is_null($cluster)) {
                return view('pages.reporting');
            }
        }

        $geopoints = $cluster->geopoints;
        $data_array= $this->getGeopointData($geopoints);

        return view('pages.cluster_reporting', [
            'project' => $cluster->name,
            'size' => $geopoints->sum('system_capacity_kWp'),
            'cost' => $geopoints->sum('system_cost_GBP'),
            'savings' => $geopoints->sum('lifetime_gen_GBP'),
            'breakeven' => $geopoints->avg('breakeven_years'),
            'tons' => round($geopoints->sum('annual_co2_saved_kg') / 1000, 2),
            'cars' => round($geopoints->sum('annual_gen_kWh') * 0.00025, 2),
            'trees' => round($geopoints->sum('annual_gen_kWh') * 0.0117),
            'oil' => round($geopoints->sum('annual_gen_kWh') * 0.2174),
            'geodata' => json_encode($geopoints),
            'monthly_savings' => json_encode($data_array['monthly_savings']),
            'monthly_exports' => json_encode($data_array['monthly_exports']),
            'monthly_gen_captive' => json_encode($data_array['monthly_gen_captive']),
            'monthly_gen_exports' => json_encode($data_array['monthly_gen_exports']),
            'yearly_gen_captive' => json_encode($data_array['yearly_gen_captive']),
            'yearly_gen_exports' => json_encode($data_array['yearly_gen_exports']),
            'saved_co2' => json_encode($data_array['yearly_co2']),
        ]);
    }
    /**
     * Display the privacy page
     *
     * @return \Illuminate\View\View
     */
    public function termsandconditions()
    {
        return view('pages.termsandconditions');
    }

    public function faq()
    {
        return view('pages.termsandconditions');
    }

    public function building()
    {
        return view('pages.saved_reporting');
    }
    /**
     * Display the pricing page
     *
     * @return \Illuminate\View\View
     */
    public function pricing()
    {
        return view('pages.pricing');
    }

    /**
     * Display the lock page
     *
     * @return \Illuminate\View\View
     */
    public function lock()
    {
        return view('pages.lock');
    }


    public function admin()
    {
        return view('pages.admin_dashboard');
    }
}
