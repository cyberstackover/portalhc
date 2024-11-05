<form class="kt-form kt-form--label-right" method="POST" id="form-targetasalinstansi">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$targetasalinstansi->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Jenis Asal Instansi</label>
				<select class="form-control kt-select2" name="id_jenis_asal_instansi">
                    <option value=""></option>
                    @foreach($jenisasalinstansis as $jenisasalinstansi)
                    @php
            	      $select = !empty(old('id_jenis_asal_instansi')) && in_array($jenisasalinstansi->id, old('id_jenis_asal_instansi'))? 'selected="selected"' : ($actionform == 'update' && ($jenisasalinstansi->id==$targetasalinstansi->id_jenis_asal_instansi)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $jenisasalinstansi->id }}" {!! $select !!}>{{ $jenisasalinstansi->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Jumlah Minimal</label>
				<input type="text" class="form-control" name="jumlah_minimal" id="jumlah_minimal" value="{{!empty(old('jumlah_minimal'))? old('jumlah_minimal') : ($actionform == 'update' && $targetasalinstansi->jumlah_minimal != ''? $targetasalinstansi->jumlah_minimal : old('jumlah_minimal'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Jumlah Maksimal</label>
				<input type="text" class="form-control" name="jumlah_maksimal" id="jumlah_maksimal" value="{{!empty(old('jumlah_maksimal'))? old('jumlah_maksimal') : ($actionform == 'update' && $targetasalinstansi->jumlah_maksimal != ''? $targetasalinstansi->jumlah_maksimal : old('jumlah_maksimal'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Keterangan</label>
				<input type="text" class="form-control" name="keterangan" id="keterangan" value="{{!empty(old('keterangan'))? old('keterangan') : ($actionform == 'update' && $targetasalinstansi->keterangan != ''? $targetasalinstansi->keterangan : old('keterangan'))}}" />
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

<script type="text/javascript" src="{{asset('js/referensi/targetasalinstansi/form.js')}}"></script>