@extends ('layouts.app')

@section('title','Thêm mới đâu việc')
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
            <h5 class="panel-title">ĐIỀU CHUYỂN GIỮA CÁC NODE NỘI BỘ</h5>
        </div>

        <form class="steps-validation" action="#">
            <h6>Chọn Node đích</h6>
            <fieldset>
                <div class="row mb-10">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 1:</b></h4>
                        <h6 class="grey-300 text-center">Chọn Node để chuyển tài sản vào</h6>
                        <div class="form-group">
                            <label class="col-lg-1 control-label">Node<span class="text-danger">*</span></label>
                            <div class="col-lg-11">
                                <select data-placeholder="Chọn node..." class="select required"
                                        id="step_1_select"></select>
                                <h6 class="grey-300 text-center mt-10">Danh sách hiển thị là các node đã liên kết với
                                    kho dành riêng cho mỗi node. nếu không tìm thấy hãy qua <code>Quản lý node</code> để
                                    liên kết kho</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <h6>Chọn Node đầu</h6>
            <fieldset>
                <div class="row mb-10">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 2:</b></h4>
                        <h6 class="grey-300 text-center">Chọn Node có tài sản muốn chuyển đi</h6>
                        <div class="form-group">
                            <label class="col-lg-1 control-label">Node<span class="text-danger">*</span></label>
                            <div class="col-lg-11">
                                <select data-placeholder="Chọn node..." class="select required" id="step_2_select">
                                    <option></option>
                                </select>
                                <h6 class="grey-300 text-center mt-10">Danh sách hiển thị là các node đã liên kết với
                                    kho dành riêng cho mỗi node. nếu không tìm thấy hãy qua <code>Quản lý node</code> để
                                    liên kết kho</h6>
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
                                <div class="form-group" id="quantity_input">
                                    <label>Số lượng điều chuyển<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control required" id="step3_input_quantity">
                                    <div id="step3_input_quantity_show_error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mt-20">
                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn btn-primary btn-xs" id="step3_add_select">
                                            Thêm tài sản <i class="icon-download position-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mt-20">
                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn bg-slate btn-xs" id="step3_delete_select">
                                            Xóa khỏi danh sách <i class="icon-upload position-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mt-20">
                                <div class="col-md-12">
                                    <label>Các tài sản sẽ chuyển sang node mới</label>
                                    <textarea rows="6" cols="5" class="form-control" readonly
                                              id="list_assets_selected"></textarea>
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
                            <label>Node đầu:</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên node</th>
                                        <th>Mã</th>
                                        <th>Tên viết tắt</th>
                                        <th>NIMS</th>
                                        <th>Phòng máy</th>
                                    </tr>
                                    </thead>
                                    <tbody id="step4_show_node_transfer">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <label>Node đích:</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên node</th>
                                        <th>Mã</th>
                                        <th>Tên viết tắt</th>
                                        <th>NIMS</th>
                                        <th>Phòng máy</th>
                                    </tr>
                                    </thead>
                                    <tbody id="step4_show_node_destination">

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
            var node_transfer = null;
            var node_destination = null;
            var i = 1;
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
                    if (currentIndex == 2 && assets_selected.length == 0) {
                        $('#step3_show_selected_error').html('<label class="validation-error-label">Bạn chưa chọn tài sản</label>');
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
                        url: "/ajax/node-to-node",
                        method: "POST",
                        dataType: 'json',
                        data: {
                            node_destination: node_destination,
                            assets: assets_selected
                        },
                        success: function (data) {
                            if (data == 'ok') {
                                $.jGrowl('Tài sản đã được điều chuyển', {
                                    header: 'Thành công!',
                                    theme: 'bg-success'
                                });
                                $(event.target.lastChild.lastChild.lastChild.lastChild).attr('href', 'javascript:void(0)');
                                setTimeout(function () {
                                    location.reload();
                                }, 2000);
                            }
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
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
                    url: '/ajax/nodes',
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
                                    text: `${item.name} | ${item.manager}`,
                                    id: item.id,
                                    data: item
                                };
                            })
                        };
                    }
                }
            });

            $('#step_1_select').on('select2:select', (e) => {
                if (e.params.data.data.warehouse_id == null) {
                    $.jGrowl('Hệ thống đang bị lỗi rất nghiêm trọng, vui lòng liên hệ System admin để biết chi tiết', {
                        header: 'Nguy hiểm!',
                        theme: 'bg-danger'
                    });
                } else {
                    node_destination = e.params.data.data;
                    step4ShowNode(node_destination, $('#step4_show_node_destination'))
                }
            });

            $('#step_2_select').select2({
                minimumInputLength: 1,
                ajax: {
                    url: '/ajax/nodes',
                    headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                    type: 'POST',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            step1_item_id: localStorage.getItem('step1'),
                            keyWord: params.term
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: $.map(data, function (item) {
                                if (item != null) return {
                                    text: `${item.name} | ${item.manager}`,
                                    id: item.id,
                                    data: item
                                };
                            })
                        };
                    }
                }
            });

            $('#step_2_select').on('select2:select', (e) => {
                if (e.params.data.data.warehouse_id == null) {
                    $.jGrowl('Hệ thống đang bị lỗi rất nghiêm trọng, vui lòng liên hệ System admin để biết chi tiết', {
                        header: 'Nguy hiểm!',
                        theme: 'bg-danger'
                    });
                } else {
                    node_transfer = e.params.data.data;
                    step4ShowNode(node_transfer, $('#step4_show_node_transfer'))
                }
            });

            $('#step_3_select').select2({
                minimumInputLength: 1,
                ajax: {
                    url: '/ajax/get-assets-by-node',
                    headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                    type: 'POST',
                    dataType: 'json',
                    data: function (params) {
                        if (assets_selected.length != 0) {
                            return {
                                selected: assets_selected,
                                node_id: node_transfer.id,
                                keyWord: params.term
                            };
                        } else {
                            return {
                                node_id: localStorage.getItem('step2'),
                                keyWord: params.term
                            }
                        }
                    },
                    processResults: function (data, params) {
                        return {
                            results: $.map(data, function (item) {
                                if (item != null) return {
                                    text: `Serial: ${item.serial} |-Serial2: ${item.serial2} |-Serial3: ${item.serial3} |Serial4: ${item.serial4} | Số lượng hiện có: ${item.quantity}`,
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
                $('#step3_show_selected_error').html('');
                tmp = e.params.data.data;
                if (parseInt(e.params.data.data.quantity) == 1) {
                    $('#quantity_input').hide();
                } else {
                    $('#quantity_input').show();
                }
            });

            $('#step3_input_quantity').keyup((event) => {
                if (!isNaN($(event.currentTarget).val())) {
                    if (parseInt($(event.currentTarget).val()) > tmp.quantity) {
                        $('#input_error_alert').html('<label class="validation-error-label">Số lượng không được vượt quá số lượng hiện tại</label>')
                    } else {
                        $('#input_error_alert').html('');
                    }
                } else {
                    $('#input_error_alert').html('<label class="validation-error-label">Vui lòng nhập số</label>')
                }
            });

            $('#step3_add_select').click((event) => {
                if (jQuery.isEmptyObject(tmp)) {
                    $('#step3_show_selected_error').html('<label class="validation-error-label">Bạn chưa chọn tài sản</label>');
                    return false;
                } else {
                    if (tmp.quantity > 1 && $('#step3_input_quantity').val() == '') {
                        $('#step3_input_quantity_show_error').html('<label class="validation-error-label">Bạn chưa nhập số lượng</label>');
                        return false;
                    } else $('#step3_input_quantity_show_error').html('')
                }
                $('#step3_show_selected_error').text('');
                $('#step3_input_quantity_show_error').text('');
                (tmp.quantity == 1) ? tmp.transfer_quantity = 1 : tmp.transfer_quantity = $('#step3_input_quantity').val();
                assets_selected.push(tmp);
                tmp = null;
                step3ShowSelected();
                step4ShowSelected();
            });

            $('#step3_delete_select').click((event) => {
                assets_selected.pop();
                step3ShowSelected();
                step4ShowSelected();
                $('#step3_add_select').attr('disabled', 'true')
            });

            function step4ShowSelected() {
                let html = null;
                let index = 1;
                assets_selected.forEach((data) => {
                    html += `<tr>
                            <td>${index}</td>
                            <td>${data.id}</td>
                            <td>${data.serial}</td>
                            <td>${data.quantity}</td>
                            <td>${data.transfer_quantity}</td>
                        </tr>`;
                    index++;
                });
                $('#step4_show_selected').html(html);
            }

            function step3ShowSelected() {
                let html = "";
                assets_selected.forEach((data) => {
                    html += `Số lượng điều chuyển: ${data.transfer_quantity} // Serial: ${data.serial} |-Serial2: ${data.serial2} |-Serial3 ${data.serial3} |-Serial4: ${data.serial4} |-Số lượng hiện có  ${data.quantity}|\n`;
                });
                $('#list_assets_selected').val(html);
                $('#step_3_select').val(null).trigger('change');
            }

            function step4ShowNode(node, elelement) {
                let html = `<tr>
                            <td>${node.id}</td>
                            <td>${node.name}</td>
                            <td>${(node.code == null) ? '' : node.code}</td>
                            <td>${(node.shortname == null) ? '' : node.shortname}</td>
                            <td>${(node.nims == null) ? '' : node.nims}</td>
                            <td>${(node.zone == null) ? '' : node.zone}</td>
                        </tr>`;

                $(elelement).html(html);
            }
        });
    </script>
@endsection