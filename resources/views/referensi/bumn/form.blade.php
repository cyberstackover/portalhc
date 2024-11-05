<form class="kt-form kt-form--label-right" method="POST" id="form-bumn">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$bumn->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-4">
				<label>Kategori Perusahaan</label>
				<select class="form-control kt-select2" name="id_jenis_perusahaan">
                    <option value=""></option>
                    @foreach($jenis_perusahaans as $jenis_perusahaan)
                    @php
            	      $select = !empty(old('id_jenis_perusahaan')) && in_array($jenis_perusahaan->id, old('id_jenis_perusahaan'))? 'selected="selected"' : ($actionform == 'update' && ($jenis_perusahaan->id==$bumn->id_jenis_perusahaan)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $jenis_perusahaan->id }}" {!! $select !!}>{{ $jenis_perusahaan->nama }}</option>
                    @endforeach
                </select>
			</div>
			<div class="col-lg-4">
				<label>Kelas Perusahaan</label>
				<select class="form-control kt-select2" name="kelas">
                    <option value=""></option>
                    @foreach($kelas_perusahaans as $kelas_perusahaan)
                    @php
            	      $select = !empty(old('kelas')) && in_array($kelas_perusahaan->id, old('kelas'))? 'selected="selected"' : ($actionform == 'update' && ($kelas_perusahaan->id==$bumn->kelas)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $kelas_perusahaan->id }}" {!! $select !!}>{{ $kelas_perusahaan->nama }}</option>
                    @endforeach
                </select>
			</div>
            <div class="col-lg-4">
                <label>Klaster Perusahaan</label>
                <select class="form-control kt-select2" name="klaster">
                    <option value=""></option>
                    @foreach($klaster_perusahaans as $klaster_perusahaan)
                        @php
                            $select = !empty(old('klaster')) && in_array($klaster_perusahaan->id, old('klaster'))? 'selected="selected"' : ($actionform == 'update' && ($klaster_perusahaan->id==$bumn->id_klaster)? 'selected="selected"' : '')
                        @endphp
                        <option value="{{ $klaster_perusahaan->id }}" {!! $select !!}>{{ $klaster_perusahaan->nama }}</option>
                    @endforeach
                </select>
            </div>
			<div class="col-lg-6 mt-4">
                <div class="form-group">
                    <label>Aktif</label>
                    <div class="kt-checkbox-list">
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" name="is_active" class="is_active" @if ($actionform === 'update' && (bool) $bumn->is_active) checked="checked" @endif>
                            <span></span>
                        </label>
                    </div>
                </div>
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

<script type="text/javascript" src="{{asset('js/referensi/bumn/form.js')}}"></script>
