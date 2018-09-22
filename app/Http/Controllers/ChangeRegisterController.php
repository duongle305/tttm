<?php

namespace App\Http\Controllers;

use App\ChangeRegister;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChangeRegisterController extends Controller
{

    public function users(){
        return response()->json(User::all(),200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('change_registers.index');
    }

    public function allChangeRegister()
    {
        $change_registers = collect(ChangeRegister::all())->map(function($item){
            $item->creator = User::find($item->creator_id)->name;
            $item->team = User::find($item->creator_id)->team->name;
            $item->combintor = $item->combintor_id ? User::find($item->combintor_id)->name:'';
            $item->executor = $item->executor_id ? User::find($item->executor_id)->name:'';
            $item->tester = $item->tester_id ? User::find($item->tester_id)->name:'';
            return $item;
        })->all();
        return datatables()->of($change_registers)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('change_registers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'date'=>'required|date_format:m/d/Y',
            'creator_id'=>'required',
            'cr_number'=>'required',
            'content'=>'required',
            'purpose'=>'required',
            'combinator_id'=>'nullable|integer',
            'combine_phone_nb'=>'nullable',
            'executor_id'=>'required|integer',
            'execute_content' =>'required|string',
            'tester_id'=>'nullable|integer',
            'result'=>'nullable',
            'note'=>'nullable',
        ]);

        $data = $request->all();
        $data['date'] = Carbon::createFromFormat('m/d/Y', $request->date)->toDateString();
        ChangeRegister::create($data);
        return redirect()->route('change_registers.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
