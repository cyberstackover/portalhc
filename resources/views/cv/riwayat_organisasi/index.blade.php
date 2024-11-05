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
                Data Organisasi Terkait Pekerjaan/Profesi
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="javascript:;" class="btn btn-brand btn-success btn-icon-sm cls-skip tidak-memiliki-cancel">
                        <i class="la la-check"></i>
                        Memiliki Organisasi
                    </a>
                    <a href="javascript:;" class="btn btn-brand btn-danger btn-icon-sm cls-skip tidak-memiliki">
                        <i class="la la-check"></i>
                        Tidak Memiliki Organisasi
                    </a>
                    <a href="javascript:;" class="btn btn-brand btn-primary btn-icon-sm cls-add first_table">
                        <i class="la la-plus"></i>
                        Tambah Data
                    </a>
                    <a href="javascript:;" class="disabled btn btn-brand btn-default btn-icon-sm cls-add-disabled first_table">
                        <i class="la la-plus"></i>
                        Tambah Data
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
                    <th >Organisasi</th> 
                    <th >Rentang Waktu</th> 
                    <th >Uraian Singkat</th>             
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
                Data Organisasi Non Formal
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="javascript:;" class="btn btn-brand btn-success btn-icon-sm cls-skip tidak-memiliki2-cancel">
                        <i class="la la-check"></i>
                        Memiliki Organisasi
                    </a>
                    <a href="javascript:;" class="btn btn-brand btn-danger btn-icon-sm cls-skip tidak-memiliki2">
                        <i class="la la-check"></i>
                        Tidak Memiliki Organisasi
                    </a>
                    <a href="javascript:;" class="btn btn-brand btn-primary btn-icon-sm cls-add second_table cls-add2">
                        <i class="la la-plus"></i>
                        Tambah Data
                    </a>
                    <a href="javascript:;" class="disabled btn btn-brand btn-default btn-icon-sm cls-add2-disabled second_table">
                        <i class="la la-plus"></i>
                        Tambah Data
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
                    <th >Organisasi</th> 
                    <th >Rentang Waktu</th> 
                    <th >Uraian Singkat</th>             
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
    var urlcreate = "{{route('cv.riwayat_organisasi.create', ['id_talenta' => $talenta->id])}}";
    var urledit = "{{route('cv.riwayat_organisasi.edit', ['id_talenta' => $talenta->id])}}";
    var urlstore = "{{route('cv.riwayat_organisasi.store', ['id_talenta' => $talenta->id])}}";
    var urldatatable = "{{route('cv.riwayat_organisasi.datatable', ['id_talenta' => $talenta->id])}}";
    var urldelete = "{{route('cv.riwayat_organisasi.delete', ['id_talenta' => $talenta->id])}}";
    var urltidakmemiliki = "{{route('cv.riwayat_organisasi.tidak_memiliki', ['id_talenta' => $talenta->id])}}";

    var tidak_memiliki = "{{@$tidak_memiliki->id_talenta}}";
    if(tidak_memiliki){
        $(".tidak-memiliki").hide();
        $(".tidak-memiliki-cancel").show();
        $(".cls-add.first_table").hide();
        $(".cls-add-disabled").show();
    }else{
        $(".tidak-memiliki").show();
        $(".tidak-memiliki-cancel").hide();
        $(".cls-add.first_table").show();
        $(".cls-add-disabled").hide();
    }

    var urlcreate2 = "{{route('cv.riwayat_organisasi.create_nonformal', ['id_talenta' => $talenta->id])}}";
    var urledit2 = "{{route('cv.riwayat_organisasi.edit_nonformal', ['id_talenta' => $talenta->id])}}";
    var urlstore2 = "{{route('cv.riwayat_organisasi.store', ['id_talenta' => $talenta->id])}}";
    var urldelete2 = "{{route('cv.riwayat_organisasi.delete', ['id_talenta' => $talenta->id])}}";
    var urldatatable2 = "{{route('cv.riwayat_organisasi.datatable_nonformal', ['id_talenta' => $talenta->id])}}";
    var urltidakmemiliki2 = "{{route('cv.riwayat_organisasi.tidak_memiliki_nonformal', ['id_talenta' => $talenta->id])}}";

    var tidak_memiliki2 = "{{@$tidak_memiliki2->id_talenta}}";
    if(tidak_memiliki2){
        $(".tidak-memiliki2").hide();
        $(".tidak-memiliki2-cancel").show();
        $(".cls-add2").hide();
        $(".cls-add2-disabled").show();
    }else{
        $(".tidak-memiliki2").show();
        $(".tidak-memiliki2-cancel").hide();
        $(".cls-add2").show();
        $(".cls-add2-disabled").hide();
    }
</script>
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/cv/riwayat_organisasi/index.js')}}"></script>
@endsection