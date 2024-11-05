<form class="kt-form kt-form--label-right" method="POST" id="form-kelengkapan">
	@csrf
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$file_pendukungs->id : null}}" />
	<input type="hidden" name="id_talenta" id="id_talenta" readonly="readonly" value="{{$actionform == 'update'? (int)$id_talenta : (int)$id_talenta}}" />
	<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{$actionform == 'update'? (int)$id_perusahaan : (int)$id_perusahaan}}" />
	<input type="hidden" name="grup_jabatan_id" id="grup_jabatan_id" readonly="readonly" value="{{$actionform == 'update'? (int)$grup_jabatan_id : (int)$grup_jabatan_id}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Jenis File:</label>
				<br>
				<select class="form-control kt-select2" name="id_jenis_file_pendukung" id="id_jenis_file_pendukung" style="width: 320px;">
                    <option value=""></option>
                    @foreach($jenis_file_pendukungs as $jenis_file_pendukung)
                    @php
            	      $select = !empty(old('id_jenis_file_pendukung')) && in_array($jenis_file_pendukung->id, old('id_jenis_file_pendukung'))? 'selected="selected"' : ($actionform == 'update' && ($jenis_file_pendukung->id==$file_pendukungs->id_jenis_file_pendukung)? 'selected="selected"' : '')
            	    @endphp
	                <option value="{{ $jenis_file_pendukung->id }}" {!! $select !!}>{{ $jenis_file_pendukung->nama }}</option>
                    @endforeach   
                </select>
			</div>
			<div class="col-lg-6">
				<label>Upload Berkas Kelengkapan:</label>
				<input type="file" class="form-control" name="file_pendukung" id="file_pendukung" />
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

<script type="text/javascript" src="{{asset('js/administrasi/kelengkapansk/formkelengkapan.js')}}"></script>