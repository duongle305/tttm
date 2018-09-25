<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $table='nodes';
    protected $fillable=['name','code','shortname','nims','manager','zone','onmip','license','sysadmin','info','published','setup','project','contract','folder','extsupport','warranty','config','intergrated','tested','note','team_id','room_id','warehouse_id','node_status_id','vendor_id','support_id','service_id','network_id','function_node_id','intergrated_system_id','network_class_id','user_id','group_id','indexes'];
    public $timestamps=true;

    public function warehouse(){
        return $this->belongsTo('App\Warehouse','warehouse_id','id');
    }
}
