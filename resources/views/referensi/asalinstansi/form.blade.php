<form class="kt-form kt-form--label-right" method="POST" id="form-asalinstansi">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$asalinstansi->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Jenis Asal Instansi</label>
				<select class="form-control kt-select2" name="id_jenis_asal_instansi">
                    <option value=""></option>
                    @foreach($jenisasalinstansis as $jenisasalinstansi)
                    @php
            	      $select = !empty(old('id_jenis_asal_instansi')) && in_array($jenisasalinstansi->id, old('id_jenis_asal_instansi'))? 'selected="selected"' : ($actionform == 'update' && ($jenisasalinstansi->id==$asalinstansi->id_jenis_asal_instansi)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $jenisasalinstansi->id }}" {!! $select !!}>{{ $jenisasalinstansi->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Nama</label>
				<input type="text" class="form-control" name="nama" id="nama" value="{{!empty(old('nama'))? old('nama') : ($actionform == 'update' && $asalinstansi->nama != ''? $asalinstansi->nama : old('nama'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Keterangan</label>
				<input type="text" class="form-control" name="keterangan" id="keterangan" value="{{!empty(old('keterangan'))? old('keterangan') : ($actionform == 'update' && $asalinstansi->keterangan != ''? $asalinstansi->keterangan : old('keterangan'))}}" />
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

<script type="text/javascript" src="{{asset('js/referensi/asalinstansi/form.js')}}"></script>