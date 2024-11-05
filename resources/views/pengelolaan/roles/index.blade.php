@extends('layouts.app')


@section('addbeforecss')
<link href="{{asset('assets/vendors/custom/multi-select-master/css/multi-select.dist.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/vendors/custom/jstree/jstree.bundle.css')}}" rel="stylesheet" type="text/css" />
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
                        Tambah Role
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
                    <th>No.</th>
                    <th>Role</th>
                    <th>Guard Name</th>
                    <th><div align="center">Aksi</div></th>
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
      var urlcreate = "{{route('pengelolaan.roles.create')}}";
      var urledit = "{{route('pengelolaan.roles.edit')}}";
      var urlstore = "{{route('pengelolaan.roles.store')}}";
      var urldatatable = "{{route('pengelolaan.roles.datatable')}}";
      var urldelete = "{{route('pengelolaan.roles.delete')}}";
      var urlgettreemenubyrole = "{{route('pengelolaan.roles.gettreemenubyrole')}}";
  </script>
  <script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendors/custom/jstree/jstree.bundle.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendors/custom/jstree-grid-master/jstreegrid.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendors/custom/multi-select-master/js/jquery.multi-select.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendors/custom/quicksearch-master/jquery.quicksearch.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('js/pengelolaan/roles/index.js')}}"></script>
@endsection