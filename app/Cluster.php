<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cluster extends Model
{
    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['is_author', 'is_editor']);
    }

    public function author()
    {
        return $this->belongsToMany(User::class)->wherePivot('is_author', '1');
    }

    public function co_owners()
    {
        return $this->belongsToMany(User::class)->wherePivot('is_author', '0');
    }

    public function geopoints()
    {
        return $this->belongsToMany(Geopoint::class);
    }

    public function setLatLon()
    {
        $averages = DB::table('geopoints')
            ->join('cluster_geopoint', 'geopoints.id', '=', 'cluster_geopoint.geopoint_id')
            ->where('cluster_id', $this->id)
            ->select(DB::raw('avg(ST_Longitude(latLon)) as avg_lon, avg(ST_Latitude(latLon)) as avg_lat'))
            ->first();
        $this->lat = $averages->avg_lat ?? 0;
        $this->lon = $averages->avg_lon ?? 0;
        $this->save();
    }

    public function addGeopoint($geopoint_id) {
        $this->geopoints()->syncWithoutDetaching($geopoint_id);
    }
}
