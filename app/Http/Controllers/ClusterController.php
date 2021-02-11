<?php

namespace App\Http\Controllers;

use App\Cluster;

use App\Http\Resources\ClusterResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class ClusterController extends Controller
{
    public function index(Request $request)
    {
        return ClusterResource::collection($request->user()->clusters);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'geopoints' => 'required|json',
        ]);
        $geopoints = json_decode($request->geopoints);
        $user = $request->user();
        if (Cluster::where('user_id', $user->id)->where('name', $request->name)->exists()) {
            return response()->json([
                'message' => 'The project with this name already exists'
            ], 422);
        }
        if ($user->isMember()) {
            return abort(404);
        }
        $cluster = Cluster::create([
            'name' => $request->name,
            'user_id' => $user->id
        ]);
        $cluster->geopoints()->attach($geopoints);
        $cluster->setLatLon();
        return response()->json([
            'message' => 'The project has been successfully created',
            'cluster' => new ClusterResource($cluster),
            'cluster_link' => URL::to('/projects/'.$cluster->name)
        ], 200);
    }

    public function addGeopoint(Request $request)
    {
        $request->validate([
            'geopoint_id' => 'required|integer',
            'new_name' => 'required_without:cluster_id',
            'cluster_id' => 'required_without:new_name|integer|exists:clusters,id'
        ]);
        $user = $request->user();
        $organization = $user->organization;
        if ($organization == null && !$user->isAdmin()) {
            return abort(404);
        }
        if ($user->isOrgAdmin()) {
            //geopoint possession validation
            $geopoint_ids = DB::table('organizations')->join('account_organization', 'organizations.id', '=', 'account_organization.organization_id')
                ->join('regions', 'account_organization.account_id', '=', 'regions.account_id')
                ->join('geopoints', 'regions.id', '=', 'geopoints.region_id')
                ->where('organizations.id', $organization->id)->select('geopoints.id')->get();
            if (!$geopoint_ids->contains('id', $request->geopoint_id)) {
                return response()->json(['message' => 'Geopoint not found'], 422);
            }
            //cluster possession validation
            if ($request->filled('cluster_id')) {
                if (!$user->clusters->contains('id', $request->cluster_id)) {
                    return response()->json(['message' => 'Project not found'], 422);
                }
                $cluster = Cluster::findOrFail($request->cluster_id);
            } else {
                if (Cluster::where('user_id', $user->id)->where('name', $request->new_name)->exists()) {
                    return response()->json([
                        'message' => 'The project with this name already exists'
                    ], 422);
                }
                $cluster = Cluster::create([
                    'name' => $request->new_name,
                    'user_id' => $user->id
                ]);
            }
            $cluster->addGeopoint($request->geopoint_id);
            $cluster->setLatLon();
            return response()->json([
                'message' => 'Geopoint has been successfully added',
                'cluster_link' => URL::to('/projects/'.$cluster->name)
            ], 200);
        } else if ($user->isOrgMember()) {
            //not available for org members for now
            return abort(404);
        }
    }

    public function removeGeopoint(Request $request)
    {
        $request->validate([
            'geopoint_id' => 'required|integer',
            'cluster_name' => 'required'
        ]);
        $user = $request->user();
        $cluster = Cluster::where([
            ['user_id', $user->id],
            ['name', $request->cluster_name]
        ])->first();
        if ($cluster == null) {
            return response()->json([
                'message' => 'The project is not found'
            ], 404);
        }
        $cluster->geopoints()->detach($request->geopoint_id);
        return response()->json([
            'message' => 'The geopoint has been successfully removed'
        ], 200);
    }
    //add each user_id in the array contained in the request to the cluster_user pivot table for a cluster
    public function shareCluster(Request $request)
    {
        //$request object should contain: 1. cluster_id of selected cluster; 2. an array of the user('s id) selected
        $request->validate([
            'cluster_id' => 'required',
            'co_owners' => 'required',
        ]);

        $cluster = Cluster::findOrFail($request->cluster_id); //
        $cluster->users()->syncWithoutDetaching($request->coOwners); //add the co-owner ids to this cluster in the pivot table

        return response()->json([
            'message' => 'Project is successfully shared' //may add co owner names in the future
        ], 200);

    }

    public function delete(Cluster $cluster){
        $cluster->delete();
        return response()->json([
            'message' => 'Project is successfully deleted'
        ], 200);
    }
}
