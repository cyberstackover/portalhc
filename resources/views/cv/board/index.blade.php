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
                    @if(\Auth::user()->hasRole('Admin') || \Auth::user()->hasRole('Bumn'))
                    <a href="/cv/board/generate" target="_blank" class="btn btn-warning btn-elevate btn-icon-sm cls-generate">
                        <i class="far fa-file-excel"></i>
                        Status Pengisian
                    </a>
                        @if(\Auth::user()->hasRole('Admin'))
                        <a href="/cv/board/template/" target="_blank" class="btn btn-danger btn-elevate btn-icon-sm cls-download">
                            <i class="la la-download"></i>
                            Download Template
                        </a>
                        @endif
                    @endif
                    @can('talent-create')
                    <a href="javascript:;" class="btn btn-primary btn-elevate btn-icon-sm cls-add">
                        <i class="la la-plus"></i>
                        Tambah Talenta
                    </a>
                    @endcan
                    @can('talent-import')
                    <a href="javascript:;" class="btn btn-success btn-elevate btn-icon-sm cls-import">
                        <i class="la la-plus"></i>
                        Import Talenta
                    </a>
                    @endcan
                </div>
            </div>
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
          <form class="kt-form kt-form--label-right" style="" kt-hidden-height="500">
              <div class="kt-portlet__body">
                  <div class="form-group row">
                        <div class="col-lg-6">
                          <label><span class="kt-font-dark">Nama:</span></label>
                          <input class="form-control" type="text" id="nama_lengkap" name="nama_lengkap">
                        </div>
                        <div class="col-lg-6">
                          <label><span class="kt-font-dark">NIK:</span></label>
                          <input class="form-control" type="text" id="nik" name="nik">
                        </div>
                  </div>
                  <div class="form-group row">
                        <div class="col-lg-4">
                            <label><span class="kt-font-dark">Asal Instansi:</span></label>
                            <select class="form-control kt-select2" id="asal_instansi" name="asal_instansi">
                            <option></option>
                              @foreach($asal_instansi as $data)
                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label><span class="kt-font-dark">Instansi/Perusahaan:</span></label>
                            <select class="form-control kt-select2" id="instansi" name="instansi">
                            <option></option>
                              @foreach($instansi as $data)
                                <option value="{{ $data->id }}">{{ $data->nama_lengkap }}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label><span class="kt-font-dark">Jabatan:</span></label>
                            <select class="form-control kt-select2" id="jabatan" name="jabatan">
                            <option></option>
                              @foreach($jabatan as $data)
                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                              @endforeach
                            </select>
                        </div>
                  </div>
                  <div class="form-group row">
                      <div class="col-lg-4">
                          <label><span class="kt-font-dark">Kategori Jabatan:</span></label>
                          <select class="form-control kt-select2" id="kategori" name="kategori">
                              <option></option>
                              @foreach($kategori_jabatan as $data)
                                  <option value="{{ $data->id }}">{{ $data->nama }}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-lg-4">
                          <label><span class="kt-font-dark">Klaster Perusahaan:</span></label>
                          <select class="form-control kt-select2" id="klaster" name="klaster">
                              <option></option>
                              @foreach($klaster as $data)
                                  <option value="{{ $data->id }}">{{ $data->nama }}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-lg-4">
                          <label><span class="kt-font-dark">Perusahaan:</span></label>
                          <select class="form-control kt-select2" id="perusahaan" name="perusahaan">
                              <option></option>
                              @foreach($perusahaans as $perusahaan)
                                  <option value="{{ $perusahaan->id }}">{{ $perusahaan->nama_lengkap }}</option>
                              @endforeach
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
                    <th width="3%">No.</th>
                    <th >Nama Lengkap</th>
                    <th >Status Talent</th>
                    <th >Jabatan</th>
                    <th >Status Pengisian</th>
                    <th >Kategori Jabatan</th>
                    <th width="18%"><div align="center">Aksi</div></th>
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
      var urlcreate = "{{route('cv.board.create')}}";
      var urlimport = "{{route('cv.board.import')}}";
      var urlminicv = "{{route('talenta.register.minicv')}}";
      var urljabatantalent = "{{route('cv.board.jabatantalent')}}";
      var urleditstatus = "{{route('cv.board.editstatus')}}";
      var urlstorestatus = "{{route('cv.board.store_status')}}";
      var urlstoreimport = "{{route('cv.board.store_import')}}";
      var urlstore = "{{route('cv.board.store')}}";
      var urldatatable = "{{route('cv.board.datatable')}}";
      var urldelete = "{{route('cv.board.delete')}}";
      var urldatatalent = "{{route('cv.board.datatalent')}}";
      var urldatajabatantalent = "{{route('cv.board.datajabatantalent')}}";
      var urldatanontalent = "{{route('cv.board.datanontalent')}}";
      var urlchecknik = "{{ route('cv.board.checknik') }}";
      var urllogstatus = "{{route('talenta.register.log_status')}}";
  </script>
  <script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('js/cv/board/index.js')}}"></script>
@endsection
