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
                            Tambah Parameter
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
                        <th>Nama Filter</th>
                        <th>tipe</th>
                        <th>Tabel Sumber</th>
                        <th>Standar Value</th>
                        <th class="text-center" width="30%">Status</th>
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
        var urlcreate = "{{ route('talenta.filter_dynamic.create') }}";
        var urledit = "{{ route('talenta.filter_dynamic.edit') }}";
        var urlstore = "{{ route('talenta.filter_dynamic.store') }}";
        var urldatatable = "{{ route('talenta.filter_dynamic.datatable') }}";
        var urldelete = "{{ route('talenta.filter_dynamic.delete') }}";
        var urlaktif = "{{ route('talenta.filter_dynamic.aktif') }}";
        var urlnumber = "{{ route('talenta.filter_dynamic.is_number') }}";
    </script>
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('js/talenta/filter_dynamic/index.js') }}"></script>
@endsection
