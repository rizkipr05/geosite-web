<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Geosite extends Model
{

    protected $fillable = [
  'category_id','name','slug','description',
  'latitude','longitude','address','region',
  'open_hours','ticket_price','status'
 ];
}
