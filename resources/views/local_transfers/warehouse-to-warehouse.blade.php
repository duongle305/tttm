@extends ('layouts.app')

@section('title','Thêm mới đâu việc')
@section('vendor_js')
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/noty.min.js') }}"></script>
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
            <h5 class="panel-title">ĐIỀU CHUYỂN GIỮA CÁC KHO NỘI BỘ</h5>
        </div>

        <form class="steps-validation" action="#">
            <h6>Chọn Node đích</h6>
            <fieldset>
                <div class="row mb-10">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 1:</b></h4>
                        <h6 class="grey-300 text-center">Chọn kho chuyển tài sản đến</h6>
                        <div class="form-group">
                            <label class="col-lg-1 control-label">Kho <span class="text-danger">*</span></label>
                            <div class="col-lg-11">
                                <select data-placeholder="Chọn kho..." class="select"
                                        id="step_1_select"></select>
                                <div id="step1_error_show"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <h6>Chọn Kho đầu</h6>
            <fieldset>
                <div class="row mb-10">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 2:</b></h4>
                        <h6 class="grey-300 text-center">Chọn kho có tài sản muốn chuyển đi</h6>
                        <div class="form-group">
                            <label class="col-lg-1 control-label">Kho <span class="text-danger">*</span></label>
                            <div class="col-lg-11">
                                <select data-placeholder="Chọn node..." class="select" id="step_2_select">
                                    <option></option>
                                </select>
                                <div id="step2_error_show"></div>
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
                        <h6 class="grey-300 text-center">Chọn tài sản muốn điều chuyển</h6>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Chọn tài sản<span class="text-danger">*</span></label>
                                    <select data-placeholder="Chọn tài sản..." class="select" id="step_3_select">
                                        <option></option>
                                    </select>
                                    <div id="step3_show_selected_error"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" id="quantity_input" style="display: none;">
                                    <label>Số lượng điều chuyển<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="step3_input_quantity">
                                    <div id="step3_input_quantity_error_show"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mt-20">
                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn btn-primary btn-xs" id="step3_add_select">
                                            Thêm tài sản <i class="icon-download position-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mt-20">
                                <div class="col-md-12">
                                    <label>Các tài sản sẽ chuyển sang node mới</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            </thead>
                                            <tbody id="step3_show_selected">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <h6>Xem lại bà kết thúc</h6>
            <fieldset>
                <div class="row mb-10">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 4:</b></h4>
                        <h6 class="grey-300 text-center">Xem lại và kết thúc</h6>
                        <div class="row">
                            <label>Kho đầu:</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên Kho</th>
                                        <th>Tên viết tắt</th>
                                        <th>Mã</th>
                                    </tr>
                                    </thead>
                                    <tbody id="step4_show_warehouse_transfer">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <label>Kho đích:</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên Kho</th>
                                        <th>Tên viết tắt</th>
                                        <th>Mã</th>
                                    </tr>
                                    </thead>
                                    <tbody id="step4_show_warehouse_destination">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <label>Tài sản:</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>ID</th>
                                        <th>Tài sản</th>
                                        <th>Số lượng hiện tại</th>
                                        <th>Số lượng điều chuyển</th>
                                    </tr>
                                    </thead>
                                    <tbody id="step4_show_selected">
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

        $(document).ready(function () {
            var step1Status = false;
            var step2Status = false;
            var step3Status = false;
            var warehouseTransfer = null;
            var warehouseDestination = null;
            var assets_selected = [];
            var tmp = null;

            var form = $(".steps-validation").show();

            $(".steps-validation").steps({
                headerTag: "h6",
                bodyTag: "fieldset",
                transitionEffect: "fade",
                titleTemplate: '<span class="number">#index#</span> #title#',
                autoFocus: true,
                onStepChanging: function (event, currentIndex, newIndex) {
                    if (currentIndex > newIndex) {
                        return true;
                    }

                    if(currentIndex == 0 && !warehouseDestination){
                        $('#step1_error_show').html('<label class="validation-error-label">Bạn chưa chọn kho đích</label>');
                        return false;
                    }
                    if(currentIndex == 0 && !step1Status){
                        return false;
                    }
                    if(currentIndex == 1 && !warehouseTransfer){
                        $('#step2_error_show').html('<label class="validation-error-label">Bạn chưa chọn kho đầu</label>');
                        return false;
                    }
                    if(currentIndex == 1 && !step2Status){
                        return false;
                    }

                    if(currentIndex == 2 && assets_selected.length == 0){
                        $('#step3_show_selected_error').html('<label class="validation-error-label">Bạn chưa chọn tài sản</label>');
                        return false;
                    }
                    if(currentIndex == 2 && !step3Status){
                        return false;
                    }

                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                },

                onStepChanged: function (event, currentIndex, priorIndex) {
                    console.log(currentIndex);
                },

                onFinishing: function (event, currentIndex) {
                    form.validate().settings.ignore = ":disabled";
                    return form.valid();
                },

                onFinished: function (event, currentIndex) {
                    $.ajax({
                        headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                        url: "/transfer-warehouse-to-warehouse/ajax/submit",
                        method: "POST",
                        dataType: 'json',
                        data: {
                            warehouseDestination: warehouseDestination,
                            assets: assets_selected
                        },
                        success: function (data) {
                            if (data == 'ok') {
                                swal({
                                    title: 'Thành công!',
                                    text: 'Điều chuyển tài sản thành công!',
                                    type: 'success',
                                    confirmButtonText: 'Đóng'
                                });
                                $(event.target.lastChild.lastChild.lastChild.lastChild).attr('href','javascript:void(0)');
                                setTimeout(function () {
                                    location.reload();
                                },2000);
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            swal({
                                title: 'Lỗi!',
                                text: 'Hệ thống đang bị lỗi rất nghiêm trọng, vui lòng liên hệ System admin để biết chi tiết',
                                type: 'error',
                                confirmButtonText: 'Đóng'
                            });
                        }
                    })
                }
            });

            $(".steps-validation").validate({
                ignore: 'input[type=hidden], .select2-search__field',
                errorClass: 'validation-error-label',
                successClass: 'validation-valid-label',
                highlight: function (element, errorClass) {
                    $(element).removeClass(errorClass);
                },
                unhighlight: function (element, errorClass) {
                    $(element).removeClass(errorClass);
                },

                errorPlacement: function (error, element) {
                    if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container')) {
                        if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                            error.appendTo(element.parent().parent().parent().parent());
                        }
                        else {
                            error.appendTo(element.parent().parent().parent().parent().parent());
                        }
                    }

                    else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
                        error.appendTo(element.parent().parent().parent());
                    }

                    else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
                        error.appendTo(element.parent());
                    }

                    else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                        error.appendTo(element.parent().parent());
                    }

                    else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
                        error.appendTo(element.parent().parent());
                    }

                    else {
                        error.insertAfter(element);
                    }
                },
                rules: {
                    email: {
                        email: true
                    }
                }
            });

            $('#step_1_select').select2({
                minimumInputLength: 1,
                ajax: {
                    url: '/transfer-warehouse-to-warehouse/ajax/get-warehouse',
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
                                    text: `Tên: ${item.name} |-Tên khác: ${item.shortname} ${item.parent_text}`,
                                    id: item.id,
                                    data: item
                                };
                            })
                        };
                    }
                }
            });

            $('#step_1_select').on('select2:select', (e) => {
                    warehouseDestination = e.params.data.data;
                    step4ShowWarehouse(warehouseDestination,$('#step4_show_warehouse_destination'));
                    $('#step1_error_show').html('');
                    step1Status = true;
            });

            $('#step_2_select').select2({
                minimumInputLength: 1,
                ajax: {
                    url: '/transfer-warehouse-to-warehouse/ajax/get-warehouse-after-warehouse-selected',
                    headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                    type: 'POST',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            warehouseDestination: warehouseDestination,
                            keyWord: params.term
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: $.map(data, function (item) {
                                if (item != null) return {
                                    text: `Tên: ${item.name} |-Ten khác: ${item.shortname} ${item.parent_text}`,
                                    id: item.id,
                                    data: item
                                };
                            })
                        };
                    }
                }
            });

            $('#step_2_select').on('select2:select', (e) => {
                step2Status = true;
                warehouseTransfer = e.params.data.data;
                step4ShowWarehouse(warehouseTransfer,$('#step4_show_warehouse_transfer'));
                $('#step2_error_show').html('');
            });

            $('#step_3_select').select2({
                minimumInputLength: 1,
                ajax: {
                    url: '/transfer-warehouse-to-warehouse/ajax/get-asset-after-warehouse-selected',
                    headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                    type: 'POST',
                    dataType: 'json',
                    data: function (params) {
                        if(assets_selected.length > 0){
                            return {
                                warehouseTransfer: warehouseTransfer,
                                keyWord: params.term,
                                selected: assets_selected
                            }
                        } else {
                            return {
                                warehouseTransfer: warehouseTransfer,
                                keyWord: params.term,
                            }
                        }

                    },
                    processResults: function (data, params) {
                        return {
                            results: $.map(data, function (item) {
                                if (item != null) return {
                                    text: `Tên: ${item.asset_name} |-Số lượng hiện có: ${item.quantity} |-Kho: ${item.warehouse_name} |-Vendor: ${item.vendor_name} |-Mã QLTS: ${item.qlts_code} |-Mã VHKT: ${item.vhkt_code}`,
                                    id: item.id,
                                    data: item
                                };
                            })
                        };
                    }
                }
            });

            $('#step_3_select').on('select2:select', (e) => {
                $('#step3_add_select').removeAttr('disabled');
                tmp = e.params.data.data;
                $('#step3_show_selected_error').html('');
                step3Status = true;
                if (parseInt(e.params.data.data.quantity) == 1) {
                    $('#quantity_input').hide();
                } else {
                    $('#quantity_input').show();
                }
            });

            $('#step3_input_quantity').keyup((event) => {
                if (isNaN($(event.currentTarget).val())) {
                    $('#step3_input_quantity_error_show').html('<label class="validation-error-label">Vui lòng nhập số</label>');
                    step3Status = false;
                } else if($(event.currentTarget).val() == ''){
                    $('#step3_input_quantity_error_show').html('<label class="validation-error-label">Số lượng không được để trống</label>')
                    step3Status = false;
                } else if (parseInt($(event.currentTarget).val()) > tmp.quantity) {
                    $('#step3_input_quantity_error_show').html('<label class="validation-error-label">Số lượng không được vượt quá số lượng hiện tại</label>')
                    step3Status = false;
                } else {
                    $('#step3_input_quantity_error_show').html('');
                    step3Status = true;
                }
            });

            $('#step3_add_select').click((event) => {
                if (jQuery.isEmptyObject(tmp)) {
                    $('#step3_show_selected_error').html('<label class="validation-error-label">Bạn chưa chọn tài sản</label>');
                    return false;
                } else if (tmp.quantity > 1 && $('#step3_input_quantity').val() == '') {
                    $('#step3_input_quantity_error_show').html('<label class="validation-error-label">Bạn chưa nhập số lượng</label>');
                    return false;
                } else if (!step3Status) return false;

                $('#step3_show_selected_error').html('');
                $('#step3_input_quantity_error_show').html('');
                $('#quantity_input').hide();
                (tmp.quantity == 1) ? tmp.transfer_quantity = 1 : tmp.transfer_quantity = $('#step3_input_quantity').val();
                assets_selected.push(tmp);
                tmp = null;
                step3ShowSelected();
                step4ShowSelected();
                $('#step_3_select').val(null).trigger('change');
            });

            $(document).on('click','.step3_delete_select',event=>{
                assets_selected.splice($(event.target).data('index'),1);
                step3ShowSelected();
                step4ShowSelected();
            });

            function step4ShowSelected() {
                let html = null;
                let index = 1;
                assets_selected.forEach((data) => {
                    html += `<tr>
                            <td>${index}</td>
                            <td>${data.id}</td>
                            <td>${data.asset_name}</td>
                            <td>${data.quantity}</td>
                            <td>${data.transfer_quantity}</td>
                        </tr>`;
                    index++;
                });
                $('#step4_show_selected').html(html);
            }

            function step3ShowSelected() {
                let html = "";
                assets_selected.forEach((data,index) => {
                    html += `<tr>
                                <td>- Số lượng điều chuyển: ${data.transfer_quantity} // ${data.asset_name} |-Số lượng hiện có: ${data.quantity} |-Kho: ${data.warehouse_name} |-Vendor: ${data.vendor_name} |-Mã QLTS: ${data.qlts_code} |-Mã VHKT: ${data.vhkt_code}</td>
                                <td><button type="button" class="btn btn-danger btn-xs step3_delete_select" data-index="${index}">Xóa</button></td>
							</tr>`;
                });
                $('#step3_show_selected').html(html);
            }

            function step4ShowWarehouse(warehouse, elelement) {
                let html = `<tr>
                            <td>${warehouse.id}</td>
                            <td>${warehouse.name}</td>
                            <td>${(warehouse.shortname == null) ? 'không có' : warehouse.shortname}</td>
                            <td>${(warehouse.code == null) ? 'không có' : warehouse.code}</td>
                        </tr>`;

                $(elelement).html(html);
            }
        });
    </script>
@endsection