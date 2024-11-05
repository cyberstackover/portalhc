<form class="kt-form kt-form--label-right" method="POST" id="form-jenissk">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$jenissk->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Nama</label>
				<input type="text" class="form-control" name="nama" id="nama" value="{{!empty(old('nama'))? old('nama') : ($actionform == 'update' && $jenissk->nama != ''? $jenissk->nama : old('nama'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Table Name</label>
				<input type="text" class="form-control" name="tablename" id="tablename" value="{{!empty(old('nama'))? old('tablename') : ($actionform == 'update' && $jenissk->tablename != ''? $jenissk->tablename : old('tablename'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Keterangan</label>
				<input type="text" class="form-control" name="keterangan" id="keterangan" value="{{!empty(old('nama'))? old('keterangan') : ($actionform == 'update' && $jenissk->keterangan != ''? $jenissk->keterangan : old('keterangan'))}}" />
			</div>
			<div class="col-lg-4">
				<label>Urutan</label>
				<input type="text" class="form-control" name="urut" id="urut" value="{{!empty(old('urut'))? old('urut') : ($actionform == 'update' && $jenissk->urut != ''? $jenissk->urut : old('urut'))}}" />
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

<script type="text/javascript" src="{{asset('js/referensi/jenissk/form.js')}}"></script>