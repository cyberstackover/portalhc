<form class="kt-form kt-form--label-right" method="POST" id="form-komposisiprofesional">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$komposisiprofesional->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Kelas BUMN</label>
				<select class="form-control kt-select2" name="id_kelas_bumn">
                    <option value=""></option>
                    @foreach($kelasbumns as $kelasbumn)
                    @php
            	      $select = !empty(old('id_kelas_bumn')) && in_array($kelasbumn->id, old('id_kelas_bumn'))? 'selected="selected"' : ($actionform == 'update' && ($kelasbumn->id==$komposisiprofesional->id_kelas_bumn)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $kelasbumn->id }}" {!! $select !!}>{{ $kelasbumn->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Jumlah Minimal</label>
				<input type="text" class="form-control" name="jumlah_minimal" id="jumlah_minimal" value="{{!empty(old('jumlah_minimal'))? old('jumlah_minimal') : ($actionform == 'update' && $komposisiprofesional->jumlah_minimal != ''? $komposisiprofesional->jumlah_minimal : old('jumlah_minimal'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Jumlah Maksimal</label>
				<input type="text" class="form-control" name="jumlah_maksimal" id="jumlah_maksimal" value="{{!empty(old('jumlah_maksimal'))? old('jumlah_maksimal') : ($actionform == 'update' && $komposisiprofesional->jumlah_maksimal != ''? $komposisiprofesional->jumlah_maksimal : old('jumlah_maksimal'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Keterangan</label>
				<input type="text" class="form-control" name="keterangan" id="keterangan" value="{{!empty(old('keterangan'))? old('keterangan') : ($actionform == 'update' && $komposisiprofesional->keterangan != ''? $komposisiprofesional->keterangan : old('keterangan'))}}" />
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

<script type="text/javascript" src="{{asset('js/referensi/komposisiprofesional/form.js')}}"></script>