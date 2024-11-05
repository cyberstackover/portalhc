<form class="kt-form kt-form--label-right" method="POST" id="form-instansi">
	@csrf
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$talenta->id : null}}" />
	<input type="hidden" name="id_talenta" id="id_talenta" readonly="readonly" value="{{$actionform == 'update'? (int)$id_talenta : (int)$id_talenta}}" />
	<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{$actionform == 'update'? (int)$id_perusahaan : (int)$id_perusahaan}}" />
	<input type="hidden" name="grup_jabatan_id" id="grup_jabatan_id" readonly="readonly" value="{{$actionform == 'update'? (int)$grup_jabatan_id : (int)$grup_jabatan_id}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-8">
				<label>Jenis Instansi:</label>
				<select class="form-control kt-select2" name="id_jenis_asal_instansi" id="id_jenis_asal_instansi" onchange=" return onAsalInstansi(this.value) ">
                    <option value=""></option>
                    @foreach($jenisasalinstansis as $jenisasalinstansi)
                    @php
            	      $select = !empty(old('id_jenis_asal_instansi')) && in_array($jenisasalinstansi->id, old('id_jenis_asal_instansi'))? 'selected="selected"' : ($actionform == 'update' && ($jenisasalinstansi->id==$talenta->id_jenis_asal_instansi)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $jenisasalinstansi->id }}" {!! $select !!}>{{ $jenisasalinstansi->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-8">
				<label>Asal Instansi:</label>
				<select class="form-control kt-select2" name="id_asal_instansi" id="id_asal_instansi">
                    <option value=""></option>
                    @foreach($asalinstansis as $asalinstansi)
                    @php
            	      $select = !empty(old('id_asal_instansi')) && in_array($asalinstansi->id, old('id_asal_instansi'))? 'selected="selected"' : ($actionform == 'update' && ($asalinstansi->id==$talenta->id_asal_instansi)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $asalinstansi->id }}" {!! $select !!}>{{ $asalinstansi->nama }}</option>
                    @endforeach
                </select>
                <label style="color: red;">*Jika Instansi Belum ada, Silakan hubungi admin KBUMN</label>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-8">
				<label>Jabatan Asal Instansi:</label>
				<input type="text" class="form-control kt-inputmask" name="jabatan_asal_instansi" id="jabatan_asal_instansi" value="{{!empty(old('jabatan_asal_instansi'))? old('jabatan_asal_instansi') : ($actionform == 'update' && $talenta->jabatan_asal_instansi != ''? $talenta->jabatan_asal_instansi : old('jabatan_asal_instansi'))}}" />
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

<script type="text/javascript" src="{{asset('js/administrasi/kelengkapansk/forminstansi.js')}}"></script>