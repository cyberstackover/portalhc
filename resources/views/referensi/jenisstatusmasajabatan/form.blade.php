<form class="kt-form kt-form--label-right" method="POST" id="form-jenisstatusmasajabatan">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$jenisstatusmasajabatan->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Nama</label>
				<input type="text" class="form-control" name="nama" id="nama" value="{{!empty(old('nama'))? old('nama') : ($actionform == 'update' && $jenisstatusmasajabatan->nama != ''? $jenisstatusmasajabatan->nama : old('nama'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Bulan Awal</label>
				<input type="text" class="form-control" name="jumlah_hari_awal" id="jumlah_hari_awal" value="{{!empty(old('nama'))? old('keterangan') : ($actionform == 'update' && $jenisstatusmasajabatan->jumlah_hari_awal != ''? $jenisstatusmasajabatan->jumlah_hari_awal : old('jumlah_hari_awal'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Bulan Akhir</label>
				<input type="text" class="form-control" name="jumlah_hari_akhir" id="jumlah_hari_akhir" value="{{!empty(old('jumlah_hari_akhir'))? old('jumlah_hari_akhir') : ($actionform == 'update' && $jenisstatusmasajabatan->jumlah_hari_akhir != ''? $jenisstatusmasajabatan->jumlah_hari_akhir : old('jumlah_hari_akhir'))}}" />
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

<script type="text/javascript" src="{{asset('js/referensi/jenisstatusmasajabatan/form.js')}}"></script>