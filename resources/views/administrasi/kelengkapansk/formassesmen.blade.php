<form class="kt-form kt-form--label-right" method="POST" id="form-assesmen">
	@csrf
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$talenta->id : null}}" />
	<input type="hidden" name="id_talenta" id="id_talenta" readonly="readonly" value="{{$actionform == 'update'? (int)$id_talenta : (int)$id_talenta}}" />
	<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{$actionform == 'update'? (int)$id_perusahaan : (int)$id_perusahaan}}" />
	<input type="hidden" name="grup_jabatan_id" id="grup_jabatan_id" readonly="readonly" value="{{$actionform == 'update'? (int)$grup_jabatan_id : (int)$grup_jabatan_id}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-8">
				<label>Nilai Assesmen Global:</label>
				<input type="text" class="form-control kt-inputmask" name="nilai_asesmen_global" id="nilai_asesmen_global" value="{{!empty(old('nilai_asesmen_global'))? old('nilai_asesmen_global') : ($actionform == 'update' && $assesmen_direksi != ''? $assesmen_direksi->nilai_asesmen_global : old('nilai_asesmen_global'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-8">
				<label>Nilai Assesmen Domestik:</label>
				<input type="text" class="form-control kt-inputmask" name="nilai_asesmen_domestik" id="nilai_asesmen_domestik" value="{{!empty(old('nilai_asesmen_domestik'))? old('nilai_asesmen_domestik') : ($actionform == 'update' && $assesmen_direksi != ''? $assesmen_direksi->nilai_asesmen_domestik : old('nilai_asesmen_domestik'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-8">
				<label>(Kosongkan Jika Direksi) Nilai Dekomwas:</label>
				<input type="text" class="form-control kt-inputmask" name="penilaian" id="penilaian" value="{{!empty(old('penilaian'))? old('penilaian') : ($actionform == 'update' && $assesmen_direksi != ''? $assesmen_direksi->penilaian : old('penilaian'))}}" />
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

<script type="text/javascript" src="{{asset('js/administrasi/kelengkapansk/formassesmen.js')}}"></script>