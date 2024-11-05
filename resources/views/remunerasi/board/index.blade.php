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
                    <a href="javascript:;" class="btn btn-brand btn-elevate btn-icon-sm cls-add">
                        <i class="la la-plus"></i>
                        Tambah Gaji Pokok Direktur Utama
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
      <div class="form-group row">
            <div class="col-lg-2">
              <label>Periode:</label>
              <select class="form-control kt-select2" id="form_tahun" name="param">
                @for ($i = 2017; $i < now()->year + 5; $i++)
                <option value="{{ $i }}" @if(now()->year == $i) selected="selected" @endif>{{ $i }}</option>
                @endfor
              </select>
            </div>
          </div>
        <!--begin: Datatable -->
        <div class="table-responsive">
          <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
              <thead>
                  <tr>
                      <th width="5%">No.</th>
                      <th width="45%">Nama</th>
                      <th width="10%">Tahun</th>
                      <th width="20%">Gaji Pokok</th>
                      <th width="20%">Tantiem Board</th>
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
      var urlcreate = "{{route('remunerasi.board.create')}}";
      var urledit = "{{route('remunerasi.board.edit')}}";
      var urlstore = "{{route('remunerasi.board.store')}}";
      var urlupdate = "{{route('remunerasi.board.update')}}";
      var urldatatable = "{{route('remunerasi.board.datatable')}}";
      var urldelete = "{{route('remunerasi.board.delete')}}";
  </script>
  <script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('js/remunerasi/board/index.js')}}"></script>
@endsection