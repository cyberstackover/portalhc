<form class="kt-form kt-form--label-right" method="POST" id="form-kelasbumn">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$kelasmasters->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Kelas BUMN</label>
				<select class="form-control kt-select2" name="kelas_bumn_id">
                    <option value=""></option>
                    @foreach($kelasbumns as $kelasbumn)
                    @php
            	      $select = !empty(old('kelas_bumn_id')) && in_array($kelasbumn->id, old('kelas_bumn_id'))? 'selected="selected"' : ($actionform == 'update' && ($kelasbumn->id==$kelasmasters->kelas_bumn_id)? 'selected="selected"' : '')
            	   @endphp
	                <option value="{{ $kelasbumn->id }}" {!! $select !!}>{{ $kelasbumn->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Standar Direksi Min</label>
				<input type="text" class="form-control" name="std_direksi" id="std_direksi" value="{{!empty(old('std_direksi'))? old('std_direksi') : ($actionform == 'update' && $kelasmasters->std_direksi != ''? $kelasmasters->std_direksi : old('std_direksi'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Standar Direksi Max</label>
				<input type="text" class="form-control" name="std_direksi_max" id="std_direksi_max" value="{{!empty(old('std_direksi_max'))? old('std_direksi_max') : ($actionform == 'update' && $kelasmasters->std_direksi_max != ''? $kelasmasters->std_direksi_max : old('std_direksi_max'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Standar Komisaris/Pengawas</label>
				<input type="text" class="form-control" name="std_komwas" id="std_komwas" value="{{!empty(old('std_komwas'))? old('std_komwas') : ($actionform == 'update' && $kelasmasters->std_komwas != ''? $kelasmasters->std_komwas : old('std_komwas'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Standar Komisaris/Pengawas Max</label>
				<input type="text" class="form-control" name="std_komwas_max" id="std_komwas_max" value="{{!empty(old('std_komwas_max'))? old('std_komwas_max') : ($actionform == 'update' && $kelasmasters->std_komwas_max != ''? $kelasmasters->std_komwas_max : old('std_komwas_max'))}}" />
			</div>
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Perusahaan</label>
                <select class="form-control kt-select2" id="perusahaan_id" name="perusahaan_id[]" multiple="multiple" >
                	@foreach($perusahaans as $value)
                	        	
        	        	@php
                           $select = !empty(old('perusahaan_id')) && in_array($value->id, old('perusahaan_id'))? 'selected="selected"' : ($actionform == 'update' && in_array($value->id, $kelashasbumns)? 'selected="selected"' : '')
                        @endphp	                            	        	
        	         <option value="{{ $value->id }}" {!! $select !!}>{{ $value->nama_lengkap }}</option>
                	        
                	@endforeach
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

<script type="text/javascript" src="{{asset('js/organ/kelasbumn/form.js')}}"></script>