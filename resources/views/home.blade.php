@extends('layouts.app')

@section('addbeforecss')

@endsection

@section('content')
<!-- begin:: Content -->
    <div class="kt-container  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--skin-solid kt-portlet--height-fluid">
                    <div class="kt-portlet__head kt-portlet__head--noborder kt-portlet__space-x">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Masa Jabatan</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <a href="#" class="btn btn-label-light btn-sm btn-bold dropdown-toggle" data-toggle="dropdown">
                                Export
                            </a>
                        </div>
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fit">
                        <div id="container" style="min-width: 900px; max-width: 800px; height: 400px; margin: 5 auto"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Content -->
@endsection

@section('addafterjs')

{{-- <script src="{{asset('assets/dashboard/code/highcharts.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/dashboard/code/modules/data.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/dashboard/code/modules/drilldown.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/dashboard/code/themes/dark-unica.js')}}" type="text/javascript"></script> --}}
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script type="text/javascript" src="{{asset('js/dashboard/index.js')}}"></script>
@endsection
