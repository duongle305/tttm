@extends ('layouts.app')

@section('title','Thêm mới đâu việc')
@section('vendor_js')
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/wizards/steps.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/core/libraries/jasny_bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/validation/validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/extensions/cookie.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/js/pages/wizard_steps.js') }}"></script>
@endsection
@section('content')
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">Basic example</h6>
        </div>

        <form class="steps-basic" action="#">
            <h6>Chọn Node đích</h6>
            <fieldset>
                <div class="row mb-10">
                    <div class="col-md-12">
                        <h4 class="text-center"><b>Bước 1:</b></h4>
                        <h6 class="grey-300 text-center">Chọn Node để chuyển tài sản vào</h6>
                        <div class="form-group">
                            <label class="col-lg-1 control-label">Node<span class="text-danger">*</span></label>
                            <div class="col-lg-11">
                                <select data-placeholder="Chọn node..." class="select" id="step_1_select"></select>
                                <h6 class="grey-300 text-center mt-10">Danh sách hiển thị là các node đã liên kết với kho dành riêng cho mỗi node. nếu không tìm thấy hãy qua <code>Quản lý node</code> để liên kết kho</h6>
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
                                <select data-placeholder="Chọn node..." class="select" id="step_2_select">
                                    <option></option>
                                </select>
                                <h6 class="grey-300 text-center mt-10">Danh sách hiển thị là các node đã liên kết với kho dành riêng cho mỗi node. nếu không tìm thấy hãy qua <code>Quản lý node</code> để liên kết kho</h6>
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
                            results: data
                        };
                    },
                    templateResult: mapOption()
                }
            });
        });

        var mapOption = (datas) => {
            let a="";
            datas.forEach(function (data) {
                a +=`<option value="${data.id}">${data.name} | ${data.manager}</option>`;
            });
            return a;
        }
    </script>
@endsection