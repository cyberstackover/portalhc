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
                            Tambah Tabel Sumber
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
                        <th>Tabel</th>
                        <th>Field</th>
                        <th>Alias</th>
                        <th>Query</th>
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
        var urlcreate = "{{ route('referensi.dynamicfilter.tabelsumber.create') }}";
        var urledit = "{{ route('referensi.dynamicfilter.tabelsumber.edit') }}";
        var urlstore = "{{ route('referensi.dynamicfilter.tabelsumber.store') }}";
        var urldatatable = "{{ route('referensi.dynamicfilter.tabelsumber.datatable') }}";
        var urldelete = "{{ route('referensi.dynamicfilter.tabelsumber.delete') }}";
    </script>
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('js/referensi/dynamicfilter/tabelsumber/index.js') }}"></script>
@endsection
