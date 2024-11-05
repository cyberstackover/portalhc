<form action="{{route('cv.kesehatan.store', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-first">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
	<div class="kt-portlet__body">
		<div class="row">
			<div class="col-lg-4">				
				<div class="form-group">
					<label>Tahun</label>
					<select class="form-control kt-select2" id="tahun_kesehatan" name="tahun_kesehatan">
	                @for ($i = 1945; $i < now()->year+1; $i++)
	                	@php
	            	      $select = ($actionform == 'update' && ($i == $data->tahun_kesehatan) || ($actionform == 'insert' && (now()->year == $i)) ? 'selected="selected"' : '')
	            	   @endphp
	                <option value="{{ $i }}" {!! $select !!}>{{ $i }}</option>
	                @endfor
              </select>
				</div>
			</div>
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Nilai Kesehatan</label>
					<select class="form-control kt-select2" name="nilai_kesehatan">
	                    <option value=""></option>
	                    @foreach($skalasehats as $sehats)       
	                    	@php
		            	      $select = ($actionform == 'update' && ($sehats->id == $data->skalasehat->id) ? 'selected="selected"' : '')
		            	    @endphp
		                	<option value="{{ $sehats->id }}" {!! $select !!}>{{ $sehats->nama }} - {{$sehats->keterangan}}</option>
	                    @endforeach
	                </select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Instansi Kesehatan</label>
					<input type="text" name="instansi_kesehatan" class="form-control" id="instansi_kesehatan" required="required">
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