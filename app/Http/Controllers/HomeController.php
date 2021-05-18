<?php

namespace App\Http\Controllers;

use App\Region;
use App\Account;
use App\Geopoint;
use App\Cluster;
use App\Helpers\pro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function saveindex()
    {
        $gloucester = Region::where('name', 'Gloucester')->first();
        return view('pages.dashboard', ['geodata' => optional($gloucester)->geopoints]);
    }

    public function index()
    {
        $user = auth()->user();
        if (!$user->hasOrganization()) {
            return redirect('dashboard/Gloucestershire');
        }
        $org = $user->organization;

        return view('pages.organization', [
            'org_name' => $org->name,
            'members' => $user->isMember() ? [] : $org->members,
            'accounts' => $user->isMember() ? $user->accounts->load('regions') : $org->accounts->load('regions'),
            'my_clusters' => $user->isMember() ? null : $user->clusters()->wherePivot('is_author', 1)->get(),
            'shared_clusters' => $user->isMember() ? null : $user->clusters()->wherePivot('is_author', 0)->get()
        ]);
    }
    public function account($account_name)
    {
        $account = Account::where('name', $account_name)->first();
        $user = auth()->user();
        if ($account == null) {
            return abort(404);
        }
        $org = $user->organization;
        if ($user->isOrgAdmin() && $account_name != Controller::DEFAULT_ACCOUNT) {
            if ($org == null) return abort(404);
            $account = $org->accounts()->where('name', $account_name)->first();
            if ($account == null) return abort(404);
        }
        if ($user->isMember() && $account_name != Controller::DEFAULT_ACCOUNT) {
            $account = $user->accounts()->where('name', $account_name)->first();
            if ($account == null) return abort(404);
        }
        $region_ids = $account->regions()->pluck('id')->toArray();
        //get all geopoints that exists in all regions of this account
        $geopoints = Geopoint::whereIn('region_id', $region_ids)->get();

        $test_geopoint = $geopoints->where("id", 17499);
        return view('pages.dashboard', [
            'geodata' => $geopoints,
            'account' => $account_name,
            "test_geopoint" => $test_geopoint
        ]);
    }
    public function region($account_name, $region_name)
    {
        $account = Account::where('name', $account_name)->first();
        $user = auth()->user();
        if ($account == null) {
            return abort(404);
        }
        $org = $user->organization;
        if ($user->isOrgAdmin() && $account_name != Controller::DEFAULT_ACCOUNT) {
            if ($org == null) return abort(404);
            $account = $org->accounts()->where('name', $account_name)->first();
            if ($account == null) return abort(404);
        }
        if ($user->isMember() && $account_name != Controller::DEFAULT_ACCOUNT) {
            $account = $user->accounts()->where('name', $account_name)->first();
            if ($account == null) return abort(404);
        }
        //to-do: need to verify user is on pro
        $region = $account->regions()->where('name', $region_name)->first();
        if ($region == null) return abort(404);
        $geopoints = Geopoint::where('region_id', $region->id)->get();

        $test_geopoint = $geopoints->where("id", 19483);
        //dd($test_geopoint);
        return view('pages.dashboard', [
            'geodata' => $geopoints,
            'account' => $account_name,
            'region' => $region_name,
            "test_geopoint" => $test_geopoint
        ]);
    }

    public function region_pro(Request $request){
        $account_name = $request->account;
        $account = Account::where('name', $account_name)->first();
        $region_name = $request->region;
        //region may or may not be passed through the request:
        if ($region_name) {
            $region = $account->regions()->where('name', $region_name)->first();
            $geopoints = Geopoint::where('region_id', $region->id)->get();
            //dd("with region name", $geopoints);
        } else{
            $region_ids = $account->regions()->pluck('id')->toArray();
            $geopoints = Geopoint::whereIn('region_id', $region_ids)->get();
            //dd("without region name", $geopoints);
        };
        //dd($geopoints);
        if($geopoints == null) return abort(404);
        //-----------user input params-------------
        //----laravel blade input seems unable to pass input of type "number" as numeric values----
        //----so manually converting input fields from string to floats in controller, for now-----
        $captive_use = $request->captive_use ?  floatval($request->captive_use) : 0.8;
        $export_tariff = $request->export_tariff ? floatval($request->export_tariff) : 0.055;
        //$domestic_tariff may have a different value if account is "PPS"
        if($request->domestic_tariff){
            $domestic_tariff = floatval($request->domestic_tariff);
        } else{
            if($account_name == "Gloucestershire | PPS"){
                $domestic_tariff = 0.095;
            }else{
                $domestic_tariff = 0.146;
            }
        }
        //dd($domestic_tariff);
        $commercial_tariff = $request->commercial_tariff ? floatval($request->commercial_tariff) : 0.12;
        $cost_of_small_system = $request->cost_of_small_system ? floatval($request->cost_of_small_system) : 6000;
        $system_size_kwp = $request->system_size_kwp ? floatval($request->system_size_kwp) : 5;

        $test_geopoint = $geopoints->where("id", 19483);

        $pro_geopoints = pro_params($captive_use, $export_tariff, $domestic_tariff, $commercial_tariff, $cost_of_small_system, $system_size_kwp, $geopoints);

        //dd($geopoints->where('id', 17499));

        $prev_inputs = [
            "captive_use" => $captive_use,
            "export_tariff" => $export_tariff,
            "domestic_tariff" => $domestic_tariff,
            "commercial_tariff" => $commercial_tariff,
            "cost_of_small_system" => $cost_of_small_system,
            "system_size_kwp" => $system_size_kwp
        ];
        return view('pages.dashboard-pro', [
            'geodata' => $pro_geopoints,
            'account' => $account_name,
            'region' => $region_name,
            'cluster' => "",
            "captive_use" => $captive_use,
            "export_tariff" => $export_tariff,
            "prev_inputs" => $prev_inputs,
            "test_geopoint" => $test_geopoint
        ]);
    }

    public function cluster_pro(Request $request){
        //dd($request);
        $user = auth()->user();
        $cluster_name = $request->cluster;
        $cluster = Cluster::where('user_id', $user->id)->where('name', $cluster_name)->first();
        //temporary fix until cluster-user relationship updated properly
        if ($cluster == null){
            $cluster = $user->clusters->where('name', $cluster_name)->first();
            if($cluster == null) {
                return abort(404);
            }
        }
        $geopoints = $cluster->geopoints;
        if($geopoints == null) return abort(404);
        //-----------user input params-------------
        //----laravel blade input seems unable to pass input of type "number" as numeric values----
        //----so manually converting input fields from string to floats in controller, for now-----
        $captive_use = $request->captive_use ?  floatval($request->captive_use) : 0.8;
        $export_tariff = $request->export_tariff ? floatval($request->export_tariff) : 0.055;
        //$domestic_tariff may have a different value if account is "PPS"
        if($request->domestic_tariff){
            $domestic_tariff = floatval($request->domestic_tariff);
        } else{
            //to-do: how to use default 0.095 for PPS accounts
           $domestic_tariff = 0.146;
        }
        //dd($domestic_tariff);
        $commercial_tariff = $request->commercial_tariff ? floatval($request->commercial_tariff) : 0.12;
        $cost_of_small_system = $request->cost_of_small_system ? floatval($request->cost_of_small_system) : 6000;
        $system_size_kwp = $request->system_size_kwp ? floatval($request->system_size_kwp) : 5;

        $test_geopoint = $geopoints->where("id", 19483);
        $pro_geopoints = pro_params($captive_use, $export_tariff, $domestic_tariff, $commercial_tariff, $cost_of_small_system, $system_size_kwp, $geopoints);
        //dd($geopoints->where('id', 17499));
        $prev_inputs = [
            "captive_use" => $captive_use,
            "export_tariff" => $export_tariff,
            "domestic_tariff" => $domestic_tariff,
            "commercial_tariff" => $commercial_tariff,
            "cost_of_small_system" => $cost_of_small_system,
            "system_size_kwp" => $system_size_kwp
        ];
        return view('pages.dashboard-pro', [
            'geodata' => $pro_geopoints,
            'cluster' => $cluster->name,
            'region'=>'',
            'account' => '',
            "captive_use" => $captive_use,
            "export_tariff" => $export_tariff,
            "prev_inputs" => $prev_inputs,
            "test_geopoint" => $test_geopoint
        ]);
    }

    public function cluster($cluster_name) {
        $user = auth()->user();

        $cluster = Cluster::where('user_id', $user->id)->where('name', $cluster_name)->first();

        //temporary fix until cluster-user relationship updated properly
        if ($cluster == null){
            $cluster = $user->clusters->where('name', $cluster_name)->first();
            if($cluster == null) {
                return abort(404);
            }
        }

        $geopoints = $cluster->geopoints;

        return view('pages.dashboard', [
        'geodata' => $geopoints,
        'cluster' => $cluster->name,
        ]);

    }

    // public function cluster($cluster_name) {
    //     $user = auth()->user();
    //     $cluster = Cluster::where('user_id', $user->id)->where('name', $cluster_name)->first();
    //     if ($cluster == null){
    //         return abort(404);
    //     }
    //     $geopoints = $cluster->geopoints;
    //     return view('pages.dashboard', [
    //         'geodata' => $geopoints,
    //         'cluster' => $cluster->name
    //     ]);
    // }
}
