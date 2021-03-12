<?php

namespace App\Http\Controllers;

use App\Region;
use App\Geopoint;
use App\Account;
use App\Jobs\ReverseGeocoding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegionController extends Controller
{
    public function wrapPoint($point)
    {
        return "ST_GeomFromText('POINT(" . $point['centre_lat'] . " " . $point['centre_lon'] . ")', 4326)";
    }
    public function store(Request $request)
    {
        $request->validate([
            'geodata' => 'required|json',
            'name' => 'required',
            'lat' => 'required|numeric|min:-90|max:90',
            'lon' => 'required|numeric|min:-180|max:180',
            'account_id' => 'required|exists:accounts,id'
        ]);
        $region = Region::create([
            'name' => $request->name,
            'lat' => $request->lat,
            'lon' => $request->lon,
            'account_id' => $request->account_id
        ]);
        $values = [];
        $json_data =  json_decode($request->geodata, true)['regions'];
        foreach ($json_data as $point) {
            $value = [];
            foreach (Geopoint::COLUMNS as $field) {
                if ($field == 'latLon') {
                    $value[] = $this->wrapPoint($point);
                } else if (is_string($point[$field])) {
                    $value[] = '"' . $point[$field] . '"'; //this for the "existingsolar" column
                } else {
                    $value[] = '"' . json_encode($point[$field]) . '"';
                }
            }
            $value[] = '"' . $region->id . '"';
            $values[] = "(" . implode(",", $value) . ")";
        };
        DB::insert('insert into geopoints (' . implode(",", Geopoint::COLUMNS) . ',region_id) values ' . implode(",", $values));
        $geopoints = Geopoint::where('region_id', $region->id)->get();
        ReverseGeocoding::dispatch($geopoints);
        return redirect()->route('region.index')->withStatus(__('Region successfully created.'));
    }
    public function create(Region $region)
    {
        return view('regions.create', [
            'accounts' => Account::all()
        ]);
    }
    public function edit(Region $region)
    {
        return view('regions.edit', [
            'region' => $region,
            'accounts' => Account::all()
        ]);
    }
    public function index()
    {
        return view('regions.index', ['regions' => Region::with('account')->get()]);
    }
    public function update(Request $request, Region $region)
    {
        $request->validate([
            'geodata' => 'nullable|json',
            'lat' => 'nullable|numeric|min:-90|max:90',
            'lon' => 'nullable|numeric|min:-180|max:180',
            'account_id' => 'nullable|exists:accounts,id'
        ]);
        if ($request->filled('name')) {
            $region->name = $request->name;
        }
        if ($request->filled('lat')) {
            $region->lat = $request->lat;
        }
        if ($request->filled('lon')) {
            $region->lon = $request->lon;
        }
        if ($request->filled('account_id')) {
            $region->account_id = $request->account_id;
        }
        if ($request->filled('geodata')) {
            Geopoint::where('region_id', $region->id)->delete();
            $values = [];
            $json_data =  json_decode($request->geodata, true)['regions'];
            foreach ($json_data as $point) {
                $value = [];
                foreach (Geopoint::COLUMNS as $field) {
                    if ($field == 'latLon') {
                        $value[] = $this->wrapPoint($point);
                    } else if (is_string($point[$field])) {
                        $value[] = '"' . $point[$field] . '"';
                    } else {
                        $value[] = '"' . json_encode($point[$field]) . '"';
                    }
                }
                $value[] = '"' . $region->id . '"';
                $values[] = "(" . implode(",", $value) . ")";
            };
            DB::insert('insert into geopoints (' . implode(",", Geopoint::COLUMNS) . ',region_id) values ' . implode(",", $values));
            $geopoints = Geopoint::where('region_id', $region->id)->get();
            ReverseGeocoding::dispatch($geopoints);
        }
        $region->save();
        return redirect()->route('region.index')->withStatus(__('Region successfully updated.'));
    }
    public function destroy(Region $region)
    {
        $region->delete();

        return redirect()->route('region.index')->withStatus(__('Region successfully deleted.'));
    }
}
