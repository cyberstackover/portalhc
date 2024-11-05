<form action="{{route('cv.keluarga.store', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-second">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
	<div class="kt-portlet__body">
		<div class="row">
			<div class="col-lg-12">				
				<div class="form-group">
					<label>Judul Karya Ilmiah</label>	
					@php
            	      $value = ($actionform == 'update'? $data->judul : '')
            	    @endphp				
					<input type="text" class="form-control" name="judul" value="{{ $value }}"/>
				</div>
			</div>
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Media Publikasi</label>	
					@php
            	      $value = ($actionform == 'update'? $data->media_publikasi : '')
            	    @endphp				
					<input type="text" class="form-control" name="media_publikasi" value="{{ $value }}"/>
				</div>
			</div>			
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Tahun</label>
					<select class="form-control kt-select2" id="form_tahun" name="tahun">
	                @for ($i = now()->year-5; $i < now()->year+1; $i++)
	                	@php
	            	      $select = ($actionform == 'update' && ($i == $data->tahun) || ($actionform == 'insert' && (now()->year == $i)) ? 'selected="selected"' : '')
	            	   @endphp
	                <option value="{{ $i }}" {!! $select !!}>{{ $i }}</option>
	                @endfor
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
<script type="text/javascript" src="{{asset('js/cv/karya_ilmiah/form-karya.js')}}"></script>
<script type="text/javascript">
</script>