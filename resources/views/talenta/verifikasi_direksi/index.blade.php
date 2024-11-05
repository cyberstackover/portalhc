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
    </div>
    
    <div class="kt-portlet__body">
        
        <div class="alert alert-custom fade show mb-5" style="background-color:yellow;" role="alert">
            <div class="alert-text"><b>Petunjuk :</b><br>
                Silahkan pilih salah satu action <font style="color:red;"> Register </font>. Search talent kemudian klik button <font style="color:red;"> Register </font>. Jika talent tidak ditemukan, klik <font style="color:red;"> Tambah Talent </font> untuk input data talent baru.
            </div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
            </div>
        </div>

        <div class="kt-portlet kt-shape-bg-color-2">
            <form class="kt-form kt-form--label-right" >
                <div class="kt-portlet__body">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label><span class="kt-font-dark">Search Talent:</span></label>
                            
                            <select class="form-control kt-select2" id="nama_lengkap" name="nama_lengkap">
                                <option></option>
                                @foreach($talenta as $data)
                                <option value="{{ $data->nama_lengkap }}">{{ $data->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label><span class="kt-font-dark">Jabatan:</span></label>
                            <input type="text" id="jabatan" name="jabatan" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 pull-right">
                            <div class="">
                                <a id="cari" type="button" class="btn btn-danger" href="javascript:;" >cari</a>
                                <a id="reset" type="button" class="btn btn-warning" href="javascript:;" >reset</a>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </form>
        </div>

        <div class="col-lg-12">
            <label class="mt-checkbox mt-checkbox-outline pull-right">
                <input type="checkbox" class="reject_all" />
                <span>Reject All</span>
            </label>
            <label class="mt-checkbox mt-checkbox-outline pull-right">
                <input type="checkbox" class="checked_all" />
                <span>Approve All &ensp;</span>
            </label>
            <div class="table-responsive">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                    <thead>
                        <tr>
                            <th width="5%" rowspan="2">No.</th>
                            <th rowspan="2" >Nama Lengkap</th>
                            <th rowspan="2" >Status Talent</th>
                            <th rowspan="2" >Jabatan</th>
                            <th rowspan="2" >Status Pengisian</th>
                            <th colspan="2"><div align="center">Aksi</div></th>
                        </tr>
                        <tr>
                            <th> Approve </th>
                            <th> Reject </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <input type="hidden" name="checked_list" class="checked_list">
            <input type="hidden" name="reject_list" class="reject_list">
            <button class="btn btn-success pull-right btn-submit" style="margin-top:40px;">Submit Talent</button>
            
        </div>
        <!--begin: Datatable -->
        <!--end: Datatable -->
    </div>
</div>
@endsection

@section('addafterjs')
  <script type="text/javascript">
      var urlminicv = "{{route('talenta.register.minicv')}}";
      var urllogstatus = "{{route('talenta.register.log_status')}}";
      var urldatatable = "{{route('talenta.verifikasi_direksi.datatable')}}";
      var urlapprove = "{{route('talenta.verifikasi_direksi.approve')}}";
      var urlreject = "{{route('talenta.verifikasi_direksi.reject')}}";
      var urlupdate_talenta = "{{route('talenta.verifikasi_direksi.update_talenta')}}";
  </script>
  
  <script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('js/talenta/verifikasi_direksi/index.js')}}"></script>
@endsection
