<form action="{{route('cv.keluarga.store', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-first">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$riwayat->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
	<div class="kt-portlet__body">
		<div class="row">
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Nama</label>	
					@php
            	      $value = ($actionform == 'update'? $riwayat->nama : '')
            	    @endphp				
					<input type="text" class="form-control" name="nama" value="{{ $value }}"/>
				</div>
			</div>
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Hubungan Keluarga</label>		
					<select class="form-control kt-select2" name="hubungan_keluarga">
		                @if($actionform == 'update' && $riwayat->hubungan_keluarga == "Suami")
			                <option value="Istri">Istri</option>
			                <option value="Suami" selected="selected">Suami</option>
		                @else
		                	<option value="Istri" selected="selected">Istri</option>
			                <option value="Suami">Suami</option>
		                @endif
		            </select>
				</div>
			</div>			
			<div class="col-lg-4">				
				<div class="form-group">
					<label>Negara</label>
					<select class="form-control kt-select2" id="negara" name="negara" onchange="return onChangeNegara(this.value)">	
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
					<select class="form-control kt-select2" id="provinsi" name="id_provinsi" onchange="return onChangeProvinsi(this.value)" {!! $disabled !!}>
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
					<label>Tempat Lahir</label>	
					@php
            	      $disabled = ($actionform == 'update' && ($riwayat->id_kota) ? '' : 'disabled="true"')
            	    @endphp
					<select class="form-control kt-select2" id="kota" name="id_kota" {!! $disabled !!}>						
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
			<div class="col-lg-4">				
				<div class="form-group">
					<label>Tanggal Lahir</label>	
					@php
            	      $value = ($actionform == 'update'? \App\Helpers\CVHelper::tglformat(@$riwayat->tanggal_lahir) : '')
            	    @endphp				
					<input type="text" name="tanggal_lahir" class="form-control" readonly=""  value="{{ $value }}" id="kt_datepicker_3">
				</div>
			</div>
			<div class="col-lg-4">				
				<div class="form-group">
					<label>Tanggal Menikah</label>		
					@php
            	      $value = ($actionform == 'update'? \App\Helpers\CVHelper::tglformat(@$riwayat->tanggal_menikah) : '')
            	    @endphp					
					<input type="text" name="tanggal_menikah" class="form-control" readonly="" value="{{ $value }}" id="kt_datepicker_3">
				</div>
			</div>
			<div class="col-lg-4">				
				<div class="form-group">
					<label>Pekerjaan</label>	
					@php
            	      $value = ($actionform == 'update'? $riwayat->pekerjaan : '')
            	    @endphp					
					<input type="text" class="form-control" name="pekerjaan" value="{{ $value }}"/>
				</div>
			</div>
			<div class="col-lg-12">				
				<div class="form-group">
					<label>Keterangan</label>	
					@php
            	      $value = ($actionform == 'update'? $riwayat->keterangan : '')
            	    @endphp					
					<textarea id="" class="form-control" rows="3" name="keterangan">{{ $value }}</textarea>
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
<script type="text/javascript" src="{{asset('js/cv/datepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('js/cv/keluarga/form-keluarga.js')}}"></script>
<script type="text/javascript">
function onChangeProvinsi(id_provinsi){
	$("#kota").prop('disabled', false);
    $.ajax({
        url: "/fetch/referensi/getkota?id_provinsi="+id_provinsi,
        type: "POST",
        dataType: "json", 
        success: function(data){
                 var contentData = "";
                 $("#kota").empty();
                 //contentData += "<option value=''>"+data.length+"</option>";
                 for(var i = 0, len = data.length; i < len; ++i) {
                     contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
                 }
                 $("#kota").append(contentData);
                 $("#kota").trigger("change");
                                     
        }
    });
}

function onChangeNegara(id_provinsi){
    if(id_provinsi == "INDONESIA"){
    	$("#provinsi").prop('disabled', false);
        $("#kota").empty();
        $.ajax({
	        url: "/fetch/referensi/getallprovinsi",
	        type: "POST",
	        dataType: "json", 
	        success: function(data){
	                 var contentData = "";
	                 $("#provinsi").empty();
	                 //contentData += "<option value=''>"+data.length+"</option>";
	                 for(var i = 0, len = data.length; i < len; ++i) {
	                     contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
	                 }
	                 $("#provinsi").append(contentData);
	                 $("#provinsi").trigger("change");
	                                     
	        }
	    });
    }else{
    	$("#provinsi").prop('disabled', true);
        $("#provinsi").empty();
    	$("#kota").prop('disabled', false);
	    $.ajax({
	        url: "/fetch/referensi/getkota?id_provinsi="+id_provinsi,
	        type: "POST",
	        dataType: "json", 
	        success: function(data){
	                 var contentData = "";
	                 $("#kota").empty();
	                 //contentData += "<option value=''>"+data.length+"</option>";
	                 for(var i = 0, len = data.length; i < len; ++i) {
	                     contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
	                 }
	                 $("#kota").append(contentData);
	                 $("#kota").trigger("change");
	                                     
	        }
	    });
    }
}

</script>