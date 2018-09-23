@extends ('layouts.app')

@section('title','Thêm mới đâu việc')
@section('vendor_js')
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
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
            <h6 class="panel-title">Basic example</h6>
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
                                <select data-placeholder="Chọn node..." class="select required" id="step_1_select"></select>
                                <h6 class="grey-300 text-center mt-10">Danh sách hiển thị là các node đã liên kết với
                                    kho dành riêng cho mỗi node. nếu không tìm thấy hãy qua <code>Quản lý node</code> để
                                    liên kết kho</h6>
                            </div>
                        </div>
                    </div>
                </div>

            </fieldset>

            <h6>Chọn Node đầu:</h6>
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
                        <h6 class="grey-300 text-center">Chọn tài sản muốn chuyển</h6>
                        <div class="form-group">
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
        $(document).ready(function () {
            var status = false;
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

                    if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                        return false;
                    }

                    if (currentIndex < newIndex) {

                        form.find(".body:eq(" + newIndex + ") label.error").remove();
                        form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                    }
                    if(!status){
                        return false
                    }
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                },

                onStepChanged: function (event, currentIndex, priorIndex) {

                    // Used to skip the "Warning" step if the user is old enough.
                    if (currentIndex === 2 && Number($("#age-2").val()) >= 18) {
                        form.steps("next");
                    }

                    // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                    if (currentIndex === 2 && priorIndex === 3) {
                        form.steps("previous");
                    }
                },

                onFinishing: function (event, currentIndex) {
                    form.validate().settings.ignore = ":disabled";
                    return form.valid();
                },

                onFinished: function (event, currentIndex) {
                    alert("Submitted!");
                }
            });

            $(".steps-validation").validate({
                ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
                errorClass: 'validation-error-label',
                successClass: 'validation-valid-label',
                highlight: function (element, errorClass) {
                    $(element).removeClass(errorClass);
                },
                unhighlight: function (element, errorClass) {
                    $(element).removeClass(errorClass);
                },

                // Different components require proper error label placement
                errorPlacement: function (error, element) {

                    // Styled checkboxes, radios, bootstrap switch
                    if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container')) {
                        if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                            error.appendTo(element.parent().parent().parent().parent());
                        }
                        else {
                            error.appendTo(element.parent().parent().parent().parent().parent());
                        }
                    }

                    // Unstyled checkboxes, radios
                    else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
                        error.appendTo(element.parent().parent().parent());
                    }

                    // Input with icons and Select2
                    else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
                        error.appendTo(element.parent());
                    }

                    // Inline checkboxes, radios
                    else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                        error.appendTo(element.parent().parent());
                    }

                    // Input group, styled file input
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
                        console.log(data);
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

            $('#step_2_select').select2({
                minimumInputLength: 1,
                ajax: {
                    url: '/ajax/nodes',
                    headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                    type: 'POST',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            step1_item_id:localStorage.getItem('step1'),
                            keyWord: params.term
                        };
                    },
                    processResults: function (data, params) {
                        console.log(data);
                        return {
                            results: $.map(data, function (item) {
                                if(item != null) return {
                                    text: `${item.name} | ${item.manager}`,
                                    id: item.id,
                                    data: item
                                };
                            })
                        };
                    }
                }
            });


            $('#step_1_select').on('select2:select', function (e) {
                if (e.params.data.data.warehouse_id == null) {
                    $.jGrowl('Hệ thống đang bị lỗi rất nghiêm trọng, vui lòng liên hệ System admin để biết chi tiết', {
                        header: 'Nguy hiểm!',
                        theme: 'bg-danger'
                    });
                    status = false;
                } else {
                    localStorage.setItem('step1',e.params.data.data.id);
                    status = true;
                }
            });

            $('#step_2_select').on('select2:select', function (e) {
                if (e.params.data.data.warehouse_id == null) {
                    $.jGrowl('Hệ thống đang bị lỗi rất nghiêm trọng, vui lòng liên hệ System admin để biết chi tiết', {
                        header: 'Nguy hiểm!',
                        theme: 'bg-danger'
                    });
                    status = false;
                } else {
                    status = true;
                }
            });
        });
    </script>
@endsection