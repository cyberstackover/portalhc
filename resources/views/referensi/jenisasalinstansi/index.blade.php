@extends('layouts.app')


@section('addbeforecss')
<link href="{{asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon-web"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                {{$pagetitle}}
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="javascript:;" class="btn btn-brand btn-elevate btn-icon-sm cls-add">
                        <i class="la la-plus"></i>
                        Tambah Jenis Asal Instansi
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
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Direksi Induk</th>
                    <th>Dekom Induk</th>
                    <th>Direksi Anak/Cucu</th>
                    <th>Dekom Anak/Cucu</th>
                    <th>Keterangan</th>
                    <th>Table Name</th>
                    <th><div align="center">Aksi</div></th>
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
      var urlcreate = "{{route('referensi.jenisasalinstansi.create')}}";
      var urledit = "{{route('referensi.jenisasalinstansi.edit')}}";
      var urlstore = "{{route('referensi.jenisasalinstansi.store')}}";
      var urldatatable = "{{route('referensi.jenisasalinstansi.datatable')}}";
      var urldelete = "{{route('referensi.jenisasalinstansi.delete')}}";
  </script>
  <script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('js/referensi/jenisasalinstansi/index.js')}}"></script>
@endsection