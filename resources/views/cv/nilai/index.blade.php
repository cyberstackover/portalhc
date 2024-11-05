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
                  Nilai, Visi dan Interest
              </h3>
          </div>
      </div>
      <div class="kt-portlet__body form">
        <form class="kt-form kt-form--label-right" method="post" id="form-datapokok" role="form" enctype="multipart/form-data" action="/cv/nilai/{{ $talenta->id }}/update">
        @csrf
        {{ method_field('POST') }}
        <div class="kt-portlet__body">
          <div class="form-group row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Uraian Nilai dan Visi Pribadi</label>
                  <textarea id="summernote" class="form-control" rows="10" name="nilai">{{ @$talenta->pribadi->nilai }}</textarea>
                </div>        
              </div>  
              <div class="col-lg-12">
                <div class="form-group">
                  <label><b>Interest</b></label>
                  <textarea id="summernote1" class="form-control" rows="3" name="ekonomi">{{ @$talenta->interest->interest }}</textarea>
                </div>        
              </div>  
          </div>                    
        </div>
        <div class="kt-portlet__foot">
          <div class="kt-form__actions">
            <div class="row">
              <div class="col-lg-12">
                <button type="submit" class="btn btn-success" style=" float: right;">Simpan</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      </div>        
  </div>
</div>
@endsection

@section('addafterjs')
  <script type="text/javascript">
    $(document).ready(function() {
      $('#summernote').summernote({
        placeholder: 'Isi Uraian Nilai dan Visi Pribadi',
        tabsize: 2,
        height: 250
      });
      $('#summernote1').summernote({
        placeholder: 'Isi Uraian Interest',
        tabsize: 2,
        height: 250
      });
      $('#summernote2').summernote({
        placeholder: 'Isi Uraian Interest',
        tabsize: 2,
        height: 250
      });
      $('#summernote3').summernote({
        placeholder: 'Isi Uraian Interest',
        tabsize: 2,
        height: 250
      });
    });
  </script>
  <!-- include summernote css/js -->
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
@endsection