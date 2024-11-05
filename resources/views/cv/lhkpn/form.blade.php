<form action="{{route('cv.keluarga.store', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-first">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
	<div class="kt-portlet__body">
		<div class="row">
			<div class="col-lg-4">				
				<div class="form-group">

					<label>File LHKPN <span style="color:red;font-size:x-small; font-weight: bold"> (*.pdf)</span></label>
					<input type="file" name="file_name" class="form-control" id="file_name" required  accept="application/pdf">
					
				</div>
			</div>
			<div class="col-lg-4">
				<label>Tanggal Pelaporan</label>
				<input type="text" class="form-control cls-datepicker" name="tgl_pelaporan" id="tgl_pelaporan" value="{{!empty(old('tgl_pelaporan'))? old('tgl_pelaporan') : ($actionform == 'update' && $data->tgl_pelaporan != ''? \Carbon\Carbon::createFromFormat('Y-m-d', $data->tgl_pelaporan)->format('d/m/Y') : old('tgl_pelaporan'))}}" />
			</div>
			<div class="col-lg-4">				
				<div class="form-group">
					<label>Jumlah (Rupiah Penuh)</label>
					<input type="text" class="form-control kt-inputmask" name="jml_kekayaan_rp" id="jml_kekayaan_rp" onkeyup="return numericFilter(this), formatNumber(this);" value="{{!empty(old('jml_kekayaan_rp'))? old('jml_kekayaan_rp') : ($actionform == 'update' && $data->jml_kekayaan_rp != ''? number_format($data->jml_kekayaan_rp,0,',',',') : old('jml_kekayaan_rp'))}}" />
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