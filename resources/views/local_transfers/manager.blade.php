@extends('layouts.app')
@section('title','Điều chuyển giữa nhân viên quản lý')

@section('vendor_js')
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/wizards/steps.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endsection


@section('content')
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">Điều chuyển tài sản</h6>
        </div>

        <form class="steps-transfers" action="#">
            <h6>Chọn nhân viên</h6>
            <fieldset>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="next-manager">Chọn nhân viên <span class="text-danger">*</span></label>
                            <select id="next-manager" data-placeholder="Chọn nhân viên..."></select>
                        </div>
                    </div>
                </div>
            </fieldset>
            <h6>Chọn tài sản</h6>
            <fieldset>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="asset">Tài sản <span class="text-danger">*</span></label>
                            <select id="asset" data-placeholder="Chọn tài sản..."></select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="quantity">Số lượng <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="quantity" placeholder="Số lượng...">
                        </div>
                    </div>
                    <div class="col-lg-2" style="margin-top: 27px !important;">
                        <div class="form-group">
                            <button class="btn btn-success" id="btn-add">Thêm tài sản</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-20">
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
            </fieldset>

            <h6>Xem lại</h6>
            <fieldset>
                <div class="row">
                    <div class="col-lg-12">
                        <label>Thông tin điều chuyển</label>
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-manager">
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-20">
                        <label>Danh sách tài sản điều chuyển</label>
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-asset">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tài sản</th>
                                    <th>Số lượng</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
@endsection


@section('custom_js')
    <script>
        // steps
        $('.steps-transfers').steps({
            headerTag: "h6",
            bodyTag: "fieldset",
            transitionEffect: "fade",
            titleTemplate: '<span class="number">#index#</span> #title#',
            labels: {
                finish: 'Submit'
            },
            onStepChanging: (e, currentIndex, nextIndex)=>{
                switch (currentIndex) {
                    case 0:{
                        if(nextManager.select2('data').length > 0){
                            let warehouse_id = nextManager.select2('data')[0].data.warehouse_id;
                            if(warehouse_id != null) return true;
                            else {
                                new PNotify({
                                    title: 'Thông báo',
                                    text: 'Nhân viên chưa liên kết kho vui lòng liên kết kho rồi quay lại.',
                                    addclass: 'bg-danger',
                                    type:'error'
                                });
                                return false;
                            }
                        }else new PNotify({
                            title: 'Thông báo',
                            text: 'Vui lòng chọn nhân viên quản lý cần chuyển tài sản đến.',
                            addclass: 'bg-danger',
                            type:'error'
                        });
                        return false;
                    }
                    case 1:{
                        if(assetId.length >= 1){
                            viewDetail();
                            return true;
                        }
                        break;
                    }
                }
            },
            onFinishing: (e)=>{
                $.ajax({
                    url:'{{ route('local-manager-transfers.transfer') }}',
                    method: 'POST',
                    headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                    data:{ manager_id: nextManager.select2('data')[0].id, assets: dataAsset },
                }).done((res)=>{
                    if(res.status) new PNotify({
                        title: 'Thông báo',
                        text: 'Điều chuyển tài sản thành công!',
                        addclass: 'bg-success',
                        type:'success'
                    });
                });
                return true;
            },
            onFinished: (e)=>{
                setTimeout(()=>{
                    location.reload();
                },2500)
            }
        });
        let nextManager = $('#next-manager');
        let asset = $('#asset');
        let btnAdd = $('#btn-add');
        let ipQuantity = $('#quantity');
        let assetId = [], dataAsset = [];
        // select next manager
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
        });
        //select asset
        asset.select2({
            ajax: {
                url: '{{ route('local-manager-transfers.assets') }}',
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                type: 'POST',
                dataType: 'json',
                data: function () {
                    return {
                        asset_id: assetId
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
        }).on('select2:select',(e)=>{
            ipQuantity.val(e.params.data.data.quantity);
        });
        ipQuantity.on('input', (e)=>{
            btnAdd.attr('disabled',true);
            if(asset.select2('data').length > 0){
                let quantity = asset.select2('data')[0].data.quantity;
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
            for(let as of dataAsset){
                row += `<tr><td>${index}</td><td>${as.text}</td><td>${as.quantity}</td><td><button class="btn btn-danger btn-xs btn-remove" data-index="${index-1}">Remove</button></td></tr>`;
                index++;
            }
            $('#asset-list tbody').html(row);

        }
        btnAdd.click((e)=>{
            e.preventDefault();
            let quantity = ipQuantity.val();
            if(asset.select2('data').length > 0 && quantity != ""){
                let data = asset.select2('data')[0];
                ipQuantity.val('');
                asset.val(null).trigger('change');
                assetId.push(data.id);
                dataAsset.push({ id: data.id, quantity: quantity, text: data.text});
                addRow();
            }
        });
        $('body').on('click','.btn-remove',(e)=>{
            assetId.splice($(e.target).data('index'),1);
            dataAsset.splice($(e.target).data('index'),1);
            addRow();
        });
        function viewDetail(){
            let manager = nextManager.select2('data')[0];
            $('#table-manager').html(`<tr><th>Nhân viên được điều chuyển</th><td>${manager.text}</td></tr>`);
            let index = 1, row = '';
            for(let as of dataAsset){
                row += `<tr><td>${index}</td><td>${as.text}</td><td>${as.quantity}</td></tr>`;
                index++;
            }
            $('#table-asset tbody').html(row);
        }

    </script>
@endsection