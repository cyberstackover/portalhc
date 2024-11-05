<form action="{{route('talenta.assessment_nilai.store_upload', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-upload">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$data->id}}" />
	<div class="kt-portlet__body">
		<div class="row">
			<div class="col-lg-12">				
				<div class="form-group">
					<label>File Hasil Assessment (*.pdf) Maximal 10 Mb</label>
					<input type="file" name="{{ $name }}" class="form-control" id="{{ $name }}" required  accept="application/pdf">
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
<script type="text/javascript">
</script>

<script type="text/javascript" src="{{asset('js/talenta/assessment_nilai/form-upload.js')}}"></script>