<style>
  .foto-talenta {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    height: 235px;
  }
</style>
<form class="kt-form kt-form--label-right" id="formjabatantalent" method="POST">
	@csrf
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="insert" />
	<div class="kt-portlet__body">
	    <div class="form-group row">
	    	<div class="col-lg-8">    
                <div class="form-group">
                  <label>Nama</label>
                  <input type="text" class="form-control" value="{{$talenta->nama_lengkap}}" disabled/>
                </div>
                <div class="form-group">
                  <label>NIK</label>
                  <input type="text" class="form-control" value="{{$talenta->nik}}" disabled/>
                </div>                
                {{-- <div class="form-group">
                  <label>Kategori Data Talent:</label>
                  <select class="form-control kt-select2" name="id_kategori_data_talent" id="id_kategori_data_talent" onchange="datatalentchange(this.value, {{$talenta->id}})">
                    <option value=""></option>
	                    @foreach($kategoridatas as $kategoridata)
	                    @php
	                    $select = !empty(old('id_kategori_data_talent')) && in_array($kategoridata->id, old('id_kategori_data_talent'))? 'selected="selected"' : (($kategoridata->id==$talenta->id_kategori_data_talent)? 'selected="selected"' : '')
	                 @endphp
	                  <option value="{{ $kategoridata->id }}" {!! $select !!}>{{ $kategoridata->nama }}</option>
	                    @endforeach
	                </select>
                </div> --}}
            </div>
	        <div class="col-lg-4">    
                <label>
                  <img class="foto-talenta"src="{{ \App\Helpers\CVHelper::getFoto($talenta->foto, ($talenta->jenis_kelamin == 'L' ? 'img/male.png': 'img/female.png')) }}" alt=""> 
                </label>
            </div>	            
	        <div class="col-lg-6">        	
                <label>Kategori Jabatan Talent:</label>
                <select class="form-control kt-select2" name="id_kategori_jabatan_talent" id="id_kategori_jabatan_talent" onchange="jabatantalentchange(this.value, {{$talenta->id}})">
	                  <option value=""></option>
	                  @foreach($kategorijabatans as $kategorijabatan)
	                  @php
	                  $select = !empty(old('id_kategori_jabatan_talent')) && in_array($kategorijabatan->id, old('id_kategori_jabatan_talent'))? 'selected="selected"' : (($kategorijabatan->id==$talenta->id_kategori_jabatan_talent)? 'selected="selected"' : '')
	               @endphp
	                <option value="{{ $kategorijabatan->id }}" {!! $select !!}>{{ $kategorijabatan->nama }}</option>
	                  @endforeach
	              </select>
	        </div>	        	            
	        {{-- <div class="col-lg-6">        	
                <label>Kategori Non Talent:</label>
                <select class="form-control kt-select2" name="id_kategori_non_talent" id="id_kategori_non_talent" onchange="nontalentchange(this.value, {{$talenta->id}})">
                      <option value=""></option>
                      @foreach($kategorinons as $kategorinon)
                      @php
                      $select = !empty(old('id_kategori_non_talent')) && in_array($kategorinon->id, old('id_kategori_non_talent'))? 'selected="selected"' : (($kategorinon->id==$talenta->id_kategori_non_talent)? 'selected="selected"' : '')
                   @endphp
                    <option value="{{ $kategorinon->id }}" {!! $select !!}>{{ $kategorinon->nama }}</option>
                      @endforeach
                  </select>
	        </div> --}}
	    </div>
	</div>
</form>
@section('addafterjs')
<script type="text/javascript">
  $('#id_kategori_data_talent,#id_kategori_jabatan_talent,#id_kategori_non_talent').select2({
      placeholder: "Pilih",
      allowClear: true
  });
</script>
@endsection