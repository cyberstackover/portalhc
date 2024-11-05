<form class="kt-form kt-form--label-right" method="POST" id="form-jenisjabatan">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$jenisjabatan->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-12">
				<label>Grup Jabatan</label>
				<select class="form-control kt-select2" name="id_grup_jabatan">
                    <option value=""></option>
                    @foreach($grupjabatans as $grupjabatan)
                    @php
            	      $select = !empty(old('id_grup_jabatan')) && in_array($grupjabatan->id, old('id_grup_jabatan'))? 'selected="selected"' : ($actionform == 'update' && ($grupjabatan->id==$jenisjabatan->id_grup_jabatan)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $grupjabatan->id }}" {!! $select !!}>{{ $grupjabatan->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-12">
				<label>Nama</label>
				<input type="text" class="form-control" name="nama" id="nama" value="{{!empty(old('nama'))? old('nama') : ($actionform == 'update' && $jenisjabatan->nama != ''? $jenisjabatan->nama : old('nama'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-12">
				<label>Prosentase Gaji(%)</label>
				<input type="text" class="form-control" name="prosentase_gaji" id="prosentase_gaji" value="{{!empty(old('prosentase_gaji'))? old('prosentase_gaji') : ($actionform == 'update' && $jenisjabatan->prosentase_gaji != ''? $jenisjabatan->prosentase_gaji : old('prosentase_gaji'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-12">
				<label>Sumber Pengali Prosentase Gaji</label>
				<select class="form-control kt-select2" name="id_jns_jab_pengali">
                    <option value=""></option>
                    @foreach($jenis_jabatans as $jenis_jabatan)
	                    @php
	            	      $select = !empty(old('id_jns_jab_pengali')) && in_array($jenis_jabatan->id, old('id_jns_jab_pengali'))? 'selected="selected"' : ($actionform == 'update' && ($jenis_jabatan->id==$jenisjabatan->id_jns_jab_pengali)? 'selected="selected"' : '')
	            	   @endphp
	                <option value="{{ $jenis_jabatan->id }}" {!! $select !!}>{{ $jenis_jabatan->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-12">
				<label>Urutan</label>
				<input type="text" class="form-control" name="urut" id="urut" value="{{!empty(old('urut'))? old('urut') : ($actionform == 'update' && $jenisjabatan->urut != ''? $jenisjabatan->urut : old('urut'))}}" />
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

<script type="text/javascript" src="{{asset('js/referensi/jenisjabatan/form.js')}}"></script>