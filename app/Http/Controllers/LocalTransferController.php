<?php

namespace App\Http\Controllers;

use App\Node;
use Illuminate\Http\Request;
use function PHPSTORM_META\map;

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
            ->select(['id', 'name', 'manager', 'warehouse_id'])
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
    public function repositoryTransfer()
    {
        return view('local_transfers.repository');
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
