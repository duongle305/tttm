@extends ('layouts.app')

@section('title','Điều chuyển tài sản giữa Kho và Nhân viên quản lý')
@section('vendor_js')
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/pnotify.min.js') }}"></script>
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
            <h5 class="panel-title">ĐIỀU CHUYỂN GIỮA KHO VÀ NVQL ĐANG ĐĂNG NHẬP</h5>
        </div>

        <form class="steps-validation" action="#">
            <h6>Chọn Kho</h6>
            <fieldset>
                <div class="row mb-10">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 1:</b></h4>
                        <h6 class="grey-300 text-center">Chọn Kho có tài sản cần chuyển</h6>
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

            <h6>Chọn tài sản cần chuyển</h6>
            <fieldset>
                <div class="row mb-10">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 2:</b></h4>
                        <h6 class="grey-300 text-center">Chọn tài sản muốn điều chuyển</h6>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Chọn tài sản<span class="text-danger">*</span></label>
                                    <select data-placeholder="Chọn tài sản..." class="select" id="step_2_select">
                                        <option></option>
                                    </select>
                                    <div id="step2_show_selected_error"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" id="quantity_input" style="display: none;">
                                    <label>Số lượng điều chuyển<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="step2_input_quantity">
                                    <div id="step2_input_quantity_error_show"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mt-20">
                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn btn-primary btn-xs" id="step2_add_select">
                                            Thêm tài sản <i class="icon-download position-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mt-20">
                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn bg-slate btn-xs" id="step2_delete_select">
                                            Xóa khỏi danh sách <i class="icon-upload position-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mt-20">
                                <div class="col-md-12">
                                    <label>Các tài sản sẽ chuyển sang kho của bạn</label>
                                    <textarea rows="6" cols="5" class="form-control" readonly id="list_assets_selected"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <h6>Xem lại và kết thúc</h6>
            <fieldset>
                <div class="row mb-10">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 3:</b></h4>
                        <h6 class="grey-300 text-center">Xem lại và kết thúc</h6>
                        <div class="row">
                            <label>Nhân viên:</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên nhân viên</th>
                                        <th>Email</th>
                                    </tr>
                                    </thead>
                                    <tbody id="step3_show_manager">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-20">
                            <label>Kho có tài sản cần chuyển:</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Code</th>
                                        <th>Tên viết tắt</th>
                                    </tr>
                                    </thead>
                                    <tbody id="step3_show_warehouse">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-20">
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
                                    <tbody id="step3_show_selected">

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
            var warehouse = null;
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

                    if(currentIndex == 0 && !warehouse){
                        $('#step1_error_show').html('<label class="validation-error-label">Bạn chưa chọn kho</label>');
                        return false;
                    }
                    if(currentIndex == 0 && !step1Status){
                        return false;
                    }

                    if(currentIndex == 1 && assets_selected.length == 0){
                        $('#step2_show_selected_error').html('<label class="validation-error-label">Bạn chưa chọn tài sản</label>');
                        return false;
                    }
                    if(currentIndex == 1 && !step2Status){
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
                        url: "/ajax/transfer-warehouse-to-manager/submit",
                        method: "POST",
                        dataType: 'json',
                        data: {
                            warehouse: warehouse,
                            assets: assets_selected
                        },
                        success: function (data) {
                            if(data == 'ok'){
                                $.jGrowl('Thành công', {
                                    header: 'Điều chuyển thành công!',
                                    theme: 'bg-success'
                                });
                                setTimeout(function () {
                                    location.reload();
                                },2000);
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            $.jGrowl('Có lỗi xảy ra', {
                                header: 'Điều chuyển không thành công!',
                                theme: 'bg-danger'
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
                    url: '/ajax/transfer-warehouse-to-manager/get-warehouse',
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
                                    text: `Tên kho: ${item.name} |-Tên khác : ${item.shortname}|- ${item.parent_text}`,
                                    id: item.id,
                                    data: item
                                };
                            })
                        };
                    }
                }
            });

            $('#step_1_select').on('select2:select', (e) => {
                warehouse = e.params.data.data;
                $('#step1_error_show').html('');
                step1Status = true;
            });

            $('#step_2_select').select2({
                minimumInputLength: 1,
                ajax: {
                    url: '/ajax/transfer-warehouse-to-manager/get-assets-after-warehouse',
                    headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                    type: 'POST',
                    dataType: 'json',
                    data: function (params) {
                        if(!jQuery.isEmptyObject(assets_selected)){
                            return {
                                selected: assets_selected,
                                warehouse: warehouse,
                                keyWord: params.term
                            };
                        }
                        else {
                            return {
                                warehouse: warehouse,
                                keyWord: params.term
                            };
                        }
                    },
                    processResults: function (data, params) {
                        return {
                            results: $.map(data, function (item) {
                                if (item != null) return {
                                    text: `Tên: ${item.asset_name} |- Số lượng hiện có: ${item.quantity} |-Kho: ${item.houseware_name} |- Vendor: ${item.vendor_name} |-Mã VHKT : ${item.vhkt_code} |-Mã QLTS: ${item.qlts_code}`,
                                    id: item.id,
                                    data: item
                                };
                            })
                        };
                    }
                }
            });

            $('#step_2_select').on('select2:select', (e) => {
                $('#step2_add_select').removeAttr('disabled');
                tmp = e.params.data.data;
                $('#step2_show_selected_error').html('');
                step2Status = true;
                if (parseInt(e.params.data.data.quantity) == 1) {
                    $('#quantity_input').hide();
                } else {
                    $('#quantity_input').show();
                }
            });

            $('#step2_input_quantity').keyup((event) => {
                if (isNaN($(event.currentTarget).val())) {
                    $('#step2_input_quantity_error_show').html('<label class="validation-error-label">Vui lòng nhập số</label>');
                    step2Status = false;
                } else if($(event.currentTarget).val() == ''){
                    $('#step2_input_quantity_error_show').html('<label class="validation-error-label">Số lượng không được để trống</label>')
                    step2Status = false;
                } else if (parseInt($(event.currentTarget).val()) > tmp.quantity) {
                    $('#step2_input_quantity_error_show').html('<label class="validation-error-label">Số lượng không được vượt quá số lượng hiện tại</label>')
                    step2Status = false;
                } else {
                    $('#step2_input_quantity_error_show').html('');
                    step2Status = true;
                }
            });

            $('#step2_add_select').click((event) => {
                if (jQuery.isEmptyObject(tmp)) {
                    $('#step2_show_selected_error').html('<label class="validation-error-label">Bạn chưa chọn tài sản</label>');
                    return false;
                } else if (tmp.quantity > 1 && $('#step2_input_quantity').val() == '') {
                    $('#step2_input_quantity_error_show').html('<label class="validation-error-label">Bạn chưa nhập số lượng</label>');
                    return false;
                } else if (!step2Status) return false;

                $('#step2_show_selected_error').html('');
                $('#step2_input_quantity_error_show').html('');
                $('#quantity_input').hide();
                (tmp.quantity == 1) ? tmp.transfer_quantity = 1 : tmp.transfer_quantity = $('#step3_input_quantity').val();
                assets_selected.push(tmp);
                tmp = null;
                step2ShowSelected();
                step3ShowManager($('#step3_show_manager'));
                step3Showarehouse(warehouse,$('#step3_show_warehouse'))
                step3ShowSelected();
            });

            $('#step2_delete_select').click((event) => {
                assets_selected.pop();
                step2ShowSelected();
                $('#step2_add_select').attr('disabled', 'true')
            });

            function step3ShowSelected() {
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
                $('#step3_show_selected').html(html);
            }

            function step2ShowSelected() {
                let html = "";
                assets_selected.forEach((data) => {
                    html += `- Số lượng điều chuyển: ${data.transfer_quantity} // ${data.asset_name} |-Số lượng hiện có: ${data.quantity} |-Kho: ${data.warehouse_name} |-Vendor: ${data.vendor_name} |-Mã QLTS: ${data.qlts_code} |-Mã VHKT: ${data.vhkt_code}\n`;
                });
                $('#list_assets_selected').val(html);
                $('#step_2_select').val(null).trigger('change');
            }

            function step3ShowManager(elelement) {
                $.ajax({
                    url: '/ajax/transfer-warehouse-to-manager/get-manager',
                    type: 'GET',
                    dataType: 'json'
                }).done(function(response) {
                    let html = `<tr>
                            <td>${response.id}</td>
                            <td>${response.username}</td>
                            <td>${(response.email == null) ? '' : response.email}</td>
                        </tr>`;
                    $(elelement).html(html);
                });


            }

            function step3Showarehouse(node, elelement) {
                let html = `<tr>
                            <td>${node.id}</td>
                            <td>${node.name}</td>
                            <td>${(node.code == null) ? '' : node.code}</td>
                            <td>${(node.shortname == null) ? '' : node.shortname}</td>
                        </tr>`;
                $(elelement).html(html);
            }
        });
    </script>
@endsection