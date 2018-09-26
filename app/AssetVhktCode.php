<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetVhktCode extends Model
{
    protected $table = 'asset_vhkt_codes';
    protected $fillable=['name','code','ccode','has_serial','vendor_id','asset_unit_id','vhkt_code_type','partnum','swid','note','user_id','group_id','indexes','created_at','updated_at'];
    public $timestamps = true;

    public function vendor(){
        return $this->belongsTo('App\Vendor','vendor_id','id');
    }
}
