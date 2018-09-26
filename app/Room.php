<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table='rooms';
    protected $fillable=['name','code','shortname','manager','address','floor','length','width','square','height','ceiling','raise','note','building_id','warehouse_id','user_id','group_id','indexes','created_at','updated_at'];
    public $timestamps = true;

    public function nodes(){
        return $this->hasMany('App\Node','room_id','id');
    }
}
