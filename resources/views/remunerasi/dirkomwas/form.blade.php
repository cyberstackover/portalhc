<form class="kt-form kt-form--label-right" method="POST" id="form-board">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$remunerasi->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">	
		<div class="form-group row">
			<div class="col-lg-4">
				<label>Tahun:</label>
				<select class="form-control kt-select2" id="tahun" name="tahun">
					<option></option>
					@for ($i = 2017; $i < now()->year + 3; $i++)
					<option value="{{ $i }}">{{ $i }}</option>
					@endfor
				</select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Perusahaan</label>
				<select class="form-control kt-select2" name="id_perusahaan">
                    <option value=""></option>
                    @foreach($perusahaans as $perusahaan)
                    @php
            	      $select = !empty(old('id_perusahaan')) && in_array($perusahaan->id, old('id_perusahaan'))? 'selected="selected"' : ($actionform == 'update' && ($perusahaan->id==$remunerasi->id_perusahaan)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $perusahaan->id }}" {!! $select !!}>{{ $perusahaan->nama_lengkap }}</option>
                    @endforeach
                </select>
			</div>
		</div>		
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Faktor Penghasilan</label>
				<select class="form-control kt-select2" name="id_faktor_penghasilan">
                    <option value=""></option>
                    @foreach($faktor_penghasilans as $faktor_penghasilan)
                    @php
            	      $select = !empty(old('id_faktor_penghasilan')) && in_array($faktor_penghasilan->id, old('id_faktor_penghasilan'))? 'selected="selected"' : ($actionform == 'update' && ($faktor_penghasilan->id==$remunerasi->id_faktor_penghasilan)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $faktor_penghasilan->id }}" {!! $select !!}>{{ $faktor_penghasilan->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>	
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Jumlah</label>
				<input type="text" class="form-control kt-inputmask" name="jumlah" id="jumlah" value="{{!empty(old('jumlah'))? old('jumlah') : ($actionform == 'update' && $remunerasi->jumlah != ''? $remunerasi->jumlah : old('jumlah'))}}" />
			</div>
			<div class="col-lg-4">
				<label>Mata Uang</label>
				<select class="form-control kt-select2" name="id_mata_uang">
                    <option value=""></option>
                    @foreach($mata_uangs as $mata_uang)
                    @php
            	      $select = !empty(old('id_mata_uang')) && in_array($mata_uang->id, old('id_mata_uang'))? 'selected="selected"' : ($actionform == 'update' && ($mata_uang->id==$remunerasi->id_mata_uang)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $mata_uang->id }}" {!! $select !!}>{{ $mata_uang->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Kurs</label>
				<input type="text" class="form-control kt-inputmask" name="kurs" id="kurs" value="{{!empty(old('kurs'))? old('kurs') : ($actionform == 'update' && $remunerasi->kurs != ''? $remunerasi->kurs : old('kurs'))}}" />
				<span class="form-text text-muted">Kosongkan untuk mata uang IDR/Rupiah.</span>
			</div>
			<div class="col-lg-6">
				<label>Tanggal Kurs</label>
				<input type="text" class="form-control kt-datepicker" name="tgl_kurs" readonly placeholder="Pilih tanggal" />
				<span class="form-text text-muted">Kosongkan untuk mata uang IDR/Rupiah.</span>
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

<script type="text/javascript" src="{{asset('js/remunerasi/board/form.js')}}"></script>