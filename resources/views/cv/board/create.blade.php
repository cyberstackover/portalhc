<link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css" />
<form class="kt-form kt-form--label-right" method="POST" id="form-first">
	@csrf
	<div class="kt-portlet__body">
	    <div class="form-group row">
	        <div class="col-lg-6">
	          <div class="form-group">
	            <label>Nama <span style="color: red">*</span></label>
	            <input type="text" class="form-control" name="nama_lengkap" id="nama" value=""/>
	          </div>
	        </div>
	        <div class="col-lg-6">        
	          <div class="form-group">
	            <label>Gelar Akademik</label>
	            <input type="text" class="form-control" name="gelar" id="gelar" value=""/>
	          </div>
	        </div>      
	        <div class="col-lg-4">        
	          <div class="form-group">
	            <label>Jenis Kelamin</label>         
	            <div class="radio-inline">
	                <label class="radio">
	                    <input type="radio" id="jk_l" checked="checked" name="jenis_kelamin" value="L">
	                    <span></span>
	                    Laki-Laki
	                </label>
	                <label class="radio">
	                    <input type="radio" id="jk_p" name="jenis_kelamin" value="P">
	                    <span></span>
	                    Perempuan
	                </label>
	            </div>
	          </div>
	        </div>    
	        <div class="col-lg-3">        
	          <div class="form-group">
	            <label>Kewarganegaraan</label>         
	            <div class="radio-inline">
	                <label class="radio">
	                    <input type="radio" id="wni" class="kewarganegaraan" checked="checked" name="kewarganegaraan" value="WNI">
	                    <span></span>
	                    WNI
	                </label>
	                <label class="radio">
	                    <input type="radio" id="wna" class="kewarganegaraan" name="kewarganegaraan" value="WNA">
	                    <span></span>
	                    WNA
	                </label>
	            </div>
	          </div>
	        </div>  
	        <div class="col-lg-5">        
	          <div class="form-group">
	            <label>NIK / Passport <span style="color: red">*</span></label>
	            <input type="text" class="form-control" name="nik" id="nik_cv" />
				<span class="text-danger" style="font-size: 10px" id="error_msg2"></span>
	          </div>
	        </div>        
	        <div class="col-lg-6">        
	          <div class="form-group">
	            <label>Tempat Lahir</label>
	            <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value=""/>
	          </div>
	        </div>
	        <div class="col-lg-6">        
	          <div class="form-group">
	            <label>Tanggal Lahir <span style="color: red">*</span></label>
              	<input type="text" name="tanggal_lahir" class="form-control" readonly="" id="kt_datepicker_3">
	          </div>
	        </div>
	        <div class="col-lg-12">
	          <div class="form-group">
	            <label>Alamat</label>
	            <textarea class="form-control" rows="3" name="alamat" id="alamat"></textarea>
	          </div>        
	        </div>
	        <div class="col-lg-6">        
	          <div class="form-group">
	            <label>Email</label>          
	            <input type="text" class="form-control" name="email" id="email"/>
	          </div>
	        </div>
	        <div class="col-lg-6">        
	          <div class="form-group">
	            <label>No Handphone</label>         
	            <input type="text" class="form-control" name="nomor_hp" id="nomor_hp"/>
	          </div>
	        </div>
	        <div class="col-lg-6">        
	          <div class="form-group">
	            <label>NPWP <span style="color: red">*</span></label>         
	            <input type="text" class="form-control" name="npwp" id="npwp"/>
	          </div>
	        </div>
	        <div class="col-lg-6">
	          <div class="form-group">
	            <label>Agama</label>
	            <select class="form-control kt-select2" name="id_agama">
	              @foreach($agama as $data)  
	              <option value="{{ $data->id }}">{{ $data->nama }}</option>
	              @endforeach
	            </select>
	          </div>
	        </div>  
	        <div class="col-lg-6">
	          <div class="form-group">
	            <label>BUMN</label>
				@if ($id_perusahaan)
	            <select class="form-control kt-select2" name="id_perusahaan">
	              	@foreach($perusahaan as $data)  
					@if($data->id == $id_perusahaan)
						<option value="{{ $data->id }}" selected>{{ $data->nama_lengkap }}</option>
					@endif
					@endforeach
	            </select>
				@else
	            <select class="form-control kt-select2" name="id_perusahaan">
					<option value=""></option>
	              	@foreach($perusahaan as $data)  
					<option value="{{ $data->id }}">{{ $data->nama_lengkap }}</option>
					@endforeach
	            </select>
				@endif
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
<script type="text/javascript">
$(document).ready(function(){
    $('#wni').click(function(a){
      $("#nik").attr('maxlength','16');
    });
    $('#wna').click(function(a){
      $("#nik").removeAttr('maxLength');
    });

});
</script>	
<script type="text/javascript" src="{{asset('js/cv/datepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('js/cv/board/form.js')}}"></script>	
