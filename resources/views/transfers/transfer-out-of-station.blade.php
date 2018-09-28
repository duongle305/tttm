@extends ('layouts.app')

@section('title','Điều chuyển tài sản gữa các Node nội bộ')
@section('vendor_js')
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
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
            <h5 class="panel-title">ĐIỀU CHUYỂN TÀI SẢN RA KHỎI TRẠM</h5>
        </div>

        <form class="steps-validation" action="#">
            <h6>Chọn tài sản</h6>
            <fieldset>
                <div class="row mb-10">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 1:</b></h4>
                        <h6 class="grey-300 text-center">Chọn tài sản muốn điều chuyển</h6>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Chọn tài sản<span class="text-danger">*</span></label>
                                    <select data-placeholder="Chọn tài sản..." class="select" id="step_1_select">
                                        <option></option>
                                    </select>
                                    <div id="step1_show_selected_error"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" id="quantity_input" style="display: none;">
                                    <label>Số lượng điều chuyển<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="step1_input_quantity">
                                    <div id="step1_input_quantity_error_show"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mt-20">
                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn btn-primary btn-xs" id="step1_add_select">
                                            Thêm tài sản <i class="icon-download position-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mt-20">
                                <div class="col-md-12">
                                    <label>Các tài sản sẽ chuyển</label>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            </thead>
                                            <tbody id="step1_show_selected">

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
                        <h4 class="text-center"><b>Bước 2:</b></h4>
                        <h6 class="grey-300 text-center">Xem lại và kết thúc</h6>
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
                                    <tbody id="step2_show_selected">

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

                    if(currentIndex == 0 && assets_selected.length == 0){
                        $('#step1_show_selected_error').html('<label class="validation-error-label">Bạn chưa chọn tài sản</label>');
                        return false;
                    }
                    if(currentIndex == 0 && !step1Status){
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
                        url: "/transfer-out-of-station/ajax/submit",
                        method: "POST",
                        dataType: 'json',
                        data: {
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
                    url: '/transfer-out-of-station/ajax/get-asset',
                    headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                    type: 'POST',
                    dataType: 'json',
                    data: function (params) {
                        if(assets_selected.length >0){
                            return {
                                selected: assets_selected,
                                keyWord: params.term,
                            }
                        } else {
                            return {
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

            $('#step_1_select').on('select2:select', (e) => {
                $('#step1_add_select').removeAttr('disabled');
                tmp = e.params.data.data;
                $('#step1_show_selected_error').html('');
                step1Status = true;
                if (parseInt(e.params.data.data.quantity) == 1) {
                    $('#quantity_input').hide();
                } else {
                    $('#quantity_input').show();
                }
            });

            $('#step1_input_quantity').keyup((event) => {
                if (isNaN($(event.currentTarget).val())) {
                    $('#step1_input_quantity_error_show').html('<label class="validation-error-label">Vui lòng nhập số</label>');
                    step3Status = false;
                } else if($(event.currentTarget).val() == ''){
                    $('#step1_input_quantity_error_show').html('<label class="validation-error-label">Số lượng không được để trống</label>')
                    step3Status = false;
                } else if (parseInt($(event.currentTarget).val()) > tmp.quantity) {
                    $('#step1_input_quantity_error_show').html('<label class="validation-error-label">Số lượng không được vượt quá số lượng hiện tại</label>')
                    step3Status = false;
                } else {
                    $('#step1_input_quantity_error_show').html('');
                    step3Status = true;
                }
            });

            $('#step1_add_select').click((event) => {
                if (jQuery.isEmptyObject(tmp)) {
                    $('#step1_show_selected_error').html('<label class="validation-error-label">Bạn chưa chọn tài sản</label>');
                    return false;
                } else if (tmp.quantity > 1 && $('#step1_input_quantity').val() == '') {
                    $('#step1_input_quantity_error_show').html('<label class="validation-error-label">Bạn chưa nhập số lượng</label>');
                    return false;
                }
                $('#step1_show_selected_error').html('');
                $('#step1_input_quantity_error_show').html('');
                $('#quantity_input').hide();
                (tmp.quantity == 1) ? tmp.transfer_quantity = 1 : tmp.transfer_quantity = $('#step1_input_quantity').val();
                $('#step1_input_quantity').val('');
                assets_selected.push(tmp);
                tmp = null;
                step1ShowSelected();
                step2ShowSelected();
            });

            $(document).on('click','.step1_delete_select',(event) => {
                assets_selected.splice($(event.target).data('index'),1);
                step1ShowSelected();
                $('#step3_add_select').attr('disabled', 'true')
            });

            function step2ShowSelected() {
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
                $('#step2_show_selected').html(html);
            }

            function step1ShowSelected() {
                let html = "";
                assets_selected.forEach((data,index) => {
                    html += `<tr>
                                <td>- Số lượng điều chuyển: ${data.transfer_quantity} // ${data.asset_name} |-Số lượng hiện có: ${data.quantity} |-Kho: ${data.warehouse_name} |-Vendor: ${data.vendor_name} |-Mã QLTS: ${data.qlts_code} |-Mã VHKT: ${data.vhkt_code}</td>
                                <td><button type="button" class="btn btn-danger btn-xs step1_delete_select" data-index="${index}">Xóa</button></td>
                            </tr>`;
                });
                $('#step1_show_selected').html(html);
                $('#step_1_select').val(null).trigger('change');
            }
        });
    </script>
@endsection