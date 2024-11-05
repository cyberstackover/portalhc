<style>
    .foto-talenta {
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 5px;
      height: 265px;
    }
    input[type="radio"]:disabled {
    -webkit-appearance: none;
    display: inline-block;
    width: 12px;
    height: 12px;
    padding: 0px;
    background-clip: content-box;
    border: 2px solid #bbbbbb;
    background-color: white;
    border-radius: 50%;
}
input[type="checkbox"]:disabled{
    -webkit-appearance: none;
    display: inline-block;
    width: 12px;
    height: 12px;
    padding: 0px;
    background-clip: content-box;
    border: 2px solid #bbbbbb;
    background-color: white;
}

input[type="radio"]:checked, input[type="checkbox"]:checked {
    background-color: #5d78ff;
}
  </style>
  <?php
    $count = 0;
  ?> 
  <form action="" class="kt-form kt-form--label-right" method="POST" id="form-first">
      @csrf
      <input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
      <input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}"/>
      <div class="kt-portlet__body">
          <div class="form-group row">
              <div class="col-lg-5">     
                  <label>
                      <img class="foto-talenta"src="{{ \App\Helpers\CVHelper::getFoto($talenta->foto, ($talenta->jenis_kelamin == 'L' ? 'img/male.png': 'img/female.png')) }}" alt=""> 
                  </label>
              </div> 
              <div class="col-lg-7">  
                  <div class="form-group">
                      <label>Nama</label>
                      <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{$talenta->nama_lengkap}}" disabled/>
                  </div>
                  <div class="form-group">
                      <label>Tempat, Tanggal Lahir</label>
                      <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{$talenta->tempat_lahir}}, {{ ($talenta->tanggal_lahir != ''? \Carbon\Carbon::createFromFormat('Y-m-d', $talenta->tanggal_lahir)->format('d/m/Y') : '') }}" disabled/>
                  </div>  
                  <div class="form-group">
                      <label>Pendidikan Terakhir</label>
                      <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{ $talenta->pendidikan }}" disabled/>
                  </div>  
              </div>
          </div>   
          <div class="form-group row">
              <div class="col-lg-12">  
                  <div class="form-group">
                      <label>Jabatan Saat ini</label>
                      <input type="text" class="form-control" name="nama_lengkap" id="nama" value="{{$talenta->jabatan }} - {{$talenta->nama_perusahaan}}" disabled/>
                  </div>  
              </div> 
              <div class="col-lg-12">
                  <hr>
              </div> 
          </div>
          
          <div class="row">	
              <div class="col-lg-6">				
                  <div class="form-group">
                      <label>Tanggal Assessment</label>	
                      @php
                        $value = ($actionform == 'update'? \App\Helpers\CVHelper::tglformat(@$data->tanggal) : '')
                      @endphp				
                      <input type="text" name="tanggal" class="form-control" value="{{ $value }}" id="tanggal" disabled>
                  </div>
              </div>
              <div class="col-lg-6">				
                  <div class="form-group">
                      <label>Tanggal Expired</label>	
                      @php
                        $value = ($actionform == 'update'? \App\Helpers\CVHelper::tglformat(@$data->tanggal_expired) : '')
                      @endphp				
                      <input type="text" name="tanggal_expired" class="form-control" value="{{ $value }}" id="tanggal_expired" disabled>
                  </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label><b>I. Kompetensi</b></label>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <div class="table-responsive">
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                          <thead>
                              <tr>
                                  <th width="3%" rowspan="2">No.</th>
                                  <th rowspan="2">Aspek Kompetensi</th>
                                  <th colspan="5" style="text-align:center;">Rating</th>
                              </tr>
                              <tr>
                                  <th  style="text-align:center;">1</th>
                                  <th  style="text-align:center;">2</th>
                                  <th  style="text-align:center;">3</th>
                                  <th  style="text-align:center;">4</th>
                                  <th  style="text-align:center;">5</th>
                              </tr>
                          </thead>
                          <tbody>
                              @php $no = 1; @endphp
                              @foreach($kompetensi as $v)
                              <tr>
                                  <td>{{ $no }}</td>
                                  <td>{{ $v->nama }}</td>
                                  <td style="text-align:center;"><input type="radio" class="kompetensi" onclick='checkHasil(this);' value="1" name="kompetensi[{{$v->id}}]" @if((@$trans_kompetensi[$v->id] ? @$trans_kompetensi[$v->id] == 1 : false)) checked @endif disabled></td>
                                  <td style="text-align:center;"><input type="radio" class="kompetensi" onclick='checkHasil(this);' value="2" name="kompetensi[{{$v->id}}]"  @if((@$trans_kompetensi[$v->id] ? @$trans_kompetensi[$v->id] == 2 : false)) checked @endif disabled></td>
                                  <td style="text-align:center;"><input type="radio" class="kompetensi" onclick='checkHasil(this);' value="3" name="kompetensi[{{$v->id}}]" @if((@$trans_kompetensi[$v->id] ? @$trans_kompetensi[$v->id] == 3 : false)) checked @endif disabled></td>
                                  <td style="text-align:center;"><input type="radio" class="kompetensi" onclick='checkHasil(this);' value="4" name="kompetensi[{{$v->id}}]" @if((@$trans_kompetensi[$v->id] ? @$trans_kompetensi[$v->id] == 4 : false)) checked @endif disabled></td>
                                  <td style="text-align:center;"><input type="radio" class="kompetensi" onclick='checkHasil(this);' value="5" name="kompetensi[{{$v->id}}]" @if((@$trans_kompetensi[$v->id] ? @$trans_kompetensi[$v->id] == 5 : false)) checked @endif disabled></td>
                              </tr>
                              @php $no++ @endphp
                              @endforeach
                          </tbody>
                      </table>
                  </div>
                </div>
              </div>	
  
              
              <div class="col-lg-12">
                <div class="form-group">
                  <label><b>II. Kualifikasi Personal</b></label>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <div class="table-responsive">
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                          <thead>
                              <tr>
                                  <th width="3%" rowspan="2">No.</th>
                                  <th rowspan="2">Aspek Personal</th>
                                  <th colspan="5" style="text-align:center;">Rating</th>
                              </tr>
                              <tr>
                                  <th  style="text-align:center;">1</th>
                                  <th  style="text-align:center;">2</th>
                                  <th  style="text-align:center;">3</th>
                                  <th  style="text-align:center;">4</th>
                                  <th  style="text-align:center;">5</th>
                              </tr>
                          </thead>
                          <tbody>
                              @php $no = 1; @endphp
                              @foreach($kualifikasi as $v)
                              <tr>
                                  <td>{{ $no }}</td>
                                  <td>{{ $v->nama }}</td>
                                  <td style="text-align:center;"><input type="radio" class="kualifikasi" onclick='checkHasil(this);' value="1" name="kualifikasi[{{$v->id}}]" @if((@$trans_kualifikasi[$v->id] ? @$trans_kualifikasi[$v->id] == 1 : false)) checked @endif disabled></td>
                                  <td style="text-align:center;"><input type="radio" class="kualifikasi" onclick='checkHasil(this);' value="2" name="kualifikasi[{{$v->id}}]" @if((@$trans_kualifikasi[$v->id] ? @$trans_kualifikasi[$v->id] == 2 : false)) checked @endif disabled></td>
                                  <td style="text-align:center;"><input type="radio" class="kualifikasi" onclick='checkHasil(this);' value="3" name="kualifikasi[{{$v->id}}]" @if((@$trans_kualifikasi[$v->id] ? @$trans_kualifikasi[$v->id] == 3 : false)) checked @endif disabled></td>
                                  <td style="text-align:center;"><input type="radio" class="kualifikasi" onclick='checkHasil(this);' value="4" name="kualifikasi[{{$v->id}}]" @if((@$trans_kualifikasi[$v->id] ? @$trans_kualifikasi[$v->id] == 4 : false)) checked @endif disabled></td>
                                  <td style="text-align:center;"><input type="radio" class="kualifikasi" onclick='checkHasil(this);' value="5" name="kualifikasi[{{$v->id}}]" @if((@$trans_kualifikasi[$v->id] ? @$trans_kualifikasi[$v->id] == 5 : false)) checked @endif disabled></td>
                              </tr>
                              @php $no++ @endphp
                              @endforeach
                          </tbody>
                      </table>
                  </div>
                </div>
              </div>
              
  
              <div class="col-lg-12">
                <div class="form-group">
                  <label><b>III. Kesimpulan</b></label>
                </div>
              </div>
              <div class="col-lg-12">
                  <div class="form-group">	
                      <input type="radio" id="qualified" name="hasil" value="Qualified" disabled {{(@$data->hasil=='Qualified') ? 'checked':'' }} disabled> Qualified 
                      <input type="radio" id="not_qualified" name="hasil" value="Not Qualified" disabled {{(@$data->hasil=='Not Qualified') ? 'checked':'' }} disabled> Not Qualified 
                  </div>
              </div>	

              <div class="div-qualified" style="{{(@$data->hasil=='Qualified') ? '':'display:none;' }}">				
                  <div class="col-lg-12">
                      <div class="form-group">
                          <label><b>IV. Karakter (Traits)</b></label>
                      </div>
                  </div>
                  <div class="col-lg-12">
                      <div class="form-group">
                          <div class="table-responsive">
                              <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                                  <thead>
                                      <tr>
                                          <th width="3%" rowspan="2">No.</th>
                                          <th rowspan="2">Aspek Karakter</th>
                                          <th colspan="5" style="text-align:center;">Rating</th>
                                      </tr>
                                      <tr>
                                          <th  style="text-align:center;">Low</th>
                                          <th  style="text-align:center;">Medium</th>
                                          <th  style="text-align:center;">High</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @php $no = 1; @endphp
                                      @foreach($karakter as $v)
                                      <tr>
                                          <td>{{ $no }}</td>
                                          <td>{{ $v->nama }}</td>
                                          <td style="text-align:center;"><input type="radio" value="1" name="karakter[{{$v->id}}]" @if((@$trans_karakter[$v->id] ? @$trans_karakter[$v->id] == 1 : false)) checked @endif disabled></td>
                                          <td style="text-align:center;"><input type="radio" value="2" name="karakter[{{$v->id}}]" @if((@$trans_karakter[$v->id] ? @$trans_karakter[$v->id] == 2 : false)) checked @endif disabled></td>
                                          <td style="text-align:center;"><input type="radio" value="3" name="karakter[{{$v->id}}]" @if((@$trans_karakter[$v->id] ? @$trans_karakter[$v->id] == 3 : false)) checked @endif disabled></td>
                                      </tr>
                                      @php $no++ @endphp
                                      @endforeach
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
                  
                  <div class="col-lg-12">
                      <div class="form-group">
                          <label><b>V. Kualifikasi Talenta</b></label>
                      </div>
                  </div>
                  <div class="col-lg-12">
                      <div class="form-group">
                          <label><b>I. Kelas BUMN </b> (Pilih Maksimal 2)</label>
                          <br/>
                          <br/>
                          <div class="form-group row">
                              @foreach($kelas as $data)  
                                  @if($count == 0)
                                  <div class="col-lg-2">
                                      <div class="form-group">
                                      <div class="checkbox-list">
                                  @endif
                                      <label class="checkbox"> 
                                      
                                          <input type="checkbox" onclick='checkKelas(this);' name="kelas[]" value="{{$data->id}}" @if((@$trans_kelas ? @$trans_kelas->contains('id_kelas_bumn', $data->id) : false)) checked @endif disabled>     
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
                  </div>
                  <div class="col-lg-12">
                      <div class="form-group">
                          <label><b>II. Sektor/Klaster BUMN</b> (Pilih Maksimal 3)</label>
                          <br/>
                          <br/>
                          <div class="form-group row">
                              @foreach($cluster as $data)  
                                  @if($count == 0)
                                  <div class="col-lg-4">
                                      <div class="form-group">
                                      <div class="checkbox-list">
                                  @endif
                                      <label class="checkbox"> 
                                      
                                          <input type="checkbox" onclick='checkCluster(this);' name="cluster[]" value="{{$data->id}}" @if((@$trans_cluster ? @$trans_cluster->contains('id_cluster_bumn', $data->id) : false)) checked @endif disabled>     
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
                  </div>
                  <div class="col-lg-12">
                      <div class="form-group">
                          <label><b>III. Keahlian Bidang/Fungsi</b> (Pilih Maksimal 5)</label>
                          <br/>
                          <br/>
                          
                          <div class="form-group row">
                          @foreach($keahlian as $data)  
                              @if($count == 0)
                              <div class="col-lg-4">
                                  <div class="form-group">
                                  <div class="checkbox-list">
                              @endif
                                  <label class="checkbox"> 
                                  @if((@$trans_keahlian ? @$trans_keahlian->contains('id_keahlian', $data->id) : false))
                                      <input type="checkbox" onclick='checkKeahlian(this);' checked name="keahlian[]" value="{{$data->id}}" disabled>     
                                  @else
                                      <input type="checkbox" onclick='checkKeahlian(this);' name="keahlian[]" value="{{$data->id}}" disabled>   
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
                  <div class="col-lg-12">
                      <div class="form-group">
                          <label><b>IV. Konteks Organisasi </b></label>
                          <br>
                          <br>
                          <div class="form-group row">
                              @foreach($konteks as $data)  
                                  @if($count == 0)
                                  <div class="col-lg-3">
                                      <div class="form-group">
                                      <div class="checkbox-list">
                                  @endif
                                      <label class="checkbox"> 
                                      @if((@$trans_konteks ? @$trans_konteks->contains('id_konteks_organisasi', $data->id) : false))
                                          <input type="checkbox" checked name="organisasi[]" value="{{$data->id}}" disabled>     
                                      @else
                                          <input type="checkbox" name="organisasi[]" value="{{$data->id}}" disabled>   
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
                  </div>
                  <div class="col-lg-12">
					<div class="form-group">
						<label><b>V. Area Kekuatan </b></label>
						<div class="form-group row">
							<textarea class="form-control kt-shape-bg-color-1" id="area_kekuatan" name="area_kekuatan" rows="5" cols="5" disabled>{{($actionform === 'update') ? $assessment_nilai->area_kekuatan : ''}}</textarea>
						</div>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="form-group">
						<label><b>VI. Area Pengembangan </b></label>
						<div class="form-group row">
                            <textarea class="form-control kt-shape-bg-color-1" id="area_pengembangan" name="area_pengembangan" rows="5" cols="5" disabled>{{($actionform === 'update') ? $assessment_nilai->area_pengembangan :'' }}</textarea>
						</div>
					</div>
				</div>
				<div class="col-lg-12">
                    <label><b>File Uploads </b></label>
                    <div class="row">
                            <div class="col-md-3">
                                <a class="tooltips" title="Download SPA" href="{{\URL::to('uploads/talenta/assessment/'.$assessment_nilai->spa_report)}}" download="{{$assessment_nilai->spa_report}}" ><span class="btn btn-lg yellow-gold sbold"><i class="flaticon2-document"></i>SPA </a><br/>
                            </div>
                            <div class="col-md-3">
                                <a class="tooltips" title="Download SPA" href="{{\URL::to('uploads/talenta/assessment/'.$assessment_nilai->short_report)}}" download="{{$assessment_nilai->short_report}}" ><span class="btn btn-lg yellow-gold sbold"><i class="flaticon2-document"></i>Short Report </a><br/>
                            </div>
                            <div class="col-md-3">
                                <a class="tooltips" title="Download SPA" href="{{\URL::to('uploads/talenta/assessment/'.$assessment_nilai->full_report)}}" download="{{$assessment_nilai->full_report}}" ><span class="btn btn-lg yellow-gold sbold"><i class="flaticon2-document"></i>Full Report </a><br/>
                            </div>
					</div>
				</div>
                
              </div>
  
              
          </div>
      </div>
  </form>
  <script type="text/javascript" src="{{asset('js/cv/datepicker.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/talenta/assessment_nilai/form.js')}}"></script>
  <script type="text/javascript">
      $(document).ready(function(){
          //readonly checkbox
          $("input[name='hasil']:not(:checked)").attr('disabled', true);
          $("input[name='hasil']:checked").attr('disabled', false);

          
        });
    
      function checkHasil(e) {
          var array = $.map($('input[class="kompetensi"]:checked'), function(c){return c.value; });
          var array2 = $.map($('input[class="kualifikasi"]:checked'), function(c){return c.value; });
          var rating1 = 0;
          var rating2 = 0;
          array.forEach(function(element){
              if(element == 1) rating1++;
              if(element == 2) rating2++;
          });
          var minrating2 = 0;
          array2.forEach(function(element){
              if(element < 2) minrating2++;
          });
  
          if(rating1 == 0 && rating2<3 && minrating2 == 0){
              $("#qualified").prop("checked", true);
              $(".div-qualified").show();
          }else{
              $("#not_qualified").prop("checked", true);
              $(".div-qualified").hide();
          }
          $("input[name='hasil']:not(:checked)").attr('disabled', true);
          $("input[name='hasil']:checked").attr('disabled', false);
      }
      function checkKelas(element) {
          var array = $.map($('input[name="kelas[]"]:checked'), function(c){return c.value; });
          var arrayfilter = [];
          $.each(array, function(i, el){
              if($.inArray(el, arrayfilter) === -1) arrayfilter.push(el);
          });
          if(arrayfilter.length>2){
              swal.fire({
                          title: "Gagal",
                          html:'Pilihan kelas Maksimal 2',
                          type: 'error',
    
                          buttonsStyling: false,
    
                          confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                          confirmButtonClass: "btn btn-default"
                     });       
              $(element).prop("checked", false);
          }
      }
      function checkCluster(element) {
          var array = $.map($('input[name="cluster[]"]:checked'), function(c){return c.value; });
          var arrayfilter = [];
          $.each(array, function(i, el){
              if($.inArray(el, arrayfilter) === -1) arrayfilter.push(el);
          });
          if(arrayfilter.length>3){
              swal.fire({
                          title: "Gagal",
                          html:'Pilihan Cluster BUMN Maksimal 3',
                          type: 'error',
    
                          buttonsStyling: false,
    
                          confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                          confirmButtonClass: "btn btn-default"
                     });       
              $(element).prop("checked", false);
          }
      }
      function checkKeahlian(element) {
          var array = $.map($('input[name="keahlian[]"]:checked'), function(c){return c.value; });
          var arrayfilter = [];
          $.each(array, function(i, el){
              if($.inArray(el, arrayfilter) === -1) arrayfilter.push(el);
          });
          if(arrayfilter.length>5){
              swal.fire({
                          title: "Gagal",
                          html:'Pilihan Keahlian Bidang/Fungsi Maksimal 5',
                          type: 'error',
    
                          buttonsStyling: false,
    
                          confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                          confirmButtonClass: "btn btn-default"
                     });       
              $(element).prop("checked", false);
          }
      }
  </script>