@extends('layouts.app')

@section('addbeforecss')
<link href="{{asset('assets/vendors/custom/multi-select-master/css/multi-select.dist.css')}}" rel="stylesheet" type="text/css" />
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
                Data Pokok SK Anak/Cucu
            </h3>
        </div>
        
        
    </div>
    <div class="kt-portlet__body form">
    	<form class="kt-form kt-form--label-right" method="post" id="form-datapokokanak" role="form" enctype="multipart/form-data" action="/administrasi/anak/storetambah">
			@csrf
			
			<div class="kt-portlet__body">
				<div class="form-group row">
                    <div class="col-lg-4">
                        <label>BUMN</label>
                        <select class="form-control kt-select2" required="required" id="id_induk" name="id_induk" onchange="return onNamaAnak(this.value)">
                            <option value=""></option>
                            @foreach($induks as $induk)
                            @php
                              $select = !empty(old('id_perusahaan')) && in_array($induk->id, old('id_perusahaan'))? 'selected="selected"' : '';
                           @endphp
                            <option value="{{ $induk->id }}" {!! $select !!}>{{ $induk->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Anak</label>
                        <select class="form-control kt-select2" required="required" id="id_anak" name="id_anak">
                            <option value=""></option>
                            @foreach($anaks as $anak)
                            @php
                              $select = !empty(old('id_perusahaan')) && in_array($anak->id, old('id_perusahaan'))? 'selected="selected"' : '';
                           @endphp
                            <option value="{{ $anak['id'] }}" {!! $select !!}>{{ $anak['nama_lengkap'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Grup Jabatan</label>
                        <select class="form-control kt-select2" required="required" id="grup_jabatan_id" name="grup_jabatan_id" onchange="return onNamaJenisSK(this.value)">
                            <option value=""></option>
                            @foreach($grupjabatans as $grupjabatan)
                            @php
                              $select = !empty(old('id_perusahaan')) && in_array($grupjabatan->id, old('id_perusahaan'))? 'selected="selected"' : '';
                           @endphp
                            <option value="{{ $grupjabatan->id }}" {!! $select !!}>{{ $grupjabatan->nama }}</option>
                            @endforeach
                        </select>           
                    </div>            
                </div>
                <div class="form-group row">
                    <div class="col-lg-4">
                        <label>Nomor SK</label>
                        <input type="text" required="required" class="form-control kt-shape-bg-color-1" name="nomor_sk" id="nomor_sk" value="{{!empty(old('nomor_sk'))? old('nomor_sk') : ''}}" />
                        <span class="form-text text-muted">Masukan Nomor SK</span>
                    </div>
                    <div class="col-lg-4">
                        <label>Tanggal SK</label>
                        <input type="text" id="tgl_sk" name="tgl_sk" required="required" class="form-control kt-shape-bg-color-1 input-sm cls-datepicker" value="{{ old('tgl_sk') }}" />
                        <span class="form-text text-muted">Masukan Tanggal SK</span>
                    </div>
                    <div class="col-lg-4">
                        <label>Tanggal Serah Terima SK</label>
                        <input type="text" id="tanggal_serah_terima" name="tanggal_serah_terima" required="required" class="form-control kt-shape-bg-color-1 input-sm cls-datepicker" value="{{ old('tanggal_serah_terima') }}" />
                        <span class="form-text text-muted">Masukan Tanggal Serah terima SK</span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-6">
                        <label>Jenis SK</label>
                        <select class="form-control multi-select" id="jenis_sk_id" name="jenis_sk_id[]" multiple="multiple">
                            <option value=""></option>
                            @foreach($jenissks as $jenissk)
                            @php
                              $select = !empty(old('id_perusahaan')) && in_array($jenissk->id, old('id_perusahaan'))? 'selected="selected"' : '';
                           @endphp
                            <option value="{{ $jenissk->id }}" {!! $select !!}>{{ $jenissk->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label>File SK</label>
                        <input type="file" required="required" name="file_name" class="form-control kt-shape-bg-color-1" id="file_name" value="{{ old('file_name') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control kt-shape-bg-color-1" id="keterangan" name="keterangan" rows="5" cols="5">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
			</div>
			<div class="kt-portlet__foot">
				<div class="kt-form__actions">
					<div class="row">
						<div class="col-lg-6">
                            <a href="/administrasi/anak/index" class="btn btn-brand btn-warning btn-icon-sm">
                                <i class="icon-xl fas fa-backspace"></i>
                                Kembali
                            </a>
							<button type="submit" class="btn btn-primary">Lanjut</button>
						</div>
					</div>
				</div>
			</div>
		</form>
    </div>

        
</div>

@endsection


@section('addafterjs')
<script src="{{asset('assets/vendors/custom/multi-select-master/js/jquery.multi-select.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/custom/quicksearch-master/jquery.quicksearch.js')}}" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
    /*$('.kt-select2').select2({
        placeholder: "Pilih"
    });*/
    onReadEvent();

    $('.kt-select2').select2({
      tags: true,
      placeholder: "Pilih"
    });

    /*$('.kt-select2').on("select2:select", function (evt) {
      var element = evt.params.data.element;
      var $element = $(element);
      
      $element.detach();
      $(this).append($element);
      $(this).trigger("change");
    });*/

    var pickerOptsGeneral = {
      orientation: "left",
      autoclose: true,
      format: 'dd/mm/yyyy'
    };

    $('.cls-datepicker').datepicker(pickerOptsGeneral);
    
    $('#tgl_sk').datepicker(pickerOptsGeneral).on('changeDate', function(ev){
        var startDate = new Date(ev.date);
        startDate.setDate(startDate.getDate());
        $('#tanggal_serah_terima').datepicker('setDate', startDate);
    });

    $('#tanggal_serah_terima').datepicker(pickerOptsGeneral);

    var validatorMainForm = function(context){
        var form = $(context);
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
    
        form.validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: false,
            ignore: "",
            rules: {
            },
    
            invalidHandler: function (event, validator) {              
                success.hide();
                error.show();
                Metronic.scrollTo(error, -200);
            },
    
            errorPlacement: function (error, element) {
                if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.hasClass("fileinput")) { 
                    error.insertAfter(element.closest("div.fileinput"));
                } else if (element.attr("data-error-container")) { 
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').size() > 0) { 
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').size() > 0) { 
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').size() > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) { 
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
    
            highlight: function (element) {
                var icon = $(element).parent('.input-icon').children('i');
                icon.removeClass('fa-check').addClass("fa-warning");
                $(element).addClass('edited');
                $(element)
                    .closest('.form-group').removeClass("has-success").addClass('has-error');   
            },
    
            unhighlight: function (element) {
                $(element)
                    .closest('.form-group').removeClass("has-error").addClass('has-success');
            },
    
            success: function (label, element) {
                var icon = $(element).parent('.input-icon').children('i');
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                icon.removeClass("fa-warning").addClass("fa-check");
                $(element).removeClass('edited');
            },
    
            submitHandler: function (form) {
                error.hide();
                form.submit();
            }
        }); 
    };
    validatorMainForm($("#newDataForm"));

});

function onNamaJenisSK(grup_jabatan_id){
    $.ajax({
        url: "/administrasi/bumn/getjenissk?grup_jabatan_id="+grup_jabatan_id,
        type: "POST",
        dataType: "json", 
        success: function(data){
                 var contentData = "";
                 $("#jenis_sk_id").empty();
                 //contentData += "<option value=''>"+data.length+"</option>";
                 for(var i = 0, len = data.length; i < len; ++i) {
                     contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
                 }
                 $("#jenis_sk_id").append(contentData);
                 $('#jenis_sk_id').multiSelect('refresh');
                                     
        }
    });
}

function onNamaAnak(id_induk){
    $.ajax({
        url: "/administrasi/anak/getanak?id_induk="+id_induk,
        type: "POST",
        dataType: "json", 
        success: function(data){
                 var contentData = "";
                 $("#id_anak").empty();
                 //contentData += "<option value=''>"+data.length+"</option>";
                 for(var i = 0, len = data.length; i < len; ++i) {
                     contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
                 }
                 $("#id_anak").append(contentData);
                 $("#id_anak").trigger("change");
                                     
        }
    });
}

function onReadEvent(){
  onLoadMultiSelect();
}

function onLoadMultiSelect(){
  $('.multi-select').multiSelect({
    selectableOptgroup: false, 
      selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Cari ...'>",
      selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Cari ...'>",
      afterInit: function (ms) {
          var that = this,
              $selectableSearch = that.$selectableUl.prev(),
              $selectionSearch = that.$selectionUl.prev(),
              selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
              selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

          that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
              .on('keydown', function (e) {
                  if (e.which === 40) {
                      that.$selectableUl.focus();
                      return false;
                  }
              });

          that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
              .on('keydown', function (e) {
                  if (e.which == 40) {
                      that.$selectionUl.focus();
                      return false;
                  }
              });
      },
      afterSelect: function () {
          this.qs1.cache();
          this.qs2.cache();
      },
      afterDeselect: function () {
          this.qs1.cache();
          this.qs2.cache();
      }
  });  
}
</script>
@endsection