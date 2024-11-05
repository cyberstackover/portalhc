<form action="{{route('cv.keluarga.store', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-first">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
	<div class="kt-portlet__body">
		<div class="row">
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Jenis Penghargaan</label>	
					@php
            	      $value = ($actionform == 'update'? $data->jenis_penghargaan : '')
            	    @endphp				
					<input type="text" class="form-control" name="jenis_penghargaan" value="{{ $value }}"/>
				</div>
			</div>
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Tingkat</label>		
					<select class="form-control kt-select2" name="tingkat">
		                @if($actionform == 'update' && $data->tingkat == "Organisasi Kerja")
			                <option value="Organisasi Kerja" selected="selected">Organisasi Kerja</option>
			                <option value="Nasional">Nasional</option>
			                <option value="Internasional">Internasional</option>
			            @elseif($actionform == 'update' && $data->tingkat == "Nasional")
			            	<option value="Organisasi Kerja">Organisasi Kerja</option>
			                <option value="Nasional" selected="selected">Nasional</option>
			                <option value="Internasional">Internasional</option>
		                @else
		                	<option value="Organisasi Kerja">Organisasi Kerja</option>
			                <option value="Nasional">Nasional</option>
			                <option value="Internasional" selected="selected">Internasional</option>
		                @endif
		            </select>
				</div>
			</div>			
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Pemberi Penghargaan</label>	
					@php
            	      $value = ($actionform == 'update'? $data->pemberi_penghargaan : '')
            	    @endphp				
					<input type="text" class="form-control" name="pemberi_penghargaan" value="{{ $value }}"/>
				</div>
			</div>			
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Tahun</label>
					<select class="form-control kt-select2" id="form_tahun" name="tahun">
	                @for ($i = 1945; $i < now()->year+1; $i++)
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
<script type="text/javascript" src="{{asset('js/cv/datepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('js/cv/penghargaan/form-penghargaan.js')}}"></script>
<script type="text/javascript">
</script>