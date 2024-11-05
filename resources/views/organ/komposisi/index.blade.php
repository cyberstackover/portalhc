@extends('layouts.app')

@section('addbeforecss')
<link href="{{asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@endsection


<!-- Konten -->
@section('content')


<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon-web"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Komposisi Dirkomwas Perusahaan
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">

        <!--begin: Datatable -->
        <div class="table-responsive">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Perusahaan</th>
                        <th>Kelas</th>
                        <th>Direksi</th>
                        <th>Dekomwas</th>
                        <th><div align="center">Aksi</div></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        

        <!--end: Datatable -->
</div>

@endsection


@section('addafterjs')

<script type="text/javascript">
    var urlcreategrupjabatan = "{{route('organ.komposisi.creategrupjabatan')}}";
    var urlstoregrupjabatan = "{{route('organ.komposisi.storegrupjabatan')}}";
    var urldatatable = "{{route('organ.komposisi.datatable')}}";
</script>

<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/organ/komposisi/index.js')}}"></script>
@endsection