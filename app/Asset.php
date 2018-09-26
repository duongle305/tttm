<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table='assets';
    protected $fillable=['serial','serial2','serial3','serial4','origin','warranty_partner','warranty_period','quantity','manager','asset_type_id','asset_position_id','warehouse_id','asset_status_id','asset_qlts_code_id','asset_vhkt_code_id','parent_id','origin_qty','note','user_id','group_id','indexes','created_at','updated_at'];
    public $timestamps=true;

    public function warehouse(){
        return $this->belongsTo('App\Warehouse','warehouse_id','id');
    }

    public function qltsCode()
    {
        return $this->hasOne('App\AssetQltsCode','id');
    }

    public function vhktCode(){
        return $this->hasOne('App\AssetVhktCode','id');
    }
}
