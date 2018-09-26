@extends('layouts.app')
@section('title','Điều chuyển giữa nhân viên quản lý')

@section('vendor_js')
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endsection


@section('content')
    <form action="#">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">Điều chuyển nhân viên quản lý</h5>
            </div>

            <div class="panel-body">
                <div class="form-group">
                    <label for="next-manager">Chọn người quản lý cần chuyển tài sản đến <span class="text-danger">*</span></label>
                    <select name="next-manager" id="next-manager" class="select2" data-placeholder="Chọn người quản lý..."></select>
                </div>
                <div class="form-group" style="display: none;">
                    <div class="row">
                        <div class="col-lg-10">
                            <label for="asset">Chọn tài sản</label>
                            <select name="asset" id="asset" class="select2" data-placeholder="Chọn tài sản..."></select>
                        </div>
                        <div class="col-lg-2">
                            <label>Số lượng</label>
                            <input type="number" id="quantity" class="form-control" placeholder="Số lượng">
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-success">Submit<i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </div>
        </div>
    </form>
@endsection


@section('custom_js')
    <script>
        let nextManager = $('#next-manager');
        let asset = $('#asset');
        nextManager.select2({
            ajax: {
                url: '{{ route('local-manager-transfers.managers') }}',
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        keyword: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (user) {
                            return {
                                text: `${user.name} / ${user.username} / ${user.email}`,
                                id: user.id,
                                data: user
                            };
                        })
                    };
                }
            }
        }).on('select2:select',(e)=>{
            asset.parent().parent().parent().hide();
            if(e.params.data.id != null){
                $.ajax({
                    url: '{{ route('local-manager-transfers.has-warehouse') }}',
                    method:'POST',
                    headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                    dataType: 'json',
                    data:{ id: e.params.data.id }
                }).done((res)=>{
                    if(res.status)
                        asset.parent().parent().parent().show();
                });
            }
        });
        asset.select2({
            ajax: {
                url: '{{ route('local-manager-transfers.assets') }}',
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                type: 'POST',
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: $.map(data, function (asset) {
                            return {
                                text: `Tài sản: ${asset.name} | Serial: ${asset.serial} | Số lượng hiện có: ${asset.quantity} | QLTS Code: ${asset.qlts_code} | Vendor: ${asset.vendor_name}`,
                                id: asset.id,
                                data: asset
                            };
                        })
                    };
                }
            }
        }).on('select2:select',(e)=>{

        });
    </script>
@endsection