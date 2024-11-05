<form action="{{route('cv.keluarga.store', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-first">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
	<div class="kt-portlet__body">
		<div class="row">
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Nama</label>	
					@php
            	      $value = ($actionform == 'update'? $data->nama : '')
            	    @endphp				
					<input type="text" class="form-control" name="nama" value="{{ $value }}"/>
				</div>
			</div>	
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Nomor Handphone<span style="color:red;font-size:x-small; font-weight: bold"> *wajib</span></label>
					@php
            	      $value = ($actionform == 'update'? $data->nomor_handphone : '')
            	    @endphp				
					<input type="text" class="form-control" name="nomor_handphone"  required value="{{ $value }}"/>
				</div>
			</div>	
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Perusahaan</label>	
					@php
            	      $value = ($actionform == 'update'? $data->perusahaan : '')
            	    @endphp				
					<input type="text" class="form-control" name="perusahaan" value="{{ $value }}"/>
				</div>
			</div>
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Jabatan</label>	
					@php
            	      $value = ($actionform == 'update'? $data->jabatan : '')
            	    @endphp				
					<input type="text" class="form-control" name="jabatan" value="{{ $value }}"/>
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
<script type="text/javascript" src="{{asset('js/cv/referensi_cv/form-referensi.js')}}"></script>
<script type="text/javascript">
</script>