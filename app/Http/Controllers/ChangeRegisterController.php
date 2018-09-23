<?php

namespace App\Http\Controllers;

use App\ChangeRegister;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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
            $item->date = Carbon::createFromFormat('Y-m-d', $item->date)->toFormattedDateString();
            $item->creator = User::find($item->creator_id)->name;
            $item->team = User::find($item->creator_id)->team->name;
            $item->executor = $item->executor_id ? User::find($item->executor_id)->name:'';
            $item->tester = $item->tester_id ? User::find($item->tester_id)->name:'';
            return $item;
        })->all();
        return DataTables::of($change_registers)
            ->addColumn('actions', function($item){
                return '<ul class="icons-list">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <i class="icon-menu9"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="'.route('change-registers.edit', $item->id).'"><i class="icon-eye"></i> Xem chi tiết</a></li>
                                    <li><a href="#"><i class="icon-file-pdf"></i> Xóa</a></li>
                                </ul>
                            </li>
                        </ul>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('change-registers.create')->with(compact('users'));
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
            'creator_id'=>'required|integer',
            'cr_number'=>'required|string',
            'content'=>'required|string',
            'purpose'=>'required|string',
            'preparer_id'=>'nullable|integer',
            'prepare_content'=>'nullable|string',
            'combinator_id'=>'nullable|integer',
            'combine_phone_nb'=>'nullable|string|min:10|max:11',
            'executor_id'=>'required|integer',
            'execute_content' =>'required|string',
            'tester_id'=>'nullable|integer',
            'result'=>'nullable|string',
            'note'=>'nullable|string',
        ]);
        $data = $request->all();
        $data['date'] = Carbon::createFromFormat('m/d/Y', $request->date)->toDateString();
        ChangeRegister::create($data);
        return redirect()->route('change-registers.index')->with(['message'=>'Thêm mới đầu việc thành công !!']);

    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ChangeRegister::find($id);
        $data->creator = User::find($data->creator_id)->name;
        $data->team = User::find($data->creator_id)->team->name;
        $users = User::all();
        return  view('change-registers.edit')->with(compact(['data','users']));
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
        $change =  ChangeRegister::find($id);
        $this->validate($request,[
            'date'=>'required|date_format:m/d/Y',
            'creator_id'=>'required|integer',
            'cr_number'=>'required|string',
            'content'=>'required|string',
            'purpose'=>'required|string',
            'preparer_id'=>'nullable|integer',
            'prepare_content'=>'nullable|string',
            'combinator_id'=>'nullable|integer',
            'combine_phone_nb'=>'nullable|string|min:10|max:11',
            'executor_id'=>'required|integer',
            'execute_content' =>'required|string',
            'tester_id'=>'nullable|integer',
            'result'=>'nullable|string',
            'note'=>'nullable|string',
        ]);
        $data = $request->all();
        $data['date'] = Carbon::createFromFormat('m/d/Y', $request->date)->toDateString();
        $change->update($data);
        return redirect()->route('change-registers.index')->with(['message'=>'Cập nhật đầu việc thành công !!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $change = ChangeRegister::find($id);
        $change->delete();
        return redirect()->route('change-registers.index')->with(['message'=>'Xóa thành công !!']);
    }
}
