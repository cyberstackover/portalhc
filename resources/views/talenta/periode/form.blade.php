<form class="kt-form kt-form--label-right" method="POST" id="form-perioderegister">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$register->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-4">
				<label>Nama</label>
				<input type="text" class="form-control" name="nama" id="nama" value="{{!empty(old('nama'))? old('nama') : ($actionform == 'update' && $register->nama != ''? $register->nama : old('nama'))}}" />
			</div>
			<div class="col-lg-4">
				<label>Tanggal Awal</label>
				<input type="text" class="form-control cls-datepicker" name="tmt_awal" id="tmt_awal" value="{{!empty(old('tmt_awal'))? old('tmt_awal') : ($actionform == 'update' && $register->tmt_awal != ''? \Carbon\Carbon::createFromFormat('Y-m-d', $register->tmt_awal)->format('d/m/Y') : old('tmt_awal'))}}" />
			</div>
			<div class="col-lg-4">
				<label>Tanggal Akhir</label>
				<input type="text" class="form-control cls-datepicker" name="tmt_akhir" id="tmt_akhir" value="{{!empty(old('tmt_akhir'))? old('tmt_akhir') : ($actionform == 'update' && $register->tmt_akhir != ''? \Carbon\Carbon::createFromFormat('Y-m-d', $register->tmt_akhir)->format('d/m/Y') : old('tmt_akhir'))}}" />
			</div>
		</div>
		<div class="form-group row">
			
			<div class="col-lg-6">
				<label>Keterangan</label>
				<input type="text" class="form-control" name="keterangan" id="keterangan" value="{{!empty(old('nama'))? old('keterangan') : ($actionform == 'update' && $register->keterangan != ''? $register->keterangan : old('keterangan'))}}" />
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

<script type="text/javascript" src="{{asset('js/talenta/periode/form.js')}}"></script>