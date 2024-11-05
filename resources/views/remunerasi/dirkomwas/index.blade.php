@extends('layouts.app')


@section('addbeforecss')
<link href="{{asset('assets/global/plugins/jquery-treegrid-master/css/jquery.treegrid.css')}}" rel="stylesheet" type="text/css" />
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
                    <!-- <a href="javascript:;" class="btn btn-brand btn-elevate btn-icon-sm cls-add">
                        <i class="la la-plus"></i>
                        Tambah Remunerasi Dirkomwas
                    </a> -->
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="form-group row">
            <div class="col-lg-5">
              <label>BUMN:</label>
              <select class="form-control kt-select2" id="form_perusahaan" name="param">
                @foreach ($perusahaans as $perusahaan)
                <option value="{{ $perusahaan->id }}">{{ $perusahaan->nama_lengkap }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-lg-2">
              <label>Periode:</label>
              <select class="form-control kt-select2" id="form_tahun" name="param">
                @for ($i = 2017; $i < now()->year + 5; $i++)
                <option value="{{ $i }}" @if(now()->year == $i) selected="selected" @endif>{{ $i }}</option>
                @endfor
              </select>
            </div>
          </div>
        <div id="remun-result"></div>
    </div>
</div>
@endsection

@section('addafterjs')
  <script type="text/javascript">
      var urlcreate = "{{route('remunerasi.dirkomwas.create')}}";
      var urledit = "{{route('remunerasi.dirkomwas.edit')}}";
      var urlstore = "{{route('remunerasi.dirkomwas.store')}}";
      var urlupdate = "{{route('remunerasi.dirkomwas.update')}}";
      var urldatatable = "{{route('remunerasi.dirkomwas.datatable')}}";
      var urldelete = "{{route('remunerasi.dirkomwas.delete')}}";
      var urldetail = "{{route('remunerasi.dirkomwas.detail')}}";
  </script>
  <script type="text/javascript" src="{{asset('assets/global/plugins/jquery-treegrid-master/js/jquery.treegrid.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/remunerasi/dirkomwas/index.js')}}"></script>
@endsection