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
    public function getAssets(Request $request)    {
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

    /* Điều chuyển Node -> Kho (Kho vật lý) */

    public function showFormNodeToWarehouse()
    {
        return view('local_transfers.node-to-warehouse');
    }

    public function transferNodeToWarehouse(Request $request)
    {
        $next_warehouse_id = $request->warehouse_id;
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



<<<<<<< HEAD
    //transfer out of station

    public function transferOutOfStation(){
        return view('transfers.transfer-out-of-station');
    }

    public function selectAsset(Request $request){
        $assets = DB::table('assets')
            ->leftJoin('asset_qlts_codes','assets.asset_qlts_code_id','=','asset_qlts_codes.id')
            ->leftJoin('asset_vhkt_codes','assets.asset_vhkt_code_id','=','asset_vhkt_codes.id')
            ->leftJoin('warehouses','assets.warehouse_id','=','warehouses.id')
            ->leftJoin('vendors','asset_qlts_codes.vendor_id','=','vendors.id')
            ->where(function ($query) use($request){
                $query->where('asset_qlts_codes.name','like',"%$request->keyWord%")
                    ->where(function ($query2){
                        $query2->where('assets.asset_position_id','=','3')
                            ->orWhere('assets.asset_position_id','=','5');
                    });
            })->select(['assets.id as id','quantity','origin_qty','asset_qlts_codes.name as asset_name','warehouses.name as warehouse_name','warehouses.id as warehouse_id','asset_qlts_codes.code as qlts_code','asset_vhkt_codes.code as vhkt_code','vendors.name as vendor_name'])
            ->get();

        if(!empty($request->selected)){
            $assets = $assets->map(function ($asset) use ($request){
                $flag = false;
                foreach ($request->selected as $select){
                    if($select['id'] == $asset->id)
                    {
                        $flag = true;
                        break;
                    }
                }
                if($flag) return null;
                return $asset;
            });
        }
        return response()->json($assets,200);
    }

    public function transferOutOfStationSubmit(Request $request){
        $flag = false;
        $assets = $request->assets;
        foreach ($assets as $asset){
            $tmpAsset = Asset::find($asset['id']);
            if(!$tmpAsset->first()) break;
            if($asset['transfer_quantity'] > 1){
                Asset::insert([
                    'serial' => $tmpAsset->serial,
                    'serial2' => $tmpAsset->serial2,
                    'serial3' => $tmpAsset->serial3,
                    'serial4' => $tmpAsset->serial4,
                    'quantity' => $asset['transfer_quantity'],
                    'parent_id' => $tmpAsset->id,
                    'origin' => $tmpAsset->origin,
                    'warranty_partner' => $tmpAsset->warranty_partner,
                    'warranty_period' => $tmpAsset->warranty_period,
                    'manager' => $tmpAsset->manager,
                    'asset_type_id' => $tmpAsset->asset_type_id,
                    'asset_position_id' => 7,
                    'warehouse_id' => null,
                    'asset_status_id' => $tmpAsset->asset_status_id,
                    'asset_qlts_code_id' => $tmpAsset->asset_qlts_code_id,
                    'asset_vhkt_code_id' => $tmpAsset->asset_vhkt_code_id,
                    'origin_qty' => $tmpAsset->origin_qty,
                    'note' => $tmpAsset->note,
                    'user_id' => $tmpAsset->user_id,
                    'group_id' => $tmpAsset->group_id,
                    'indexes' => $tmpAsset->indexes,
                    'created_at' => now()->toDateTimeString()
                ]);

                $tmpAsset->quantity = (int)$tmpAsset->quantity - (int)$asset['transfer_quantity'];
                $tmpAsset->save();
                $flag = true;
            } else {
                $tmpAsset->warehouse_id = null;
                $tmpAsset->quantity = 0;
                $tmpAsset->asset_position_id = 7;
                $tmpAsset->save();
                $flag = true;
            }
        }

        return (!$flag) ? response()->json('failed',403) : response()->json('ok',200);
=======

    /* Transfer Warranty repairs */

    public function showFormWarrantyRepair()
    {
        return view('transfers.warranty-repairs');
    }

    public function getAssetTransferWarrantyRepair()
    {
        /* 2: Trên mạng lưới, 3: Trong kho, 5: Trực ca giữ làm nghiệp vụ */
        $asset = DB::table('assets')->whereIn('asset_position_id',[2,3,5])
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
            )->get();
        return response()->json($asset, 200);
>>>>>>> 449e333f078bd64d624f4a7502f4724905d79415
    }
}
