<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Geosite extends Model
{

    protected $fillable = [
        'category_id','name','slug','description',
        'latitude','longitude','address','region',
        'open_hours','ticket_price','status',
        'osm_type','osm_id','osm_source' // Add these OSM columns
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }
}
