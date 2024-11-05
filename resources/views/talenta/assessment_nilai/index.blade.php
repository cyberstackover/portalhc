@extends('layouts.app')
@section('addbeforecss')
<link href="{{asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="col-lg-12">
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
                    <a href="javascript:;" class="btn btn-brand btn-primary btn-icon-sm cls-add">
                        <i class="la la-plus"></i>
                        Tambah Data Hasil Assessment
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
      <div class="form-group row">
        <div class="col-lg-12">
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable table-responsive" id="datatable">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th >Lembaga Assessment</th>  
                    <th >Tanggal</th>  
                    <th >Expired</th> 
                    <th width="15%" align="center">File Uploads</th>             
                    <th >Hasil</th>  
                    <th width="20%"><div align="center">Aksi</div></th>
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
    var urlcreate = "{{route('talenta.assessment_nilai.create', ['id_talenta' => $talenta->id])}}";
    var urlupload_spa = "{{route('talenta.assessment_nilai.upload_spa', ['id_talenta' => $talenta->id])}}";
    var urlupload_short = "{{route('talenta.assessment_nilai.upload_short', ['id_talenta' => $talenta->id])}}";
    var urlupload_full = "{{route('talenta.assessment_nilai.upload_full', ['id_talenta' => $talenta->id])}}";
    var urledit = "{{route('talenta.assessment_nilai.edit', ['id_talenta' => $talenta->id])}}";
    var urlstore = "{{route('talenta.assessment_nilai.store', ['id_talenta' => $talenta->id])}}";
    var urlstoreupload = "{{route('talenta.assessment_nilai.store_upload', ['id_talenta' => $talenta->id])}}";
    var urldatatable = "{{route('talenta.assessment_nilai.datatable', ['id_talenta' => $talenta->id])}}";
    var urldelete = "{{route('talenta.assessment_nilai.delete', ['id_talenta' => $talenta->id])}}";
</script>
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/talenta/assessment_nilai/index.js')}}"></script>
@endsection