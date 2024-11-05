@extends('layouts.app')
<style>
  .foto-talenta {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    height: 235px;
  }
</style>
@section('content')
<div class="col-lg-2">
    @include('cv.tab')
</div>
<div class="col-lg-10">
  <div class="kt-portlet kt-portlet--mobile">
      <div class="kt-portlet__head kt-portlet__head--lg">
          <div class="kt-portlet__head-label">
              <span class="kt-portlet__head-icon">
                  <i class="kt-font-brand flaticon-web"></i>
              </span>
              <h3 class="kt-portlet__head-title">
                  Biodata
              </h3>
          </div>
      </div>
      <div class="kt-portlet__body form">
        <form class="kt-form kt-form--label-right" method="post" id="form-datapokok" role="form" enctype="multipart/form-data" action="/cv/biodata/{{ $talenta->id }}/update">
        @csrf
        {{ method_field('POST') }}
        <div class="kt-portlet__body">
          <div class="form-group row">
              <div class="col-lg-6">   
                <div class="form-group"> 
                <label>Foto Talenta <span style="color: red">WAJIB</span><span style="color:red;font-size:x-small">(*max file size 25MB)</span></label></br>   
                <label>
                  <img class="foto-talenta" src="{{ \App\Helpers\CVHelper::getFoto($talenta->foto, ($talenta->jenis_kelamin == 'L' ? 'img/male.png': 'img/female.png')) }}" alt=""> 
                </label>
                <div></div>
                <div class="custom-file">
                  <input type="file" name="file_name" class="form-control" id="file_name">
                </div>
                </div>
              </div> 
              <div class="col-lg-6">    
                <div class="form-group">
                <label>Foto KTP / Passport <span style="color: red">WAJIB</span><span style="color:red;font-size:x-small">(*max file size 25MB)</span></label></br>   
                <label>
                  <img class="foto-talenta" src="{{ \App\Helpers\CVHelper::getFotoKtp($talenta->file_ktp, ($talenta->jenis_kelamin == 'L' ? 'img/ktp_male.png': 'img/ktp_female.png')) }}" alt=""> 
                </label>
                <div></div>
                <div class="custom-file">
                  <input type="file" name="file_ktp" class="form-control" id="file_ktp">
                </div>
                </div>
              </div> 
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Nama</label>

                  @if ($kategori_user == 1)
                  <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{$talenta->nama_lengkap}}"/>
                  @else
                  <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{$talenta->nama_lengkap}}" required/>
                  @endif

                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Gelar Akademik</label>
                  <input type="text" class="form-control" name="gelar" id="gelar" value="{{$talenta->gelar}}"/>
                </div>
              </div>
              <div class="col-lg-6">
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
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Kewarganegaraan</label>         
                  <div class="radio-inline">
                      <label class="radio">
                          <input type="radio" id="wni" checked="checked" name="kewarganegaraan" value="WNI">
                          <span></span>
                          WNI
                      </label>
                      <label class="radio">
                          <input type="radio" id="wna" name="kewarganegaraan" value="WNA">
                          <span></span>
                          WNA
                      </label>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">    
                <div class="form-group">
                  <label>NIK / Passport</label>

                  @if ($kategori_user == 1)
                  <input type="text" class="form-control" name="nik" maxlength="16" id="nik" value="{{$talenta->nik}}"/>
                  @else
                  <input type="text" class="form-control" name="nik" maxlength="16" id="nik" value="{{$talenta->nik}}" required/>
                  @endif

                </div>   
              </div>
              <div class="col-lg-6">   
                <div class="form-group">
                  <label>Status Kawin</label>
                  @if ($kategori_user == 1)
                  <select class="form-control kt-select2" name="id_status_kawin">
                      <option value="">- Pilih -</option> 
                      @foreach($status_kawin as $data)  
                        @php
                          $select = ($talenta->id_status_kawin != null && ($data->id == $talenta->id_status_kawin) ? 'selected="selected"' : '');
                        @endphp
                        <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                      @endforeach
                    </select>
                  @else
                  <select class="form-control kt-select2" name="id_status_kawin" required>
                      <option value="">- Pilih -</option> 
                      @foreach($status_kawin as $data)  
                        @php
                          $select = ($talenta->id_status_kawin != null && ($data->id == $talenta->id_status_kawin) ? 'selected="selected"' : '');
                        @endphp
                        <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                      @endforeach
                    </select>
                  @endif
                </div>
              </div>
              <div class="col-lg-4">        
                <div class="form-group">
                  <label>Negara</label>
                  @if ($kategori_user == 1)
                  <select class="form-control kt-select2 negara" name="negara" onchange="return onChangeNegara(this.value)"> 
                      <option value="INDONESIA">INDONESIA</option>
                      @foreach($negara as $data)  
                        @php
                          $select = ($talenta->id_kota != null && ($data->id == $talenta->refKota->provinsi_id) ? 'selected="selected"' : '');
                        @endphp
                        <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                      @endforeach
                    </select>
                  @else
                  <select class="form-control kt-select2 negara" name="negara" onchange="return onChangeNegara(this.value)" required> 
                      <option value="INDONESIA">INDONESIA</option>
                      @foreach($negara as $data)  
                        @php
                          $select = ($talenta->id_kota != null && ($data->id == $talenta->refKota->provinsi_id) ? 'selected="selected"' : '');
                        @endphp
                        <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                      @endforeach
                    </select>
                  @endif
                </div>
              </div>
              <div class="col-lg-4">        
                <div class="form-group">
                  <label>Provinsi</label>
                  @if ($kategori_user == 1)
                  @php
                  $disabled = ($talenta->id_kota != null && ($talenta->refKota->is_luar_negeri) ? 'disabled="true"' : '')
                @endphp
                <select class="form-control kt-select2 provinsi" name="id_provinsi" onchange="return onChangeProvinsi(this.value)" {!! $disabled !!}>
                <option value="">- Pilih -</option>
                @foreach($provinsi as $data)       
                  @php
                    $select = ($talenta->id_provinsi != null && ($data->id == $talenta->id_provinsi) ? 'selected="selected"' : '');
                  @endphp
                  <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                @endforeach
                </select>
                  @else
                  @php
                  $disabled = ($talenta->id_kota != null && ($talenta->refKota->is_luar_negeri) ? 'disabled="true"' : '')
                @endphp
                <select class="form-control kt-select2 provinsi" name="id_provinsi" onchange="return onChangeProvinsi(this.value)" {!! $disabled !!} required>
                  <option value="">- Pilih -</option>
                  @foreach($provinsi as $data)       
                    @php
                      $select = ($talenta->id_provinsi != null && ($data->id == $talenta->id_provinsi) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                  @endforeach
                  </select>
                  @endif 
                </div>
              </div>
              <div class="col-lg-4">        
                <div class="form-group">
                  <label>Tempat Lahir</label> 
                  @if ($kategori_user == 1)
                  @php
                  $disabled = ($talenta->id_kota != null ? '' : 'disabled="true"')
                @endphp
                <select class="form-control kt-select2 kota" name="id_kota" {!! $disabled !!}>           
                  <option value=""></option>
                  @foreach($kota as $data)       
                    @php
                      $select = ($data->id == $talenta->id_kota ? 'selected="selected"' : '')
                    @endphp
                    <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                  @endforeach
                </select>
                  @else
                  @php
                  $disabled = ($talenta->id_kota != null ? '' : 'disabled="true"')
                @endphp
                <select class="form-control kt-select2 kota" name="id_kota" {!! $disabled !!} required>           
                  <option value=""></option>
                  @foreach($kota as $data)       
                    @php
                      $select = ($data->id == $talenta->id_kota ? 'selected="selected"' : '')
                    @endphp
                    <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                  @endforeach
                </select>
                  @endif
                </div>
              </div>
              <div class="col-lg-6">        
                <div class="form-group">
                  <label>Tanggal Lahir</label>
                  @if ($kategori_user == 1)
                  @if($talenta->tanggal_lahir)
                    <input type="text" name="tanggal_lahir" class="form-control" readonly="" value="{{\App\Helpers\CVHelper::tglformat(@$talenta->tanggal_lahir)}}" id="kt_datepicker_3">
                  @else
                    <input type="text" name="tanggal_lahir" class="form-control" readonly="" id="kt_datepicker_3">
                  @endif
                  @else
                  @if($talenta->tanggal_lahir)
                    <input type="text" name="tanggal_lahir" class="form-control" readonly="" value="{{\App\Helpers\CVHelper::tglformat(@$talenta->tanggal_lahir)}}" id="kt_datepicker_3" required>
                  @else
                    <input type="text" name="tanggal_lahir" class="form-control" readonly="" id="kt_datepicker_3">
                  @endif
                  @endif
                  
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Agama</label>
                  @if ($kategori_user == 1)
                  <select class="form-control kt-select2" name="id_agama">
                      <option value="">- Pilih -</option>
                      @foreach($agama as $data)       
                        @php
                          $select = ($data->id == $talenta->id_agama ? 'selected="selected"' : '')
                        @endphp
                      <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                      @endforeach
                    </select>
                  @else
                  <select class="form-control kt-select2" name="id_agama" required>
                    <option value="">- Pilih -</option>
                    @foreach($agama as $data)       
                      @php
                        $select = ($data->id == $talenta->id_agama ? 'selected="selected"' : '')
                      @endphp
                    <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                    @endforeach
                  </select>
                  @endif
                  
                </div>
              </div>  
              <div class="col-lg-6">        
                <div class="form-group">
                  <label>Golongan Darah</label>
                  @if ($kategori_user == 1)
                  <select class="form-control kt-select2" id="golongan_darah" name="id_gol_darah">
                    <option value="">- Pilih -</option> 
                    @foreach($golongan_darah as $data)  
                      @php
                        $select = ($talenta->id_gol_darah != null && ($data->id == $talenta->id_gol_darah) ? 'selected="selected"' : '');
                      @endphp
                      <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                    @endforeach
                  </select>
                  @else
                  <select class="form-control kt-select2" id="golongan_darah" name="id_gol_darah" required>
                    <option value="">- Pilih -</option> 
                    @foreach($golongan_darah as $data)  
                      @php
                        $select = ($talenta->id_gol_darah != null && ($data->id == $talenta->id_gol_darah) ? 'selected="selected"' : '');
                      @endphp
                      <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                    @endforeach
                  </select>
                  @endif
                </div>
              </div>
              <div class="col-lg-6">        
                <div class="form-group">
                  <label>Suku</label>
                  @if ($kategori_user == 1)
                    <select class="form-control kt-select2 suku" name="id_suku">
                      <option value="">- Pilih -</option> 
                      @foreach($suku as $data)  
                        @php
                          $select = ($talenta->id_suku != null && ($data->id == $talenta->id_suku) ? 'selected="selected"' : '');
                        @endphp
                        <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                      @endforeach
                    </select>
                  @else
                    <select class="form-control kt-select2 suku" name="id_suku" required>
                      <option value="">- Pilih -</option> 
                      @foreach($suku as $data)  
                        @php
                          $select = ($talenta->id_suku != null && ($data->id == $talenta->id_suku) ? 'selected="selected"' : '');
                        @endphp
                        <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                      @endforeach
                    </select>
                  @endif
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Alamat</label>
                  @if ($kategori_user == 1)
                    <textarea class="form-control" rows="3" name="alamat" id="alamat">{{ $talenta->alamat }}</textarea>
                  @else
                    <textarea class="form-control" rows="3" name="alamat" id="alamat" required>{{ $talenta->alamat }}</textarea>
                  @endif
                </div>        
              </div>
              <div class="col-lg-4">        
                <div class="form-group">
                  <label>Email</label>

                  @if ($kategori_user == 1)
                  <input type="text" class="form-control" name="email" id="email" value="{{ $talenta->email }}"/>
                  @else
                  <input type="text" class="form-control" name="email" id="email" value="{{ $talenta->email }}" required/>
                  @endif

                </div>
              </div>
              <div class="col-lg-4">        
                <div class="form-group">
                  <label>No Handphone</label>
                  @if ($kategori_user == 1)
                  <input type="text" class="form-control" name="nomor_hp" id="nomor_hp" value="{{ $talenta->nomor_hp }}"/>
                  @else
                  <input type="text" class="form-control" name="nomor_hp" id="nomor_hp" value="{{ $talenta->nomor_hp }}" required/>
                  @endif
                </div>
              </div>
              <!-- <div class="col-lg-6">
                <div class="form-group append-sosmed">
                  <label>Social Media</label>
                  @if (count($trans_social_media) == 0)
                    <div class="input-group list-sosmed sosmed-master sosmed-append">
                      <select class="form-control kt-select2 sosmed-select" name="social_media_select[]">
                        @foreach($social_media as $data)       
                          <option value="{{ $data->id }}">{{ $data->nama }}</option>
                        @endforeach
                      </select>
                      <div class="input-group-append">
                          <input type="text" class="form-control sosmed-name" name="social_media_text[]" placeholder="Silahkan Isi">
                          <button class="btn btn-primary add-sosmed" type="button"><i class="flaticon2-plus icon-sm"></i></button>
                      </div>
                    </div>
                  @else
                    @foreach($trans_social_media as $key => $data_sosmed)  
                      <div class="input-group list-sosmed @if($key == 0) sosmed-master @else sosmed-append @endif">
                        <select class="form-control kt-select2 sosmed-select" name="social_media_select[]" id="social_media_select">
                          @foreach($social_media as $data)       
                            @php
                              $select = ($data->id == $data_sosmed->id_social_media ? 'selected="selected"' : '')
                            @endphp
                          <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                          @endforeach
                        </select>
                        <div class="input-group-append">
                          <input type="text" class="form-control sosmed-name" name="social_media_text[]" placeholder="Silahkan Isi" value="{{$data_sosmed->name_social_media}}">
                          @if($key == 0)
                            <button class="btn btn-primary add-sosmed" type="button"><i class="flaticon2-plus icon-sm"></i></button>
                          @else                            
                            <button class="btn btn-danger delete-sosmed" type="button"><i class="flaticon2-delete icon-sm"></i></button>
                          @endif
                        </div>
                      </div>
                    @endforeach 
                  @endif
                </div>         
              </div>   -->
              <div class="col-lg-4">        
                <div class="form-group">
                  <label>NPWP</label>
                  @if ($kategori_user == 1)
                  <input type="text" class="form-control" name="npwp" id="npwp" value="{{ $talenta->npwp }}"/>
                  @else
                  <input type="text" class="form-control" name="npwp" id="npwp" value="{{ $talenta->npwp }}" required/>
                  @endif
                </div>
              </div> 
              
              <div class="col-lg-6">
                <div class="form-group">
                <label>Talent Asal BUMN:</label>
                @if ($kategori_user == 1)
                <select class="form-control kt-select2 id_perusahaan" name="id_perusahaan">
                    <option value=""></option>
                    @foreach($perusahaans as $perusahaan)
                    @php
                    $select = !empty(old('id_perusahaan')) && in_array($perusahaan->id, old('id_perusahaan'))? 'selected="selected"' : (($perusahaan->id==$talenta->id_perusahaan)? 'selected="selected"' : '')
                    @endphp
                    <option value="{{ $perusahaan->id }}" {!! $select !!}>{{ $perusahaan->nama_lengkap }}</option>
                    @endforeach
                </select>
                @else
                <select class="form-control kt-select2 id_perusahaan" name="id_perusahaan" required>
                    @foreach($perusahaans as $perusahaan)
                    @php
                    $select = !empty(old('id_perusahaan')) && in_array($perusahaan->id, old('id_perusahaan'))? 'selected="selected"' : (($perusahaan->id==$talenta->id_perusahaan)? 'selected="selected"' : '')
                    @endphp
                    <option value="{{ $perusahaan->id }}" {!! $select !!}>{{ $perusahaan->nama_lengkap }}</option>
                    @endforeach
                </select>
                @endif
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                <label>Jenis Instansi:</label>
                @if ($kategori_user == 1)
                <select class="form-control kt-select2 id_jenis_asal_instansi" name="id_jenis_asal_instansi" onchange=" return onAsalInstansi(this.value) ">
                    <option value=""></option>
                    @foreach($jenisasalinstansis as $jenisasalinstansi)
                    @php
                    $select = !empty(old('id_jenis_asal_instansi')) && in_array($jenisasalinstansi->id, old('id_jenis_asal_instansi'))? 'selected="selected"' : (($jenisasalinstansi->id==$talenta->id_jenis_asal_instansi)? 'selected="selected"' : '')
                @endphp
                  <option value="{{ $jenisasalinstansi->id }}" {!! $select !!}>{{ $jenisasalinstansi->nama }}</option>
                    @endforeach
                </select>
                @else
                <select class="form-control kt-select2 id_jenis_asal_instansi" name="id_jenis_asal_instansi" onchange=" return onAsalInstansi(this.value) " required>
                    <option value=""></option>
                    @foreach($jenisasalinstansis as $jenisasalinstansi)
                    @php
                    $select = !empty(old('id_jenis_asal_instansi')) && in_array($jenisasalinstansi->id, old('id_jenis_asal_instansi'))? 'selected="selected"' : (($jenisasalinstansi->id==$talenta->id_jenis_asal_instansi)? 'selected="selected"' : '')
                @endphp
                  <option value="{{ $jenisasalinstansi->id }}" {!! $select !!}>{{ $jenisasalinstansi->nama }}</option>
                    @endforeach
                </select>
                @endif
              </div>
              </div>
              
              
              <div class="col-lg-6">
                <div class="form-group">
                <label>Asal Instansi:</label>
                @if ($kategori_user == 1)
                <select class="form-control kt-select2 id_asal_instansi" name="id_asal_instansi">
                    <option value=""></option>
                    @foreach($asalinstansis as $asalinstansi)
                    @php
                    $select = !empty(old('id_asal_instansi')) && in_array($asalinstansi->id, old('id_asal_instansi'))? 'selected="selected"' : (($asalinstansi->id==$talenta->id_asal_instansi)? 'selected="selected"' : '')
                @endphp
                  <option value="{{ $asalinstansi->id }}" {!! $select !!}>{{ $asalinstansi->nama }}</option>
                    @endforeach
                </select>
                @else
                <select class="form-control kt-select2 id_asal_instansi" name="id_asal_instansi" required>
                    <option value=""></option>
                    @foreach($asalinstansis as $asalinstansi)
                    @php
                    $select = !empty(old('id_asal_instansi')) && in_array($asalinstansi->id, old('id_asal_instansi'))? 'selected="selected"' : (($asalinstansi->id==$talenta->id_asal_instansi)? 'selected="selected"' : '')
                @endphp
                  <option value="{{ $asalinstansi->id }}" {!! $select !!}>{{ $asalinstansi->nama }}</option>
                    @endforeach
                </select>
                @endif
              </div>
              </div>
             
             
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Jabatan Asal Instansi:</label>
                  @if ($kategori_user == 1)
                    <input type="text" class="form-control kt-inputmask" name="jabatan_asal_instansi" id="jabatan_asal_instansi" value="{{!empty(old('jabatan_asal_instansi'))? old('jabatan_asal_instansi') : ($talenta->jabatan_asal_instansi != ''? $talenta->jabatan_asal_instansi : old('jabatan_asal_instansi'))}}"/>
                  @else
                    <input type="text" class="form-control kt-inputmask" name="jabatan_asal_instansi" id="jabatan_asal_instansi" value="{{!empty(old('jabatan_asal_instansi'))? old('jabatan_asal_instansi') : ($talenta->jabatan_asal_instansi != ''? $talenta->jabatan_asal_instansi : old('jabatan_asal_instansi'))}}"  required/>
                  @endif
                </div>
              </div>

              
              <div class="col-lg-6">
                <div class="form-group">
                <label>Eksisting</label>
                  <div class="kt-checkbox-list">
                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                      <input type="checkbox" name="is_existing" id="is_existing" @if($talenta->is_existing) checked="checked" @endif>
                      <span></span>
                    </label>
                  </div>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                <label>Talenta</label>
                  <div class="kt-checkbox-list">
                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                      <input type="checkbox" name="is_talenta" id="is_talenta" @if($talenta->is_talenta) checked="checked" @endif>
                      <span></span>
                    </label>
                  </div>
                </div>
              </div>

              
              <div class="col-lg-6">
                <div class="form-group">
                <label>Kategori Jabatan Talent:</label>
                @if ($kategori_user == 1)
                <select class="form-control kt-select2" name="id_kategori_jabatan_talent" id="id_kategori_jabatan_talent" onchange="return onGetNonTalent(this.value)">
                  <option value=""></option>
                  @foreach($kategorijabatans as $kategorijabatan)
                  @php
                  $select = !empty(old('id_kategori_jabatan_talent')) && in_array($kategorijabatan->id, old('id_kategori_jabatan_talent'))? 'selected="selected"' : (($kategorijabatan->id==$talenta->id_kategori_jabatan_talent)? 'selected="selected"' : '')
               @endphp
                <option value="{{ $kategorijabatan->id }}" {!! $select !!}>{{ $kategorijabatan->nama }}</option>
                  @endforeach
              </select>
                @else
                <select class="form-control kt-select2" name="id_kategori_jabatan_talent" id="id_kategori_jabatan_talent">
                  <option value=""></option>
                  @foreach($kategorijabatans as $kategorijabatan)
                  @php
                  $select = !empty(old('id_kategori_jabatan_talent')) && in_array($kategorijabatan->id, old('id_kategori_jabatan_talent'))? 'selected="selected"' : (($kategorijabatan->id==$talenta->id_kategori_jabatan_talent)? 'selected="selected"' : '')
               @endphp
                <option value="{{ $kategorijabatan->id }}" {!! $select !!}>{{ $kategorijabatan->nama }}</option>
                  @endforeach
              </select>
                @endif
                </div>
              </div>

              
              <div class="col-lg-6" id="divnontalent" style="display: none;">
                <div class="form-group">
                  <label>Kategori Non Talent:</label>
                  <select class="form-control id_kategori_non_talent" name="id_kategori_non_talent">
                      <option value=""></option>
                      @foreach($kategorinons as $kategorinon)
                      @php
                      $select = !empty(old('id_kategori_non_talent')) && in_array($kategorinon->id, old('id_kategori_non_talent'))? 'selected="selected"' : (($kategorinon->id==$talenta->id_kategori_non_talent)? 'selected="selected"' : '')
                   @endphp
                    <option value="{{ $kategorinon->id }}" {!! $select !!}>{{ $kategorinon->nama }}</option>
                      @endforeach
                  </select>
                </div>
              </div>
            

          </div>                    
        </div>
        <div class="kt-portlet__foot">
          <div class="kt-form__actions">
            <div class="row">
              <div class="col-lg-12">
                <button type="submit" class="btn btn-success" style=" float: right;">Simpan</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      </div>        
  </div>
</div>
@endsection

@section('addafterjs')
  <script type="text/javascript">
    var add_sosmed_btn = $('.add-sosmed');
    var urlupdate = "{{ route('cv.biodata.update', ['id_talenta' => $talenta->id]) }}";
    var jenis_kelamin = "{{ $talenta->jenis_kelamin }}";
    var kewarganegaraan = "{{ $talenta->kewarganegaraan }}";
    var datatalent = "{{ $talenta->id_kategori_data_talent }}";

    $('.kt-select2').select2({
        placeholder: "Pilih"
    });

    /*$('#id_jenis_asal_instansi').select2({
        placeholder: "Pilih"
    });

    $('#id_asal_instansi').select2({
        tags: true,
        placeholder: "Pilih"
    });

    $('#id_perusahaan').select2({
        tags: true,
        placeholder: "Pilih"
    });

    $('#social_media_select').select2({
        placeholder: "Pilih"
    });

    $('#id_kategori_jabatan_talent').select2({
        placeholder: "Pilih",
        allowClear: true
    });*/

      if(jenis_kelamin == "L"){
        $("#jk_l").prop('checked', true);
        $("#jk_p").prop('checked', false);
      }else{
        $("#jk_p").prop('checked', true);
        $("#jk_l").prop('checked', false);
      }

      if(kewarganegaraan == "WNI"){
        $("#wni").prop('checked', true);
        $("#wna").prop('checked', false);
      }else{
        $("#wna").prop('checked', true);
        $("#wni").prop('checked', false);
      }

      if(datatalent == 1){
        $("#data_existing").prop('checked', true);
        $("#data_talenta").prop('checked', false);
      }else{
        $("#data_talenta").prop('checked', true);
        $("#data_existing").prop('checked', false);
      }

      $('#wni').click(function(a){
          $("#nik").attr('maxlength','16');
      });
      $('#wna').click(function(a){
          $("#nik").removeAttr('maxLength');
      });
      
      $('#nik').on('keypress', function(evt){
        if ($('#wni').is(':checked')){
          if (evt.which < 48 || evt.which > 57)
          {
            evt.preventDefault();
          }
        }
      });

      /*function yesnoCheck() {
          if (document.getElementById('is_existing').checked) {
              document.getElementById('ifYes').style.display = 'none';
              document.getElementById('ifYes1').style.display = 'none';
          } 
          else if(document.getElementById('is_talenta').checked) {
              document.getElementById('ifYes').style.display = 'block';
              document.getElementById('ifYes1').style.display = 'block';
         }
      }*/
      
  </script>
  <script>
    $(document).ready(function(){
      $(".add-sosmed").click(function(){
        var sosmed_master = $('.sosmed-master').clone();
        $('.append-sosmed').append(sosmed_master);
        sosmed_last = $('.append-sosmed').find('.list-sosmed').last();
        sosmed_last.find('.sosmed-select').val('');
        sosmed_last.find('.sosmed-name').val('');
        sosmed_last.addClass('sosmed-append');
        sosmed_last.removeClass('sosmed-master');
        sosmed_last.find('.add-sosmed').removeClass('btn-primary').addClass('btn-danger');
        sosmed_last.find('.add-sosmed').removeClass('add-sosmed').addClass('delete-sosmed');
        sosmed_last.find('.flaticon2-plus').removeClass('flaticon2-plus').addClass('flaticon2-delete');
        
        $('.delete-sosmed').click(function(a){
            $(this).closest('.list-sosmed').remove();
        });
      });

      $('.delete-sosmed').click(function(a){
          $(this).closest('.list-sosmed').remove();
      });
    });
    </script>
  <script type="text/javascript" src="{{asset('js/cv/datepicker.js')}}"></script>
<script type="text/javascript">
function onChangeProvinsi(id_provinsi){
  $(".kota").prop('disabled', false);
    $.ajax({
        url: "/fetch/referensi/getkota?id_provinsi="+id_provinsi,
        type: "POST",
        dataType: "json", 
        success: function(data){
                 var contentData = "";
                 $(".kota").empty();
                 //contentData += "<option value=''>"+data.length+"</option>";
                 for(var i = 0, len = data.length; i < len; ++i) {
                     contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
                 }
                 $(".kota").append(contentData);
                 $(".kota").trigger("change");
                                     
        }
    });
}

function onChangeNegara(id_provinsi){
    if(id_provinsi == "INDONESIA"){
      $(".provinsi").prop('disabled', false);
        $(".kota").empty();
        $.ajax({
          url: "/fetch/referensi/getallprovinsi",
          type: "POST",
          dataType: "json", 
          success: function(data){
                   var contentData = "";
                   $(".provinsi").empty();
                   //contentData += "<option value=''>"+data.length+"</option>";
                   for(var i = 0, len = data.length; i < len; ++i) {
                       contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
                   }
                   $(".provinsi").append(contentData);
                   $(".provinsi").trigger("change");
                                       
          }
      });
    }else{
      $(".provinsi").prop('disabled', true);
        $("#provinsi").empty();
      $(".kota").prop('disabled', false);
      $.ajax({
          url: "/fetch/referensi/getkota?id_provinsi="+id_provinsi,
          type: "POST",
          dataType: "json", 
          success: function(data){
                   var contentData = "";
                   $(".kota").empty();
                   //contentData += "<option value=''>"+data.length+"</option>";
                   for(var i = 0, len = data.length; i < len; ++i) {
                       contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
                   }
                   $(".kota").append(contentData);
                   $(".kota").trigger("change");
                                       
          }
      });
    }
}

function onAsalInstansi(id_jenis_asal_instansi){
    $.ajax({
        url: "/administrasi/kelengkapansk/getasalinstansi?id_jenis_asal_instansi="+id_jenis_asal_instansi,
        type: "POST",
        dataType: "json", 
        success: function(data){
                 var contentData = "";
                 $(".id_asal_instansi").empty();
                 //contentData += "<option value=''>"+data.length+"</option>";
                 for(var i = 0, len = data.length; i < len; ++i) {
                     contentData += "<option value='"+data[i].id+"'>"+data[i].nama+"</option>";
                 }
                 $(".id_asal_instansi").append(contentData);
                 $(".id_asal_instansi").trigger("change");
                                     
        }
    });
}

function onGetNonTalent(id_kategori_jabatan_talent){
  if(id_kategori_jabatan_talent == 7){
    $('#divnontalent').removeAttr('style');
  } else {
    $('#divnontalent').css('display', 'none');
  }
}

</script>
@endsection