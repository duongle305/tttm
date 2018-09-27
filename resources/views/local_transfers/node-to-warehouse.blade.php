@extends ('layouts.app')

@section('title','Điều chuyển tài sản giữa Node và Kho')
@section('vendor_js')
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/wizards/steps.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/core/libraries/jasny_bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/validation/validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/extensions/cookie.js') }}"></script>
@endsection
@section('content')
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">Điều chuyển tài sản từ kho sang node</h6>
        </div>

        <form class="steps" action="#">
            <h6>Chọn kho đích</h6>
            <fieldset>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="warehouse">Chọn kho</label>
                            <select id="warehouse" data-placeholder="Chọn kho..."></select>
                        </div>
                    </div>
                </div>
            </fieldset>
            <h6>Chọn node</h6>
            <fieldset>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="node">Chọn node <span class="text-danger">*</span></label>
                            <select id="node" data-placeholder="Chọn node..."></select>
                        </div>
                    </div>
                </div>
            </fieldset>

            <h6>Chọn tài sản</h6>
            <fieldset>
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

            </fieldset>

            <h6>Xem lại</h6>
            <fieldset>
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-12">
                            <label>Thông tin điều chuyển</label>
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-transfer">
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
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
                </div>
            </fieldset>
        </form>
    </div>
@endsection
@section('custom_js')
    <script>
        $(".steps").steps({
            headerTag: "h6",
            bodyTag: "fieldset",
            transitionEffect: "fade",
            titleTemplate: '<span class="number">#index#</span> #title#',
            labels: {
                previous: 'Quay lại',
                next: 'Tiếp tục',
                finish: 'Submit'
            },
            onStepChanging: (e, currentIndex, nextIndex)=>{
                switch (nextIndex) {

                    case 3:{
                        if(excepts.length > 0){
                            viewDetail();
                            return true;
                        }
                    }
                    default:{
                        return true;
                    }
                }
            },
            onFinishing: ()=>{
                $.ajax({
                    url:'{{ route('node-to-warehouse.submit') }}',
                    method: 'POST',
                    headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                    data:{ warehouse_id: warehouse.select2('data')[0].id, assets: assetList},
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
            onFinished: function (event, currentIndex) {
                $('a[href="#finish"]').hide();
            }
        });

        let node = $('#node');
        let warehouse = $('#warehouse');
        let asset = $('#asset');
        let ipQuantity = $('#quantity');
        let btnAdd = $('#btn-add');
        let excepts = [], assetList = [];
        /* Node */
        node.select2({
            ajax:{
                url:'{{ route('node-to-warehouse.nodes') }}',
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
                        results: $.map(data, function (node) {
                            return {
                                text: `Tên node: ${node.name} | Mã: ${node.code} | Nims: ${node.nims} | Phòng máy: ${node.room_name}`,
                                id: node.id,
                                node: node
                            };
                        })
                    };
                }
            }
        }).on('select2:select',(e)=>{
            if(e.params.data.node.warehouse_id == null) {
                new PNotify({
                    title: 'Thông báo',
                    text: 'Lỗi nghiêm trọng, node hiện tại không có kho chứa vui lòng liên hệ SysAdmin.',
                    addclass: 'bg-danger',
                    type: 'error'
                });
                $(e.target).val(null).trigger('change');
            }
        });
        warehouse.select2({
            ajax:{
                url:'{{ route('node-to-warehouse.warehouses') }}',
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
                        results: $.map(data, function (warehouse) {
                            return {
                                text: `Tên kho: ${warehouse.name} | Mã: ${warehouse.code} | Ghi chú: ${warehouse.note} | Kho cha: ${warehouse.parent_text}`,
                                id: warehouse.id,
                                warehouse: warehouse
                            };
                        })
                    };
                }
            }
        });
        asset.select2({
            ajax: {
                url: '{{ route('node-to-warehouse.assets') }}',
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                type: 'POST',
                dataType: 'json',
                data: function (prams) {
                    return {
                        keyword: prams.term,
                        excepts: excepts,
                        warehouse_id: node.select2('data')[0].node.warehouse_id
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
            for(let as of assetList){
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
                excepts.push(data.id);
                assetList.push({ id: data.id, quantity: quantity, text: data.text});
                addRow();
            }
        });
        $('body').on('click','.btn-remove',(e)=>{
            excepts.splice($(e.target).data('index'),1);
            assetList.splice($(e.target).data('index'),1);
            addRow();
        });
        function viewDetail(){
            $('#table-transfer tbody').html(`
                                <tr>
                                    <th>Node điều chuyển</th>
                                    <td>${node.select2('data')[0].text}</td>
                                </tr>
                                <tr>
                                    <th>Kho đích</th>
                                    <td>${warehouse.select2('data')[0].text}</td>
                                </tr>`);
            let index = 1, row = '';
            for(let as of assetList){
                row += `<tr><td>${index}</td><td>${as.text}</td><td>${as.quantity}</td></tr>`;
                index++;
            }
            $('#table-asset tbody').html(row);
        }
    </script>
@endsection