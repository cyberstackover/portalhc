<form class="kt-form kt-form--label-right" method="POST" id="form-henti">
	@csrf
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$skhenti->id : null}}" />
	<input type="hidden" name="id_surat_keputusan" id="id_surat_keputusan" readonly="readonly" value="{{$actionform == 'update'? (int)$id_surat_keputusan : (int)$id_surat_keputusan}}" />
	<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{$actionform == 'update'? (int)$id_perusahaan : (int)$id_perusahaan}}" />
	<input type="hidden" name="grup_jabatan_id" id="grup_jabatan_id" readonly="readonly" value="{{$actionform == 'update'? (int)$grup_jabatan_id : (int)$grup_jabatan_id}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-10">
				<label>Nama</label>
				<select class="form-control kt-select2" name="id_talenta" id="id_talenta">
                    <option value=""></option>
                    @foreach($pejabats as $pejabat)
                    @php
            	      $select = !empty(old('id_talenta')) && in_array($pejabat->id, old('id_talenta'))? 'selected="selected"' : ($actionform == 'update' && ($pejabat->id==$skhenti->id_talenta)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $pejabat->id }}" {!! $select !!}>{{ $pejabat->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Alasan Berhenti</label>
				<select class="form-control kt-select2" name="id_alasan_pemberhentian">
                    <option value=""></option>
                    @foreach($alasanberhentis as $alasanberhenti)
                    @php
            	      $select = !empty(old('id_alasan_pemberhentian')) && in_array($alasanberhenti->id, old('id_alasan_pemberhentian'))? 'selected="selected"' : ($actionform == 'update' && ($alasanberhenti->id==$skhenti->id_alasan_pemberhentian)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $alasanberhenti->id }}" {!! $select !!}>{{ $alasanberhenti->keterangan }}</option>
                    @endforeach
                </select>
			</div>
			<div class="col-lg-6">
				<label>Tanggal Akhir Menjabat</label>
				<input type="text" class="form-control date-picker-akhir" name="tanggal_akhir_menjabat" id="tanggal_akhir_menjabat" value="{{!empty(old('tanggal_akhir_menjabat'))? old('tanggal_akhir_menjabat') : ($actionform == 'update' && $skhenti->tanggal_akhir_menjabat != ''? \Carbon\Carbon::createFromFormat('Y-m-d', $skhenti->tanggal_akhir_menjabat)->format('d/m/Y') : old('tanggal_akhir_menjabat'))}}" />
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

<script type="text/javascript" src="{{asset('js/administrasi/bumn/formhenti.js')}}"></script>