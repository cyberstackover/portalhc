<form action="{{route('cv.pendidikan.store', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-pendidikan">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$riwayat->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
	<div class="kt-portlet__body">
		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label>Jenjang Pendidikan</label>
                    <select class="form-control kt-select2" name="id_jenjang_pendidikan">
                    <option value=""></option>
                    @foreach($jenjangPendidikan as $data)       
                    	@php
	            	      $select = ($actionform == 'update' && ($data->id == $riwayat->id_jenjang_pendidikan) ? 'selected="selected"' : '')
	            	    @endphp
	                	<option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                    @endforeach
                </select>
            	</div>
			</div>
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Tahun Lulus</label>
					<select class="form-control kt-select2" name="tahun">
	                @for ($i = 1900; $i < now()->year+5; $i++)
	                	@php
	            	      $select = ($actionform == 'update' && ($i == $riwayat->tahun) || ($actionform == 'insert' && (now()->year == $i)) ? 'selected="selected"' : '')
	            	   @endphp
	                <option value="{{ $i }}" {!! $select !!}>{{ $i }}</option>
	                @endfor
              </select>
				</div>
			</div>
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Penjurusan</label>	
					@php
            	      $value = ($actionform == 'update'? $riwayat->penjurusan : '')
            	    @endphp				
					<input type="text" class="form-control penjurusan" name="penjurusan" value="{{ $value }}"/>
				</div>
			</div>
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Perguruan Tinggi</label>
                    <select class="form-control universitas" name="id_universitas" style="width:100%"  onchange="return onChangeUniv(this.value)">
                    <option value=""></option>
                    @foreach($universitas as $data)       
                    	@php
	            	      $select = ($actionform == 'update' && ($data->id == $riwayat->id_universitas) ? 'selected="selected"' : '')
	            	    @endphp
	                	<option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                    @endforeach
                </select>
            	</div>
			</div>
			<div class="col-lg-4">				
				<div class="form-group">
					<label>Negara</label>
					<select class="form-control kt-select2 negara" name="negara" onchange="return onChangeNegara(this.value)">	
						<option value="INDONESIA">INDONESIA</option>
						@foreach($negara as $data)       
	                    	@php
		            	      $select = ($actionform == 'update' && ($data->id == $riwayat->refKota->provinsi_id) ? 'selected="selected"' : '')
		            	    @endphp
		                	<option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
	                    @endforeach
					</select>
				</div>
			</div>
			<div class="col-lg-4">				
				<div class="form-group">
					<label>Provinsi</label>	
					@php
            	      $disabled = ($actionform == 'update' && ($riwayat->refKota->is_luar_negeri) ? 'disabled="true"' : '')
            	    @endphp
					<select class="form-control kt-select2 provinsi" name="provinsi" onchange="return onChangeProvinsi(this.value)" {!! $disabled !!}>
					<option value=""></option>
					@foreach($provinsi as $data)       
                    	@php
	            	      $select = ($actionform == 'update' && ($data->id == $riwayat->refKota->provinsi_id) ? 'selected="selected"' : '')
	            	    @endphp
	                	<option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                    @endforeach
					</select>
				</div>
			</div>
			<div class="col-lg-4">				
				<div class="form-group">
					<label>Kota</label>	
					@php
            	      $disabled = ($actionform == 'update' && ($riwayat->id_kota) ? '' : 'disabled="true"')
            	    @endphp
					<select class="form-control kt-select2 kota" name="id_kota" {!! $disabled !!}>						
                    <option value=""></option>
					@foreach($kota as $data)       
                    	@php
	            	      $select = ($actionform == 'update' && ($data->id == $riwayat->id_kota) ? 'selected="selected"' : '')
	            	    @endphp
	                	<option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                    @endforeach
					</select>
				</div>
			</div>
			<div class="col-lg-12">				
				<div class="form-group">
					<label>Pengharganan yang Didapat</label>
					@php
            	      $value = ($actionform == 'update'? $riwayat->penghargaan : '')
            	    @endphp						
					<input type="text" class="form-control" name="penghargaan" value="{{ $value }}"/>
				</div>
			</div>
		</div>
	</div>
	<div class="kt-portlet__foot">
		<div class="kt-form__actions">
			<div class="row">
				<div class="col-lg-6">
					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript" src="{{asset('js/cv/pendidikan/form-pendidikan.js')}}"></script>
<script type="text/javascript" src="{{asset('js/cv/datepicker.js')}}"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.universitas').select2({
      tags: true,
      placeholder: "Pilih"
	});
	
});
function onChangeProvinsi(id_provinsi){
	if(id_provinsi != ''){
		$(".kota").prop('disabled', false);
		$.ajax({
			url: "/fetch/referensi/getkota?id_provinsi="+id_provinsi,
			type: "POST",
			dataType: "json", 
			success: function(data){
					var contentData = "";
					$(".kota").empty();
					//contentData += "<option value=''>"+data.length+"</option>";
					for(var i = 0, len = data.length; i < len; ++i) {
						contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
					}
					$(".kota").append(contentData);
					$(".kota").trigger("change");
										
			}
		});
	}
}

function onChangeNegara(id_provinsi){
    if(id_provinsi == "INDONESIA"){
    	$(".provinsi").prop('disabled', false);
        $(".kota").empty();
        $.ajax({
	        url: "/fetch/referensi/getallprovinsi",
	        type: "POST",
	        dataType: "json", 
	        success: function(data){
	                 var contentData = "";
	                 $(".provinsi").empty();
	                 //contentData += "<option value=''>"+data.length+"</option>";
	                 for(var i = 0, len = data.length; i < len; ++i) {
	                     contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
	                 }
	                 $(".provinsi").append(contentData);
	                 $(".provinsi").trigger("change");
	                                     
	        }
	    });
    }else if(id_provinsi != ''){
    	$(".provinsi").prop('disabled', true);
        $(".provinsi").empty();
    	$(".kota").prop('disabled', false);
	    $.ajax({
	        url: "/fetch/referensi/getkota?id_provinsi="+id_provinsi,
	        type: "POST",
	        dataType: "json", 
	        success: function(data){
	                 var contentData = "";
	                 $(".kota").empty();
	                 //contentData += "<option value=''>"+data.length+"</option>";
	                 for(var i = 0, len = data.length; i < len; ++i) {
	                     contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
	                 }
	                 $(".kota").append(contentData);
	                 $(".kota").trigger("change");
	                                     
	        }
	    });
    }
}

function onChangeUniv(id_universitas){
	$.ajax({
		url: "/fetch/referensi/getdatauniversitas?id_universitas="+id_universitas,
		type: "POST",
		dataType: "json", 
		success: function(data){
			if(data.kota != ''){
				$(".negara").empty();
				var contentData = "<option value='"+data.id_negara+"'>"+data.negara+"</option>";
				$(".negara").append(contentData);
				$(".negara").trigger("change");

				$(".provinsi").empty();
				var contentData = "<option value='"+data.id_provinsi+"'>"+data.provinsi+"</option>";
				$(".provinsi").append(contentData);
				$(".provinsi").trigger("change");	

				$(".kota").empty();
				var contentData = "<option value='"+data.id_kota+"'>"+data.kota+"</option>";
				$(".kota").append(contentData);
				$(".kota").trigger("change");
			}
		}
	});
}

</script>