<?php

namespace App\Http\Controllers;

use App\Asset;
use App\Node;
use App\WareHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    /* Điều chuyển  kho (Kho vật lý) sang node */
    public function showFormWarehouseToNode()
    {
        return view('local_transfers.warehouse-to-node');
    }
    public function getNodes(Request $request)
    {
        $nodes = DB::table('nodes')
            ->where('nodes.name','like',"%$request->keyword%")
            ->join('rooms','nodes.room_id','=','rooms.id')
            ->select(
                'nodes.id',
                'nodes.name',
                'nodes.code',
                'nodes.nims',
                'nodes.warehouse_id',
                'rooms.name as room_name'
            )->get();
        return response()->json($nodes, 200);
    }
    public function getWarehouses(Request $request)
    {
        $warehouses = WareHouse::where('parent_id','>',9)
            ->where('name','like',"%$request->keyword%")
            ->select('id','name','code','note','parent_text')
            ->get();
        return response()->json($warehouses, 200);
    }
    public function getAssets(Request $request)
    {
        if(empty($request->warehouse_id)) return response()->json([],200);
        $excepts = empty($request->excepts) ? [] : $request->excepts;
        $assets = Asset::where('warehouse_id',$request->warehouse_id)
            ->join('asset_qlts_codes','assets.asset_qlts_code_id','=','asset_qlts_codes.id')
            ->join('vendors','asset_qlts_codes.vendor_id','=','vendors.id')
            ->select(
                'assets.id',
                'assets.serial',
                'assets.quantity',
                'assets.origin_qty',
                'asset_qlts_codes.name',
                'asset_qlts_codes.code as qlts_code',
                'vendors.name as vendor_name'
            )->whereNotIn('assets.id', $excepts)
            ->get();
        return response()->json($assets, 200);
    }
    public function transferWarehouseToNode(Request $request)
    {
        $node = Node::find($request->node_id);
        $next_warehouse_id = $node->warehouse_id;
        $assets = $request->assets;
        foreach ($assets as $as){
            $as = (object) $as;
            $asset = Asset::find($as->id);
            if($asset->origin_qty == 1){
                $asset->warehouse_id = $next_warehouse_id;
                $asset->save();
            }else{
                Asset::create([
                    'serial'=>$asset->serial,
                    'serial2'=>$asset->serial2,
                    'serial3'=>$asset->serial3,
                    'serial4'=>$asset->serial4,
                    'origin'=>$asset->origin,
                    'warranty_partner'=>$asset->warranty_partner,
                    'warranty_period'=>$asset->warranty_period,
                    'quantity'=> intval($as->quanity),
                    'manager'=> $asset->manager,
                    'asset_type_id'=>$asset->asset_type_id,
                    'asset_position_id'=>$asset->asset_position_id,
                    'warehouse_id'=>$next_warehouse_id,
                    'asset_status_id'=>$asset->asset_status_id,
                    'asset_qlts_code_id'=>$asset->asset_qlts_code_id,
                    'asset_vhkt_code_id'=>$asset->asset_vhkt_code_id,
                    'parent_id'=>$asset->id,
                    'origin_qty'=>$asset->origin_qty,
                    'note'=>$asset->note,
                    'user_id'=>$asset->user_id,
                    'group_id'=>$asset->group_id,
                    'indexes'=>$asset->indexes,
                ]);
                $asset->quantity = intval($asset->quantity) - intval($as->quantity);
                $asset->save();
            }
        }
        return response()->json(['status'=>'true'],200);
    }
    /* Điều chuyển  kho (Kho vật lý) sang node */
}
