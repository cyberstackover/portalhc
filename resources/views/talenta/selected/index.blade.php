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
                    @can('talent-create')
                    <a href="javascript:;" class="btn btn-primary btn-elevate btn-icon-sm cls-add">
                        <i class="la la-plus"></i>
                        Tambah Talenta
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    
    <div class="kt-portlet__body">
        
        <div class="alert alert-custom fade show mb-5" style="background-color:yellow;" role="alert">
            <div class="alert-text"><b>Petunjuk :</b><br>
                Untuk menambahkan talent, search talent kemudian centang checkbox. Jika talent tidak ditemukan, klik Tambah Talenta untuk input data talent baru.
            </div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
            </div>
        </div>


      <ul class="nav nav-tabs nav-tabs mb-5">
            <li class="nav-item">
                <a class="nav-link a-step-1" href="{{route('talenta.register.index')}}">
                    <span class="nav-text">Data Talent</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active a-step-2" href="#">
                    <span class="nav-text">Register <label class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">{{$jumlah}}</label></span>
                </a>
            </li>
      </ul>

    <div class="form-group row">
    <div class="col-lg-4">
        <label><span class="kt-font-dark">Search Talent:</span></label>
        
        <select class="form-control kt-select2" id="nama_lengkap" name="nama_lengkap">
            <option></option>
            @foreach($talenta as $data)
            <option value="{{ $data->nama_lengkap }}">{{ $data->nama_lengkap }}</option>
            @endforeach
        </select>
    </div>
    </div>

    <div class="kt-portlet__body">
      <!--begin: Datatable -->
      <div class="table-responsive">
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th >Nama Lengkap</th>
                    <th >Jabatan</th>
                    <th >Status Pengisian</th>
                    <th >Log Status</th>
                    <th ><div align="center">Aksi</div></th>
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
      var urlminicv = "{{route('talenta.register.minicv')}}";
      var urllogstatus = "{{route('talenta.register.log_status')}}";
      var urldatatable = "{{route('talenta.selected.datatable')}}";
  </script>
  
  <script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('js/talenta/selected/index.js')}}"></script>
@endsection
