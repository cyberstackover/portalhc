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
                </div>
            </div>
        </div>
    </div>

    <div class="kt-portlet__body">
      <!-- start pencarian -->
      <div class="kt-portlet kt-shape-bg-color-2" data-ktportlet="true" id="kt_portlet_tools_6">
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
          
            <form class="kt-form kt-form--label-right" id="form" kt-hidden-height="500">
                <div class="kt-portlet__body">
                    <div class="row global-search">
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <label><span class="kt-font-dark">Status Talenta:</span></label>
                                    <select multiple="" class="form-control kt-select2" name="status_talenta" id="status_talenta">
                                        <option></option>
                                        <option value="All">Semua Status</option>
                                        @foreach($status_talenta as $data)
                                            <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label><span class="kt-font-dark">Cluster:</span></label>
                                    <select class="form-control kt-select2" name="cluster" id="cluster" onchange="return onChangeBumn(this.value)">
                                        <option></option>
                                        <option value="All">Semua Cluster</option>
                                        @foreach($cluster as $data)
                                            <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label><span class="kt-font-dark">Kelas:</span></label>
                                    <select class="form-control kt-select2" name="kelas" id="kelas" onchange="return onChangeBumn(this.value)">
                                        <option></option>
                                        <option value="All">Semua Kelas</option>
                                        @foreach($kelas as $data)
                                            <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label><span class="kt-font-dark">BUMN:</span></label>
                                    <select class="form-control kt-select2" name="perusahaan" id="perusahaan">
                                        <option></option>
                                        <option value="All">Semua BUMN</option>
                                        @foreach($perusahaan as $data)
                                            <option value="{{ $data->id }}">{{ $data->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group row" style="background-color:#ced6f0;height:2px;"> 
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: -25px;">
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <div class="col-lg-2">
                                    <label><span class="kt-font-dark">Filter:</span></label>
                                </div>
                                <div class="col-lg-2">
                                    <label><span class="kt-font-dark">Opsi:</span></label>
                                </div>
                                <div class="col-lg-3">
                                    <label><span class="kt-font-dark">Nilai:</span></label>
                                </div>
                                <div class="col-lg-2">
                                    <label><span class="kt-font-dark">Operator:</span></label>
                                </div>
                                <div class="col-lg-2">
                                    <label><span class="kt-font-dark">Sorting:</span></label>
                                </div>
                                <div class="col-lg-1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="search append-search">
                        <div class="row search-box search-dynamic search-master">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <!-- <div class="col-lg-2 menu menu-ke-1 div-required">
                                        <select class="form-control kt-select2" name="menu[]" onchange="return onChangeMenu(this.value,1)">
                                            <option></option>
                                        </select>
                                    </div> -->
                                    <div class="col-lg-2 submenu submenu-ke-1 div-required">
                                        <select class="form-control kt-select2" name="submenu[]" onchange="return onChangeSubMenu(this.value,1)">
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 opsi opsi-ke-1 div-required">
                                        <select class="form-control kt-select2" name="opsi[]">
                                        </select>
                                    </div>
                                    <div class="col-lg-3 nilai nilai-ke-1 div-required">
                                        <input type="text" name="nilai[]" class="form-control">
                                    </div>
                                    <div class="col-lg-2 operator operator-ke-1 div-required">
                                        <select class="form-control kt-select2" name="operator[]">
                                            <option>and</option>
                                            <option>or</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 sorting sorting-ke-1">
                                        <select class="form-control kt-select2 select-sorting" name="sorting[]" id="sorting" data-id='1'>
                                            <option></option>
                                            <option value="asc">Ascending</option>
                                            <option value="desc">Descending</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-1 delete delete-ke-1">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 pull-right">
                            <div class="">
                                <input type="hidden" name="checked_list" class="checked_list">
                                <a id="tambah" type="button" class="btn btn-success" href="javascript:;" >tambah filter</a>
                                <a id="cari" type="button" class="btn btn-danger" href="javascript:;" >cari</a>
                                <a id="reset" type="button" class="btn btn-warning" href="javascript:;" >reset</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="alert alert-custom fade show mb-5 div-query" style="background-color:yellow;display:none;" role="alert">
            <div class="alert-text query"><b>Search :</b><br>
                Untuk menambahkan talent, search talent kemudian klik button <font style="color:red;"> Register </font>. Jika talent tidak ditemukan, klik <font style="color:red;">Tambah Talenta</font> untuk input data talent baru.
            </div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="flaticon2-delete" style="font-size:x-small;"></i></span>
                </button>
            </div>
        </div>

        <div>
            <div class="form-group row btn-header" style="display:none;margin-bottom:-15px;margin-top:-20px;">
                <div class="col-lg-12">
                    <a id="export" type="button" style="float:right;" class="btn btn-primary btn-elevate btn-icon-sm" href="javascript:;" >
                        <i class="far fa-file-excel"></i>
                        Export Excel
                    </a>
                </div>
            </div>
            <div class="form-group row hasil-search" style="display:none;">
                <div class="col-lg-12">
                    <!--begin: Datatable -->
                    <div class="table-responsive">
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                            <thead>
                                <tr id="datatable_th">
                                    <th ><div align="center"></div></th>
                                    <th width="5%">No.</th>
                                    <th >Nama Lengkap</th>
                                    <th >BUMN</th>
                                    <th >Jabatan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end: Datatable -->

            <div class="form-group row btn-footer" style="display:none;">
                <div class="col-lg-12">
                    <a id="compare" type="button" style="float:left;margin-top:-20px;" class="btn btn-success" href="javascript:;" >compare</a>
                </div>
            </div>
        </div>
    

    </div>
</div>
@endsection

@section('addafterjs')
  <script type="text/javascript">
        var urlminicv = "{{route('talenta.register.minicv')}}";
        var urllogstatus = "{{route('talenta.register.log_status')}}";
        var urldatatable = "{{route('talenta.dynamic_search.datatable')}}";
        var urlexport = "{{route('talenta.dynamic_search.export')}}";
        var urlcompare = "{{route('talenta.dynamic_search.compare')}}";
  </script>
  <script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('js/talenta/dynamic_search/index.js')}}"></script>
@endsection
