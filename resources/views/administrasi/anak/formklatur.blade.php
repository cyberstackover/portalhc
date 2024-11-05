<form class="kt-form kt-form--label-right" method="POST" id="form-klatur-anak">
	@csrf
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$skklatur->id : null}}" />
	<input type="hidden" name="id_surat_keputusan" id="id_surat_keputusan" readonly="readonly" value="{{$actionform == 'update'? (int)$id_surat_keputusan : (int)$id_surat_keputusan}}" />
	<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{$actionform == 'update'? (int)$id_perusahaan : (int)$id_perusahaan}}" />
	<input type="hidden" name="grup_jabatan_id" id="grup_jabatan_id" readonly="readonly" value="{{$actionform == 'update'? (int)$grup_jabatan_id : (int)$grup_jabatan_id}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			
			<div class="col-lg-6">
				<label>Jabatan</label>
				<select class="form-control kt-select2" name="id_struktur_organ">
                    <option value=""></option>
                    @foreach($jabatans as $jabatan)
                    @php
            	      $select = !empty(old('id_struktur_organ')) && in_array($jabatan->id, old('id_struktur_organ'))? 'selected="selected"' : ($actionform == 'update' && ($jabatan->id==$skhenti->id_struktur_organ)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $jabatan->id }}" {!! $select !!}>{{ $jabatan->jabatan }}</option>
                    @endforeach
                </select>
			</div>
			<div class="col-lg-6">
				<label>Nomenklatur</label>
				<input type="text" class="form-control" name="nomenklatur_baru" id="nomenklatur_baru" value="{{!empty(old('nomenklatur_baru'))? old('nomenklatur_baru') : ($actionform == 'update' && $kelasmasters->nomenklatur_baru != ''? $kelasmasters->nomenklatur_baru : old('nomenklatur_baru'))}}" />
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

<script type="text/javascript" src="{{asset('js/administrasi/anak/formklatur.js')}}"></script>