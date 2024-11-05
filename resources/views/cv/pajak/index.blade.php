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
                Data SPT Tahunan
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="javascript:;" class="btn btn-brand btn-primary btn-icon-sm cls-add first_table">
                        <i class="la la-plus"></i>
                        Tambah Data SPT Tahunan
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
                    <th >File SPT Tahunan</th>  
                    <th >Tahun</th>
                    <th >User</th>             
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
    var urlcreate = "{{route('cv.pajak.create', ['id_talenta' => $talenta->id])}}";
    var urledit = "{{route('cv.pajak.edit', ['id_talenta' => $talenta->id])}}";
    var urlstore = "{{route('cv.pajak.store', ['id_talenta' => $talenta->id])}}";
    var urldatatable = "{{route('cv.pajak.datatable', ['id_talenta' => $talenta->id])}}";
    var urldelete = "{{route('cv.pajak.delete', ['id_talenta' => $talenta->id])}}";
</script>
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/cv/pajak/index.js')}}"></script>
@endsection