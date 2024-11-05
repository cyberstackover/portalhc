<?php
  $count = 0;
?> 
@extends('layouts.app')
@section('content')
<div class="col-lg-2">
    @include('cv.tab')
</div>
<div class="col-lg-10">
  <div class="kt-portlet kt-portlet--mobile">
      <div class="kt-portlet__head kt-portlet__head--lg">
          <div class="kt-portlet__head-label">
              <span class="kt-portlet__head-icon">
                  <i class="kt-font-brand flaticon-web"></i>
              </span>
              <h3 class="kt-portlet__head-title">
                  Kelas BUMN
              </h3>
          </div>
      </div>
      <div class="kt-portlet__body form">
        <form class="kt-form kt-form--label-right" method="post" id="form-datapokok" role="form" enctype="multipart/form-data" action="/cv/kelas/{{ $talenta->id }}/update">
        @csrf
        {{ method_field('POST') }}
          <div class="form-group row">
              @foreach($kelas as $data)  
                @if($count == 0)
                  <div class="col-lg-2">
                    <div class="form-group">
                      <div class="checkbox-list">
                @endif
                      <label class="checkbox"> 
                      @if($trans_kelas->contains('id_kelas', $data->id))
                        <input type="checkbox" onclick='saveClick(this);' checked name="kelas" value="{{$data->id}}">     
                      @else
                        <input type="checkbox" onclick='saveClick(this);' name="kelas" value="{{$data->id}}">   
                      @endif
                      <span></span>{{ $data->nama }}</label>
                @if($count == 0)
                      </div>
                    </div>
                  </div>   
                @endif
                <?php
                  $count++;
                  if($count == 1){
                    $count = 0;
                  }
                ?> 
              @endforeach                     
            </div>      
        </form>
      </div>        
  </div>
  
  <div class="kt-portlet kt-portlet--mobile">
      <div class="kt-portlet__head kt-portlet__head--lg">
          <div class="kt-portlet__head-label">
              <span class="kt-portlet__head-icon">
                  <i class="kt-font-brand flaticon-web"></i>
              </span>
              <h3 class="kt-portlet__head-title">
                  Sektor/Klaster Perusahaan
              </h3>
          </div>
      </div>
      <div class="kt-portlet__body form">
        <form class="kt-form kt-form--label-right" method="post" id="form-datapokok" role="form" enctype="multipart/form-data" action="/cv/cluster/{{ $talenta->id }}/update">
        @csrf
        {{ method_field('POST') }}
          <div class="form-group row">
              @foreach($cluster as $data)  
                @if($count == 0)
                  <div class="col-lg-4">
                    <div class="form-group">
                      <div class="checkbox-list">
                @endif
                      <label class="checkbox"> 
                      @if($trans_cluster->contains('id_cluster', $data->id))
                        <input type="checkbox" onclick='saveClickCluster(this);' checked name="cluster" value="{{$data->id}}">     
                      @else
                        <input type="checkbox" onclick='saveClickCluster(this);' name="cluster" value="{{$data->id}}">   
                      @endif
                      <span></span>{{ $data->nama }}</label>
                @if($count == 0)
                      </div>
                    </div>
                  </div>   
                @endif
                <?php
                  $count++;
                  if($count == 1){
                    $count = 0;
                  }
                ?> 
              @endforeach                     
            </div>     
        </form>
      </div>        
  </div>
  
  <div class="kt-portlet kt-portlet--mobile">
      <div class="kt-portlet__head kt-portlet__head--lg">
          <div class="kt-portlet__head-label">
              <span class="kt-portlet__head-icon">
                  <i class="kt-font-brand flaticon-web"></i>
              </span>
              <h3 class="kt-portlet__head-title">
                  Keahlian Bidang/Fungsi
              </h3>
          </div>
      </div>
      <div class="kt-portlet__body form">
        <form class="kt-form kt-form--label-right" method="post" id="form-datapokok" role="form" enctype="multipart/form-data" action="/cv/keahlian/{{ $talenta->id }}/update">
        @csrf
        {{ method_field('POST') }}
          <div class="form-group row">
              @foreach($keahlian as $data)  
                @if($count == 0)
                  <div class="col-lg-5">
                    <div class="form-group">
                      <div class="checkbox-list">
                @endif
                      <label class="checkbox"> 
                      @if($trans_keahlian->contains('id_keahlian', $data->id))
                        <input type="checkbox" onclick='saveClickkeahlian(this);' checked name="keahlian" value="{{$data->id}}">     
                      @else
                        <input type="checkbox" onclick='saveClickkeahlian(this);' name="keahlian" value="{{$data->id}}">   
                      @endif
                      <span></span>{{ $data->jenis_keahlian }}</label>
                @if($count == 0)
                      </div>
                    </div>
                  </div>   
                @endif
                <?php
                  $count++;
                  if($count == 1){
                    $count = 0;
                  }
                ?> 
              @endforeach                     
            </div>      
        </form>
      </div>        
  </div>
</div>
@endsection

@section('addafterjs')
<script type="text/javascript">
var urlstore = "{{route('cv.kelas.update', ['id_talenta' => $talenta->id])}}";
var urlstorecluster = "{{route('cv.cluster.update', ['id_talenta' => $talenta->id])}}";
var urlstorekeahlian = "{{route('cv.keahlian.update', ['id_talenta' => $talenta->id])}}";

function saveClick(element) {
  var array = $.map($('input[name="kelas"]:checked'), function(c){return c.value; })
  console.log(array);
  $.ajax({
     type: 'post',
     url: urlstore,
     data: {kelas:array},
     dataType : 'json',
     beforeSend: function(){
        KTApp.block('.kt-form', {
            overlayColor: '#000000',
            type: 'v2',
            state: 'primary',
            message: 'Sedang proses, silahkan tunggu ...'
        });
      },
     success: function(data){
       if(data.flag == 'success') {
         KTApp.unblock('.kt-form');
       }else{
          KTApp.unblock('.kt-form');
          $(element).prop('checked', false);
         
         swal.fire({
                    title: data.title,
                    html: data.msg,
                    type: data.flag,

                    buttonsStyling: false,

                    confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                    confirmButtonClass: "btn btn-default"
               });
       }
     },
     error: function(jqXHR, exception){
       KTApp.unblock('.kt-form');
       var msgerror = '';
       if (jqXHR.status === 0) {
           msgerror = 'jaringan tidak terkoneksi.';
       } else if (jqXHR.status == 404) {
           msgerror = 'Halaman tidak ditemukan. [404]';
       } else if (jqXHR.status == 500) {
           msgerror = 'Internal Server Error [500].';
       } else if (exception === 'parsererror') {
           msgerror = 'Requested JSON parse gagal.';
       } else if (exception === 'timeout') {
           msgerror = 'RTO.';
       } else if (exception === 'abort') {
           msgerror = 'Gagal request ajax.';
       } else {
           msgerror = 'Error.\n' + jqXHR.responseText;
       }
       swal.fire({
            title: "Error System",
            html: msgerror+', coba ulangi kembali !!!',
            type: 'error',

            buttonsStyling: false,

            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
            confirmButtonClass: "btn btn-default"
       });                                 
     }
 });
}

function saveClickCluster(element) {
  var array = $.map($('input[name="cluster"]:checked'), function(c){return c.value; })
  console.log(array);
  $.ajax({
     type: 'post',
     url: urlstorecluster,
     data: {cluster:array},
     dataType : 'json',
     beforeSend: function(){
        KTApp.block('.kt-form', {
            overlayColor: '#000000',
            type: 'v2',
            state: 'primary',
            message: 'Sedang proses, silahkan tunggu ...'
        });
      },
     success: function(data){
       if(data.flag == 'success') {
         KTApp.unblock('.kt-form');
       }else{
          KTApp.unblock('.kt-form');
          $(element).prop('checked', false);
         
         swal.fire({
                    title: data.title,
                    html: data.msg,
                    type: data.flag,

                    buttonsStyling: false,

                    confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                    confirmButtonClass: "btn btn-default"
               });
       }
     },
     error: function(jqXHR, exception){
       KTApp.unblock('.kt-form');
       var msgerror = '';
       if (jqXHR.status === 0) {
           msgerror = 'jaringan tidak terkoneksi.';
       } else if (jqXHR.status == 404) {
           msgerror = 'Halaman tidak ditemukan. [404]';
       } else if (jqXHR.status == 500) {
           msgerror = 'Internal Server Error [500].';
       } else if (exception === 'parsererror') {
           msgerror = 'Requested JSON parse gagal.';
       } else if (exception === 'timeout') {
           msgerror = 'RTO.';
       } else if (exception === 'abort') {
           msgerror = 'Gagal request ajax.';
       } else {
           msgerror = 'Error.\n' + jqXHR.responseText;
       }
       swal.fire({
            title: "Error System",
            html: msgerror+', coba ulangi kembali !!!',
            type: 'error',

            buttonsStyling: false,

            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
            confirmButtonClass: "btn btn-default"
       });                                 
     }
 });
}

function saveClickkeahlian(element) {
  var array = $.map($('input[name="keahlian"]:checked'), function(c){return c.value; })
  console.log(array);
  $.ajax({
     type: 'post',
     url: urlstorekeahlian,
     data: {keahlian:array},
     dataType : 'json',
     beforeSend: function(){
        KTApp.block('.kt-form', {
            overlayColor: '#000000',
            type: 'v2',
            state: 'primary',
            message: 'Sedang proses, silahkan tunggu ...'
        });
      },
     success: function(data){
       if(data.flag == 'success') {
         KTApp.unblock('.kt-form');
       }else{
          KTApp.unblock('.kt-form');
          $(element).prop('checked', false);
         
         swal.fire({
                    title: data.title,
                    html: data.msg,
                    type: data.flag,

                    buttonsStyling: false,

                    confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                    confirmButtonClass: "btn btn-default"
               });
       }
     },
     error: function(jqXHR, exception){
       KTApp.unblock('.kt-form');
       var msgerror = '';
       if (jqXHR.status === 0) {
           msgerror = 'jaringan tidak terkoneksi.';
       } else if (jqXHR.status == 404) {
           msgerror = 'Halaman tidak ditemukan. [404]';
       } else if (jqXHR.status == 500) {
           msgerror = 'Internal Server Error [500].';
       } else if (exception === 'parsererror') {
           msgerror = 'Requested JSON parse gagal.';
       } else if (exception === 'timeout') {
           msgerror = 'RTO.';
       } else if (exception === 'abort') {
           msgerror = 'Gagal request ajax.';
       } else {
           msgerror = 'Error.\n' + jqXHR.responseText;
       }
       swal.fire({
            title: "Error System",
            html: msgerror+', coba ulangi kembali !!!',
            type: 'error',

            buttonsStyling: false,

            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
            confirmButtonClass: "btn btn-default"
       });                                 
     }
 });
}
</script>
@endsection