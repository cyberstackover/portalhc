<form action="{{route('cv.keluarga.store', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-second">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
	<input type="hidden" name="formal_flag" id="actionform" readonly="readonly" value="FALSE"/>
	<div class="kt-portlet__body">
		<div class="row">
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Nama Organisasi</label>	
					@php
            	      $value = ($actionform == 'update'? $data->nama_organisasi : '')
            	    @endphp				
					<input type="text" class="form-control" name="nama_organisasi" value="{{ $value }}"/>
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
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Tanggal Awal</label>	
					@php
            	      $value = ($actionform == 'update'? \App\Helpers\CVHelper::tglformat(@$data->tanggal_awal) : '')
            	    @endphp				
					<input type="text" name="tanggal_awal" class="form-control" readonly=""  value="{{ $value }}" id="kt_datepicker_3">
				</div>
			</div>
			<div class="col-lg-6">				
				<div class="form-group">
					<label>Tanggal Akhir</label>	
					@php
            	      $value = ($actionform == 'update'? \App\Helpers\CVHelper::tglformat(@$data->tanggal_akhir) : '')
            	    @endphp				
					<input type="text" name="tanggal_akhir" class="form-control" readonly=""  value="{{ $value }}" id="kt_datepicker_3">
					<span class="form-text text-muted">Kosongkan Jika Masih Aktif</span>
				</div>
			</div>
			<div class="col-lg-12">				
				<div class="form-group">
					<label>Uraian Singkat</label>	
					@php
            	      $value = ($actionform == 'update'? $data->kegiatan_organisasi : '')
            	    @endphp					
					<textarea id="" class="form-control" rows="3" name="kegiatan_organisasi">{{ $value }}</textarea>
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
<script type="text/javascript" src="{{asset('js/cv/riwayat_organisasi/form-non_formal.js')}}"></script>
<script type="text/javascript">
</script>