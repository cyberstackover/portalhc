<form class="kt-form kt-form--label-right" method="POST" id="form-jenisasalinstansi">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$jenisasalinstansi->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Nama</label>
				<input type="text" class="form-control" name="nama" id="nama" value="{{!empty(old('nama'))? old('nama') : ($actionform == 'update' && $jenisasalinstansi->nama != ''? $jenisasalinstansi->nama : old('nama'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Keterangan</label>
				<input type="text" class="form-control" name="keterangan" id="keterangan" value="{{!empty(old('nama'))? old('keterangan') : ($actionform == 'update' && $jenisasalinstansi->keterangan != ''? $jenisasalinstansi->keterangan : old('keterangan'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Table Name</label>
				<input type="text" class="form-control" name="tablename" id="tablename" value="{{!empty(old('nama'))? old('tablename') : ($actionform == 'update' && $jenisasalinstansi->tablename != ''? $jenisasalinstansi->tablename : old('tablename'))}}" />
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

<script type="text/javascript" src="{{asset('js/referensi/jenisasalinstansi/form.js')}}"></script>