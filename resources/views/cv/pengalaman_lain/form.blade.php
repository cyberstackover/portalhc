<form action="{{route('cv.keluarga.store', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-first">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
	<input type="hidden" name="formal_flag" id="actionform" readonly="readonly" value="TRUE"/>
	<div class="kt-portlet__body">
		<div class="row">
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Acara/Thema</label>	
					@php
            	      $value = ($actionform == 'update'? $data->acara : '')
            	    @endphp				
					<input type="text" class="form-control" name="acara" value="{{ $value }}"/>
				</div>
			</div>	
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Penyelenggara</label>	
					@php
            	      $value = ($actionform == 'update'? $data->penyelenggara : '')
            	    @endphp				
					<input type="text" class="form-control" name="penyelenggara" value="{{ $value }}"/>
				</div>
			</div>	
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Periode</label>
					<select class="form-control kt-select2" id="form_tahun" name="periode">
	                @for ($i = now()->year-6; $i < now()->year+2; $i++)
	                	@php
	            	      $select = ($actionform == 'update' && ($i == $data->periode) || ($actionform == 'insert' && (now()->year == $i)) ? 'selected="selected"' : '')
	            	   @endphp
	                	<option value="{{ $i }}" {!! $select !!}>{{ $i }}</option>
	                @endfor
              		</select>
				</div>
			</div>
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Lokasi</label>	
					@php
            	      $value = ($actionform == 'update'? $data->lokasi : '')
            	    @endphp				
					<input type="text" class="form-control" name="lokasi" value="{{ $value }}"/>
				</div>
			</div>	
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Peserta</label>	
					@php
            	      $value = ($actionform == 'update'? $data->peserta : '')
            	    @endphp				
					<input type="text" class="form-control" name="peserta" value="{{ $value }}"/>
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
<script type="text/javascript" src="{{asset('js/cv/pengalaman_lain/form.js')}}"></script>
<script type="text/javascript">
</script>