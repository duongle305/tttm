<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table='assets';
    protected $fillable=['serial','serial2','serial3','serial4','origin','warranty_partner','warranty_period','quantity','manager','asset_type_id','asset_position_id','warehouse_id','asset_status_id','asset_qlts_code_id','asset_vhkt_code_id','parent_id','origin_qty','note','user_id','group_id','indexes'];
    public $timestamps=true;

    public function warehouse(){
        return $this->belongsTo('App\Warehouse','warehouse_id','id');
    }

    public function qlts_code()
    {
        return $this->belongsTo('App\AssetQltsCode','asset_qlts_code_id');
    }
}
