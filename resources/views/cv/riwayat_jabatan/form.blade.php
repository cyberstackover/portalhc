<form action="{{route('cv.keluarga.store', ['id_talenta' => $id_talenta])}}" class="kt-form kt-form--label-right" method="POST" id="form-first">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
	<input type="hidden" name="formal_flag" id="actionform" readonly="readonly" value="TRUE"/>
	<div class="kt-portlet__body">
		<div class="row">
			<div class="col-lg-12">				
				<div class="form-group">
					<label>Perusahaan <span style="color: red">*WAJIB</span></label>

					<select class="form-control kt-select2" name="nama_perusahaan"  required> 
                      @foreach($perusahaans as $perusahaan)  
                        @php
                          $select = ($actionform == 'update' && ($perusahaan->nama_lengkap == $data->nama_perusahaan) ? 'selected="selected"' : '');
                        @endphp
                        <option value="{{ $perusahaan->nama_lengkap }}" {!! $select !!}>{{ $perusahaan->nama_lengkap }}</option>
                      @endforeach
                    </select>
				</div>
			</div>	
			<div class="col-lg-12">				
				<div class="form-group">
					<label>Jabatan</label>	
					@php
            	      $value = ($actionform == 'update'? $data->jabatan : '')
            	    @endphp					
					<textarea id="" class="form-control" rows="3" name="jabatan">{{ $value }}</textarea>
				</div>
			</div>	
			<div class="col-lg-12">				
				<div class="form-group">
					<label>Bidang Jabatan <span style="color: red">*WAJIB</span></label>

					<select class="form-control kt-select2" name="bidang_jabatan_id"  required> 
					<option value="">Pilih</option>
                      @foreach($bidang_jabatans as $bidang_jabatan)  
                        @php
                          $select = ($actionform == 'update' && ($bidang_jabatan->id == $data->bidang_jabatan_id) ? 'selected="selected"' : '');
                        @endphp
                        <option value="{{ $bidang_jabatan->id }}" {!! $select !!}>{{ $bidang_jabatan->nama }}</option>
                      @endforeach
                    </select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">				
				<div class="form-group">
					<label>Tanggal Awal <span style="color: red">*WAJIB</span></label></label>	
					@php
            	      $value = ($actionform == 'update'? \App\Helpers\CVHelper::tglformat(@$data->tanggal_awal) : '')
            	    @endphp				
					<input type="text" required="required" name="tanggal_awal" class="form-control" readonly=""  value="{{ $value }}" id="kt_datepicker_3">
				</div>
			</div>	
			<div class="col-lg-12">				
				<div class="form-group">
					<label>Tanggal Akhir <span style="color: red">*WAJIB</span></label>	
					@php
            	      $value = ($actionform == 'update'? \App\Helpers\CVHelper::tglformat(@$data->tanggal_akhir) : '')
            	    @endphp				
					<input type="text" name="tanggal_akhir" class="form-control" readonly=""  value="{{ $value }}" id="kt_datepicker_3">
				</div>
			</div>	
		</div>
		<div class="row">
			<div class="col-lg-12">				
				<div class="form-group">
					<label>Tupoksi <span style="color: red">*WAJIB</span></label>	
					@php
            	      $value = ($actionform == 'update'? $data->tupoksi : '')
            	    @endphp					
					<textarea id="" class="form-control" rows="3" name="tupoksi">{{ $value }}</textarea>
				</div>
			</div>	
			<div class="col-lg-12">				
				<div class="form-group">
					<label>Achievement</label>	
					@php
            	      $value = ($actionform == 'update'? $data->achievement : '')
            	    @endphp					
					<textarea id="" class="form-control" rows="3" name="achievement">{{ $value }}</textarea>
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
<script type="text/javascript" src="{{asset('js/cv/datepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('js/cv/riwayat_organisasi/form-formal.js')}}"></script>
<script type="text/javascript">
</script>