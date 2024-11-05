<!-- sidebar -->
<?php
    use App\Helpers\CVHelper;


    $notif_biodata = CVHelper::biodataFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_aspirasi = CVHelper::kelasFillCheck($talenta->id) && CVHelper::clusterFillCheck($talenta->id) && CVHelper::keahlianFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_publikasi = CVHelper::karyaIlmiahFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_pendidikan = CVHelper::pendidikanFillCheck($talenta->id) && CVHelper::pelatihanFillCheck($talenta->id)? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_jabatan = CVHelper::jabatanFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_keahlian = CVHelper::keahlianFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_organisasi = CVHelper::organisasiFillCheck($talenta->id) && CVHelper::organisasinonformalFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_penghargaan = CVHelper::penghargaanFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_pengalaman_lain = CVHelper::pengalamanLainFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_keluarga = CVHelper::keluargaFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_summary = CVHelper::summaryFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_pribadi = CVHelper::nilaiFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_pajak = CVHelper::pajakFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_lhkpn = CVHelper::lhkpnFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_tani = CVHelper::taniFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_kesehatan = CVHelper::kesehatanFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_kelas = CVHelper::kelasFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_cluster = CVHelper::clusterFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_social = CVHelper::socialFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
    $notif_referensi = CVHelper::referensiCVFillCheck($talenta->id) ? "" : "<span class='label label-rounded label-danger'><b>!</b></span>";
?>
<link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css" />
<div class="row">
    <div class="col-lg-12">
        <div class="kt-portlet kt-portlet--mobile text-left" >
            @if($active == "biodata")
                <a href="{{ route('cv.biodata.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md text"><i class="flaticon2-next"></i>Biodata {!!$notif_biodata!!}</a>
            @else               
                <a href="{{ route('cv.biodata.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Biodata {!!$notif_biodata!!}</a>
            @endif

            @if($active == "nilai")
                <a href="{{ route('cv.nilai.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Nilai, Visi dan Interest {!!$notif_pribadi!!}</a>
            @else               
                <a href="{{ route('cv.nilai.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Nilai, Visi dan Interest {!!$notif_pribadi!!}</a>
            @endif

            @if($active == "aspirasi")
                <a href="{{ route('cv.aspirasi.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md text"><i class="flaticon2-next"></i>Aspirasi {!!$notif_aspirasi!!}</a>
            @else               
                <a href="{{ route('cv.aspirasi.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Aspirasi {!!$notif_aspirasi!!}</a>
            @endif

            @if($active == "riwayat_jabatan")
                <a href="{{ route('cv.riwayat_jabatan.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Riwayat Jabatan {!!$notif_jabatan!!}</a>
            @else               
                <a href="{{ route('cv.riwayat_jabatan.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Riwayat Jabatan {!!$notif_jabatan!!}</a>
            @endif

            @if($active == "organisasi")
                <a href="{{ route('cv.riwayat_organisasi.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Organisasi/ Komunitas {!!$notif_organisasi!!}</a>
            @else               
                <a href="{{ route('cv.riwayat_organisasi.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Organisasi/ Komunitas {!!$notif_organisasi!!}</a>
            @endif

            @if($active == "penghargaan")
                <a href="{{ route('cv.penghargaan.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Penghargaan {!!$notif_penghargaan!!}</a>
            @else               
                <a href="{{ route('cv.penghargaan.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Penghargaan {!!$notif_penghargaan!!}</a>
            @endif

            @if($active == "pendidikan")
                <a href="{{ route('cv.pendidikan.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Pendidikan dan Pelatihan {!!$notif_pendidikan!!}</a>
            @else               
                <a href="{{ route('cv.pendidikan.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Pendidikan dan Pelatihan {!!$notif_pendidikan!!}</a>
            @endif

            @if($active == "publikasi")
                <a href="{{ route('cv.karya_ilmiah.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Publikasi {!!$notif_publikasi!!}</a>
            @else               
                <a href="{{ route('cv.karya_ilmiah.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Publikasi {!!$notif_publikasi!!}</a>
            @endif
            
            @if($active == "pengalaman")
                <a href="{{ route('cv.pengalaman_lain.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Pembicara Narasumber {!!$notif_pengalaman_lain!!}</a>
            @else               
                <a href="{{ route('cv.pengalaman_lain.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected" ><i class="flaticon2-next"></i>Pembicara Narasumber {!!$notif_pengalaman_lain!!}</a>
            @endif
            
            @if($active == "referensi_cv")
                <a href="{{ route('cv.referensi_cv.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Referensi {!!$notif_referensi!!}</a>
            @else               
                <a href="{{ route('cv.referensi_cv.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Referensi {!!$notif_referensi!!}</a>
            @endif
            
            @if($active == "keluarga")
                <a href="{{ route('cv.keluarga.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Data Keluarga {!!$notif_keluarga!!}</a>
            @else               
                <a href="{{ route('cv.keluarga.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Data Keluarga {!!$notif_keluarga!!}</a>
            @endif

            @if($active == "pajak")
                <a href="{{ route('cv.pajak.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>SPT Tahunan {!!$notif_pajak!!}</a>
            @else               
                <a href="{{ route('cv.pajak.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>SPT Tahunan {!!$notif_pajak!!}</a>
            @endif

            @if($active == "lhkpn")
                <a href="{{ route('cv.lhkpn.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>LHKPN {!!$notif_lhkpn!!}</a>
            @else               
                <a href="{{ route('cv.lhkpn.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>LHKPN {!!$notif_lhkpn!!}</a>
            @endif

            @if($active == "tani")
                <a href="{{ route('cv.tani.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>TANI {!!$notif_tani!!}</a>
            @else               
                <a href="{{ route('cv.tani.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>TANI {!!$notif_tani!!}</a>
            @endif

            @if($active == "kesehatan")
                <a href="{{ route('cv.kesehatan.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Kesehatan {!!$notif_tani!!}</a>
            @else               
                <a href="{{ route('cv.kesehatan.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Kesehatan {!!$notif_kesehatan!!}</a>
            @endif

           <!--  @if($active == "pelatihan")
                <a href="#" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Data Pelatihan</a>
            @else               
                <a href="#" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Data Pelatihan</a>
            @endif
 -->

            <!-- @if($active == "keahlian")
                <a href="{{ route('cv.keahlian.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Keahlian dan Interest {!!$notif_keahlian!!}</a>
            @else               
                <a href="{{ route('cv.keahlian.index', ['id_talenta' => $talenta->id]) }}"" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Keahlian dan Interest {!!$notif_keahlian!!}</a>
            @endif -->

            <!-- @if($active == "interest")
                <a href="{{ route('cv.interest.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Interest</a>
            @else               
                <a href="{{ route('cv.interest.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Interest</a>
            @endif -->

           <!--  @if($active == "karya__ilmiah")
                <a href="#" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Karya Ilmiah</a>
            @else               
                <a href="#" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Karya Ilmiah</a>
            @endif -->

            <!-- @if($active == "summary")
                <a href="{{ route('cv.summary.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Summary {!!$notif_summary!!}</a>
            @else               
                <a href="{{ route('cv.summary.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Summary {!!$notif_summary!!}</a>
            @endif -->

            <!-- @if($active == "kelas")
                <a href="{{ route('cv.kelas.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Kelas BUMN {!!$notif_kelas!!}</a>
            @else               
                <a href="{{ route('cv.kelas.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Kelas BUMN {!!$notif_kelas!!}</a>
            @endif -->

            <!-- @if($active == "cluster")
                <a href="{{ route('cv.cluster.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Cluster BUMN {!!$notif_cluster!!}</a>
            @else               
                <a href="{{ route('cv.cluster.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Cluster BUMN {!!$notif_cluster!!}</a>
            @endif -->

            @if($active == "social")
                <a href="{{ route('cv.social.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Sosial Media {!!$notif_tani!!}</a>
            @else               
                <a href="{{ route('cv.social.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Sosial Media {!!$notif_social!!}</a>
            @endif

            @can('assessment_upload-create')
            @if($active == "assessment_upload")
                <a href="{{ route('cv.assessment_upload.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-primary mr-2 text-left btn-md"><i class="flaticon2-next"></i>Hasil Assessment</a>
            @else               
                <a href="{{ route('cv.assessment_upload.index', ['id_talenta' => $talenta->id]) }}" class="btn btn-hover-primary mr-2 text-left btn-md not_selected"><i class="flaticon2-next"></i>Hasil Assessment</a>
            @endif
            @endcan
        </div>
    </div>
</div>
