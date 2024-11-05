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
                Data Referensi
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="javascript:;" class="btn btn-brand btn-success btn-icon-sm cls-skip tidak-memiliki-cancel">
                        <i class="la la-check"></i>
                        Memiliki Referensi
                    </a>
                    <a href="javascript:;" class="btn btn-brand btn-danger btn-icon-sm cls-skip tidak-memiliki">
                        <i class="la la-check"></i>
                        Tidak Memiliki Referensi
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
                    <th >Nama</th>  
                    <th >Perusahaan</th> 
                    <th >Jabatan</th> 
                    <th >Nomor Handphone</th>             
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
  <!-- <div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon-web"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Data Karya Ilmiah
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="javascript:;" class="btn btn-brand btn-primary btn-icon-sm cls-add second_table">
                        <i class="la la-plus"></i>
                        Tambah Data Karya Ilmiah
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
                    <th >Judul dan Media Publikasi</th> 
                    <th >Tahun</th>             
                    <th width="10%"><div align="center">Aksi</div></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        </div>
      </div>
    </div>
  </div>   -->  
  <!--End Pelatihan Section -->
</div>
@endsection

@section('addafterjs')
<script type="text/javascript">
    var urlcreate = "{{route('cv.referensi_cv.create', ['id_talenta' => $talenta->id])}}";
    var urledit = "{{route('cv.referensi_cv.edit', ['id_talenta' => $talenta->id])}}";
    var urlstore = "{{route('cv.referensi_cv.store', ['id_talenta' => $talenta->id])}}";
    var urldatatable = "{{route('cv.referensi_cv.datatable', ['id_talenta' => $talenta->id])}}";
    var urldelete = "{{route('cv.referensi_cv.delete', ['id_talenta' => $talenta->id])}}";
    var urltidakmemiliki = "{{route('cv.referensi_cv.tidak_memiliki', ['id_talenta' => $talenta->id])}}";

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

    var jenis_instansi =  "{{@$talenta->id_jenis_asal_instansi}}";
    var eksternal =  "{{@$eksternal->id}}";
    if(jenis_instansi == eksternal){
        $(".tidak-memiliki").hide();
        $(".tidak-memiliki-cancel").hide();
        $(".cls-add.first_table").show();
        $(".cls-add-disabled").hide();
    }

</script>
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/cv/referensi_cv/index.js')}}"></script>
@endsection