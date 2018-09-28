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

    public function assetPosition(){
        return $this->belongsTo('App\AssetPosition','asset_position_id','id');
    }

    public function nextPosition()
    {
        return $this->hasMany('App\AssetNextPosition','asset_position_id','current_id');
    }
    public function isNextPositionPermit($nextPositionID){
        $flag = false;
        foreach (AssetNextPosition::where('current_id','=',$this->assetPosition()->first()->id)->get() as $case){
            if($case->next_id == $nextPositionID){
                $flag = true;
                break;
            }
        }
        return $flag;
    }


}
