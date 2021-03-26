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
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lon . '&key=' . $key . '&result_type=street_address');
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
            'saved_co2' => json_encode($geopoint->yearly_co2_saved_kg)
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
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lon . '&key=' . $key . '&result_type=street_address');
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
            'saved_co2' => json_encode($geopoint->yearly_co2_saved_kg)
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
        $monthly_savings = array_fill(0, 12, 0);
        $monthly_exports = array_fill(0, 12, 0);
        $yearly_co2 = array_fill(0, 26, 0);
        foreach ($geopoints as $geopoint) {
            for ($i = 0; $i < 12; $i++) {
                $monthly_savings[$i] += $geopoint->monthly_gen_saving_value_GBP[$i];
                $monthly_exports[$i] += $geopoint->monthly_gen_export_value_GBP[$i];
            }
            for ($i = 0; $i < 26; $i++) {
                $yearly_co2[$i] += $geopoint->yearly_co2_saved_kg[$i];
            }
        }
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
            'monthly_savings' => json_encode($monthly_savings),
            'monthly_exports' => json_encode($monthly_exports),
            'saved_co2' => json_encode($yearly_co2)
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
        $monthly_savings = array_fill(0, 12, 0);
        $monthly_exports = array_fill(0, 12, 0);
        $monthly_gen_captive = array_fill(0, 12, 0);
        $monthly_gen_exports = array_fill(0, 12, 0);
        $yearly_gen_captive = array_fill(0, 25, 0);
        $yearly_gen_exports = array_fill(0, 25, 0);
        $yearly_co2 = array_fill(0, 26, 0);
        foreach ($geopoints as $geopoint) {
            for ($i = 0; $i < 12; $i++) {
                $monthly_savings[$i] += $geopoint->monthly_gen_saving_value_GBP[$i];
                $monthly_exports[$i] += $geopoint->monthly_gen_export_value_GBP[$i];
                $monthly_gen_captive[$i] += $geopoint->monthly_gen_captive_kWh[$i];
                $monthly_gen_exports[$i] += $geopoint->monthly_gen_export_kWh[$i];
            }
            for ($i = 0; $i < 26; $i++) {
                $yearly_co2[$i] += $geopoint->yearly_co2_saved_kg[$i];
            }
            for ($i = 0; $i < 25; $i++) {
                $yearly_gen_captive[$i] += $geopoint->yearly_gen_captive_kWh[$i]; //verified that y-gen-cap and y-gen-exp both are arrays of 25 values
                $yearly_gen_exports[$i] += $geopoint->yearly_gen_export_kWh[$i];
            }
        }
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
            'monthly_savings' => json_encode($monthly_savings),
            'monthly_exports' => json_encode($monthly_exports),
            'monthly_gen_captive' => json_encode($monthly_gen_captive),
            'monthly_gen_exports' => json_encode($monthly_gen_exports),
            'yearly_gen_captive' => json_encode($yearly_gen_captive),
            'yearly_gen_exports' => json_encode($yearly_gen_exports),
            'saved_co2' => json_encode($yearly_co2)
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
