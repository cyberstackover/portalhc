<form class="kt-form kt-form--label-right" method="POST" id="form-jenisfilependukung">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$jenisfilependukung->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Nama</label>
				<input type="text" class="form-control" name="nama" id="nama" value="{{!empty(old('nama'))? old('nama') : ($actionform == 'update' && $jenisfilependukung->nama != ''? $jenisfilependukung->nama : old('nama'))}}" />
			</div>
			<div class="col-lg-6">
				<label>path</label>
				<input type="text" class="form-control" name="path" id="path" value="{{!empty(old('path'))? old('path') : ($actionform == 'update' && $jenisfilependukung->path != ''? $jenisfilependukung->path : old('path'))}}" />
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

<script type="text/javascript" src="{{asset('js/referensi/jenisfilependukung/form.js')}}"></script>