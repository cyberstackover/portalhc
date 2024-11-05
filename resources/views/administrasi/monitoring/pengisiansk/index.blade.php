@extends('layouts.app')

@section('addbeforecss')

@endsection


<!-- Konten -->
@section('content')


<div class="col-lg-12">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-web"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Monitoring Pengisian SK
                </h3>
                
            </div>
        </div>
        
        <div class="kt-portlet__body">
            @if($users->kategori_user_id == 1)
            <!-- start pencarian -->
            <div class="kt-portlet kt-portlet--collapsed kt-shape-bg-color-2 cari" data-ktportlet="true" id="kt_portlet_tools_6">
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
                <form class="kt-form kt-form--label-right" style="" kt-hidden-height="740">
                    <div class="kt-portlet__body">
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label><span class="kt-font-dark">Perusahaan:</span></label>
                                <select class="form-control kt-select2" id="id_bumn" name="id_bumn">
                                    <option></option>
                                  @foreach($bumns as $bumn)
                                    <option value="{{ $bumn->id }}">{{ $bumn->nama_lengkap }}</option>
                                  @endforeach
                                </select>
                            </div>
                            {{--  <div class="col-lg-4">
                                <label><span class="kt-font-dark">Awal Menjabat:</span></label>
                                <input class="form-control cls-datepicker" type="text" id="awal_tgl" name="awal_tgl">
                            </div>
                            <div class="col-lg-4">
                                <label><span class="kt-font-dark">Akhir Menjabat:</span></label>
                                <input class="form-control cls-datepicker" type="text" id="akhir_tgl" name="akhir_tgl">
                            </div>  --}}
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
            @endif
            <!--begin: Datatable -->
            <div class="card card-custom">
                <div class="card-header card-header-tabs-line">
                    <div class="card-toolbar">
                        <ul class="nav nav-tabs nav-bold nav-tabs-line">
                            @if($users->kategori_user_id == 1)
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#induk" id='bumn' role="tab" aria-selected="true">
                                    <span class="nav-text">BUMN</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#grup" id='anak' role="tab" aria-selected="true">
                                    <span class="nav-text">GRUP</span>
                                </a>
                            </li>
                            @else 
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#grup" role="tab" aria-selected="true">
                                    <span class="nav-text">GRUP</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane {{$users->kategori_user_id == 1 ? "active" : ""}}" id="induk" role="tabpanel">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    @if($users->kategori_user_id == 1)
                                    <h3 class="kt-portlet__head-title">
                                       Monitoring SK INDUK
                                    </h3>
                                    @else
                                    <h3 class="kt-portlet__head-title">
                                       Monitoring SK GRUP
                                    </h3>
                                    @endif
                                </div>
                                <div class="kt-portlet__head-actions">
                                    <a href="{{route('administrasi.monitoring.pengisiansk.export')}}" target="_blank" class="btn btn-danger btn-elevate btn-icon-sm cls-download">
                                        <i class="la la-download"></i>
                                        Download Monitoring SK
                                    </a>
                                    {{-- <a href="javascript:;" class="btn btn-primary btn-elevate btn-icon-sm cls-add">
                                        <i class="la la-plus"></i>
                                        Tambah Talenta
                                    </a>
                                            <a href="javascript:;" class="btn btn-success btn-elevate btn-icon-sm cls-import">
                                        <i class="la la-plus"></i>
                                        Import Talenta
                                    </a> --}}
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="table-responsive">
                                    <!-- start table -->
                                    <table class="table table-striped- table-bordered table-hover table-checkable" id="{{$users->kategori_user_id == 1 ? "datatable" : ""}}">
                                        <thead>
                                            <tr>
                                                <th rowspan="2"><div align="center">Perusahaan</div></th>
                                                <th colspan="3"><div align="center"> BUMN</div></th>
                                                <th colspan="3"><div align="center"> Anak / Cucu</div></th>
                                                <th rowspan="2"><div align="center">Presentase(%)</div></th>
                                            </tr>
                                            <tr>
                                                <th width="10%"><div align="center">Direksi</div></th>
                                                <th width="10%"><div align="center">Dekom/Dewas</div></th>
                                                <th width="10%"><div align="center">Jabatan Kosong</div></th>
                                                <th width="10%"><div align="center">Direksi</div></th>
                                                <th width="10%"><div align="center">Dekom/Dewas</div></th>
                                                <th width="10%"><div align="center">Jabatan Kosong</div></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
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
                                    <!-- end table -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane {{$users->kategori_user_id == 2 ? "active" : ""}}" id="grup" role="tabpanel">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">
                                       Monitoring SK Grup
                                    </h3>
                                </div>
                                <div class="kt-portlet__head-actions">
                                    <a href="javascript:void(0)" data-param="" class="btn btn-warning btn-elevate btn-icon-sm cls-download export2">
                                        <i class="la la-download"></i>
                                        Download Monitoring SK Grup
                                    </a>
                                    {{-- <a href="javascript:;" class="btn btn-primary btn-elevate btn-icon-sm cls-add">
                                        <i class="la la-plus"></i>
                                        Tambah Talenta
                                    </a>
                                            <a href="javascript:;" class="btn btn-success btn-elevate btn-icon-sm cls-import">
                                        <i class="la la-plus"></i>
                                        Import Talenta
                                    </a> --}}
                                </div>
                            </div>
                            <br/>
                            <div class="kt-portlet__head-toolbar">
                                <div class="form-group row">
                                    <div class="col-lg-5">

                                        @if($users->kategori_user_id == 1)
                                        <select class="form-control kt-select2" id="form_perusahaan" name="param">
                                                <option value="">Pilih_Perusahaan</option>
                                                @foreach ($bumns as $perusahaan)
                                                <option value="{{ $perusahaan->id }}">{{ $perusahaan->nama_lengkap }}</option>
                                                @endforeach
                                        </select>
                                        @else 
                                        <select class="form-control kt-select2" id="form_perusahaan" name="param">
                                        
                                        <option value="{{ $bumns->id }}">{{ $bumns->nama_lengkap }}</option>
                                        
                                    </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="table-responsive">
                                    <!-- start table -->
                                    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatablegrup">
                                        <thead>
                                            <tr>
                                                <th width="25%"><div align="center">Perusahaan</div></th>
                                                <th width="10%"><div align="center">Direksi</div></th>
                                                <th width="10%"><div align="center">Dekom/Dewas</div></th>
                                                <th width="10%"><div align="center">Jabatan Kosong</div></th>
                                                <th width="10%"><div align="center">Presentase(%)</div></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <!-- end table -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
            <!--end: Datatable -->
    </div>
</div>


@endsection


@section('addafterjs')
<script type="text/javascript">
    var urldatatable = "{{route('administrasi.monitoring.pengisiansk.datatable')}}";
    var urldatatablegrup = "{{route('administrasi.monitoring.pengisiansk.datatablegrup')}}";
    var export2_param = "{{ route('administrasi.monitoring.pengisiansk.export2',['id_perusahaan' => ':param']) }}";
    var export2 = "{{ route('administrasi.monitoring.pengisiansk.export2') }}";
</script>
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/administrasi/monitoring/pengisiansk/index.js')}}"></script>
@endsection