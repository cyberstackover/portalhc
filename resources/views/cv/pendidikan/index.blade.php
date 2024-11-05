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
                Data Pendidikan
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="javascript:;" class="btn btn-brand btn-primary btn-icon-sm cls-add pendidikan">
                        <i class="la la-plus"></i>
                        Tambah Data Pendidikan
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
                    <th >Jenjang - Penjurusan</th>  
                    <th >Perguruan Tinggi</th> 
                    <th >Tahun Lulus</th>    
                    <th >Kota / Negara</th>   
                    <th >Penghargaan yang Didapat</th>             
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
                Data Pelatihan
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="javascript:;" class="btn btn-brand btn-primary btn-icon-sm cls-add pelatihan">
                        <i class="la la-plus"></i>
                        Tambah Data Pelatihan
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
      <div class="form-group row">
        <div class="col-lg-12">
        <!--begin: Datatable -->
        <div class="table-responsive">
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable2">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th >Latihan dan Pengembangan Kompetensi</th>  
                    <th >Penyelanggara/Kota</th>
                    <th >Jenis Diklat</th>
                    <th >Tingkat</th>  
                    <th >Lama Diklat</th>
                    <th >Tahun</th>  
                    <th >Nomor Sertifikasi</th>            
                    <th width="10%"><div align="center">Aksi</div></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        </div>
        <!--end: Datatable -->
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('addafterjs')
<script type="text/javascript">
    var urlcreate = "{{route('cv.pendidikan.create', ['id_talenta' => $talenta->id])}}";
    var urledit = "{{route('cv.pendidikan.edit', ['id_talenta' => $talenta->id])}}";
    var urlstore = "{{route('cv.pendidikan.store', ['id_talenta' => $talenta->id])}}";
    var urldatatable = "{{route('cv.pendidikan.datatable', ['id_talenta' => $talenta->id])}}";
    var urldelete = "{{route('cv.pendidikan.delete', ['id_talenta' => $talenta->id])}}";

    var urlcreate2 = "{{route('cv.pelatihan.create', ['id_talenta' => $talenta->id])}}";
    var urledit2 = "{{route('cv.pelatihan.edit', ['id_talenta' => $talenta->id])}}";
    var urlstore2 = "{{route('cv.pelatihan.store', ['id_talenta' => $talenta->id])}}";
    var urldelete2 = "{{route('cv.pelatihan.delete', ['id_talenta' => $talenta->id])}}";
    var urldatatable2 = "{{route('cv.pelatihan.datatable', ['id_talenta' => $talenta->id])}}";
</script>
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/cv/pendidikan/index.js')}}"></script>
@endsection