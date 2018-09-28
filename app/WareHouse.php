<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WareHouse extends Model
{
    protected $table='warehouses';
    protected $fillable=['name','code','shortname','note','parent_text','temporary','parent_id','user_id','group_id','indexes','created_at','updated_at'];
    public $timestamps=true;
}
