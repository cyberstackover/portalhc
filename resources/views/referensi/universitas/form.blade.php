<form class="kt-form kt-form--label-right" method="POST" id="form-universitas">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$universitas->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-12">
				<label>Nama</label>
				<input type="text" class="form-control" name="nama" id="nama" value="{{!empty(old('nama'))? old('nama') : ($actionform == 'update' && $universitas->nama != ''? $universitas->nama : old('nama'))}}" />
			</div>
		</div>		
		<div class="form-group row">
			<div class="col-lg-12">	
				<label>Negara</label>
				<select class="form-control kt-select2 provinsi" name="id_negara" onchange="return onChangeNegara(this.value)">	
					<option value="281">INDONESIA</option>
					@foreach($negara as $data)  
						@php
						  $select = ($actionform == 'update' && ($data->id == $universitas->id_negara) ? 'selected="selected"' : '');
						@endphp
						<option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-12">	
				<label>Kota</label>	
				{{--  @php
				  $disabled = ($actionform == 'update' && ($universitas->id_kota) ? '' : 'disabled="true"');
				@endphp  --}}
				<select class="form-control kt-select2 kota" name="id_kota" >						
				<option value=""></option>
				@foreach($kota as $data)       
					@php
					  $select = ($actionform == 'update' && ($data->id == $universitas->id_kota) ? 'selected="selected"' : '');
					@endphp
					<option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
				@endforeach
				</select>
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

<script type="text/javascript" src="{{asset('js/referensi/universitas/form.js')}}"></script>

<script type="text/javascript">
	function onChangeNegara(id_negara){
		if(id_negara == "281"){
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
		  $.ajax({
			  url: "/fetch/referensi/getkota?id_provinsi="+id_negara,
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