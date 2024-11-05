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
                    Administrasi SK Anak/Cucu
                </h3>
            </div>
            @can('adanak-create')
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="/administrasi/anak/tambah" class="btn btn-brand btn-elevate btn-icon-sm cls-add">
                            <i class="la la-plus"></i>
                            Tambah Data SK
                        </a>
                    </div>
                </div>
            </div>
            @endcan
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
                <form class="kt-form kt-form--label-right" style="" kt-hidden-height="340">
                    <div class="kt-portlet__body">
                        <div class="form-group row">
                          <div class="col-lg-5">
                            <label><span class="kt-font-dark">Induk:</span></label>
                            <select class="form-control kt-select2" id="form_induk" name="form_induk" onchange=" return onNamaPerusahaan(this.value) ">
                              <option></option>
                              @foreach($induks as $induk)
                                <option value="{{ $induk->id }}">{{ $induk->nama_lengkap }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-lg-5">
                            <label><span class="kt-font-dark">Perusahaan:</span></label>
                            <select class="form-control kt-select2" id="id_perusahaan" name="id_perusahaan">
                            <option></option>
                              @foreach($bumns as $bumn)
                                <option value="{{ $bumn['id'] }}">{{ $bumn['nama_lengkap'] }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-lg-2">
                            <label><span class="kt-font-dark">Grup Jabatan:</span></label>
                            <select class="form-control kt-select2" id="id_grup_jabat" name="id_grup_jabat">
                              <option></option>
                              @foreach($grupjabats as $grupjabat)
                                <option value="{{ $grupjabat->id }}">{{ $grupjabat->nama }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label><span class="kt-font-dark">Nomor SK:</span></label>
                                <input class="form-control" type="text" id="nomor_sk" name="nomor_sk">
                            </div>
                            <div class="col-lg-6">
                                <label><span class="kt-font-dark">Jenis SK:</span></label>
                                <select class="form-control kt-select2" id="id_jenis_sk" name="id_jenis_sk">
                                    <option></option>
                                  @foreach($jenissks as $jenissk)
                                    <option value="{{ $jenissk->id }}">{{ $jenissk->nama }}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label><span class="kt-font-dark">Tanggal SK:</span></label>
                                <input class="form-control cls-datepicker" type="text" id="tgl_sk" name="tgl_sk">
                            </div>
                            <div class="col-lg-6">
                                <label><span class="kt-font-dark">Status:</span></label>
                                <select class="form-control kt-select2" id="id_status" name="id_status">
                                  <option></option>  
                                  <option value="SUBMIT">SUBMIT</option>
                                  <option value="DRAFT">DRAFT</option>
                                </select>
                            </div>
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

            <!--begin: Datatable -->
            <div class="table-responsive">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th width="15%">No SK</th>
                            <th width="25%">Perusahaan</th>
                            <th width="10%">Jenis SK</th>
                            <th width="20%">Status Terakhir</th>
                            <th width="15%"><div align="center">Aksi</div></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>    
            <!--end: Datatable -->
    </div>
</div>


@endsection


@section('addafterjs')
<script type="text/javascript">
    var urldelete = "{{route('administrasi.anak.delete')}}";
    var urldatatable = "{{route('administrasi.anak.datatable')}}";
    var urldetailsk = "{{route('administrasi.anak.detail')}}";
</script>
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/administrasi/anak/index.js')}}"></script>
@endsection