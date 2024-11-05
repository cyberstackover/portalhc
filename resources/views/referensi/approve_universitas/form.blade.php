<form class="kt-form kt-form--label-right" method="POST" id="form-universitas">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$universitas->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-12">
				<label>Nama</label>
				<input type="text" class="form-control" name="nama" id="nama" value="{{!empty(old('nama'))? old('nama') : ($actionform == 'update' && $universitas->nama != ''? $universitas->nama : old('nama'))}}" />
			</div>
		</div>		
		<div class="form-group row">
			<div class="col-lg-12">
				<label>Kota</label>
				<input type="text" class="form-control" name="kota" id="kota" value="{{!empty(old('kota'))? old('kota') : ($actionform == 'update' && $universitas->kota != ''? $universitas->kota : old('kota'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-12">
				<label>Negara</label>
				<input type="text" class="form-control" name="negara" id="negara" value="{{!empty(old('negara'))? old('negara') : ($actionform == 'update' && $universitas->negara != ''? $universitas->negara : old('negara'))}}" />
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

<script type="text/javascript" src="{{asset('js/referensi/universitas/form.js')}}"></script>