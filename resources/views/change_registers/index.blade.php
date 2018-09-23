@extends ('layouts.app')

@section('title','Quan ly dau viec')
@section('vendor_js')
    <script type="text/javascript" src="{{ asset('assets/js/plugins/ui/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
@endsection
@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <div class="col-lg-10 pb-10">
                <h6 class="panel-title">Danh sách đầu việc</h6>
            </div>
            <div class="col-lg-2 text-right pb-10">
                <a href="{{ route('change-registers.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Thêm mới</a>
            </div>
        </div>
        <div class="panel-body">
            <table class="table datatable-show-all" id="change_registers_table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Ngày</th>
                    <th>Người tạo</th>
                    <th>Ban</th>
                    <th>Số CR</th>
                    <th>Người thực hiện</th>
                    <th>Nội dung thực hiện</th>
                    <th>Người kiểm tra</th>
                    <th>Kết quả</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('custom_js')
    <script>
        $('#change_registers_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('change-registers.all') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'date',name: 'date'},
                { data: 'creator',name: 'creator'},
                { data: 'team', name: 'team'},
                { data: 'cr_number',name: 'cr_number'},
                { data: 'executor',name: 'executor'},
                { data: 'execute_content',name: 'execute_content'},
                { data: 'tester',name: 'tester'},
                { data: 'result',name: 'result'},
                { data: 'actions',name: 'actions', orderable: false, searchable: false},
            ]
        });
    </script>
@endsection
