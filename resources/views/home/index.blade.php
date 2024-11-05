@extends('layouts.app')

@section('addbeforecss')
<link href="{{asset('assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/plugins/Highcharts-6.0.3/code/css/highcharts.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    <div class="row">

        <div class="col-lg-12">
            <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--skin-solid kt-portlet--height-fluid" style="min-height:400px;">
                <div class="kt-portlet__head kt-portlet__head--noborder kt-portlet__space-x">
                    <div class="kt-portlet__head-label">
                        <div class="dropdown">
                            <div class="btn-group">
                                <select class="form-control kt-select2" id="select_chart" name="select_chart" style="width: 100%">
                                    <option value="1">Masa Jabatan</option>
                                    <option value="2">Kontribusi Dekom dari Kementerian Lembaga</option>
                                    <option value="3">Asal Instansi</option>
                                    <option value="4">Demografi Direksi Dekom</option>
                                    <option value="5">Jumlah Direksi/Dekom per-tahun</option>
                                    <!-- <option value="6">Komposisi Ormas</option> -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="filter_masa" class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <div class="dropdown">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Filter
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" id="filter-bumn" href="#">BUMN</a>
                                            <a class="dropdown-item" id="filter-anak" href="#">Anak</a>
                                            <a class="dropdown-item" id="filter-cucu" href="#">Cucu</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="filter_demografi" class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <div class="dropdown">
                                    <div class="btn-group">
                                        <select class="form-control kt-select2" id="select_demografi" name="select_demografi" style="width: 100%">
                                            <option value="1" selected="selected">Jenis Kelamin</option>
                                            <option value="2">Komposisi Usia</option>
                                            <option value="3">Jenjang Pendidikan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="filter_jumlah" class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <div class="dropdown">
                                    <div class="btn-group">
                                        <select class="form-control kt-select2" id="select_jumlah" name="select_jumlah" style="width: 100%">
                                            <option value="1" selected="selected">BUMN</option>
                                            <option value="2">Anak & Cucu</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body kt-portlet__body--fit" style="padding-top: 70px;">
                    <div class="row">
                        <div class="col-lg-6">
                            <div id="divChart1"></div>
                        </div>
                        <div class="col-lg-6">
                            <div id="divChart2"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                        <div id="divChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="kt-portlet kt-shape-bg-color-2" style="min-height:100px;">
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-lg-6 text-center">
                            <label><h4><span class="kt-font-dark">Informasi Direksi Komisaris</span></h4></label>
                            <select class="form-control kt-select2" id="perusahaan" name="perusahaan">
                            <option></option>
                                @foreach($perusahaan as $data)
                                <option value="{{ $data->id }}">{{ $data->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 text-center">
                            <label><h4><span class="kt-font-dark">Histori Pejabat</span></h4></label>
                            <select class="form-control kt-select2" id="talenta" name="talenta">
                            <option></option>
                                @foreach($talenta as $data)
                                <option value="{{ $data->id }}">{{ $data->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    var urlchartmasajabatans = "{{ route('home.chartmasajabatans') }}";
    var urlgettabledetail = "{{ route('home.gettabledetail') }}";
    var urlchartkontribusi = "{{ route('home.chartkontribusi') }}";
    var urlgettablekontribusi = "{{ route('home.gettablekontribusi') }}";
    var urlchartinstansi = "{{ route('home.chartinstansi') }}";
    var urlchartdemografi = "{{ route('home.chartdemografi') }}";
    var urlchartdemografijk = "{{ route('home.chartdemografijk') }}";
    var urlchartdemografiusia = "{{ route('home.chartdemografiusia') }}";
    var urlchartdemografipendidikan = "{{ route('home.chartdemografipendidikan') }}";
    var urlchartjumlah = "{{ route('home.chartjumlah') }}";
    var urlgettableperusahaan = "{{ route('home.gettableperusahaan') }}";
    var urlgettabletalenta = "{{ route('home.gettabletalenta') }}";
</script>

<script type="text/javascript" src="{{asset('js/home/index.js')}}"></script>
@endsection
