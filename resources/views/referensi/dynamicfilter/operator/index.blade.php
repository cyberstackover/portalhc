@extends('layouts.app')


@section('addbeforecss')
    <link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-web"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    {{ $pagetitle }}
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="javascript:;" class="btn btn-brand btn-elevate btn-icon-sm cls-add">
                            <i class="la la-plus"></i>
                            Tambah Operator
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th>Nama</th>
                        <th>Operator</th>
                        <th class="text-center">Status</th>
                        <th>
                            <div align="center">Aksi</div>
                        </th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
@endsection

@section('addafterjs')
    <script type="text/javascript">
        var urlcreate = "{{ route('referensi.dynamicfilter.operator.create') }}";
        var urledit = "{{ route('referensi.dynamicfilter.operator.edit') }}";
        var urlstore = "{{ route('referensi.dynamicfilter.operator.store') }}";
        var urldatatable = "{{ route('referensi.dynamicfilter.operator.datatable') }}";
        var urldelete = "{{ route('referensi.dynamicfilter.operator.delete') }}";
        var urlaktif = "{{ route('referensi.dynamicfilter.operator.aktif') }}";
        var urlnumber = "{{ route('referensi.dynamicfilter.operator.is_number') }}";
        var urlsorting = "{{ route('referensi.dynamicfilter.operator.is_sorting') }}";
    </script>
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('js/referensi/dynamicfilter/operator/index.js') }}"></script>
@endsection
