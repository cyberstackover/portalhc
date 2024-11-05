@extends('layouts.app')

@section('addbeforecss')
<link href="{{asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/plugins/Highcharts-6.0.3/code/css/highcharts.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon-web"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Dashboard
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">

        <div class="kt-container  kt-grid__item kt-grid__item--fluid">
            <div class="row">

                <div class="col-lg-12">
                    <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--skin-solid kt-portlet--height-fluid" style="min-height:400px;">

                        <div class="kt-portlet__body kt-portlet__body--fit" style="padding-top: 70px;">
                            <div class="row">
                                <div class="col-lg-12">
                                <div id="divChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
    </div>

    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon-web"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Rekapitulasi Status Talent
            </h3>
        </div>
        
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">   
                    <a href="/talenta/dashboard/downloadrekap" target="_blank" class="btn btn-success btn-elevate btn-icon-sm cls-generate">
                        <i class="far fa-file-excel"></i>
                        Download Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">

        <!--begin: Datatable -->
        <div class="table-responsive">
          <table class="table table-striped- table-bordered table-hover table-checkable" id="tablerekap">
                 <thead>
                   <tr>
                     <th>No.</th>
                     <th>BUMN</th>
                     <th>Selected</th>
                     <th>Nominated</th>
                     <th>Eligible</th>
                     <th>Qualified</th>
                     <th>Jumlah</th>
                   </tr>
                 </thead>
                 
                 <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                 </tfoot>
          </table>
        </div>
        <!--end: Datatable -->
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myCenterModalLabel" aria-hidden="true" style="display: none;" id="dash-modal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div id="divviewtable"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




@endsection


@section('addafterjs')
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/Highcharts-6.0.3/code/js/highcharts.js') }}"></script>
<script src="{{ asset('assets/plugins/Highcharts-6.0.3/code/js/highcharts-3d.js') }}"></script>
<script src="{{ asset('assets/plugins/Highcharts-6.0.3/code/js/modules/exporting.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/generalfunction.js') }}"></script>

<script type="text/javascript">
    var urlchartjumlahtalenta = "{{ route('talenta.dashboard.chartjumlahtalenta') }}";
    var urlgettabledetail = "{{ route('talenta.dashboard.gettabledetail') }}";
    var urldetail_rekap = "{{ route('talenta.dashboard.detail_rekap') }}";
</script>

<script type="text/javascript" src="{{asset('js/talenta/dashboard/index.js')}}"></script>
@endsection
