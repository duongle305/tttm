<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetTempTransfer extends Model
{
    protected $table='asset_temp_transfers';
    protected $guarded = [];

    public function asset(){
        return $this->belongsTo('App\Asset','asset_id','id');
    }

}
