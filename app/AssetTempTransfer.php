<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetTempTransfer extends Model
{
    protected $table='asset_temp_transfers';
    protected $fillable=['asset_id','current_warehouse_id','quantity','next_warehouse_id','user_id','group_id','created_at','updated_at'];

    public function asset(){
        return $this->belongsTo('App\Asset','asset_id','id');
    }

}
