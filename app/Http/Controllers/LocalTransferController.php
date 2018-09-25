<?php

namespace App\Http\Controllers;

use App\Asset;
use App\AssetQltsCode;
use App\Node;
use App\WareHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocalTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('local_transfers.index');
    }


    public function getNodes(Request $request)
    {
        $nodes = DB::table('nodes')
            ->leftJoin('rooms','nodes.room_id','=','rooms.id')
            ->where('nodes.name', 'like', "%$request->keyWord%")
            ->select(['nodes.id as id', 'nodes.name', 'nodes.manager', 'nodes.warehouse_id','nodes.code','nodes.shortname','nodes.nims','nodes.zone','rooms.name as room_name'])
            ->get();
        if(!empty($request->step1_item_id)){
            $nodes = collect($nodes)->map(function($item) use ($request){
                if($item->id == $request->step1_item_id) unset($item);
                else return $item;
            })->all();
        }
        return response()->json($nodes, 200);
    }

    public function getAssetsByNode(Request $request)
    {
        $warehouse_id = $request->node['warehouse_id'];

        $assets = DB::table('assets')
            ->leftJoin('asset_qlts_codes','assets.asset_qlts_code_id','=','asset_qlts_codes.id')
            ->leftJoin('asset_vhkt_codes','assets.asset_vhkt_code_id','=','asset_vhkt_codes.id')
            ->leftJoin('warehouses','assets.warehouse_id','=','warehouses.id')
            ->leftJoin('vendors','vendors.id','=','asset_qlts_codes.vendor_id')
            ->where(function ($query) use($request,$warehouse_id) {
                $query->where('serial', 'like', "%$request->keyWord%")
                    ->where('warehouse_id', '=', $warehouse_id)
                    ->where('asset_position_id', '=', '3');
            })
            ->select(['assets.id as id','quantity','origin_qty','asset_qlts_codes.name as asset_name','warehouses.name as warehouse_name','asset_qlts_codes.code as qlts_code','asset_vhkt_codes.code as vhkt_code','vendors.name as vendor_name'])
            ->get();
        if(!empty($request->selected)){
            $assets = $assets->map(function ($asset) use ($request){
                $flag = false;
                foreach ($request->selected as $select){
                    if($select['id'] == $asset->id) {
                        $flag = true;
                        break;
                    }
                }
                if($flag) return null;
                return $asset;
            });
        }
        return response()->json($assets, 200);
    }

    public function nodeToNode(Request $request){
        $warehouse = Node::find($request->node_destination['id'])->warehouse;
        $assets = $request->assets;
        foreach ($assets as $asset){
            $tmpAsset = Asset::find($asset['id']);
            if($asset['transfer_quantity'] > 1){
                $childAsset = Asset::insert([
                    'serial' => $asset['serial'],
                    'serial2' => $asset['serial2'],
                    'serial3' => $asset['serial3'],
                    'serial4' => $asset['serial4'],
                    'quantity' => $asset['transfer_quantity'],
                    'parent_id' => $tmpAsset->id,
                    'origin' => $tmpAsset->origin,
                    'warranty_partner' => $tmpAsset->warranty_partner,
                    'warranty_period' => $tmpAsset->warranty_period,
                    'manager' => $tmpAsset->manager,
                    'asset_type_id' => $tmpAsset->asset_type_id,
                    'asset_position_id' => $tmpAsset->asset_position_id,
                    'warehouse_id' => $warehouse->id,
                    'asset_status_id' => $tmpAsset->asset_status_id,
                    'asset_qlts_code_id' => $tmpAsset->asset_qlts_code_id,
                    'asset_vhkt_code_id' => $tmpAsset->asset_vhkt_code_id,
                    'origin_qty' => $tmpAsset->origin_qty,
                    'note' => $tmpAsset->note,
                    'user_id' => $tmpAsset->user_id,
                    'group_id' => $tmpAsset->group_id,
                    'indexes' => $tmpAsset->indexes,
                    'created_at'=> now()->toDateTimeString()
                ]);
                $tmpAsset->quantity = (int)$tmpAsset->quantity - (int)$asset['transfer_quantity'];
                $tmpAsset->save();
            } else {
                $tmpAsset->warehouse_id = $warehouse->id;
                $tmpAsset->save();
            }
        }
        return response()->json('ok',200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function wareHouseTransfers()
    {
        return view('local_transfers.warehouse');
    }
    public function getWareHouseTransfers(Request $request){
        $wareHouses = WareHouse::where('name','like',"%$request->keyword%")
            ->select(['id','name','code','parent_text'])
            ->get();
        if(!empty($request->prevStepId)){
            $wareHouses = collect($wareHouses)->map(function($item) use($request){
                if($request->prevStepId === $item->id) {
                    unset($item);
                }else return $item;
            })->all();
        }
        return response()->json($wareHouses,200);
    }

    public function getAssetByWareHouseId(Request $request){
        if($request->warehouse_id == null) return [];
        $excepts = collect($request->excepts)->map(function($item){ return $item['id']; });
        $assets = Asset::where('warehouse_id',$request->warehouse_id)
            ->with(['qltsCode'])
            ->get();
        $assets = collect($assets)->mapWithKeys(function($item){
            $item->name = $item->qltsCode->name;
            return [$item->id => $item];
        })->except($excepts)->all();
        return response()->json($assets, 200);
    }

    public function checkQuantity(Request $request)
    {
        $asset = Asset::find($request->id);
        if(!$asset)  return response()->json(['message'=>'Data not found !'],400);
        if($request->quantity > $asset->quantity) return response()->json(['status'=>false,'message'=>'Số lượng chuyển đi không được lớn hơn số lượng hiện có'],200);
        return response()->json(['status'=>true],200);
    }

    public function wareHouseToNode(Request $request)
    {
        $nextNode= (object) $request->nextNode;
        $nextWareHouse = Node::find($nextNode->id);
        $assets = $request->assetList;
        foreach ($assets as $asset){
            $asset = (object) $asset;
            if($asset->origin_qty <= 1){
                $as = Asset::find($asset->id);
                $as->warehouse_id = $nextWareHouse->warehouse_id;
                $as->save();
                return response()->json($as, 200);
            }else{
                $as = new Asset();
                $as->serial = $asset->serial;
                $as->serial2 = $asset->serial2;
                $as->serial3 = $asset->serial3;
                $as->serial4 = $asset->serial4;
                $as->origin = $asset->origin;
                $as->warranty_partner = $asset->warranty_partner;
                $as->warranty_period = $asset->warranty_period;
                $as->quantity = $asset->newQuantity;
                $as->manager = $asset->manager ? $asset->manager : null;
                $as->asset_type_id = $asset->asset_type_id;
                $as->asset_position_id = $asset->asset_position_id;
                $as->warehouse_id = Node::find($nextNode->id)->warehouse->id;
                $as->asset_status_id = $asset->asset_status_id;
                $as->asset_qlts_code_id = $asset->asset_qlts_code_id;
                $as->asset_vhkt_code_id = $asset->asset_vhkt_code_id;
                $as->parent_id = $asset->id;
                $as->origin_qty = $asset->origin_qty;
                $as->note = $asset->note;
                $as->user_id = $asset->user_id;
                $as->group_id = $asset->group_id;
                $as->indexes = $asset->indexes;
                $as->save();
                $update = Asset::find($asset->id);
                $update->quantity = (intval($asset->quantity)-intval($asset->newQuantity));
                $update->save();
            }
        }
        return response()->json(['message'=>'Điều chuyển tài sản thành công']);
    }


    public function warrantyRepair(Request $request)
    {
        
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
