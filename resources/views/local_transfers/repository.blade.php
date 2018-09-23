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
                                <a href="" class="disabled btn">AA</a>
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
        let status = false;
        let form = $('#form_repository_transfer');
        form.on('select2:select', (e)=>{
            if(e.params.data.data.warehouse_id !== null) status = true;
            else{
                $.jGrowl('Hệ thống đang bị lỗi rất nghiêm trọng: Node không có kho chứa tài sản. Đề nghị liên hệ System Admin', {
                    header: 'Lỗi nghiêm trọng',
                    theme: 'bg-danger'
                });
            }
        });
        form.steps({
            headerTag: "h6",
            bodyTag: "fieldset",
            transitionEffect: "fade",
            titleTemplate: '<span class="number">#index#</span> #title#',
            autoFocus: true,
            onStepChanging: function (event, currentIndex, newIndex) {
                console.log(currentIndex);
                let node1 = $('#step_1_select');
                let node2 = $('#step_2_select');
                switch (currentIndex) {
                    case 0:{
                        console.log(node1.val());
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
                        break;
                    }
                }

            },
            onStepChanged: function (event, currentIndex, priorIndex) {
            },
            onFinishing: function (event, currentIndex) {
            },
            onFinished: function (event, currentIndex) {
            }
        });
       $('#step_1_select').select2({
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('local_transfers.nodes') }}',
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
                                text: `${item.name} - ${item.nims} - ${item.manager}`,
                                id: item.id,
                                data: item
                            };
                        })
                    };
                }
            }
        });
    </script>
@endsection