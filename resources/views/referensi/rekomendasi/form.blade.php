<form class="kt-form kt-form--label-right" method="POST" id="form-rekomendasi">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$rekomendasi->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-12">
				<label>Nama</label>
				<input type="text" class="form-control" name="nama" id="nama" value="{{!empty(old('nama'))? old('nama') : ($actionform == 'update' && $rekomendasi->nama != ''? $rekomendasi->nama : old('nama'))}}" />
			</div>
		</div>
		<div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3">{{!empty(old('deskripsi'))? old('deskripsi') : ($actionform == 'update' && $rekomendasi->deskripsi != ''? $rekomendasi->deskripsi : old('deskripsi'))}}</textarea>
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

<script type="text/javascript" src="{{asset('js/referensi/rekomendasi/form.js')}}"></script>