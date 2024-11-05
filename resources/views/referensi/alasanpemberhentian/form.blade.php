<form class="kt-form kt-form--label-right" method="POST" id="form-alasanpemberhentian">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$alasanpemberhentian->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Jenis Alasan Pemberhentian</label>
				<select class="form-control kt-select2" name="id_kategori_pemberhentian">
                    <option value=""></option>
                    @foreach($kategoripemberhentians as $kategoripemberhentian)
                    @php
            	      $select = !empty(old('id_kategori_pemberhentian')) && in_array($kategoripemberhentian->id, old('id_kategori_pemberhentian'))? 'selected="selected"' : ($actionform == 'update' && ($kategoripemberhentian->id==$alasanpemberhentian->id_kategori_pemberhentian)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $kategoripemberhentian->id }}" {!! $select !!}>{{ $kategoripemberhentian->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Keterangan</label>
				<input type="text" class="form-control" name="keterangan" id="keterangan" value="{{!empty(old('keterangan'))? old('keterangan') : ($actionform == 'update' && $alasanpemberhentian->keterangan != ''? $alasanpemberhentian->keterangan : old('keterangan'))}}" />
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

<script type="text/javascript" src="{{asset('js/referensi/alasanpemberhentian/form.js')}}"></script>