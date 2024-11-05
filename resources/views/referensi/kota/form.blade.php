<form class="kt-form kt-form--label-right" method="POST" id="form-kota">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$kota->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Provinsi</label>
				<select class="form-control kt-select2" name="provinsi_id">
                    <option value=""></option>
                    @foreach($provinsis as $provinsi)
                    @php
            	      $select = !empty(old('provinsi_id')) && in_array($provinsi->id, old('provinsi_id'))? 'selected="selected"' : ($actionform == 'update' && ($provinsi->id==$kota->provinsi_id)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $provinsi->id }}" {!! $select !!}>{{ $provinsi->nama }}</option>
                    @endforeach
                </select>
			</div>
			<div class="col-lg-6">
				<label>Nama</label>
				<input type="text" class="form-control" name="nama" id="nama" value="{{!empty(old('nama'))? old('nama') : ($actionform == 'update' && $kota->nama != ''? $kota->nama : old('nama'))}}" />
			</div>
			<div class="col-lg-6">
				<label>is luar negeri?</label>
				<select class="form-control kt-select2" name="is_luar_negeri">
                    <option value=""></option>
                    <option value="0" {{ !empty(old('is_luar_negeri')) && in_array(0, old('is_luar_negeri'))? 'selected="selected"' : ($actionform == 'update' && (0==$kota->is_luar_negeri)? 'selected="selected"' : '') }}>False</option>
                    <option value="1" {{ !empty(old('is_luar_negeri')) && in_array(1, old('is_luar_negeri'))? 'selected="selected"' : ($actionform == 'update' && (1==$kota->is_luar_negeri)? 'selected="selected"' : '') }}>True</option>
                </select>
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

<script type="text/javascript" src="{{asset('js/referensi/kota/form.js')}}"></script>
