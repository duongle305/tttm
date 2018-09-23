@extends ('layouts.app')

@section('title','Thêm mới đâu việc')
@section('vendor_js')
    <script type="text/javascript" src="{{ asset('assets/js/plugins/ui/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endsection
@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h6 class="panel-title">Thêm mới đầu việc</h6>
        </div>
        <div class="panel-body">
            <form action="{{ route('change_registers.update', $data->id) }}" class="form-horizontal" method="post">
                <fieldset class="content-group">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-group">
                        <label class="control-label col-lg-2">Ngày<span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                <input name="date" type="text" class="form-control daterange-single" value="{{ date('m/d/Y', strtotime($data->date)) }}" readonly="readonly">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Người tạo<span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input  type="text" class="form-control" readonly="readonly" value="{{ $data->creator }}" >
                            <input type="text" name="creator_id" hidden value="{{ $data->creator_id }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Ban<span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" readonly="readonly" value="{{ $data->team }}">
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('cr_number')? 'has-error has-feedback':'' }}">
                        <label class="control-label col-lg-2">Số CR<span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input name="cr_number" type="text" class="form-control" value="{{ $data->cr_number }}" readonly="readonly">
                            <span class="help-block">{{ $errors->has('cr_number') ? $errors->first('cr_number') : '' }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('content')? 'has-error has-feedback':'' }}">
                        <label class="control-label col-lg-2">Nội dung<span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <textarea name="content" rows="3" cols="5" class="form-control">{{ $data->content }}</textarea>
                            <span class="help-block">{{ $errors->has('content') ? $errors->first('content') : '' }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('purpose')? 'has-error has-feedback':'' }}">
                        <label class="control-label col-lg-2">Mục đích<span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <textarea name="purpose" rows="3" cols="5" class="form-control">{{ $data->purpose }}</textarea>
                            <span class="help-block">{{ $errors->has('purpose') ? $errors->first('purpose') : '' }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Người chuẩn bị</label>
                        <div class="col-lg-10">
                            <select class="select-search" id="prearer_id">
                                <option value="">None</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $data->prepare_id == $user->id?'selected':'' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Nội dung chuẩn bị</label>
                        <div class="col-lg-10">
                            <input type="text" name="prepare_content" class="form-control" value="{{ $data->prepare_content }}">
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('combinator_id')? 'has-error has-feedback':'' }}">
                        <label class="control-label col-lg-2">Người phối hợp</label>
                        <div class="col-lg-10">
                            <select class="select-search" id="combinator_id">
                                <option value="">None</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $data->combinator_id == $user->id?'selected':'' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <span class="help-block">{{ $errors->has('combinator_id') ? $errors->first('combinator_id') : '' }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('combine_phone_nb')? 'has-error has-feedback':'' }}">
                        <label class="control-label col-lg-2">SĐT phối hợp</label>
                        <div class="col-lg-10">
                            <input name="combine_phone_nb" type="text" class="form-control" value="{{ $data->combine_phone_nb }}">
                            <span class="help-block">{{ $errors->has('combine_phone_nb') ? $errors->first('combine_phone_nb') : '' }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('executor_id')? 'has-error has-feedback':'' }}">
                        <label class="control-label col-lg-2">Người thực hiện<span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <select name="executor_id" class="select-search">
                                <option value="">None</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $data->executor_id == $user->id?'selected':'' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <span class="help-block">{{ $errors->has('executor_id') ? $errors->first('executor_id') : '' }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('execute_content')? 'has-error has-feedback':'' }}">
                        <label class="control-label col-lg-2">Nội dung thực hiện<span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <textarea name="execute_content" rows="3" cols="5" class="form-control">{{ $data->execute_content }}</textarea>
                            <span class="help-block">{{ $errors->has('execute_content') ? $errors->first('execute_content') : '' }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('tester_id')? 'has-error has-feedback':'' }}">
                        <label class="control-label col-lg-2">Người kiểm tra</label>
                        <div class="col-lg-10">
                            <select name="tester_id" class="select-search">
                                <option value="">None</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $data->tester_id == $user->id?'selected':'' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <span class="help-block">{{ $errors->has('tester_id') ? $errors->first('tester_id') : '' }}</span>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('result')? 'has-error has-feedback':'' }}">
                        <label class="control-label col-lg-2">Kết quả</label>
                        <div class="col-lg-10">
                            <input name="result" type="text" class="form-control" value="{{ $data->result }}">
                            <span class="help-block">{{ $errors->has('result') ? $errors->first('result') : '' }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Ghi chú</label>
                        <div class="col-lg-10">
                            <textarea name="note" rows="3" cols="5" class="form-control"> {{ $data->note }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12  text-right">
                            <button type="submit" class="btn btn-success">Submit</button>
                            <a href="{{ route('change_registers.index') }}" class="btn btn-danger">Close</a>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
@endsection
@section('custom_js')
    <script>
        $('.select-search').select2();
    </script>
@endsection
