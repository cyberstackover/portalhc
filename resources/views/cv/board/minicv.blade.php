<style>
  .foto-talenta {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    height: 235px;
  }
</style>
<form class="kt-form kt-form--label-right" method="POST" enctype="multipart/form-data" id="form-import" action="/keterangan/import_excel" >
	@csrf
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="insert" />
	<div class="kt-portlet__body">
	    <div class="form-group row">
	    	<div class="col-lg-8">    
                <div class="form-group">
                  <label>Nama</label>
                  <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{$talenta->nama_lengkap}}" disabled/>
                </div>
                <div class="form-group">
                  <label>NIK</label>
                  <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{$talenta->nik}}" disabled/>
                </div>                
                <div class="form-group">
                  <label>Asal Instansi</label>
                  <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{$talenta->jenis_asal_instansi }} {{ ($talenta->instansi ? ' - '.$talenta->instansi : '') }}" disabled/>
                </div>
            </div>
	        <div class="col-lg-4">    
                <label>
                  <img class="foto-talenta"src="{{ \App\Helpers\CVHelper::getFoto($talenta->foto, ($talenta->jenis_kelamin == 'L' ? 'img/male.png': 'img/female.png')) }}" alt=""> 
                </label>
            </div>	            
	        <div class="col-lg-6">        	
                <label>BUMN/Perusahaan</label>
                <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{$talenta->nama_perusahaan}}" disabled/>
	        </div>	        	            
	        <div class="col-lg-6">        	
                <label>Jabatan</label>
                <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{$talenta->jabatan}}" disabled/>
	        </div>
	    </div>
	</div>
</form>
