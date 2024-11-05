<form class="kt-form kt-form--label-right" method="POST" id="form-independen-anak">
	@csrf
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$skindependen->id : null}}" />
	<input type="hidden" name="id_surat_keputusan" id="id_surat_keputusan" readonly="readonly" value="{{$actionform == 'update'? (int)$id_surat_keputusan : (int)$id_surat_keputusan}}" />
	<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{$actionform == 'update'? (int)$id_perusahaan : (int)$id_perusahaan}}" />
	<input type="hidden" name="grup_jabatan_id" id="grup_jabatan_id" readonly="readonly" value="{{$actionform == 'update'? (int)$grup_jabatan_id : (int)$grup_jabatan_id}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Nama</label>
				<select class="form-control kt-select2" id="id_talenta" name="id_talenta" onchange=" return onTglJabat(this.value, {{ $id_perusahaan }}) ">
                    <option value=""></option>
                    @foreach($pejabats as $pejabat)
                    @php
            	      $select = !empty(old('id_talenta')) && in_array($pejabat->id, old('id_talenta'))? 'selected="selected"' : ($actionform == 'update' && ($pejabat->id==$skindependen->id_talenta)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $pejabat->id }}" {!! $select !!}>{{ $pejabat->nama_lengkap }}</option>
                    @endforeach
                </select>
                
			</div>
			<div class="col-lg-6">
				<label>Periode</label>
				<select class="form-control kt-select2" name="id_periode_jabatan">
                    <option value=""></option>
                    @foreach($periodes as $periode)
                    @php
            	      $select = !empty(old('id_periode_jabatan')) && in_array($periode->id, old('id_periode_jabatan'))? 'selected="selected"' : ($actionform == 'update' && ($periode->id==$skindependen->id_periode_jabatan)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $periode->id }}" {!! $select !!}>{{ $periode->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Tanggal Awal Menjabat</label>
				<input type="text" class="form-control cls-datepicker" name="tanggal_awal_menjabat" id="tanggal_awal_menjabat" value="{{!empty(old('tanggal_awal_menjabat'))? old('tanggal_awal_menjabat') : ($actionform == 'update' && $skindependen->tanggal_awal_menjabat != ''? \Carbon\Carbon::createFromFormat('Y-m-d', $skindependen->tanggal_awal_menjabat)->format('d/m/Y') : old('tanggal_awal_menjabat'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Tanggal Akhir Menjabat</label>
				<input type="text" class="form-control cls-datepicker" name="tanggal_akhir_menjabat" id="tanggal_akhir_menjabat" value="{{!empty(old('tanggal_akhir_menjabat'))? old('tanggal_akhir_menjabat') : ($actionform == 'update' && $skindependen->tanggal_akhir_menjabat != ''? \Carbon\Carbon::createFromFormat('Y-m-d', $skindependen->tanggal_akhir_menjabat)->format('d/m/Y') : old('tanggal_akhir_menjabat'))}}" />
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

<script type="text/javascript" src="{{asset('js/administrasi/anak/formindependen.js')}}"></script>