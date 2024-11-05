<div class="progress" style="height: 20px;">
    <div class="progress-bar progress-bar-striped progress-bar-animated " role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>
<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{$id_perusahaan}}" />
<ul class="nav nav-tabs nav-tabs-line mb-5" style="display: none;">
    <li class="nav-item">
        <a class="nav-link active a-step-1" data-toggle="tab" href="#step1">
            <span class="nav-text">1. Data Asal Instansi</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link a-step-2" data-toggle="tab" href="#step2">
            <span class="nav-text">2. Data Assesmen</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link a-step-3" data-toggle="tab" href="#step3" tabindex="-1">
            <span class="nav-text">3. Data Penghasilan</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link a-step-4" data-toggle="tab" href="#step4" tabindex="-1">
            <span class="nav-text">4. Upload File</span>
        </a>
    </li>
</ul>
<div class="tab-content mt-5" id="myTabContent">
    <div class="tab-pane fade show active" id="step1" role="tabpanel" aria-labelledby="step1">
    	<h5><i class="flaticon2-file-1  text-primary"></i>  Data Asal Instansi</h5>
    	<hr>
    	
    	<div class="form-group row">
			<div class="col-lg-8">
				<label>Jenis Instansi:</label>
				<select class="form-control kt-select2" name="id_jenis_asal_instansi" id="id_jenis_asal_instansi">
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
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-8">
				<label>Jabatan Asal Instansi:</label>
				<input type="text" class="form-control kt-inputmask" name="jabatan_asal_instansi" id="jabatan_asal_instansi" value="{{!empty(old('jabatan_asal_instansi'))? old('jabatan_asal_instansi') : ($actionform == 'update' && $talenta->jabatan_asal_instansi != ''? $talenta->jabatan_asal_instansi : old('jabatan_asal_instansi'))}}" />
			</div>
		</div>
        <div class="row justify-content-between">
            <div>
            </div>
            <div>
            	<span class="processing text-right" style="display: none;">Saving...  </span>
                <button type="button" class="btn btn-primary lanjut-step-2">
                	Lanjut
            	</button>
            </div>
        </div>
	</div>
    <div class="tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step2">
    	<h5><i class="flaticon2-file-1  text-primary"></i> Data Assesmen</h5>
    	<hr>
    	
		@if($id_grup_jabatan == 1)
		<div class="form-group row">
			<div class="col-lg-8">
				<label>Nilai Assesmen Global:</label>
				<input type="text" class="form-control kt-inputmask" name="nilai_asesmen_global" id="nilai_asesmen_global" value="{{!empty(old('nilai_asesmen_global'))? old('nilai_asesmen_global') : ($actionform == 'update' && $assesmen_direksi != ''? $assesmen_direksi->nilai_asesmen_global : old('nilai_asesmen_global'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-8">
				<label>Nilai Assesmen Domestik:</label>
				<input type="text" class="form-control kt-inputmask" name="nilai_asesmen_domestik" id="nilai_asesmen_domestik" value="{{!empty(old('nilai_asesmen_domestik'))? old('nilai_asesmen_domestik') : ($actionform == 'update' && $assesmen_direksi != ''? $assesmen_direksi->nilai_asesmen_domestik : old('nilai_asesmen_domestik'))}}" />
				<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{$id_perusahaan}}" />
			</div>
		</div>
		@else
		<div class="form-group row">
			<div class="col-lg-8">
				<label>Nilai Dekomwas:</label>
				<input type="text" class="form-control kt-inputmask" name="penilaian" id="penilaian" value="{{!empty(old('penilaian'))? old('penilaian') : ($actionform == 'update' && $penilaian_dekom != ''? $penilaian_dekom->penilaian : old('penilaian'))}}" />
				<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{$id_perusahaan}}" />
			</div>
		</div>
		@endif
    	<div class="row justify-content-between">
            <div>
                <button type="button" class="btn btn-outline-primary kembali-step-1">
                    Kembali
                </button>
            </div>
            <div>
            	<span class="processing text-right" style="display: none;">Saving...  </span>
                <button type="button" class="btn btn-primary lanjut-step-3">
                	Lanjut
            	</button>
            </div>
        </div>
	</div>
    <div class="tab-pane fade" id="step3" role="tabpanel" aria-labelledby="step3">
    	<h5><i class="flaticon2-file-1  text-primary"></i> Data Penghasilan</h5>
    	<hr>
    	<div class="form-group row">
			<div class="col-lg-4">
				<label>Tahun:</label>
				<input type="text" class="form-control kt-inputmask" name="tahun" id="tahun" value="" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Gaji Pokok:</label>
				<input type="text" class="form-control kt-inputmask" name="gaji_pokok" id="gaji_pokok" value="" />
			</div>
			<div class="col-lg-6">
				<label>Tantiem:</label>
				<input type="text" class="form-control kt-inputmask" name="tantiem" id="tantiem" value="" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Tunjangan:</label>
				<input type="text" class="form-control kt-inputmask" name="tunjangan" id="tunjangan" value="" />
			</div>
			<div class="col-lg-6">
				<label>Take Home Pay:</label>
				<input type="text" class="form-control kt-inputmask" name="takehomepay" id="takehomepay" value="" />
			</div>
		</div>
		<div class="row justify-content-between">
            <div>
            </div>
            <div>
                <button type="button" class="btn btn-success" id="tambah-penghasilan" style="margin-bottom: 15px;">
                	Tambah
            	</button>
            </div>
        </div>
		<div class="row">
			<table class="table table-bordered" id="tabel-penghasilan">
			    <thead>
			        <tr>
			            <th scope="col">Tahun</th>
			            <th scope="col">Gaji Pokok</th>
			            <th scope="col">Tantiem</th>
			            <th scope="col">Tunjangan</th>
			            <th scope="col">Take Home Pay</th>
			            <th scope="col">Aksi</th>
			        </tr>
			    </thead>
			    <tbody>
			    	@if(count($penghasilans) > 0)
                    @foreach($penghasilans as $penghasilan)
                    <tr>
                        <td>{{$penghasilan->tahun}}</td>
                        <td>{{$penghasilan->gaji_pokok}}</td>
                        <td>{{$penghasilan->tantiem}}</td>
                        <td>{{$penghasilan->tunjangan}}</td>
                        <td>{{$penghasilan->takehomepay}}</td>
                        <td><a href="#" class="btn btn-icon btn-sm mr-2 btn-danger"><i class="flaticon2-trash"></i></a></td>
                    </tr>
                    @endforeach
                    @endif
			    </tbody>
			</table>
		</div>
    	<div class="row justify-content-between">
            <div>
                <button type="button" class="btn btn-outline-primary kembali-step-2">
                    Kembali
                </button>
            </div>
            <div>
            	<span class="processing text-right" style="display: none;">Saving...  </span>
                <button type="button" class="btn btn-primary lanjut-step-4">
                	Lanjut
            	</button>
            </div>
        </div>
	</div>
    <div class="tab-pane fade" id="step4" role="tabpanel" aria-labelledby="step4">
    	<h5><i class="flaticon2-file-1  text-primary"></i> Upload File/Berkas Kelengkapan</h5>
    	<hr>
    	<form action="{{ route('administrasi.kelengkapansk.store-data-filekelengkapan') }}" method="post" class="form-horizontal" role="form" id="file-kelengkapan">
    		<input type="hidden" name="id_talenta" id="id_talenta" readonly="readonly" value="{{$talenta->id}}" />
	    	<input type="hidden" name="id_grup_jabatan" id="id_grup_jabatan" readonly="readonly" value="{{$id_grup_jabatan}}" />
	    	<input type="hidden" name="id_perusahaan" id="id_perusahaan" readonly="readonly" value="{{$id_perusahaan}}" />
    	<div class="form-group row">
			<div class="col-lg-6">
				<label>Jenis File:</label>
				<br>
				<select class="form-control kt-select2" name="id_jenis_file_pendukung" id="id_jenis_file_pendukung" style="width: 320px;">
                    <option value=""></option>
                    @foreach($jenis_file_pendukungs as $jenis_file_pendukung)
	                <option value="{{ $jenis_file_pendukung->id }}" >{{ $jenis_file_pendukung->nama }}</option>
                    @endforeach
                </select>
			</div>
			<div class="col-lg-6">
				<label>Upload Berkas Kelengkapan:</label>
				<input type="file" class="form-control" name="file_pendukung" id="file_pendukung" />
			</div>
		</div>
		<div class="row justify-content-between">
            <div>
            </div>
            <div>
                <button type="submit" class="btn btn-success tambah-filekelengkapan" style="margin-bottom: 15px;">
                	Tambah
            	</button>
            </div>
        </div>
    	</form>
		<div class="row">
			<table class="table table-bordered" id="table-file-pendukung">
			    <thead>
			        <tr>
			            <th scope="col">Jenis File</th>
			            <th scope="col">Nama File</th>
			            <th scope="col">Aksi</th>
			        </tr>
			    </thead>
			    <tbody>
			    	@foreach($file_pendukungs as $file_pendukung)
			        <tr>
			            <td>{{$file_pendukung->jenis_file_pendukung->nama}}</td>
			            <td><a href="{{url('/')}}/{{$file_pendukung->filename}}" target="_blank">{{str_replace("storage/kelengkapansk_files/", "", $file_pendukung->filename) }}</a></td>
			            <td><a href="#" class="btn btn-icon btn-sm mr-2 btn-danger"><i class="flaticon2-trash"></i></a></td>
			        </tr>
			        @endforeach
			    </tbody>
			</table>
		</div>
    	<div class="row justify-content-between">
            <div>
                <button type="button" class="btn btn-outline-primary kembali-step-3">
                    Kembali
                </button>
            </div>
            <div>
            	<span class="processing text-right" style="display: none;">Saving...  </span>
                <button type="button" class="btn btn-primary selesai">
                	Selesai
            	</button>
            </div>
        </div>
	</div>
</div>

<script type="text/javascript" src="{{asset('js/administrasi/kelengkapansk/form.js')}}"></script>