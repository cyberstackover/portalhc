<form class="kt-form kt-form--label-right" method="POST" id="form-keahlian">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$keahlian->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Jenis Keahlian</label>
				<input type="text" class="form-control" name="jenis_keahlian" id="jenis_keahlian" value="{{!empty(old('jenis_keahlian'))? old('jenis_keahlian') : ($actionform == 'update' && $keahlian->jenis_keahlian != ''? $keahlian->jenis_keahlian : old('jenis_keahlian'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Deskripsi</label>
				<input type="text" class="form-control" name="deskripsi" id="deskripsi" value="{{!empty(old('deskripsi'))? old('deskripsi') : ($actionform == 'update' && $keahlian->deskripsi != ''? $keahlian->deskripsi : old('deskripsi'))}}" />
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

<script type="text/javascript" src="{{asset('js/referensi/keahlian/form.js')}}"></script>