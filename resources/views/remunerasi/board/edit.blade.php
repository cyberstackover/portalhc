<form class="kt-form kt-form--label-right" method="POST" id="form-board">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$remunerasi->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">	
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Jumlah</label>
				<input type="text" class="form-control kt-inputmask" name="jumlah_default" id="jumlah_default" value="{{!empty(old('jumlah_default'))? old('jumlah_default') : ($actionform == 'update' && $remunerasi->jumlah_default != ''? $remunerasi->jumlah_default : old('jumlah_default'))}}" />
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
				<input type="text" class="form-control kt-inputmask" name="kurs" id="kursx" value="{{!empty(old('kurs'))? old('kurs') : ($actionform == 'update' && $remunerasi->kurs != ''? $remunerasi->kurs : old('kurs'))}}" />
				<span class="form-text text-muted">Kosongkan untuk mata uang IDR/Rupiah.</span>
			</div>
			<div class="col-lg-6">
				<label>Tanggal Kurs</label>
				<input type="text" class="form-control kt-datepicker" name="tgl_kurs" readonly placeholder="Pilih tanggal" value="{{!empty(old('tgl_kurs'))? old('tgl_kurs') : ($actionform == 'update' && $remunerasi->tgl_kurs != ''? $remunerasi->tgl_kurs : old('tgl_kurs'))}}" />
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

<script type="text/javascript" src="{{asset('js/remunerasi/board/edit.js')}}"></script>