<style>
  .nav-item {
    margin:-5px;
  }
  .nav-text {
    font-size:small;
  }

  .foto-talenta {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    height: 150px;
  }

</style>

<div class="form-group row">
  <div class="col-lg-12">
    <div class="kt-portlet__body" >
      <div class="form-group row">
        <div class="col-lg-12 text-center"> 
          @if(\Auth::user()->hasRole('Admin') || \Auth::user()->hasRole('Bumn'))
          <a class="btn btn-outline-success btn-icon-sm pull-right" target="_blank" href="/cv/biodata/{{$talenta->id}}/index">
              <i class="flaticon-edit"></i>Edit CV
          </a>
          <img class="foto-talenta" style="margin-left: 90px;" src="{{ \App\Helpers\CVHelper::getFoto($talenta->foto, ($talenta->jenis_kelamin == 'L' ? 'img/male.png': 'img/female.png')) }}" alt=""> 
          @else
          <img class="foto-talenta" src="{{ \App\Helpers\CVHelper::getFoto($talenta->foto, ($talenta->jenis_kelamin == 'L' ? 'img/male.png': 'img/female.png')) }}" alt=""> 
          @endif
        </div> 
        <div class="col-lg-12 text-center">
          <div class="form-group">
            <h4>{{$talenta->nama_lengkap}}</h4>
            {{$talenta->nik}}
          </div>
        </div>
      </div>
  </div>
  <div class="col-lg-12" style="margin-top:-50px;">
      <!--begin: Datatable -->
      <div class="card card-custom">
          <div class="card-header card-header-tabs-line">
              <div class="card-toolbar">
                  <ul class="nav nav-tabs nav-tabs mb-5">
                    <li class="nav-item">
                        <a class="nav-link active a-step-1" data-toggle="tab" href="#Biodata">
                            <span class="nav-text">Biodata</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link a-step-2" data-toggle="tab" href="#Nilai">
                            <span class="nav-text">Nilai, Visi dan Interest</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link a-step-2" data-toggle="tab" href="#Aspirasi">
                            <span class="nav-text">Aspirasi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link a-step-3" data-toggle="tab" href="#Jabatan">
                            <span class="nav-text">Jabatan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link a-step-5" data-toggle="tab" href="#Organisasi">
                            <span class="nav-text">Organisasi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link a-step-6" data-toggle="tab" href="#Penghargaan">
                            <span class="nav-text">Penghargaan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link a-step-2" data-toggle="tab" href="#Pendidikan">
                            <span class="nav-text">Pendidikan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link a-step-4" data-toggle="tab" href="#Publikasi">
                            <span class="nav-text">Publikasi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link a-step-7" data-toggle="tab" href="#Pengalaman">
                            <span class="nav-text">Pembicara Narasumber</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link a-step-4" data-toggle="tab" href="#Keahlian">
                            <span class="nav-text">Keahlian</span>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link a-step-4" data-toggle="tab" href="#Referensi">
                            <span class="nav-text">Referensi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link a-step-8" data-toggle="tab" href="#Keluarga">
                            <span class="nav-text">Keluarga</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link a-step-10" data-toggle="tab" href="#Pajak">
                            <span class="nav-text">Pajak</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link a-step-9" data-toggle="tab" href="#Summary">
                            <span class="nav-text">Summary</span>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link a-step-12" data-toggle="tab" href="#LHKPN">
                            <span class="nav-text">LHKPN</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link a-step-13" data-toggle="tab" href="#TANI">
                            <span class="nav-text">TANI</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link a-step-11" data-toggle="tab" href="#Kesehatan">
                            <span class="nav-text">Kesehatan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link a-step-14" data-toggle="tab" href="#Sosmed">
                            <span class="nav-text">Social Media</span>
                        </a>
                    </li>
                    @can('assessment_upload-create')
                    <li class="nav-item">
                        <a class="nav-link a-step-14" data-toggle="tab" href="#Assessment">
                            <span class="nav-text">Hasil Assessment</span>
                        </a>
                    </li>
                    @endcan
                </ul>
              </div>
          </div>
          <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="Biodata" role="tabpanel" aria-labelledby="Biodata">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Biodata
                            </h3>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                      <div class="form-group row">
                        <table class="table table-striped- table-bordered table-hover table-checkable">
                          <tr> 
                            <td class="kt-font-bold">Nama</td>
                            <td>{{$talenta->nama_lengkap}}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">Gelar Akademik</td>
                            <td>{{$talenta->gelar}}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">Jenis Kelamin</td>
                            <td>{{ ($talenta->jenis_kelamin=='P'? 'Perempuan':'Laki-laki')}}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">Kewarganegaraan</td>
                            <td>{{$talenta->kewarganegaraan}}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">NIK / Passport</td>
                            <td>{{$talenta->nik}} 
                                @if(!empty($talenta->file_ktp)) 
                                  <a target="_blank" href="{{ \URL::to('img/ktp/'.$talenta->file_ktp) }}"><i class="flaticon2-file" ></i></a>
                                @endif
                            </td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">Status Kawin</td>
                            <td>{{ @$talenta->statusKawin->nama }}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">Tempat Lahir</td>
                            <td>{{ @$talenta->refKota->nama }}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">Tanggal Lahir</td>
                            <td>{{\App\Helpers\CVHelper::tglformat(@$talenta->tanggal_lahir)}}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">Agama</td>
                            <td>{{ @$talenta->agama->nama }}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">Golongan Darah</td>
                            <td>{{ @$talenta->golonganDarah->nama }}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">Suku</td>
                            <td>{{ @$talenta->suku }}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">Alamat</td>
                            <td>{{ $talenta->alamat }}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">Email</td>
                            <td>{{ $talenta->email }}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">No Handphone</td>
                            <td>{{ $talenta->nomor_hp }}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">NPWP</td>
                            <td>{{ $talenta->npwp }}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">Asal Instansi</td>
                            <td>{{ @$talenta->jenisAsalInstansi->nama }} - {{ @$talenta->asalInstansi->nama }}</td>
                          </tr>
                          <tr> 
                            <td class="kt-font-bold">Jabatan Saat Ini</td>
                            <td></td>
                          </tr>
                        </table>  
                      </div>  
                    </div>
                </div>
                
                <!-- Datatable -->
                <div class="tab-pane fade" id="Pendidikan" role="tabpanel" aria-labelledby="Pendidikan">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Data Pendidikan
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      <div class="table-responsive">
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatablependidikan">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th >Jenjang - Penjurusan</th>  
                                    <th >Perguruan Tinggi</th> 
                                    <th >Tahun Lulus</th>    
                                    <th >Kota / Negara</th>   
                                    <th >Penghargaan yang Didapat</th> 
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    </div>
                  </div>
                    
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Data Pelatihan
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      <div class="table-responsive">
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatablepelatihan">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th >Latihan dan Pengembangan Kompetensi</th>  
                                    <th >Penyelanggara/Kota</th>
                                    <th >Jenis Diklat</th>  
                                    <th >Tahun Diklat</th>  
                                    <th >Nomor Sertifikasi</th>            
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->
                
                <!-- Datatable -->
                <div class="tab-pane fade" id="Jabatan" role="tabpanel" aria-labelledby="Jabatan">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                            Data Riwayat Jabatan BUMN Group
                          </h3>
                      </div>
                  </div>
                  <div class="">
                    <div class="">
                      <table class="table table-striped table-bordered table-hover table-checkable" id="datatablejabatan">
                          <thead>
                              <tr>
                                <th width="5%">No.</th>
                                <th >Jabatan</th>
                                <th >Uraian Singkat Tugas dan Kewenangan</th>
                                <th >Tanggal Mulai</th>
                                <th >Tanggal Akhir</th>
                                <th >Kategori Keahlian</th>
                                <th >Achievement (Maksimal 5 Pencapaian)</th>
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                    </div>
                  </div>
                    
                  <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                      <h3 class="kt-portlet__head-title">
                        Data Riwayat Jabatan Lain
                      </h3>
                    </div>
                  </div>
                  <div class="">
                    <div class="">
                      <table class="table table-striped table-bordered table-hover table-checkable" id="datatablejabatanlain">
                          <thead>
                              <tr>
                                <th width="5%">No.</th>
                                <th >Penugasan</th>
                                <th >Tupoksi</th>
                                <th >Tanggal Mulai</th>
                                <th >Tanggal Akhir</th>
                                <th >Kategori Keahlian</th>
                                <th >Instansi/ Perusahaan</th>
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->


                <!-- Datatable -->
                <div class="tab-pane fade" id="Keahlian" role="tabpanel" aria-labelledby="Keahlian">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                            Keahlian
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="form-group row">
                      <?php $count = 0; ?>
                      @foreach($keahlian as $data)  
                        @if($count == 0)
                          <div class="col-lg-3">
                            <div class="form-group">
                              <div class="checkbox-list">
                        @endif
                              <label class="checkbox"> 
                              @if($trans_keahlian->contains('id_keahlian', $data->id))
                                <input type="checkbox" checked name="keahlian" value="{{$data->id}}" disabled>     
                              @else
                                <input type="checkbox" name="keahlian" value="{{$data->id}}" disabled>   
                              @endif
                              <span></span>{{ $data->jenis_keahlian }}</label>
                        @if($count == 0)
                              </div>
                            </div>
                          </div>   
                        @endif
                        <?php
                          $count++;
                          if($count == 1){
                            $count = 0;
                          }
                        ?> 
                      @endforeach
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->
                
                <!-- Datatable -->
                <div class="tab-pane fade" id="Organisasi" role="tabpanel" aria-labelledby="Organisasi">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                            Data Organisasi Formal
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatableorganisasi">
                            <thead>
                                <tr>
                                  <th width="5%">No.</th>
                                  <th >Nama Organisasi</th>  
                                  <th >Jabatan</th> 
                                  <th >Rentang Waktu</th> 
                                  <th >Uraian Singkat</th>   
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                  </div>
                    
                  <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                      <h3 class="kt-portlet__head-title">
                        Data Organisasi Non Formal
                      </h3>
                    </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="datatableorganisasinonformal">
                          <thead>
                              <tr>
                                <th width="5%">No.</th>
                                <th >Nama Organisasi</th>  
                                <th >Jabatan</th> 
                                <th >Rentang Waktu</th> 
                                <th >Uraian Singkat</th>   
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->

                <!-- Datatable -->
                <div class="tab-pane fade" id="Penghargaan" role="tabpanel" aria-labelledby="Penghargaan">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Data Penghargaan
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatablepenghargaan">
                            <thead>
                                <tr>
                                  <th width="5%">No.</th>
                                  <th >Jenis Penghargaan</th>  
                                  <th >Tingkat</th> 
                                  <th >Diberikan Oleh</th> 
                                  <th >Tahun</th>   
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->
                
                <!-- Datatable -->
                <div class="tab-pane fade" id="Publikasi" role="tabpanel" aria-labelledby="Publikasi">
                  <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                      <h3 class="kt-portlet__head-title">
                        Data Publikasi
                      </h3>
                    </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="datatablekaryailmiah">
                          <thead>
                              <tr>
                                <th width="5%">No.</th>
                                <th >Judul dan Media Publikasi</th> 
                                <th >Tahun</th>                
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->
                
                <!-- Datatable -->
                <div class="tab-pane fade" id="Referensi" role="tabpanel" aria-labelledby="Referensi">
                  <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                      <h3 class="kt-portlet__head-title">
                        Data Referensi
                      </h3>
                    </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="datatablereferensi">
                          <thead>
                              <tr>
                                <th width="5%">No.</th>
                                <th >Nama</th>  
                                <th >Perusahaan</th> 
                                <th >Jabatan</th> 
                                <th >Nomor Handphone</th>                  
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->
                
                <!-- Datatable -->
                <div class="tab-pane fade" id="Pengalaman" role="tabpanel" aria-labelledby="Pengalaman">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Data Pengalaman Sebagai Pembicara/Narasumber/Juri
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="datatablepengalaman">
                          <thead>
                              <tr>
                                <th width="5%">No.</th>
                                <th >Acara/Thema</th>  
                                <th >Penyelenggara</th> 
                                <th >Periode</th> 
                                <th >Lokasi dan Peserta</th>   
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->

                <!-- Datatable -->
                <div class="tab-pane fade" id="Keluarga" role="tabpanel" aria-labelledby="Keluarga">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Data Keluarga
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="datatablekeluarga">
                          <thead>
                              <tr>
                                <th width="5%">No.</th>
                                <th >Nama</th>  
                                <th >Tempat Lahir</th> 
                                <th >Tanggal Lahir</th> 
                                <th >Tanggal Menikah</th>   
                                <th >Pekerjaan</th>   
                                <th >Keterangan</th>    
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                    </div>
                  </div>
              
                  <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                      <h3 class="kt-portlet__head-title">
                        Data Anak
                      </h3>
                    </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="datatableanak">
                          <thead>
                              <tr>
                                <th width="5%">No.</th>
                                <th >Nama</th>  
                                <th >Tempat Lahir</th> 
                                <th >Tanggal Lahir</th> 
                                <th >Jenis Kelamin</th>   
                                <th >Pekerjaan</th>   
                                <th >Keterangan</th>                
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->

                <!-- Datatable -->
                <div class="tab-pane fade" id="Summary" role="tabpanel" aria-labelledby="Summary">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Summary
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label>Uraian Kompetensi</label>
                          <textarea id="summernote3" class="form-control" rows="10" name="kompetensi" disabled>{{ @$talenta->summary->kompetensi }}</textarea>
                        </div>        
                      </div>  
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label>Uraian Kepribadian</label>
                          <textarea id="summernote4"class="form-control" rows="10" name="kepribadian" disabled>{{ @$talenta->summary->kepribadian }}</textarea>
                        </div>        
                      </div> 
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->
                
                <!-- Datatable -->
                <div class="tab-pane fade" id="Pajak" role="tabpanel" aria-labelledby="Pajak">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Data SPT Tahunan
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="datatablepajak">
                          <thead>
                              <tr>
                                <th width="5%">No.</th>
                                <th >File SPT Tahunan</th>  
                                <th >Tahun</th>
                                <th >User</th>    
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->

                <!-- Datatable -->
                <div class="tab-pane fade" id="Kesehatan" role="tabpanel" aria-labelledby="Kesehatan">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Data Kesehatan
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="datatablekesehatan">
                          <thead>
                              <tr>
                                <th width="5%">No.</th>
                                <th >Tahun</th>
                                <th >Nilai Kesehatan</th>
                                <th >Instansi Kesehatan</th>      
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->
                
                <!-- Datatable -->
                <div class="tab-pane fade" id="LHKPN" role="tabpanel" aria-labelledby="LHKPN">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Data LHKPN
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="datatablelhkpn">
                          <thead>
                              <tr>
                                <th width="5%">No.</th>
                                <th >File LHKPN</th>  
                                <th >Tanggal Pelaporan</th>
                                <th >Jumlah(Rp)</th>
                                <th >User</th>           
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->

                <!-- Datatable -->
                <div class="tab-pane fade" id="TANI" role="tabpanel" aria-labelledby="TANI">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Data TANI
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="datatabletani">
                          <thead>
                              <tr>
                                <th width="5%">No.</th> 
                                <th >Tahun Awal</th>
                                <th >Tahun Akhir</th>
                                <th >Jumlah(Rp)</th>                   
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->
                
                <!-- Datatable -->
                <div class="tab-pane fade" id="Assessment" role="tabpanel" aria-labelledby="Assessment">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Data Hasil Assessment
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="datatableassessment">
                          <thead>
                              <tr>
                                <th width="5%">No.</th>
                                <th >File</th>  
                                <th >Tanggal</th>
                                <th >User</th>                    
                              </tr>
                          </thead>
                          <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->
                
                <!-- Datatable -->
                <div class="tab-pane fade" id="Sosmed" role="tabpanel" aria-labelledby="Sosmed">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Social Media
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      
                    @foreach($trans_social_media as $key => $data_sosmed)  
                      <div class="input-group list-sosmed @if($key == 0) sosmed-master @else sosmed-append @endif">
                        <select class="form-control kt-select2 sosmed-select" name="social_media_select[]" id="social_media_select" disabled="disabled">
                          @foreach($social_media as $data)       
                            @php
                              $select = ($data->id == $data_sosmed->id_social_media ? 'selected="selected"' : '')
                            @endphp
                          <option value="{{ $data->id }}" {!! $select !!}>{{ $data->nama }}</option>
                          @endforeach
                        </select>
                        <div class="input-group-append">
                          <input type="text" class="form-control sosmed-name" name="social_media_text[]" placeholder="Silahkan Isi" value="{{$data_sosmed->name_social_media}}" disabled="disabled">
                        </div>
                      </div>
                    @endforeach 
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->

                <!-- Datatable -->
                <div class="tab-pane fade" id="Nilai" role="tabpanel" aria-labelledby="Nilai">
                  <div class="kt-portlet__body">
                    <div class="col-lg-12">
                      <div class="form-group row">
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label>Uraian Nilai dan Visi Pribadi</label>
                              <textarea id="summernote" class="form-control" rows="10" name="nilai" disabled>{{ @$talenta->pribadi->nilai }}</textarea>
                            </div>        
                          </div>  
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label><b>Interest</b></label>
                              <textarea id="summernote1" class="form-control" rows="3" name="ekonomi" disabled>{{ @$talenta->interest->interest }}</textarea>
                            </div>        
                          </div>  
                      </div> 
                    </div>
                  </div>
                </div>
                <!--end: Datatable -->
                
                <!-- Datatable -->
                <div class="tab-pane fade" id="Aspirasi" role="tabpanel" aria-labelledby="Aspirasi">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Kelas BUMN
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="form-group row">
                      @foreach($kelas as $data)  
                        @if($count == 0)
                          <div class="col-lg-3">
                            <div class="form-group">
                              <div class="checkbox-list">
                        @endif
                              <label class="checkbox"> 
                              @if($trans_kelas->contains('id_kelas', $data->id))
                                <input type="checkbox" disabled checked name="kelas" value="{{$data->id}}">     
                              @else
                                <input type="checkbox" disabled name="kelas" value="{{$data->id}}">   
                              @endif
                              <span></span>{{ $data->nama }}</label>
                        @if($count == 0)
                              </div>
                            </div>
                          </div>   
                        @endif
                        <?php
                          $count++;
                          if($count == 1){
                            $count = 0;
                          }
                        ?> 
                      @endforeach   
                    </div>
                  </div>

                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Sektor/Cluster BUMN
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="form-group row">
                        @foreach($cluster as $data)  
                          @if($count == 0)
                            <div class="col-lg-5">
                              <div class="form-group">
                                <div class="checkbox-list">
                          @endif
                                <label class="checkbox"> 
                                @if($trans_cluster->contains('id_cluster', $data->id))
                                  <input type="checkbox" disabled checked name="cluster" value="{{$data->id}}">     
                                @else
                                  <input type="checkbox" disabled name="cluster" value="{{$data->id}}">   
                                @endif
                                <span></span>{{ $data->nama }}</label>
                          @if($count == 0)
                                </div>
                              </div>
                            </div>   
                          @endif
                          <?php
                            $count++;
                            if($count == 1){
                              $count = 0;
                            }
                          ?> 
                        @endforeach                     
                    </div>  
                  </div>  
                  
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                          <h3 class="kt-portlet__head-title">
                          Keahlian
                          </h3>
                      </div>
                  </div>
                  <div class="kt-portlet__body">
                    <div class="form-group row">
                        @foreach($keahlian as $data)  
                          @if($count == 0)
                            <div class="col-lg-3">
                              <div class="form-group">
                                <div class="checkbox-list">
                          @endif
                                <label class="checkbox"> 
                                @if($trans_keahlian->contains('id_keahlian', $data->id))
                                  <input type="checkbox" disabled checked name="keahlian" value="{{$data->id}}">     
                                @else
                                  <input type="checkbox" disabled name="keahlian" value="{{$data->id}}">   
                                @endif
                                <span></span>{{ $data->jenis_keahlian }}</label>
                          @if($count == 0)
                                </div>
                              </div>
                            </div>   
                          @endif
                          <?php
                            $count++;
                            if($count == 1){
                              $count = 0;
                            }
                          ?> 
                        @endforeach                     
                    </div>  
                  </div>
                </div>
                <!--end: Datatable -->
                

              </div>
          </div>
      </div>
  </div>        
      <!--end: Datatable -->
</div>



<script type="text/javascript">
    var urldatatablependidikan = "{{route('cv.pendidikan.datatable', ['id_talenta' => $talenta->id])}}";
    var urldatatablepelatihan = "{{route('cv.pelatihan.datatable', ['id_talenta' => $talenta->id])}}";
    var urldatatablejabatan = "{{route('cv.riwayat_jabatan.datatable', ['id_talenta' => $talenta->id])}}";
    var urldatatablejabatanlain = "{{route('cv.riwayat_jabatan_lain.datatable', ['id_talenta' => $talenta->id])}}";
    var urldatatableorganisasi = "{{route('cv.riwayat_organisasi.datatable', ['id_talenta' => $talenta->id])}}";
    var urldatatableorganisasinonformal = "{{route('cv.riwayat_organisasi.datatable_nonformal', ['id_talenta' => $talenta->id])}}";
    var urldatatablepenghargaan = "{{route('cv.penghargaan.datatable', ['id_talenta' => $talenta->id])}}";
    var urldatatablekaryailmiah = "{{route('cv.karya_ilmiah.datatable', ['id_talenta' => $talenta->id])}}";
    var urldatatablepengalaman = "{{route('cv.pengalaman_lain.datatable', ['id_talenta' => $talenta->id])}}";
    var urldatatablekeluarga = "{{route('cv.keluarga.datatable', ['id_talenta' => $talenta->id])}}";
    var urldatatableanak = "{{route('cv.keluarga.datatable_anak', ['id_talenta' => $talenta->id])}}";
    var urldatatablepajak = "{{route('cv.pajak.datatable', ['id_talenta' => $talenta->id])}}";
    var urldatatablekesehatan = "{{route('cv.kesehatan.datatable', ['id_talenta' => $talenta->id])}}";
    var urldatatablelhkpn = "{{route('cv.lhkpn.datatable', ['id_talenta' => $talenta->id])}}";
    var urldatatabletani = "{{route('cv.tani.datatable', ['id_talenta' => $talenta->id])}}";
    var urldatatablereferensi = "{{route('cv.referensi_cv.datatable', ['id_talenta' => $talenta->id])}}";
    var urldatatableassessment = "{{route('cv.assessment_upload.datatable', ['id_talenta' => $talenta->id])}}";
    $(document).ready(function() {
      $('#summernote').summernote({
        placeholder: 'Isi Uraian Nilai dan Visi Pribadi',
        tabsize: 2,
        height: 250
      });
      $('#summernote1').summernote({
        placeholder: 'Isi Uraian Interest',
        tabsize: 2,
        height: 250
      });
      $('#summernote2').summernote({
        placeholder: 'Isi Uraian Interest',
        tabsize: 2,
        height: 250
      });
      $('#summernote3').summernote({
        placeholder: 'Isi Uraian Interest',
        tabsize: 2,
        height: 250
      });
    });
</script>
<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('js/talenta/minicv.js')}}"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>