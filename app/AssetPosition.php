<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetPosition extends Model
{
    protected $table='asset_positions';
    protected $fillable=['name','fix','note','user_id','group_id','indexes','created_at','updated_at'];

    public function assets(){
        return $this->hasMany('App\Asset','asset_position_id','id');
    }
}
