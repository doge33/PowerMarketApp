<?php

namespace App\Http\Controllers;

use App\Region;
use App\Account;
use App\Geopoint;

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
        $members = [];
        foreach($org->members as $member){
            $members[] = $member->name;
        }
        return view('pages.organization', [
            'org_name' => $org->name,
            'members' => $members,
            'accounts' => $org->accounts->load('regions')
        ]);
    }
    public function account($account_name)
    {
        $account = Account::where('name', $account_name)->first();
        $user = auth()->user();
        if($account == null){
            return abort(404);
        }
        if($user->isOrgAdmin() && $account_name != Controller::DEFAULT_ACCOUNT){
            $org = $user->organization;
            if($org == null) return abort(404);
            $account = $org->accounts()->where('name', $account_name)->first();
            if($account == null) return abort(404);
        }
        //here should be validation for org members
        $region_ids = $account->regions()->pluck('id')->toArray();
        $geopoints = Geopoint::whereIn('region_id', $region_ids)->get();
        return view('pages.dashboard', [
            'geodata' => $geopoints,
            'account' => $account_name
        ]);
    }
    public function region($account_name, $region_name){
        $account = Account::where('name', $account_name)->first();
        $user = auth()->user();
        if($account == null){
            return abort(404);
        }
        if($user->isOrgAdmin() && $account_name != Controller::DEFAULT_ACCOUNT){
            $org = $user->organization;
            if($org == null) return abort(404);
            $account = $org->accounts()->where('name', $account_name)->first();
            if($account == null) return abort(404);
        }
        //here should be validation for org members
        $region = $account->regions()->where('name', $region_name)->first();
        if($region == null) return abort(404);
        $geopoints = Geopoint::where('region_id', $region->id)->get();
        return view('pages.dashboard', [
            'geodata' => $geopoints,
            'account' => $account_name,
            'region' => $region_name
        ]);
    }
}
