@extends('layouts.app')
@section('title','Điều chuyển giữa nhân viên quản lý')

@section('vendor_js')
    <script type="text/javascript" src="assets/js/plugins/forms/wizards/steps.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endsection


@section('content')
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">Điều chuyển tài sản</h6>
        </div>

        <form class="steps-transfers" action="#">
            <h6>Chọn nhân viên</h6>
            <fieldset>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="next-manager">Chọn nhân viên <span class="text-danger">*</span></label>
                            <select name="next-manager" id="next-manager"></select>
                        </div>
                    </div>
                </div>
            </fieldset>
            <h6>Additional info</h6>
            <fieldset data-mode="async" data-url="assets/demo_data/wizard/additional.html"></fieldset>
        </form>
    </div>
@endsection


@section('custom_js')
    <script>
        $('.steps-transfers').steps({
            headerTag: "h6",
            bodyTag: "fieldset",
            transitionEffect: "fade",
            titleTemplate: '<span class="number">#index#</span> #title#',
            labels: {
                finish: 'Submit'
            },
            onStepChanging: (e, currentIndex, nextIndex)=>{
                switch (currentIndex) {
                    case 0:{

                    }
                }
            },
        });
        let nextManager = $('#next-manager');
        let asset = $('#asset');
        nextManager.select2({
            ajax: {
                url: '{{ route('local-manager-transfers.managers') }}',
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
                        results: $.map(data, function (user) {
                            return {
                                text: `${user.name} / ${user.username} / ${user.email}`,
                                id: user.id,
                                data: user
                            };
                        })
                    };
                }
            }
        }).on('select2:select',(e)=>{
            asset.parent().parent().parent().hide();
            if(e.params.data.id != null){
                $.ajax({
                    url: '{{ route('local-manager-transfers.has-warehouse') }}',
                    method:'POST',
                    headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                    dataType: 'json',
                    data:{ id: e.params.data.id }
                }).done((res)=>{
                    if(res.status)
                        asset.parent().parent().parent().show();
                });
            }
        });
        asset.select2({
            ajax: {
                url: '{{ route('local-manager-transfers.assets') }}',
                headers: {'X-CSRF-Token': $('input[name="_token"]').attr('value')},
                type: 'POST',
                dataType: 'json',
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

        });
    </script>
@endsection