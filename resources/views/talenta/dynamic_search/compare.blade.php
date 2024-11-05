<style>
  .foto-talenta {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    height: 100px;
  }
  .detail-talenta td {
    padding:7px;
  }
  .detail-talenta tr.border_bottom td{
    border-bottom: 1px solid #d2d2d2;
    /* border-right: 1px solid #d2d2d2; */
  }
  .detail-talenta table {
    border-collapse:separate; 
    font-size: 10px;
    text-align:center;
  }

</style>

<div class="kt-portlet__body" >
    <div class="form-group row btn-footer text-center">
        <div class="col-lg-12">
            <a href="{{route('talenta.dynamic_search.compare_pdf', ['id' => $id, 'dynamic_filter_id' => $dynamic_filter_id])}}" target="_blank" style="float:right;margin-top:-90px;margin-right:20px;" class="btn btn-primary btn-elevate btn-icon-sm cls-compare-pdf">
                <i class="far fa-file-pdf"></i>
                Download PDF
            </a>
        </div>
    </div>
    <div class="form-group row" style="margin-top: -40px;">
        <div class="col-lg-12 detail-talenta">
            <table width="100%">
                <tr class="border_bottom">
                    <td width="20%"><b></b></td>
                    @foreach ($talentas as $talenta)
                    <td width="27%">
                        <img class="foto-talenta" src="{{ \App\Helpers\CVHelper::getFoto($talenta->foto, ($talenta->jenis_kelamin == 'Laki-laki' ? 'img/male.png': 'img/female.png')) }}" alt=""> <br>
                        <span style="font-size:12px;"><b>{{$talenta->nama_lengkap}}</b></span><br>
                    </td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>NIK</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->nik}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Jabatan</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->jabatan}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>BUMN</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->nama_perusahaan}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Jenis Kelamin</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->jenis_kelamin}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Email</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->email}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>No Handphone</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->nomor_hp}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Tempat Lahir</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->tempat_lahir}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Tanggal Lahir</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{\App\Helpers\CVHelper::tglformat(@$talenta->tanggal_lahir)}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Agama</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->agama}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Alamat</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->alamat}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Pendidikan</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->pendidikan}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Riwayat Jabatan</b></td>
                    @for($i = 0; $i < count($riwayat_jabatan); $i++)
                        <td>
                        @foreach ($riwayat_jabatan[$i] as $val)
                                - {{$val->jabatan . ' '. $val->perusahaan}} 
                                {{ '(' . $val->tahun_awal. (($val->tahun_akhir? '-'.$val->tahun_akhir:'')) . ')' }}
                                <br>
                        @endforeach
                        </td>
                    @endfor
                </tr>
                
                @foreach($dynamic_filter as $a)
                <tr class="border_bottom">
                    @if($a->submenu== 'TANI')
                        <td style="text-align:left;"><b>{{$a->submenu}}</b></td>
                        @foreach ($talentas as $talenta)
                        <td>{{ number_format($talenta->{$a->alias}) }}</td>
                        @endforeach
                    @elseif($a->submenu!= 'Nama' && $a->submenu!= 'Jabatan' && $a->submenu!= 'Jenis Kelamin' && $a->submenu!= 'Pendidikan')
                        <td style="text-align:left;"><b>{{$a->submenu}}</b></td>
                        @foreach ($talentas as $talenta)
                        <td>{{ $talenta->{$a->alias} }}</td>
                        @endforeach
                    @endif
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>



<script type="text/javascript">

</script>

<script src="{{asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>