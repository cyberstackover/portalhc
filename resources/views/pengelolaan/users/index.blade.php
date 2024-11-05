@extends('layouts.app')


@section('addbeforecss')
<link href="{{asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
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
                  @can('user-create')
                    <a href="javascript:;" class="btn btn-brand btn-elevate btn-icon-sm cls-add">
                        <i class="la la-plus"></i>
                        Tambah User
                    </a>
                    @endcan
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
                    <th style="width: 5%;">No.</th>
                    <th style="width: 15%;">Nama</th>
                    <th style="width: 15%;">Email</th>
                    <th style="width: 20%;">Username</th>
                    <th style="width: 20%;">Perusahaan</th>
                    <th style="width: 5%;">Roles</th>
                    <th style="width: 10%;"><div align="center">Aksi</div></th>
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
      var urlcreate = "{{route('pengelolaan.users.create')}}";
      var urledit = "{{route('pengelolaan.users.edit')}}";
      var urlstore = "{{route('pengelolaan.users.store')}}";
      var urldatatable = "{{route('pengelolaan.users.datatable')}}";
      var urldelete = "{{route('pengelolaan.users.delete')}}";
      var urlcheckuser = "{{route('pengelolaan.users.checkuser')}}";
      var urlfetchkategoriuser = "{{route('pengelolaan.general.fetchkategoriuser')}}";
      var urlfetchbumnactive = "{{route('pengelolaan.general.fetchbumnactive')}}";
      var urlfetchassessment = "{{route('pengelolaan.general.fetchassessment')}}";
  </script>
  <script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('js/pengelolaan/users/index.js')}}"></script>
@endsection