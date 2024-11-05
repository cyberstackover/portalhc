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
                    Monitoring Pejabat
                </h3>
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
                <form class="kt-form kt-form--label-right" style="" kt-hidden-height="740" enctype="multipart/form-data" action="/administrasi/monitoring/pejabat/export">
                    <div class="kt-portlet__body">
                        <div class="form-group row">
                              <div class="col-lg-4">
                                <label><span class="kt-font-dark">Nama Pejabat:</span></label>
                                <input class="form-control" type="text" id="pejabat" name="pejabat">
                              </div>
                              <div class="col-lg-4">
                                  <label><span class="kt-font-dark">Nomor SK:</span></label>
                                  <input class="form-control" type="text" id="nomor_sk" name="nomor_sk">
                              </div>
                              <div class="col-lg-4">
                                <label><span class="kt-font-dark">Aktif/Tidak Aktif:</span></label>
                                <select class="form-control kt-select2" id="pejabataktif" name="pejabataktif">
                                  <option value="AKTIF">AKTIF</option>
                                  <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label><span class="kt-font-dark">Grup Jabatan:</span></label>
                                <select class="form-control kt-select2" id="id_grup_jabat" name="id_grup_jabat">
                                <option></option>
                                  @foreach($grupjabats as $grupjabat)
                                    <option value="{{ $grupjabat->id }}">{{ $grupjabat->nama }}</option>
                                  @endforeach
                                </select>
                              </div>
                            <div class="col-lg-6">
                                <label><span class="kt-font-dark">Tanggal SK:</span></label>
                                <input class="form-control cls-datepicker" type="text" id="tgl_sk" name="tgl_sk">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-2">
                                <label><span class="kt-font-dark">Jabatan:</span></label>
                                <select class="form-control kt-select2" id="id_jabatan" name="id_jabatan">
                                    <option></option>
                                  @foreach($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label><span class="kt-font-dark">Periode Jabatan:</span></label>
                                <select class="form-control kt-select2" id="id_periode" name="id_periode">
                                    <option></option>
                                  @foreach($periodes as $periode)
                                    <option value="{{ $periode->id }}">{{ $periode->nama }}</option>
                                  @endforeach
                                </select>
                            </div>
                            @if ($kategori_user == 1)
                            <div class="col-lg-4">
                                <label><span class="kt-font-dark">Jenis Instansi:</span></label>
                                <select class="form-control kt-select2" id="id_jenis_asal_instansi" name="id_jenis_asal_instansi" onchange=" return onAsalInstansi(this.value) ">
                                    <option></option>
                                  @foreach($jenisasalinstansis as $jenisasalinstansi)
                                    <option value="{{ $jenisasalinstansi->id }}">{{ $jenisasalinstansi->nama }}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label><span class="kt-font-dark">Instansi:</span></label>
                                <select class="form-control kt-select2" id="id_asal_instansi" name="id_asal_instansi">
                                    <option></option>
                                  @foreach($asalinstansis as $instansi)
                                    <option value="{{ $instansi->id }}">{{ $instansi->nama }}</option>
                                  @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label><span class="kt-font-dark">Perusahaan:</span></label>
                                <select class="form-control kt-select2" id="id_bumn" name="id_bumn">
                                    <option></option>
                                  @foreach($bumns as $bumn)
                                    <option value="{{ $bumn->id }}">{{ $bumn->nama_lengkap }}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label><span class="kt-font-dark">Awal Menjabat:</span></label>
                                <input class="form-control cls-datepicker" type="text" id="awal_tgl" name="awal_tgl">
                            </div>
                            <div class="col-lg-4">
                                <label><span class="kt-font-dark">Akhir Menjabat:</span></label>
                                <input class="form-control cls-datepicker" type="text" id="akhir_tgl" name="akhir_tgl">
                            </div>
                        </div>
                        @if ($kategori_user == 1)
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label><span class="kt-font-dark">Jenis Kelamin</span></label>
                                <select class="form-control kt-select2" id="jenis_kelamin" name="jenis_kelamin">
                                    <option></option>
                                  <option value="L">Laki-Laki</option>
                                  <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label><span class="kt-font-dark">Agama</span></label>
                                <select class="form-control kt-select2" id="id_agama" name="id_agama">
                                  <option value=""></option>
                                  @foreach($agama as $data)
                                  <option value="{{ $data->id }}" >{{ $data->nama }}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label><span class="kt-font-dark">Masa Jabatan:</span></label>
                                <select class="form-control kt-select2" id="masa_jabatan" name="masa_jabatan">
                                  <option></option>
                                  <option value="expire">Expire</option>
                                  <option value="kurang3">Kurang 3 Bulan</option>
                                  <option value="kurang6">kurang 6 Bulan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label><span class="kt-font-dark">Kewarganegaraan</span></label>
                                <select class="form-control kt-select2" id="kewarganegaraan" name="kewarganegaraan">
                                    <option></option>
                                  <option value="WNI">Indonesia</option>
                                  <option value="WNA">Asing</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label><span class="kt-font-dark">Suku</span></label>
                                <select class="form-control kt-select2" id="id_suku" name="id_suku">
                                    <option></option>
                                  @foreach($sukus as $suku)
                                    <option value="{{ $suku->id }}">{{ $suku->nama }}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label><span class="kt-font-dark">Kelas</span></label>
                                <select class="form-control kt-select2" id="kelas" name="kelas">
                                    <option></option>
                                    @foreach($kelass as $kelas)
                                        <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label><span class="kt-font-dark">Klaster</span></label>
                                <select class="form-control kt-select2" id="id_klaster" name="id_klaster">
                                    <option></option>
                                    @foreach($klasters as $klaster)
                                        <option value="{{ $klaster->id }}">{{ $klaster->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="">
                            <a id="cari" type="button" class="btn btn-danger" href="javascript:;" >
                                            cari
                                        </a>
                            <a id="reset" type="button" class="btn btn-warning" href="javascript:;" >
                                            reset
                                        </a>
                            @if($users->kategori_user_id == 1)
                            <button class="btn btn-success btn-elevate btn-icon-sm cls-generate">
                                <i class="far fa-file-excel"></i>
                                Generate Report
                            </button>

                            {{-- <a class="btn btn-success btn-elevate btn-icon-sm cls-generate">
                                <i class="far fa-file-excel"></i>
                                Generate Report
                            </a> --}}

                            {{-- <a href="/administrasi/monitoring/pejabat/export" target="_blank" class="btn btn-success btn-elevate btn-icon-sm cls-download">
                                <i class="la la-download"></i>
                                Download Data
                            </a> --}}
                            @endif
                        </div>
                    </div>
                </form>

                <!--end::Form-->
            </div>

            <!-- end pencarian -->
            <!--begin: Datatable -->
            <div class="table-responsive">

                <!-- start table -->
                <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th width="10%">Perusahaan</th>
                            <th width="15%">Grup Jabat</th>
                            <th width="23%">Pejabat</th>
                            <th width="2%">Junto</th>
                            <th width="20%">Nomor SK</th>
                            <th width="10%">Awal Menjabat</th>
                            <th width="10%">Akhir Menjabat</th>
                            <th width="15%">Asal Instansi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <!-- end table -->
            </div>
        </div>
            <!--end: Datatable -->
    </div>
</div>


@endsection


@section('addafterjs')
<script type="text/javascript">
    var urldatatable = "{{route('administrasi.monitoring.pejabat.datatable')}}";
    var urlexport = "{{route('administrasi.monitoring.pejabat.export')}}";
    var urlminicv = "{{route('talenta.register.minicv')}}";
    var urldetailjo = "{{route('administrasi.bumn.detailjo')}}";
    var urldetailsk = "{{route('administrasi.bumn.detail')}}";
</script>
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/administrasi/monitoring/pejabat/index.js')}}"></script>
@endsection
