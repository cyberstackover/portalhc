<form action="{{route('cv.pelatihan.store', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-pelatihan">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$riwayat->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
	<div class="kt-portlet__body">
		<div class="row">			
			<div class="col-lg-3">				
				<div class="form-group">
					<label>Jenis Diklat</label>
					<select class="form-control kt-select2" name="id_jenis">	
						<option value=""></option>
						@foreach($jenisdiklat as $data)  
							@php
		            	      $select = ($actionform == 'update' && ($data->id == $riwayat->id_jenis) ? 'selected="selected"' : '')
		            	    @endphp
							<option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-lg-3">				
				<div class="form-group">
					<label>Tingkat</label>		
					<select class="form-control kt-select2" name="id_tingkat">	
						<option value=""></option>
						@foreach($tingkatdiklat as $data)  
							@php
		            	      $select = ($actionform == 'update' && ($data->id == $riwayat->id_tingkat) ? 'selected="selected"' : '')
		            	    @endphp
							<option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-lg-3">				
				<div class="form-group">
					<label>Lama Hari</label>
					@php
            	      $value = ($actionform == 'update'? $riwayat->lama_hari : '')
            	    @endphp		
					<input type="number" class="form-control" name="lama_hari" value="{{ $value }}"/>
				</div>
			</div>
			<div class="col-lg-4">				
				<div class="form-group">
					<label>Tahun</label>
					<select class="form-control kt-select2" name="tahun_diklat">
	                @for ($i = 1900; $i < now()->year+5; $i++)
	                	@php
	            	      $select = ($actionform == 'update' && ($i == $riwayat->tahun_diklat) || ($actionform == 'insert' && (now()->year == $i)) ? 'selected="selected"' : '')
	            	   @endphp
	                <option value="{{ $i }}" {!! $select !!}>{{ $i }}</option>
	                @endfor
              </select>
				</div>
			</div>
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Penyelenggara</label>	
					@php
            	      $value = ($actionform == 'update'? $riwayat->penyelenggara : '')
            	    @endphp				
					<input type="text" class="form-control" name="penyelenggara" value="{{ $value }}"/>
				</div>
			</div>
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Latihan dan Pengembangan Kompetensi</label>	
					@php
            	      $value = ($actionform == 'update'? $riwayat->pengembangan_kompetensi : '')
            	    @endphp				
					<input type="text" class="form-control" name="pengembangan_kompetensi" value="{{ $value }}"/>
				</div>
			</div>
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Nomor Sertifikasi</label>
					@php
            	      $value = ($actionform == 'update'? $riwayat->nomor_sertifikasi : '')
            	    @endphp						
					<input type="text" class="form-control" name="nomor_sertifikasi" value="{{ $value }}"/>
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
<script type="text/javascript" src="{{asset('js/cv/pelatihan/form-pelatihan.js')}}"></script>
<script type="text/javascript" src="{{asset('js/cv/datepicker.js')}}"></script>
<script type="text/javascript">
function onChangeProvinsi(id_provinsi){
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
    }else{
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

</script>
