<form class="kt-form kt-form--label-right" method="POST" id="form-penghasilan">
	@csrf
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$penghasilans->id : null}}" />
	<input type="hidden" name="id_talenta" id="id_talenta" readonly="readonly" value="{{$actionform == 'update'? (int)$id_talenta : (int)$id_talenta}}" />
	<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{$actionform == 'update'? (int)$id_perusahaan : (int)$id_perusahaan}}" />
	<input type="hidden" name="grup_jabatan_id" id="grup_jabatan_id" readonly="readonly" value="{{$actionform == 'update'? (int)$grup_jabatan_id : (int)$grup_jabatan_id}}" />
	<input type="hidden" name="id_struktur_organ" id="id_struktur_organ" readonly="readonly" value="{{$actionform == 'update'? (int)$penghasilans->id_struktur_organ : (int)$id_struktur_organ}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-4">
				<label>Tahun:</label>
				<input type="text" id="tahun" name="tahun" required="required" class="form-control input-sm date-picker" value="{{!empty(old('tahun'))? old('tahun') : ($actionform == 'update' && $penghasilans->tahun != ''? $penghasilans->tahun : old('tahun'))}}" />
			</div>
			<div class="col-lg-4">
				<label>Satuan:</label>
				<select class="form-control kt-select2" name="mata_uang" id="mata_uang">
                    <option value=""></option>
                    @foreach($matauangs as $matauang)
	                    @php
	            	      $select = !empty(old('mata_uang')) && in_array($matauang->id, old('mata_uang'))? 'selected="selected"' : ($actionform == 'update' && ($matauang->id==$penghasilans->mata_uang)? 'selected="selected"' : '')
	            	    @endphp
	                <option value="{{ $matauang->id }}" {!! $select !!}>{{ $matauang->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Gaji Pokok:</label>
				<input type="text" class="form-control kt-inputmask" name="gaji_pokok" id="gaji_pokok" onkeyup="return numericFilter(this), formatNumber(this);" value="{{!empty(old('gaji_pokok'))? old('gaji_pokok') : ($actionform == 'update' && $penghasilans->gaji_pokok != ''? number_format($penghasilans->gaji_pokok,0,',',',') : old('gaji_pokok'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Tantiem:</label>
				<input type="text" class="form-control kt-inputmask" name="tantiem" id="tantiem" onkeyup="return numericFilter(this), formatNumber(this);" value="{{!empty(old('tantiem'))? old('tantiem') : ($actionform == 'update' && $penghasilans->tantiem != ''? number_format($penghasilans->tantiem,0,',',',') : old('tantiem'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Tunjangan:</label>
				<input type="text" class="form-control kt-inputmask" name="tunjangan" id="tunjangan" onkeyup="return numericFilter(this), formatNumber(this);" value="{{!empty(old('tunjangan'))? old('tunjangan') : ($actionform == 'update' && $penghasilans->tunjangan != ''? number_format($penghasilans->tunjangan,0,',',',') : old('tunjangan'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Take Home Pay:</label>
				<input type="text" class="form-control kt-inputmask" name="takehomepay" id="takehomepay" onkeyup="return numericFilter(this), formatNumber(this);" value="{{!empty(old('takehomepay'))? old('takehomepay') : ($actionform == 'update' && $penghasilans->takehomepay != ''? number_format($penghasilans->takehomepay,0,',',',') : old('takehomepay'))}}" />
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

<script type="text/javascript" src="{{asset('js/administrasi/kelengkapansk/formpenghasilan.js')}}"></script>