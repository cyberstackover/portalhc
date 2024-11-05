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
                Data Keluarga
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="javascript:;" class="btn btn-brand btn-primary btn-icon-sm cls-add first_table">
                        <i class="la la-plus"></i>
                        Tambah Data Keluarga
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
                    <th >Nama</th>  
                    <th >Tempat Lahir</th> 
                    <th >Tanggal Lahir</th> 
                    <th >Tanggal Menikah</th>   
                    <th >Pekerjaan</th>   
                    <th >Keterangan</th>             
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

  <!-- Pelatihan Section -->
  <div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon-web"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Data Anak
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="javascript:;" class="btn btn-brand btn-success btn-icon-sm cls-skip tidak-memiliki-cancel">
                        <i class="la la-check"></i>
                        Memiliki Anak
                    </a>
                    <a href="javascript:;" class="btn btn-brand btn-danger btn-icon-sm cls-skip tidak-memiliki">
                        <i class="la la-check"></i>
                        Tidak Memiliki Anak
                    </a>
                    <a href="javascript:;" class="btn btn-brand btn-primary btn-icon-sm cls-add second_table">
                        <i class="la la-plus"></i>
                        Tambah Data
                    </a>
                    <a href="javascript:;" class="disabled btn btn-brand btn-default btn-icon-sm cls-add-disabled second_table">
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
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable2">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th >Nama</th>  
                    <th >Tempat Lahir</th> 
                    <th >Tanggal Lahir</th> 
                    <th >Jenis Kelamin</th>   
                    <th >Pekerjaan</th>   
                    <th >Keterangan</th>             
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
</div>
@endsection

@section('addafterjs')
<script type="text/javascript">
    var urlcreate = "{{route('cv.keluarga.create', ['id_talenta' => $talenta->id])}}";
    var urledit = "{{route('cv.keluarga.edit', ['id_talenta' => $talenta->id])}}";
    var urlstore = "{{route('cv.keluarga.store', ['id_talenta' => $talenta->id])}}";
    var urldatatable = "{{route('cv.keluarga.datatable', ['id_talenta' => $talenta->id])}}";
    var urldelete = "{{route('cv.keluarga.delete', ['id_talenta' => $talenta->id])}}";

    var urlcreate2 = "{{route('cv.keluarga.create_anak', ['id_talenta' => $talenta->id])}}";
    var urledit2 = "{{route('cv.keluarga.edit_anak', ['id_talenta' => $talenta->id])}}";
    var urlstore2 = "{{route('cv.keluarga.store_anak', ['id_talenta' => $talenta->id])}}";
    var urldelete2 = "{{route('cv.keluarga.delete_anak', ['id_talenta' => $talenta->id])}}";
    var urldatatable2 = "{{route('cv.keluarga.datatable_anak', ['id_talenta' => $talenta->id])}}";
    var urltidakmemiliki = "{{route('cv.keluarga.tidak_memiliki', ['id_talenta' => $talenta->id])}}";

    var tidak_memiliki = "{{@$tidak_memiliki->id_talenta}}";
    if(tidak_memiliki){
        $(".tidak-memiliki").hide();
        $(".tidak-memiliki-cancel").show();
        $(".cls-add.second_table").hide();
        $(".cls-add-disabled").show();
    }else{
        $(".tidak-memiliki").show();
        $(".tidak-memiliki-cancel").hide();
        $(".cls-add.second_table").show();
        $(".cls-add-disabled").hide();
    }

    var status_kawin = "{{@$talenta->id_status_kawin}}";
    if(status_kawin != '1'){
        $(".cls-add.first_table").hide();
    }
</script>
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/cv/keluarga/index.js')}}"></script>
@endsection