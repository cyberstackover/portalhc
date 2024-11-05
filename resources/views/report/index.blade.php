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
    	<form class="kt-form kt-form--label-right" method="post" id="form-report" role="form" enctype="multipart/form-data" action="/report/export">
			@csrf
			{{ method_field('GET') }}
            <div class="col-lg-12">
                <div class="form-group row">
                    <div class="col-lg-4">
                        <label><span class="kt-font-dark">Filter Report:</span></label>
                        <select class="form-control kt-select2" id="filter" name="filter">
                            <option value="all">All</option>
                            <option value="bumn">BUMN</option>
                            <option value="anak">Anak Perusahaan</option>
                            <option value="cucu">Cucu Perusahaan</option>
                        </select>
                    </div>
                    <div class="col-lg-4" style="margin-top:25px;">
                        <button type="submit" class="btn btn-warning btn-elevate btn-icon-sm cls-generate">
                            <i class="far fa-file-excel"></i>
                            Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('addafterjs')
  <script type="text/javascript">
      var urlexport = "{{route('report.export')}}";
  </script>
  <script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('js/report/index.js')}}"></script>
@endsection