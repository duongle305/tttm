@extends ('layouts.app')

@section('title','Thêm mới đâu việc')
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
            <h6 class="panel-title">Điều chuyển vật tư</h6>
        </div>

        <form id="form_repository_transfer" action="#">
            <h6>Chọn Node đích</h6>
            <fieldset>
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 1:</b></h4>
                        <h6 class="grey-300 text-center">Chọn Node để chuyển tài sản vào</h6>
                        <div class="form-group">
                            <select data-placeholder="Chọn node..." class="select" name="nextNode" id="nodes">
                                <option value=""></option>
                            </select>
                            <h6 class="grey-300 text-center mt-10">Danh sách hiển thị là các node đã liên kết với kho dành riêng cho mỗi node. nếu không tìm thấy hãy qua <code>Quản lý node</code> để liên kết kho</h6>
                        </div>
                    </div>
                </div>

            </fieldset>

            <h6>Chọn kho:</h6>
            <fieldset>
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 2:</b></h4>
                        <h6 class="grey-300 text-center">Chọn kho có tài sản muốn chuyển đi</h6>
                        <div class="form-group">
                            <select data-placeholder="Chọn kho..." name="currentWarehouse" class="select" id="warehouses">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>
            </fieldset>

            <h6>Chọn tài sản</h6>
            <fieldset>
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="text-center"><b>Bước 3:</b></h4>
                        <h6 class="grey-300 text-center">Chọn tài sản muốn chuyển</h6>
                        <div class="form-group">
                            <div class="col-lg-10">
                                <select data-placeholder="Chọn tài sản..." class="select" id="assets">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <input type="number" id="quantity" class="form-control" placeholder="Số lượng">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 text-center mt-20">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary btn-sm" id="add-asset">Add to list</button>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="panel panel-flat p-10">
                            <ul class="media-list media-list-linked" id="asset-list"></ul>
                        </div>
                    </div>
                </div>
            </fieldset>

            <h6>Xem lại</h6>
            <fieldset>
                <div class="row">
                    <div class="col-lg-12">
                        <label for="">Node</label>
                        <table class="table table-togglable table-hover" id="node-warehouse">
                        </table>
                    </div>
                    <div class="col-lg-12">
                        <label>Tài sản</label>
                        <table class="table table-togglable table-hover" id="asset-list-detail">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tài sản</th>
                                    <th>Số lượng</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
@endsection
@section('custom_js')
    <script>
        let assetList = [], nextNode = {}, currentWarehouse = {};
        let status = false;
        let form = $('#form_repository_transfer');
        // review asset
        function review(){
            let nw= $('#node-warehouse');
            nw.html(
                `<tr>
                    <th>Node đích</th>
                    <td>${nextNode.name} / ${nextNode.nims} / ${nextNode.manager}</td>
                </tr>
                <tr>
                    <th>Kho chuyển</th>
                    <td>${currentWarehouse.name} / ${currentWarehouse.code}</td>
                </tr>`
            );
            let assets = '';
            for(let t of assetList){
               assets += `
                    <tr>
                        <td>${t.id}</td>
                        <td>${t.serial} // ${t.name}</td>
                        <td>${t.newQuantity}</td>
                    </tr>
                `;
            }
            $('#asset-list-detail tbody').html(assets);

        }
        form.steps({
            headerTag: "h6",
            bodyTag: "fieldset",
            transitionEffect: "fade",
            titleTemplate: '<span class="number">#index#</span> #title#',
            autoFocus: true,
            onStepChanging: function (event, currentIndex, newIndex) {
                let node1 = $('#nodes');
                let warehouse =  $('#warehouses');
                if(newIndex === 3){
                    review();
                }
                switch (currentIndex) {
                    case 0:{
                        if(node1.val() == null || node1.val() === ""){
                            $.jGrowl('Vui lòng chọn node để chuyển tài sản', {
                                header: 'Có lỗi xảy ra',
                                theme: 'bg-danger'
                            });
                        }else{
                            if(!status) $.jGrowl('Hệ thống đang bị lỗi rất nghiêm trọng: Node không có kho chứa tài sản. Đề nghị liên hệ System Admin', {
                                header: 'Lỗi nghiêm trọng',
                                theme: 'bg-danger'
                            });
                            else {
                                nextNode = node1.select2('data')[0].data;
                                return $(event.target).show().valid();
                            }
                        }
                        break;
                    }
                    case 1:{
                        if(warehouse.val() == null || warehouse.val() === ""){
                            $.jGrowl('Vui lòng chọn kho để chuyển tài sản đi', {
                                header: 'Có lỗi xảy ra',
                                theme: 'bg-danger'
                            });
                        }else{
                            currentWarehouse = warehouse.select2('data')[0].data;
                            return $(event.target).show().valid();
                        }
                        break;
                    }
                    case 2:{
                        if(assetList.length <= 0){
                            $.jGrowl('Vui lòng chọn vật tư cần chuyển đi', {
                                header: 'Có lỗi xảy ra',
                                theme: 'bg-danger'
                            });
                        }else{
                            return $(event.target).show().valid();
                        }
                        break;
                    }
                    default:{
                        return $(event.target).show().valid();
                    }
                }
            },
            onFinishing: function (event, currentIndex) {
                $.ajax({
                    url: '{{ route('local-transfers.warehouse-to-node') }}',
                    headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                    method: 'POST',
                    data:{
                        currentWarehouse: currentWarehouse,
                        nextNode: nextNode,
                        assetList: assetList,
                    }
                }).done((e)=>{
                    $.jGrowl('Điều chuyển tài sản thành công !!', {
                        header: 'Lỗi nghiêm trọng',
                        theme: 'bg-success'
                    });
                });
                return $(event.target).show().valid();
            },
            onFinished: function (event, currentIndex) {
                setTimeout(()=>{
                    location.reload();
                },2000);
            }
        });
        //step 1
        $('#nodes').select2({
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('local-transfers.nodes') }}',
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        keyWord: params.term
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: `${item.name} / ${item.nims} / ${item.manager}`,
                                id: item.id,
                                data: item
                            };
                        })
                    };
                }
            }
        }).on('select2:select', (e)=>{
            if(e.params.data.data.warehouse_id !== null) status = true;
            else{
                $.jGrowl('Hệ thống đang bị lỗi rất nghiêm trọng: Node không có kho chứa tài sản. Đề nghị liên hệ System Admin', {
                    header: 'Lỗi nghiêm trọng',
                    theme: 'bg-danger'
                });
            }
        });
        //step 2
        $('#warehouses').select2({
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('local-transfers.warehouses') }}',
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        keyword: params.term,
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: `${item.name} / ${item.code}`,
                                id: item.id,
                                data: item
                            };
                        })
                    };
                }
            }
        });
        //step 3
        $('#assets').select2({
            ajax: {
                url: '{{ route('local-transfers.assets') }}',
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                type: 'POST',
                dataType: 'json',
                data: function () {
                    return {
                        warehouse_id: currentWarehouse.id,
                        excepts: assetList
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: `Tên: ${item.name} // Serial: ${item.serial} // Serial2: ${item.serial2} // Số lượng: ${item.quantity}`,
                                id: item.id,
                                data: item
                            };
                        })
                    };
                }
            }
        }).on('select2:select',(e)=>{
            $('#quantity').val(e.params.data.data.quantity);
        });

        //add asset to list
        $('#add-asset').click((e)=>{
            e.preventDefault();
            let qty = $('#quantity');
            let quantity = qty.val();
            if(quantity > 0 && quantity != null){
                qty.val('');
                let data = $('#assets').select2('data')[0].data;
                data.newQuantity = quantity;
                assetList.push(data);
                $('#asset-list').append(`<li class="media">
                                            <div class="media-body">
                                                Tên: ${data.name} || ${data.serial} || ${data.serial2} - Số lượng: ${quantity}
                                            </div>
                                         </li>`);
            }else{
                $.jGrowl('Vui lòng nhập số lượng chuyển đi lớn 0', {
                    header: 'Có lỗi xảy ra',
                    theme: 'bg-danger'
                });
            }
        });
        // check quantity
        $('#quantity').keyup((e)=>{
            let btn = $('#add-asset');
            btn.attr('disabled',true);
            let data = $('#assets').select2('data')[0].data;
            $.ajax({
                url: '{{ route('local-transfers.quantity') }}',
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                method:'POST',
                data:{ id: data.id, quantity: $(e.target).val() }
            }).done((res)=>{
                btn.attr('disabled',true);
                if(!res.status){
                    $.jGrowl(res.message, {
                        header: 'Có lỗi xảy ra',
                        theme: 'bg-danger'
                    });
                }else{
                    $('#add-asset').attr('disabled',false);
                }
            }).always((e)=>{});
        });
    </script>
@endsection