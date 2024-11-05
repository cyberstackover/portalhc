<form action="{{route('cv.assessment_upload.store', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-first">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
	<div class="kt-portlet__body">
		<div class="row">
			<div class="col-lg-6">				
				<div class="form-group">
					<label>File Hasil Assessment (*.pdf)</label>
					<input type="file" name="file_name" class="form-control" id="file_name" required  accept="application/pdf">
				</div>
			</div>
			<div class="col-lg-6">
				<label>Tanggal</label>
				<input type="text" class="form-control cls-datepicker" name="tanggal" id="tanggal" value="{{!empty(old('tgl_pelaporan'))? old('tgl_pelaporan') : ($actionform == 'update' && $data->tgl_pelaporan != ''? \Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggal)->format('d/m/Y') : old('tanggal'))}}" />
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