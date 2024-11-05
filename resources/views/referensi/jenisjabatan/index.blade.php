@extends('layouts.app')


@section('addbeforecss')
<link href="{{asset('assets/global/plugins/jquery-treegrid-master/css/jquery.treegrid.css')}}" rel="stylesheet" type="text/css" />
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
                    <a href="javascript:;" class="btn btn-brand btn-elevate btn-icon-sm cls-add">
                        <i class="la la-plus"></i>
                        Tambah Jenis Jabatan
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
        @php $no=1; @endphp
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover tree">
                <thead>
                    <tr>
                        <th style="width: 30%;">Nama</th>
                        <th style="width: 10%;">Prosentase</th>
                        <th style="width: 15%;">Sumber Pengali</th>
                        <th style="width: 25%;">Grup jabatan</th>
                        <th style="width: 5%;">Urutan</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                 @foreach ($jenisjabatans as $jenisjabatan)

                    <tr data-count="2">
                        <td>{{$jenisjabatan->nama}}</td>
                        <td>{{$jenisjabatan->prosentase_gaji}}</td>
                        <td>@if($jenisjabatan->induk_pengali) {{$jenisjabatan->induk_pengali->nama}} @endif</td>
                        <td>{{$jenisjabatan->grup_jabatan->nama}}</td>
                        <td>{{$jenisjabatan->urut}}</td>
                        <td>
                          <div align="center">
                            <button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="{{$jenisjabatan->id}}" data-toggle="tooltip" data-original-title="Ubah data Jenis Jabatan {{$jenisjabatan->nama}}"><i class="flaticon-edit"></i></button>
                            &nbsp;
                            <button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="{{$jenisjabatan->id}}" data-jenisjabatan="{{$jenisjabatan->nama}}" data-toggle="tooltip" data-original-title="Hapus data Jenis Jabatan {{$jenisjabatan->nama}}"><i class="flaticon-delete"></i></button>
                          </div>
                        </td>
                    </tr>
                    @php $no++; @endphp
                 @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('addafterjs')
  <script type="text/javascript">
      var urlcreate = "{{route('referensi.jenisjabatan.create')}}";
      var urledit = "{{route('referensi.jenisjabatan.edit')}}";
      var urlstore = "{{route('referensi.jenisjabatan.store')}}";
      var urldatatable = "{{route('referensi.jenisjabatan.datatable')}}";
      var urldelete = "{{route('referensi.jenisjabatan.delete')}}";
  </script>
  <script type="text/javascript" src="{{asset('assets/global/plugins/jquery-treegrid-master/js/jquery.treegrid.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/referensi/jenisjabatan/index.js')}}"></script>
@endsection