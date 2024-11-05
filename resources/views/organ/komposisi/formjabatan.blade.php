<form class="kt-form kt-form--label-right" method="POST" id="form-grupjabatan">
	@csrf
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$strukturorgas->id : null}}" />
	<input type="hidden" name="perusahaan_id" id="perusahaan_id" readonly="readonly" value="{{$perusahaan_id}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Grup Jabatan</label>
				<select class="form-control kt-select2" name="grup_jabatan_id">
                    <option value=""></option>
                    @foreach($grupjabatans as $grupjabatan)
                    @php
            	      $select = !empty(old('grup_jabatan_id')) && in_array($grupjabatan->id, old('grup_jabatan_id'))? 'selected="selected"' : ($actionform == 'update' && ($grupjabatan->id==$getjenisjabatan->id_grup_jabatan)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $grupjabatan->id }}" {!! $select !!}>{{ $grupjabatan->nama }}</option>
                    @endforeach
                </select>
			</div>
			<div class="col-lg-6">
				<label>Jenis Jabatan</label>
				<select class="form-control kt-select2" name="id_jenis_jabatan">
                    <option value=""></option>
                    @foreach($jenisjabatans as $jenisjabatan)
                    @php
            	      $select = !empty(old('id_jenis_jabatan')) && in_array($jenisjabatan->id, old('id_jenis_jabatan'))? 'selected="selected"' : ($actionform == 'update' && ($jenisjabatan->id==$strukturorgas->id_jenis_jabatan)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $jenisjabatan->id }}" {!! $select !!}>{{ $jenisjabatan->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Nomenklatur<span style="color: red">*</span></label>
				<input type="text" required="required" class="form-control" name="nomenklatur_jabatan" id="nomenklatur_jabatan" value="{{!empty(old('nomenklatur_jabatan'))? old('nomenklatur_jabatan') : ($actionform == 'update' && $strukturorgas->nomenklatur_jabatan != ''? $strukturorgas->nomenklatur_jabatan : old('nomenklatur_jabatan'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Bidang Jabatan</label>
				<select class="form-control kt-select2" name="id_bidang_jabatan[]" multiple="multiple">
                    <option value=""></option>
                    @foreach($bidangjabatans as $bidangjabatan)
            	   @php
                       $select = !empty(old('id_bidang_jabatan')) && in_array($bidangjabatan->id, old('id_bidang_jabatan'))? 'selected="selected"' : ($actionform == 'update' && in_array($bidangjabatan->id, $strukturjabatans)? 'selected="selected"' : '')
                    @endphp	
	                <option value="{{ $bidangjabatan->id }}" {!! $select !!}>{{ $bidangjabatan->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			@if($actionform == 'update')
			<div class="col-lg-3">
				<label>Urutan<span style="color: red">*</span></label>
				<input type="text" required="required" class="form-control" name="urut" id="urut" value="{{!empty(old('urut'))? old('urut') : ($actionform == 'update' && $strukturorgas->urut != ''? $strukturorgas->urut : old('urut'))}}" />
			</div>
			@endif
		</div>
		<div class="form-group row">
			<div class="col-lg-12">
				<label>Keterangan</label>
				<textarea class="form-control kt-shape-bg-color-1" id="keterangan" name="keterangan" rows="5" cols="5">{{!empty(old('keterangan'))? old('keterangan') : ($actionform == 'update' && $strukturorgas->keterangan != ''? $strukturorgas->keterangan : old('keterangan'))}}</textarea>
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

<script type="text/javascript" src="{{asset('js/organ/komposisi/formjabatan.js')}}"></script>