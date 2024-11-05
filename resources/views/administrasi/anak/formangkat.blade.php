<form class="kt-form kt-form--label-right" method="POST" id="form-angkat-anak">
	@csrf
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$skangkat->id : null}}" />
	<input type="hidden" name="id_surat_keputusan" id="id_surat_keputusan" readonly="readonly" value="{{$actionform == 'update'? (int)$id_surat_keputusan : (int)$id_surat_keputusan}}" />
	<input type="hidden" name="grup_jabatan_id" id="grup_jabatan_id" readonly="readonly" value="{{$actionform == 'update'? (int)$grup_jabatan_id : (int)$grup_jabatan_id}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-8">
				<label>Nama</label>
				<select class="form-control kt-select2" id="id_talenta" name="id_talenta">
                    <option value=""></option>
                    @foreach($pejabats as $pejabat)
                    @php
            	      $select = !empty(old('id_talenta')) && in_array($pejabat->id, old('id_talenta'))? 'selected="selected"' : ($actionform == 'update' && ($pejabat->id==$skangkat->id_talenta)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $pejabat->id }}" {!! $select !!}>{{ $pejabat->nama_lengkap }} - {{$pejabat->nik}}</option>
                    @endforeach
                </select>
                <a href="javascript:;" class="btn btn-outline-brand btn-sm btn-elevate btn-pill cls-add-orang-anak">
					<i class="la la-plus"></i>
					Nama
				</a>
			</div>
			<div class="col-lg-4">
				<label>Jabatan</label>
				<select class="form-control kt-select2" name="id_struktur_organ">
                    <option value=""></option>
                    @foreach($jabatans as $jabatan)
                    @php
            	      $select = !empty(old('id_struktur_organ')) && in_array($jabatan->id, old('id_struktur_organ'))? 'selected="selected"' : ($actionform == 'update' && ($jabatan->id==$skangkat->id_struktur_organ)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $jabatan->id }}" {!! $select !!}>{{ $jabatan->jabatan }}</option>
                    @endforeach
                </select>
			</div>
			
		</div>
		<div class="form-group row">
			<div class="col-lg-4">
				<label>Periode</label>
				<select class="form-control kt-select2" name="id_periode_jabatan">
                    @foreach($periodes as $periode)
                    @php
            	      $select = !empty(old('id_periode_jabatan')) && in_array($periode->id, old('id_periode_jabatan'))? 'selected="selected"' : ($actionform == 'update' && ($periode->id==$skangkat->id_periode_jabatan)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $periode->id }}" {!! $select !!}>{{ $periode->nama }}</option>
                    @endforeach
                </select>
			</div>
			<div class="col-lg-4">
				<label>Tanggal Awal Menjabat</label>
				<input type="text" class="form-control cls-datepicker" name="tanggal_awal_menjabat" id="tanggal_awal_menjabat" value="{{!empty(old('tanggal_awal_menjabat'))? old('tanggal_awal_menjabat') : ($actionform == 'update' && $skangkat->tanggal_awal_menjabat != ''? \Carbon\Carbon::createFromFormat('Y-m-d', $skangkat->tanggal_awal_menjabat)->format('d/m/Y') : old('tanggal_awal_menjabat'))}}" />
			</div>
			<div class="col-lg-4">
				<label>Tanggal Akhir Menjabat</label>
				<input type="text" class="form-control cls-datepicker" name="tanggal_akhir_menjabat" id="tanggal_akhir_menjabat" value="{{!empty(old('tanggal_akhir_menjabat'))? old('tanggal_akhir_menjabat') : ($actionform == 'update' && $skangkat->tanggal_akhir_menjabat != ''? \Carbon\Carbon::createFromFormat('Y-m-d', $skangkat->tanggal_akhir_menjabat)->format('d/m/Y') : old('tanggal_akhir_menjabat'))}}" />
			</div>
		</div>
	</div>
	@if($id_perusahaan)
		<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{$actionform == 'update'? (int)$id_perusahaan : (int)$id_perusahaan}}" />
		@else
		<div class="form-group row">
			<div class="col-lg-6">
				<div class="form-group">
				  <label>BUMN</label>
				  <select class="form-control kt-select2" name="id_perusahaan">
					  <option value=""></option>
						@foreach($perusahaan as $data)  
					  <option value="{{ $data->id }}">{{ $data->nama_lengkap }}</option>
					  @endforeach
				  </select>
				</div>
			  </div>
		</div>
		@endif
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

<script type="text/javascript">
	var urlcreateorang = "{{route('administrasi.anak.createorang')}}";
	var urlstoreorang = "{{route('administrasi.anak.neworang')}}";
	var urlchecknik = "{{ route('administrasi.bumn.checknik') }}";
	var urlchecknpwp = "{{ route('administrasi.bumn.checknpwp') }}";
</script>

<script type="text/javascript" src="{{asset('js/administrasi/anak/formangkat.js')}}"></script>