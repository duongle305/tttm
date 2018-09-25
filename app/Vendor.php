<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table='vendors';
    protected $fillable=['name','code','shortname','logo','note','user_id','group_id','indexes','created_at','updated_at'];
    public $timestamps = true;

    public function assetQltsCodes(){
        return $this->hasMany('App\AssetQltsCode','vendor_id','id');
    }
}
