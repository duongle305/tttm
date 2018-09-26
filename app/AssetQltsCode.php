<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetQltsCode extends Model
{
    protected $table = 'asset_qlts_codes';
    protected $fillable=['name','code','has_serial','vendor_id','asset_unit_id','note','user_id','group_id','indexes','created_at','updated_at'];
    public $timestamps = true;

    public function vendor(){
        return $this->belongsTo('App\Vendor','vendor_id','id');
    }

    public function asset(){
        return $this->hasOne('App\Asset','asset_qlts_code_id');
    }
}
