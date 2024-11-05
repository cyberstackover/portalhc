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
                {{$pagetitle}}
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="javascript:;" class="btn btn-brand btn-elevate btn-icon-sm cls-add">
                        <i class="la la-plus"></i>
                        Tambah kelas BUMN
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">

        <!--begin: Datatable -->
        <div class="table-responsive">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                <thead>
                    <tr>
                        <th rowspan="2"><div align="center">No.</div></th>
                        <th rowspan="2"><div align="center">Kelas BUMN</div></th>
                        <th colspan="2"><div align="center">Direksi</div></th>
                        <th colspan="2"><div align="center">Dekomwas</div></th>
                        <th rowspan="2"><div align="center">Perusahaan</div></th>
                        <th rowspan="2"><div align="center">Aksi</div></th>
                    </tr>
                    <tr>
                        <th>Standar MIN</th>
                        <th>Standar MAX</th>
                        <th>Standar MIN</th>
                        <th>Standar MAX</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        

        <!--end: Datatable -->
    </div>
</div>

@endsection


@section('addafterjs')

<script type="text/javascript">
    var urlcreate = "{{route('organ.kelasbumn.create')}}";
    var urledit = "{{route('organ.kelasbumn.edit')}}";
    var urlstore = "{{route('organ.kelasbumn.store')}}";
    var urldatatable = "{{route('organ.kelasbumn.datatable')}}";
    var urldelete = "{{route('organ.kelasbumn.delete')}}";
    var urldetailbumn = "{{route('organ.kelasbumn.detailbumn')}}";
</script>
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/organ/kelasbumn/index.js')}}"></script>

@endsection