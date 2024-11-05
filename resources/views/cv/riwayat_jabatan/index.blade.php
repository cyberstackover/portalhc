@extends('layouts.app')
@section('addbeforecss')
<link href="{{asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="col-lg-2">
    @include('cv.tab')
</div>
<div class="col-lg-10">
  <div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon-web"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Data Riwayat Jabatan BOD/BOC di BUMN Group
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="javascript:;" class="btn btn-brand btn-primary btn-icon-sm cls-add first_table">
                        <i class="la la-plus"></i>
                        Tambah Data Jabatan BOD/BOC Group
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
      <div class="form-group row">
        <div class="col-lg-12">
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th >Jabatan</th>
                    <th >Tupoksi</th>  
                    <th >Awal Menjabat</th> 
                    <th >Akhir Menjabat</th>
                    <th> Kategori Keahlian</th>
                    <th >Achievement (Maksimal 5 Pencapaian)</th>             
                    <th width="10%"><div align="center">Aksi</div></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <!--end: Datatable -->
        </div>
      </div>
    </div>
  </div>

  <!-- nor formal Section -->
  <div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon-web"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Data Riwayat Jabatan Lain
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="javascript:;" class="btn btn-brand btn-primary btn-icon-sm cls-add second_table">
                        <i class="la la-plus"></i>
                        Tambah Data Jabatan Lain
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
      <div class="form-group row">
        <div class="col-lg-12">
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable2">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th >Jabatan</th>
                    <th >Tupoksi</th>  
                    <th >Awal Menjabat</th> 
                    <th >Akhir Menjabat</th>  
                    <th> Kategori Keahlian</th> 
                    <th width="10%"><div align="center">Aksi</div></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        </div>
      </div>
    </div>
  </div>    
  <!--End non formal Section -->
</div>
@endsection

@section('addafterjs')
<script type="text/javascript">
    var urlcreate = "{{route('cv.riwayat_jabatan.create', ['id_talenta' => $talenta->id])}}";
    var urledit = "{{route('cv.riwayat_jabatan.edit', ['id_talenta' => $talenta->id])}}";
    var urlstore = "{{route('cv.riwayat_jabatan.store', ['id_talenta' => $talenta->id])}}";
    var urldatatable = "{{route('cv.riwayat_jabatan.datatable', ['id_talenta' => $talenta->id])}}";
    var urldelete = "{{route('cv.riwayat_jabatan.delete', ['id_talenta' => $talenta->id])}}";

    var urlcreate2 = "{{route('cv.riwayat_jabatan_lain.create', ['id_talenta' => $talenta->id])}}";
    var urledit2 = "{{route('cv.riwayat_jabatan_lain.edit', ['id_talenta' => $talenta->id])}}";
    var urlstore2 = "{{route('cv.riwayat_jabatan_lain.store', ['id_talenta' => $talenta->id])}}";
    var urldelete2 = "{{route('cv.riwayat_jabatan_lain.delete', ['id_talenta' => $talenta->id])}}";
    var urldatatable2 = "{{route('cv.riwayat_jabatan_lain.datatable', ['id_talenta' => $talenta->id])}}";
</script>
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/cv/riwayat_jabatan/index.js')}}"></script>
@endsection