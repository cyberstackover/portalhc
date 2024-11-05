@extends('layouts.app')

@section('addbeforecss')

@endsection


<!-- Konten -->
@section('content')

<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon-web"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                {{$nama_perusahaan->nama_lengkap}}
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="/organ/komposisi/index" class="btn btn-brand btn-warning btn-icon-sm">
                        <i class="icon-sm fas fa-backspace"></i>
                        Kembali
                    </a>
                    <a href="javascript:;" type="button" class="btn btn-brand btn-elevate btn-icon-sm cls-add" style="height:34px" title="Tambah Jabatan">
                        <i class="fa fa-btn fa-plus"></i>
                        Tambah Jabatan
                    </a>
                    <input type="hidden" name="perusahaan_id" id="perusahaan_id" readonly="readonly" value="{{$perusahaan_id}}" />
                    <input type="hidden" name="nama_perusahaan" id="nama_perusahaan" readonly="readonly" value="{{$nama_perusahaan->nama_lengkap}}" />
                </div>
            </div>
        </div>
    </div>
    
    <div class="kt-portlet__body">
        <!--begin: Datatable -->
        <div class="table-responsive">

        	<!-- start table -->
        	<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="10%">Grup Jabatan</th>
                        <th width="30%">Nomenklatur</th>
                        <th width="30%">Perubahan Nomenklatur</th>
                        <th width="10%">Jenis Jabatan</th>
                        <th width="15%">Bidang Jabatan</th>
                        <th width="2%">Aktif</th>
                        <th width="2%">urutan</th>
                        <th width="15%">Aksi</th>
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

@endsection


@section('addafterjs')
<script type="text/javascript">
    var urlcreate = "{{route('organ.komposisi.creategrupjabatan')}}";
    var urlstore = "{{route('organ.komposisi.storegrupjabatan')}}";
    var urledit = "{{route('organ.komposisi.editgrupjabatan')}}";
    var urldatatable = "{{route('organ.komposisi.datatable2')}}";
    var urldelete = "{{route('organ.komposisi.delete')}}";
    var urlchangeaktif = "{{route('organ.komposisi.changeaktif')}}";
</script>
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/organ/komposisi/tambah2.js')}}"></script>
@endsection