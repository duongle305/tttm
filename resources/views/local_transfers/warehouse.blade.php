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
                <div class="row mb-10">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 1:</b></h4>
                        <h6 class="grey-300 text-center">Chọn Node để chuyển tài sản vào</h6>
                        <div class="form-group">
                            <label class="col-lg-1 control-label">Node<span class="text-danger">*</span></label>
                            <div class="col-lg-11">
                                <select data-placeholder="Chọn node..." class="select" id="nodes"></select>
                                <h6 class="grey-300 text-center mt-10">Danh sách hiển thị là các node đã liên kết với kho dành riêng cho mỗi node. nếu không tìm thấy hãy qua <code>Quản lý node</code> để liên kết kho</h6>
                            </div>
                        </div>
                    </div>
                </div>

            </fieldset>

            <h6>Chọn kho:</h6>
            <fieldset>
                <div class="row mb-10">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 2:</b></h4>
                        <h6 class="grey-300 text-center">Chọn kho có tài sản muốn chuyển đi</h6>
                        <div class="form-group">
                            <label class="col-lg-1 control-label">Kho<span class="text-danger">*</span></label>
                            <div class="col-lg-11">
                                <select data-placeholder="Chọn kho..." class="select" id="warehouses"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <h6>Chọn tài sản</h6>
            <fieldset>
                <div class="row mb-10">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 3:</b></h4>
                        <h6 class="grey-300 text-center">Chọn tài sản muốn chuyển</h6>
                        <div class="form-group">
                            <label class="col-lg-1 control-label">Tài sản<span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select data-placeholder="Chọn tài sản..." class="select" id="assets"></select>
                            </div>
                            <label class="col-lg-1 control-label">Số lượng<span class="text-danger">*</span></label>
                            <div class="col-lg-1">
                                <input type="nunber" class="form-control" id="quantity">
                            </div>
                            <div class="col-lg-1">
                                <button type="button" class="btn btn-success btn-block" id="add-asset">Add</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12">
                                <div class="panel panel-flat">
                                    <div class="table-responsive pre-scrollable">
                                        <table class="table" id="asset_selected">
                                            <thead>
                                            <tr>
                                                <th>Thông tin chi tiết</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <h6>Xem lại</h6>
            <fieldset>
                <div class="row">

                </div>
            </fieldset>
        </form>
    </div>
@endsection
@section('custom_js')
    <script>
        let status = false;
        let form = $('#form_repository_transfer');
        form.steps({
            headerTag: "h6",
            bodyTag: "fieldset",
            transitionEffect: "fade",
            titleTemplate: '<span class="number">#index#</span> #title#',
            autoFocus: true,
            onStepChanging: function (event, currentIndex, newIndex) {
                let node1 = $('#nodes');
                if(node1.val() == null){
                    $.jGrowl('Vui lòng chọn node để chuyển tài sản', {
                        header: 'Có lỗi xảy ra',
                        theme: 'bg-danger'
                    });
                }else{
                    if(!status) $.jGrowl('Hệ thống đang bị lỗi rất nghiêm trọng: Node không có kho chứa tài sản. Đề nghị liên hệ System Admin', {
                        header: 'Lỗi nghiêm trọng',
                        theme: 'bg-danger'
                    });
                    else return $(event.target).show().valid();
                }
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
            },
            onFinishing: function (event, currentIndex) {
            },
            onFinished: function (event, currentIndex) {
            }
        });
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
        $('#warehouses').select2({
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('local-transfers.warehouses') }}',
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        keyword: params.term
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
        }).on('select2:select', (e)=>{
            sessionStorage.setItem('warehouse_id',e.params.data.data.id);
        });
        $('#assets').select2({
            ajax: {
                url: '{{ route('local-transfers.assets') }}',
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                type: 'POST',
                dataType: 'json',
                data: function () {
                    return {
                        warehouse_id: sessionStorage.getItem('warehouse_id')
                    };
                },
                processResults: function (data, params) {
                    console.log(data);
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
            let data = e.params.data.data;
            $('#add-asset').on('click', (e)=>{
                data.quantity = $('#quantity').val();
                $('#assets').val('');
                $('#quantity').val('');
                addRow(data);
            });
        });
        function addRow(data){
            let  row = `<tr><td>Tên: ${data.name} // Serial: ${data.serial} // Serial2: ${data.serial2} // Số lượng: ${data.quantity} </td><td><button class="btn btn-dannger remove">Remove</button></td></tr>`;
            $('#asset_selected').append(row);
        }
    </script>
@endsection