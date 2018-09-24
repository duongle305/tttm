<?php

namespace App\Http\Controllers;

use App\Asset;
use App\Node;
use App\WareHouse;
use Illuminate\Http\Request;

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
        $nodes = Node::where('name', 'like', "%$request->keyWord%")
            ->select(['id', 'name', 'manager', 'nims', 'warehouse_id'])
            ->get();
        if(!empty($request->step1_item_id)){
            $nodes = collect($nodes)->map(function($item) use ($request){
                if($item->id == $request->step1_item_id) unset($item);
                else return $item;
            })->all();

        }
        return response()->json($nodes, 200);
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
            ->with(['qlts_code'])
            ->get();
        $assets = collect($assets)->mapWithKeys(function($item){
            $item->name = $item->qlts_code->name;
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
