@extends('layouts.app')

@section('title','Điều chuyển bảo hành sửa chữa')

@section('vendor_js')
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>@endsection
@section('content')
    <form action="#">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">Điều chuyển BHSC</h5>
            </div>

            <div class="panel-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-8">
                            <label for="asset">Tài sản <span class="text-danger">*</span></label>
                            <select id="asset" data-placeholder="Chọn tài sản..."></select>
                        </div>
                        <div class="col-lg-2">
                            <label for="quantity">Số lượng <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="quantity" placeholder="Số lượng...">
                        </div>
                        <div class="col-lg-2" style="margin-top: 27px !important;">
                            <button class="btn btn-success" id="btn-add">Thêm tài sản</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="asset-list">Danh sách tài sản điều chuyển <span class="text-danger">*</span></label>
                            <div class="table-responsive pre-scrollable">
                                <table class="table table-striped" id="asset-list">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tài sản</th>
                                        <th>Số lượng</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </form>
@endsection


@section('custom_js')
    <script>
        let asset = $('#asset');
        let excepts = [];
        let assets = [];
        asset.select2({
            ajax: {
                url: '{{ route('warranty-repairs.assets') }}',
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                type: 'POST',
                dataType: 'json',
                data: function (prams) {
                    return {
                        keyword: prams.term,
                        excepts: excepts,
                    };
                },
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
        });
    </script>
@endsection