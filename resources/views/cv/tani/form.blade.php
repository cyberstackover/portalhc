<form action="{{route('cv.keluarga.store', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-first">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
	<div class="kt-portlet__body">
		<div class="row">		
			<div class="col-lg-2">
				<label>Tanggal Awal</label>
				<input type="text" class="form-control cls-datepicker" name="tgl_awal" id="tgl_awal" value="{{!empty(old('tgl_awal'))? old('tgl_awal') : ($actionform == 'update' && $data->tgl_awal != ''? \Carbon\Carbon::createFromFormat('Y-m-d', $data->tgl_awal)->format('d/m/Y') : old('tgl_awal'))}}" />
			</div>
			<div class="col-lg-2">
				<label>Tanggal Akhir</label>
				<input type="text" class="form-control cls-datepicker" name="tgl_akhir" id="tgl_akhir" value="{{!empty(old('tgl_akhir'))? old('tgl_akhir') : ($actionform == 'update' && $data->tgl_akhir != ''? \Carbon\Carbon::createFromFormat('Y-m-d', $data->tgl_akhir)->format('d/m/Y') : old('tgl_akhir'))}}" />
			</div>
			<div class="col-lg-8">				
				<div class="form-group">
					<label>TANI (Rupiah Penuh)</label>
					<input type="text" class="form-control kt-inputmask" name="tani" id="tani" onkeyup="return numericFilter(this), formatNumber(this);" value="{{!empty(old('tani'))? old('tani') : ($actionform == 'update' && $data->tani != ''? number_format($data->tani,0,',',',') : old('tani'))}}" placeholder="Nilai penghasilan bersih (nett) dalam 1 tahun" />
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