<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $guarded = [];

    public function geopoints()
    {
        return $this->hasMany(Geopoint::class);
    }
}
