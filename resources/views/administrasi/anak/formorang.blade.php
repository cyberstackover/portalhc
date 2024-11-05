<form class="kt-form kt-form--label-right" method="POST" id="form-orang-anak">
	@csrf
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
			  <div class="form-group">
				<label>Nama <span style="color: red">*</span></label>
				<input type="text" class="form-control" name="nama_lengkap" id="nama" value=""/>
			  </div>
			</div>  
		</div>
		<div class="form-group row">   
			<div class="col-lg-3">        
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
						<input type="radio" class="kewarganegaraan" id="wni" checked="checked" name="kewarganegaraan" value="WNI">
						<span></span>
						WNI
					</label>
					<label class="radio">
						<input type="radio" class="kewarganegaraan" id="wna" name="kewarganegaraan" value="WNA">
						<span></span>
						WNA
					</label>
				</div>
			  </div>
			</div>  
			<div class="col-lg-6">        
			  <div class="form-group">
				<label>NIK / Passport <span style="color: red">*</span></label>
				<input type="text" class="form-control" maxlength="16" name="nik" id="nik" value=""/>
				<span class="text-danger" style="font-size: 10px" id="error_msg"></span>
			  </div>
			</div> 
		</div>
		<div class="form-group row">       
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
		</div>
	</div>
	<div class="kt-portlet__foot">
		<div class="kt-form__actions">
			<div class="row">
				<div class="col-lg-12">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript" src="{{asset('js/administrasi/anak/formorang.js')}}"></script>