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
                  Cluster BUMN
              </h3>
          </div>
      </div>
      <div class="kt-portlet__body form">
        <form class="kt-form kt-form--label-right" method="post" id="form-datapokok" role="form" enctype="multipart/form-data" action="/cv/cluster/{{ $talenta->id }}/update">
        @csrf
        {{ method_field('POST') }}
        <div class="kt-portlet__body">
          <div class="form-group row">
            <div class="col-lg-12">
              <div class="form-group">
                <label><b>Cluster BUMN</b></label>
              </div>
            </div>
              @foreach($cluster as $data)  
                @if($count == 0)
                  <div class="col-lg-5">
                    <div class="form-group">
                      <div class="checkbox-list">
                @endif
                      <label class="checkbox"> 
                      @if($trans_cluster->contains('id_cluster', $data->id))
                        <input type="checkbox" onclick='saveClick();' checked name="cluster" value="{{$data->id}}">     
                      @else
                        <input type="checkbox" onclick='saveClick();' name="cluster" value="{{$data->id}}">   
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
          </div>       
        </form>
      </div>        
  </div>
@endsection

@section('addafterjs')
<script type="text/javascript">
var urlstore = "{{route('cv.cluster.update', ['id_talenta' => $talenta->id])}}";

function saveClick() {
  var array = $.map($('input[name="cluster"]:checked'), function(c){return c.value; })
  console.log(array);
  $.ajax({
     type: 'post',
     url: urlstore,
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