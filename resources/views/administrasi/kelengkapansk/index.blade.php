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
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">

        <!-- start pencarian -->
      <div class="kt-portlet kt-portlet--collapsed kt-shape-bg-color-2" data-ktportlet="true" id="kt_portlet_tools_6">
          <div class="kt-portlet__head">
              <div class="kt-portlet__head-label">
                  <h3 class="kt-portlet__head-title">
                      Pencarian
                  </h3>
              </div>
              <div class="kt-portlet__head-toolbar">
                  <div class="kt-portlet__head-group">
                      <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-warning btn-icon-md" aria-describedby="tooltip_m3bv968wwi"><i class="la la-angle-down"></i></a>
                  </div>
              </div>
          </div>
          <!--begin::Form-->
          <form class="kt-form kt-form--label-right" style="" kt-hidden-height="500">
              <div class="kt-portlet__body">
                  <div class="form-group row">
                        <div class="col-lg-6">
                          <label><span class="kt-font-dark">Nama:</span></label>
                          <input class="form-control" type="text" id="nama_lengkap" name="nama_lengkap">
                        </div>
                        <div class="col-lg-6">
                          <label><span class="kt-font-dark">Perusahaan:</span></label>
                          <select class="form-control kt-select2" id="id_perusahaan" name="id_perusahaan">
                            <option></option>
                              @foreach($perusahaan as $data)
                                <option value="{{ $data->id }}">{{ $data->nama_lengkap }}</option>
                              @endforeach
                            </select>
                        </div>
                  </div>                  
                  <div class="form-group row">
                        <div class="col-lg-4">
                            <label><span class="kt-font-dark">Nomor SK:</span></label>
                            <input class="form-control" type="text" id="nomor_sk" name="nomor_sk">
                        </div>
                        <div class="col-lg-4">
                            <label><span class="kt-font-dark">Jabatan:</span></label>
                            <select class="form-control kt-select2" id="jabatan" name="jabatan">
                            <option></option>
                              @foreach($jabatan as $data)
                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label><span class="kt-font-dark">Tanggal SK:</span></label>
                            <input class="form-control cls-datepicker" type="text" id="tgl_sk" name="tgl_sk">
                        </div>
                  </div>
              </div>
              <div class="kt-portlet__foot">
                  <div class="">
                      <a id="cari" type="button" class="btn btn-danger" href="javascript:;" >
                                      cari
                                  </a>
                      <a id="reset" type="button" class="btn btn-warning" href="javascript:;" >
                                      reset
                                  </a>
                  </div>
              </div>
          </form>
          <!--end::Form-->
      </div>      
      <!-- end pencarian -->

        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama - Jabatan</th>
                    <th>Perusahaan</th>
                    <th>Nomor SK</th>
                    <th>Kelengkapan(%)</th>
                    <th><div align="center">Aksi</div></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!--end: Datatable -->
    </div>
</div>
@endsection

@section('addafterjs')
  <script type="text/javascript">
      var urlcreate = "{{route('administrasi.kelengkapansk.create')}}";
      var urledit = "{{route('administrasi.kelengkapansk.edit')}}";
      var urlstore = "{{route('administrasi.kelengkapansk.store')}}";
      var urldatatable = "{{route('administrasi.kelengkapansk.datatable')}}";
      var urldelete = "{{route('administrasi.kelengkapansk.delete')}}";
      var urlfilekelengkapan = "{{route('administrasi.kelengkapansk.store-data-filekelengkapan')}}";
  </script>
  <script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('js/administrasi/kelengkapansk/index.js')}}"></script>
@endsection