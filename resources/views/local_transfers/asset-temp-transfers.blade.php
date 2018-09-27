@extends('layouts.app')

@section('title','Danh sách tài sản chờ nhận')

@section('vendor_js')
    <script type="text/javascript" src="{{ asset('assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Danh sách tài sản điều chuyển chờ nhận</h5>
        </div>

        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped datatable-basic" id="table-assets">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên tài sản</th>
                            <th>Mã QLTS</th>
                            <th>Số lượng điều chuyển</th>
                            <th>Vendor</th>
                            <th>Người chuyển</th>
                            <th>Email người chuyển</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>


    </div>
@endsection

@section('custom_js')
    <script>
        let assetTempTransfer = $('.datatable-basic').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('local-manager-transfers.assets-temp') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'asset_name',name: 'asset_name'},
                { data: 'code',name: 'code'},
                { data: 'asset_quantity',name: 'asset_quantity'},
                { data: 'vendor_name', name: 'vendor_name'},
                { data: 'manager_transfer',name: 'manager_transfer'},
                { data: 'email_transfer',name: 'email_transfer'},
                { data: 'actions',name: 'actions', orderable: false, searchable: false},
            ]
        });
        $('body').on('click','.accept', (e)=>{
            $.ajax({
                url:'{{  route('local-manager-transfers.accept-asset-transfer') }}',
                method:'POST',
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                data: { id : $(e.target).data('id') }
            }).done((res)=>{
                assetTempTransfer.ajax.reload();
            });
        });
        $('body').on('click','.cancel', (e)=>{
        });
    </script>
@endsection