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
                    @can('talent-create')
                    <a href="javascript:;" class="btn btn-primary btn-elevate btn-icon-sm cls-add">
                        <i class="la la-plus"></i>
                        Tambah Talenta
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    
    <div class="kt-portlet__body">

        <div class="alert alert-custom fade show mb-5" style="background-color:yellow;" role="alert">
            <div class="alert-text petunjuk"><b>Petunjuk :</b><br>
                Untuk menambahkan talent, search talent kemudian klik button <font style="color:red;"> Register </font>. Jika talent tidak ditemukan, klik <font style="color:red;">Tambah Talenta</font> untuk input data talent baru.
            </div>
            <div class="alert-close">
                <button style="margin-top:-50px;" type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="flaticon2-delete" style="font-size:x-small;"></i></span>
                </button>
            </div>
        </div>

      <!-- end pencarian -->

      <div class="kt-portlet kt-shape-bg-color-2">
            <form class="kt-form kt-form--label-right" >
                <div class="kt-portlet__body">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label><span class="kt-font-dark">Search Talent:</span></label>
                            
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap">
                            <!-- <select class="form-control kt-select2 talenta" id="nama_lengkap" name="nama_lengkap">
                                <option></option>
                                @foreach($talenta as $data)
                                <option value="{{ $data->nama_lengkap }}">{{ $data->nama_lengkap }}</option>
                                @endforeach
                            </select> -->
                        </div>
                        <div class="col-lg-6">
                            <label><span class="kt-font-dark">Status:</span></label>
                            <select class="form-control kt-select2" id="id_status_talenta" name="id_status_talenta">
                                <option></option>
                                @foreach($status_talenta as $data)
                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 pull-right">
                            <div class="">
                                <a id="cari" type="button" class="btn btn-danger" href="javascript:;" >cari</a>
                                <a id="reset" type="button" class="btn btn-warning" href="javascript:;" >reset</a>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </form>
        </div>

        <ul class="nav nav-tabs nav-tabs mb-5">
            <li class="nav-item">
                <a href="#datatalent" class="nav-link active btn-talent" data-toggle="tab"> Data Talent </a>
            </li>
            <li class="nav-item">
                <a href="#selected" class="nav-link btn-register" data-toggle="tab"> Register 
                    <label id="jumlah" class="kt-badge kt-badge--danger kt-badge--pill kt-badge--rounded">{{$jumlah}}</label>
                </a>
            </li>
        </ul>
        

        <div class="tab-content">
            <!-- Data Talent -->
            <div class="tab-pane active" role="tabpanel" id="datatalent">
                <!--begin: Datatable -->
                <div class="table-responsive">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th >Nama Lengkap</th>
                                <th >Status Talent</th>
                                <th >Jabatan</th>
                                <th >Status Pengisian</th>
                                <th ><div align="center">Aksi</div></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!--end: Datatable -->
            </div>
            <!-- end Data Talent -->

            <!-- Register -->
            <div class="tab-pane" role="tabpanel" id="selected">
                <!--begin: Datatable -->
                <div class="table-responsive">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatableselected">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th >Nama Lengkap</th>
                                <th >Status Talent</th>
                                <th >Jabatan</th>
                                <th >Status Pengisian</th>
                                <th ><div align="center">Aksi</div></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!--end: Datatable -->
            
                <div class="col-lg-12">
                    <input type="hidden" name="checked_list" class="checked_list">
                    <input type="hidden" name="reject_list" class="reject_list">
                    <button class="btn btn-success pull-right btn-submit" style="margin-top:40px;">Submit Talent</button>
                </div>
            </div>
            <!-- end Register -->
        </div>

    </div>
</div>
@endsection

@section('addafterjs')
  <script type="text/javascript">
        var urlcreate = "{{route('talenta.register.create')}}";
        var urlminicv = "{{route('talenta.register.minicv')}}";
        var urllogstatus = "{{route('talenta.register.log_status')}}";
        var urlstoreimport = "{{route('talenta.register.store_import')}}";
        var urlstore = "{{route('talenta.register.store')}}";
        var urldatatable = "{{route('talenta.register.datatable')}}";
        var urldatatableselected = "{{route('talenta.selected.datatable')}}";
        var urldelete = "{{route('talenta.register.delete')}}";
        var urlselected = "{{route('talenta.register.selected')}}";
        var urlcancelselected = "{{route('talenta.register.cancel_selected')}}";
        var urlsubmittalenta = "{{route('talenta.register.update_talenta')}}";
  </script>
  <script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('js/talenta/register/index.js')}}"></script>
@endsection
