@extends('layouts.app')

@section('title','Điều chuyển bảo hành sửa chữa')

@section('vendor_js')

    <script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.date.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/legacy.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endsection
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
                            <button class="btn btn-success btn-block" id="btn-add">Thêm tài sản</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-6">
                            <label>Ngày hỏng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Ngày hỏng" id="broken_date">
                        </div>
                        <div class="col-lg-6">
                            <label>Nguyên nhân hỏng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="reason">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-12">
                            <label>Điều kiện môi trường <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="environment">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-12">
                            <label>Ghi chú</label>
                            <textarea id="note" class="form-control" rows="3"></textarea>
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
                                        <th>Ngày hỏng</th>
                                        <th>Nguyên nhân</th>
                                        <th>Điều kiện môi trường</th>
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
                    <button id="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </form>
@endsection


@section('custom_js')
    <script>
        ipDate = $('#broken_date');
        ipReason = $('#reason');
        ipEnvironment = $('#environment');
        ipNote = $('#note');
        ipDate.pickadate();
        let asset = $('#asset');
        let excepts = [];
        let assets = [];
        let ipQuantity = $('#quantity');
        let btnAdd = $('#btn-add');
        asset.select2({
            ajax: {
                url: '{{ route('warranty-repairs.assets') }}',
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                     return {
                        excepts: excepts,
                        keyword: params.term,
                        page: params.page || 1
                     };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data.data, function (asset) {
                            if (asset != null) return {
                                text: `Tài sản: ${asset.name} | Serial: ${asset.serial} | Số lượng hiện có: ${asset.quantity} | QLTS Code: ${asset.qlts_code} | Vendor: ${asset.vendor_name}`,
                                id: asset.id,
                                asset: asset
                            };
                        }),
                        pagination: {
                            more: (params.page * 10) <= data.total
                        }
                    };
                }
            }
        }).on('select2:select',(e)=>{
            ipQuantity.val(e.params.data.asset.quantity);
        });
        ipQuantity.on('input', (e)=>{
            btnAdd.attr('disabled',true);
            if(asset.select2('data').length > 0){
                let quantity = asset.select2('data')[0].asset.quantity;
                if(quantity < $(e.target).val()){
                    new PNotify({
                        title: 'Thông báo',
                        text: 'Số lượng điều chuyển không được lớn hơn số lượng hiện có.',
                        addclass: 'bg-danger',
                        type:'error',
                    });
                }else btnAdd.attr('disabled',false);
            }
        });
        function addRow(){
            let row = '';
            let index = 1;
            for(let as of assets){
                row += `<tr>
                            <td>${index}</td>
                            <td>${as.text}</td>
                            <td>${as.brokenDate}</td>
                            <td>${as.reason}</td>
                            <td>${as.environment}</td>
                            <td>${as.quantity}</td>
                            <td><button class="btn btn-danger btn-xs btn-remove" data-index="${index-1}">Remove</button></td>
                        </tr>`;
                index++;
            }
            $('#asset-list tbody').html(row);
        }
        btnAdd.click((e)=>{
            e.preventDefault();
            let quantity = ipQuantity.val();
            if(asset.select2('data').length > 0 && quantity !== ""){
                if(ipDate.val() === '' || ipReason.val() === '' || ipEnvironment.val() === ''){
                    new PNotify({
                        title: 'Thông báo',
                        text: 'Vui lòng điền đầy đủ thông tin điều chuyển',
                        addclass: 'bg-danger',
                        type:'error',
                    });
                    return;
                }
                let data = asset.select2('data')[0];
                ipQuantity.val('');
                asset.val(null).trigger('change');
                excepts.push(data.id);
                assets.push({
                    id: data.id,
                    quantity: quantity,
                    text: data.text,
                    brokenDate: ipDate.val(),
                    reason: ipReason.val(),
                    environment: ipEnvironment.val(),
                    note: ipNote.val(),
                });
                ipDate.val('');
                ipReason.val('');
                ipEnvironment.val('');
                ipNote.val('');
                addRow();
            }
        });
        $('body').on('click','.btn-remove',(e)=>{
            excepts.splice($(e.target).data('index'),1);
            assets.splice($(e.target).data('index'),1);
            addRow();
        });
        $('#submit').click((e)=>{
            e.preventDefault();
            $.ajax({
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                url: "{{ route('warranty-repairs.submit') }}",
                method: "POST",
                dataType: 'json',
                data: {
                    assets: assets
                },
                success:(res)=>{
                    if(res.status) new PNotify({
                        title: 'Thông báo',
                        text: 'Điều chuyển BHSC thành công !!',
                        addclass: 'bg-success',
                        type:'success',
                    });
                    setTimeout(()=>{
                        location.reload();
                    },2500);
                }
            });
        });
    </script>
@endsection