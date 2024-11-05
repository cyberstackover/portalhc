@extends('layouts.app')

@section('addbeforecss')
<link href="{{asset('assets/global/plugins/jquery-treegrid-master/css/jquery.treegrid.css')}}" rel="stylesheet" type="text/css" />
@endsection


<!-- Konten -->
@section('content')


<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon2-pen"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                {{$nama_perusahaan->nama_lengkap}}
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="/organ/komposisi/index" class="btn btn-brand btn-warning btn-icon-sm">
                        <i class="icon-xl fas fa-backspace"></i>
                        Kembali
                    </a>
                    <input type="hidden" name="perusahaan_id" id="perusahaan_id" readonly="readonly" value="{{$perusahaan_id}}" />
                    <input type="hidden" name="nama_perusahaan" id="nama_perusahaan" readonly="readonly" value="{{$nama_perusahaan->nama_lengkap}}" />
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
        
        <div id="divresultdireksi"></div>
        <div class="clearfix"></div>
    </div>

        
</div>

@endsection


@section('addafterjs')
<script type="text/javascript">
    var urlcreategrupjabatan = "{{route('organ.komposisi.creategrupjabatan')}}";
    var urlcreateanak = "{{route('organ.komposisi.createanak')}}";
    var urlstoregrupjabatan = "{{route('organ.komposisi.storegrupjabatan')}}";
    var urlstoreanak = "{{route('organ.komposisi.storeanak')}}";
    var urldeleteanak = "{{route('organ.komposisi.deleteanak')}}";
    var urleditanak = "{{route('organ.komposisi.editanak')}}";
    var urldeleteall = "{{route('organ.komposisi.deleteall')}}";
    var urlchangeaktif = "{{route('organ.komposisi.changeaktif')}}";
</script>

<script type="text/javascript" src="{{asset('assets/global/plugins/jquery-treegrid-master/js/jquery.treegrid.js')}}"></script>
<script type="text/javascript" src="{{asset('js/organ/komposisi/tambah.js')}}"></script>

@endsection