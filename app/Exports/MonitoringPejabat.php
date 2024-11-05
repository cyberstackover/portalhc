<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class MonitoringPejabat implements FromCollection, WithHeadings
{

  public function __construct($id_bumn = "",$pejabat= "",$pejabataktif= "",$id_grup_jabat= "",$tgl_sk= "",$id_jabatan= "",$id_periode= "",$id_asal_instansi="",$nomor_sk="",$jenis_kelamin="",$id_agama="",$masa_jabatan="",$kewarganegaraan="",$id_suku="",$kelas="",$id_klaster=""){
        $this->id_bumn = $id_bumn;
        $this->pejabat = $pejabat;
        $this->pejabataktif = $pejabataktif;
        $this->id_grup_jabat = $id_grup_jabat;
        $this->tgl_sk = $tgl_sk;
        $this->id_jabatan = $id_jabatan;
        $this->id_periode = $id_periode;
        $this->id_asal_instansi = $id_asal_instansi;
        $this->nomor_sk = $nomor_sk;
        $this->jenis_kelamin = $jenis_kelamin;
        $this->id_agama = $id_agama;
        $this->masa_jabatan = $masa_jabatan;
        $this->kewarganegaraan = $kewarganegaraan;
        $this->id_suku = $id_suku;
        $this->kelas = $kelas;
        $this->id_klaster = $id_klaster;
    }

	public function headings(): array
    {
        return [
           ['Pejabat', 'Perusahaan', 'Kelas', 'Klaster', 'Jabatan', 'Grup Jabatan', 'Periode', 'No SK', 'Tanggal SK', 'Awal Menjabat', 'Akhir Menjabat', 'Instansi', 'Asal Instansi', 'Jenis Kelamin', 'NIK', 'NPWP', 'EMAIL', 'Nomer HP', 'Kewarganegaraan', 'Golongan Darah', 'Suku', 'Agama', 'Tanggal Lahir', 'Tempat Lahir', 'Jenjang Pendidikan' ]
        ];
    }

    public function collection()
    {
      $where = '';
      if($this->id_bumn){
        $where = 'AND perusahaan.id = '.$this->id_bumn;
      } else {
         $where .= " ";
      }

      if($this->pejabat){
         $where .= " and lower(talenta.nama_lengkap) like lower('%".$this->pejabat."%') ";
      } else {
         $where .= " ";
      }

      if($this->pejabataktif){
         if ($this->pejabataktif == 'AKTIF') {
           $aktif = 't';
         } else {
           $aktif = 'f';
         }
         $where .= " and view_organ_perusahaan.aktif = '$aktif' ";
      } else {
         $where .= " ";
      }

      if($this->id_grup_jabat){
         $where .= " AND surat_keputusan.id_grup_jabatan = ".$this->id_grup_jabat." ";
      } else {
         $where .= " ";
      }

      if($this->tgl_sk){
         $where .= " and surat_keputusan.tanggal_sk =  '".\Carbon\Carbon::createFromFormat('d/m/Y', $this->tgl_sk)->format('Y-m-d')."' ";
      } else {
         $where .= " ";
      }

      if($this->id_jabatan){
         $where .= " and jenis_jabatan.id = ".$this->id_jabatan." ";
      } else {
         $where .= " ";
      }

      if($this->id_periode){
         $where .= " and view_organ_perusahaan.id_periode_jabatan = ".$this->id_periode." ";
      } else {
         $where .= " ";
      }

      if($this->id_asal_instansi){
         $where .= " and talenta.id_asal_instansi = ".$this->id_asal_instansi." ";
      } else {
         $where .= " ";
      }

      if($this->nomor_sk){
         $where .= " and lower(surat_keputusan.nomor) like lower('%".$this->nomor_sk."%') ";
      } else {
         $where .= " ";
      }

      if($this->jenis_kelamin){
         $where .= " and talenta.jenis_kelamin = '".$this->jenis_kelamin."' ";
      } else {
         $where .= " ";
      }

      if($this->id_agama){
         $where .= " and talenta.id_agama = ".$this->id_agama." ";
      } else {
         $where .= " ";
      }

      if($this->masa_jabatan){
         if($this->masa_jabatan == 'kurang3'){
           $where .= " AND ( view_organ_perusahaan.tanggal_akhir < CURRENT_DATE - INTERVAL '3 months ago' ) ";
         } elseif ($this->masa_jabatan == 'kurang6') {
             $where .= " AND ( view_organ_perusahaan.tanggal_akhir < CURRENT_DATE - INTERVAL '6 months ago' ) ";
         } elseif ($this->masa_jabatan == 'expire') {
              $where .= " AND ( view_organ_perusahaan.tanggal_akhir < CURRENT_DATE ) ";
         }
      } else {
         $where .= " ";
      }

      if($this->kewarganegaraan){
         $where .= " and talenta.kewarganegaraan = '".$this->kewarganegaraan."' ";
      } else {
         $where .= " ";
      }

      if($this->id_suku){
         $where .= " and talenta.id_suku = '".$this->id_suku."' ";
      } else {
         $where .= " ";
      }

        if($this->kelas){
            $where .= " and perusahaan.kelas = '".$this->kelas."' ";
        } else {
            $where .= " ";
        }

        if($this->id_klaster){
            $where .= " and perusahaan.id_klaster = '".$this->id_klaster."' ";
        } else {
            $where .= " ";
        }

      $id_sql = "SELECT
                    perusahaan.ID,
                  CASE

                      WHEN ( view_organ_perusahaan.tanggal_akhir < CURRENT_DATE ) THEN
                    TRUE ELSE FALSE
                    END AS expire,
                  CASE

                      WHEN ( view_organ_perusahaan.tanggal_akhir < CURRENT_DATE - INTERVAL '3 months ago' ) THEN
                    TRUE ELSE FALSE
                    END AS kurang3,
                  CASE

                      WHEN ( view_organ_perusahaan.tanggal_akhir < CURRENT_DATE - INTERVAL '6 months ago' ) THEN
                    TRUE ELSE FALSE
                    END AS kurang6,
                  CASE

                      WHEN view_organ_perusahaan.aktif = 't' THEN
                      talenta.nama_lengkap ELSE talenta.nama_lengkap
                    END AS pejabat,
                  CASE

                      WHEN view_organ_perusahaan.aktif = 't' THEN
                      'AKTIF' ELSE'TIDAK AKTIF'
                    END AS aktifpejabat,
                    perusahaan.nama_lengkap AS bumns,
                    perusahaan.kelas as kelas_bumn,
                    cluster_bumn.nama as klaster_bumn,
                    grup_jabatan.ID AS grup_jabat_id,
                    grup_jabatan.nama AS grup_jabat_nama,
                  CASE

                      WHEN view_organ_perusahaan.nomenklatur IS NULL THEN
                      struktur_organ.nomenklatur_jabatan ELSE view_organ_perusahaan.nomenklatur
                    END AS nama_jabatan,
                    surat_keputusan.nomor,
                    surat_keputusan.tanggal_sk,
                    view_organ_perusahaan.tanggal_awal,
                    view_organ_perusahaan.tanggal_akhir,
                    view_organ_perusahaan.plt,
                    view_organ_perusahaan.komisaris_independen,
                    instansi_baru.nama AS instansi,
                    jenis_asal_instansi.nama AS asal_instansi,
                    talenta.jabatan_asal_instansi,
                    view_organ_perusahaan.id_periode_jabatan AS periode,
                    struktur_organ.ID AS struktur_id,
                    ARRAY_AGG(jenjang_pendidikan.nama) as jenjang_pendidikan,
                    talenta.jenis_kelamin,
                    talenta.nik,
                    talenta.npwp,
                    talenta.email,
                    talenta.nomor_hp,
                    talenta.kewarganegaraan,
                    talenta.gol_darah,
                    talenta.suku,
                    agamas.nama as talenta_agama,
                    talenta.tanggal_lahir,
                    talenta.tempat_lahir
                  FROM
                    view_organ_perusahaan
                    LEFT JOIN talenta ON talenta.ID = view_organ_perusahaan.id_talenta
                    LEFT JOIN struktur_organ ON struktur_organ.ID = view_organ_perusahaan.id_struktur_organ
                    LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan
                    LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                    LEFT JOIN surat_keputusan ON surat_keputusan.ID = view_organ_perusahaan.id_surat_keputusan
                    LEFT JOIN instansi_baru ON instansi_baru.ID = talenta.id_asal_instansi
                    LEFT JOIN jenis_asal_instansi ON jenis_asal_instansi.ID = instansi_baru.id_jenis_asal_instansi
                    LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                    LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.
                    ID LEFT JOIN sk_kom_independen ON sk_kom_independen.id_struktur_organ = struktur_organ.ID
                    LEFT JOIN riwayat_pendidikan on riwayat_pendidikan.id_talenta = talenta.id
                    LEFT JOIN jenjang_pendidikan on jenjang_pendidikan.id = riwayat_pendidikan.id_jenjang_pendidikan
                    LEFT JOIN agamas on agamas.id = talenta.id_agama
                    LEFT JOIN cluster_bumn on cluster_bumn.id = perusahaan.id_klaster
                  WHERE
                    surat_keputusan.save = 't'
                    AND struktur_organ.aktif = 't' $where
                  GROUP BY
                    perusahaan.ID,
                    view_organ_perusahaan.tanggal_akhir,
                    view_organ_perusahaan.aktif,
                    talenta.nama_lengkap,
                    talenta.ID,
                    grup_jabatan.ID,
                    view_organ_perusahaan.nomenklatur,
                    struktur_organ.nomenklatur_jabatan,
                    surat_keputusan.nomor,
                    surat_keputusan.tanggal_sk,
                    view_organ_perusahaan.tanggal_awal,
                    view_organ_perusahaan.plt,
                    view_organ_perusahaan.komisaris_independen,
                    instansi_baru.nama,
                    jenis_asal_instansi.nama,
                    view_organ_perusahaan.id_periode_jabatan,
                    struktur_organ.ID,
                    talenta.jenis_kelamin,
                    talenta.nik,
                    talenta.npwp,
                    talenta.email,
                    talenta.nomor_hp,
                    talenta.kewarganegaraan,
                    talenta.gol_darah,
                    talenta.suku,
                    agamas.nama,
                    talenta.tanggal_lahir,
                    talenta.tempat_lahir,
                    cluster_bumn.nama
                  ORDER BY
                    perusahaan.ID ASC,
                    grup_jabatan.ID ASC,
                    struktur_organ.urut ASC";
    	/*$id_sql = "SELECT
                            perusahaan.ID,
                          CASE

                              WHEN ( view_organ_perusahaan.tanggal_akhir < CURRENT_DATE ) THEN
                            TRUE ELSE FALSE
                            END AS expire,
                          CASE

                              WHEN ( view_organ_perusahaan.tanggal_akhir < CURRENT_DATE - INTERVAL '3 months ago' ) THEN
                            TRUE ELSE FALSE
                            END AS kurang3,
                          CASE

                              WHEN ( view_organ_perusahaan.tanggal_akhir < CURRENT_DATE - INTERVAL '6 months ago' ) THEN
                            TRUE ELSE FALSE
                            END AS kurang6,
                          CASE

                              WHEN view_organ_perusahaan.aktif = 't' THEN
                              talenta.nama_lengkap ELSE talenta.nama_lengkap
                            END AS pejabat,
                          CASE

                              WHEN view_organ_perusahaan.aktif = 't' THEN
                              'AKTIF' ELSE'TIDAK AKTIF'
                            END AS aktifpejabat,
                            perusahaan.nama_lengkap AS bumns,
                            grup_jabatan.ID AS grup_jabat_id,
                            grup_jabatan.nama AS grup_jabat_nama,
                          CASE

                              WHEN view_organ_perusahaan.nomenklatur IS NULL THEN
                              struktur_organ.nomenklatur_jabatan ELSE view_organ_perusahaan.nomenklatur
                            END AS nama_jabatan,
                            surat_keputusan.nomor,
                            surat_keputusan.tanggal_sk,
                            view_organ_perusahaan.tanggal_awal,
                            view_organ_perusahaan.tanggal_akhir,
                            view_organ_perusahaan.plt,
                            view_organ_perusahaan.komisaris_independen,
                            instansi.nama AS instansi,
                            jenis_asal_instansi.nama AS asal_instansi,
                            view_organ_perusahaan.id_periode_jabatan AS periode,
                            struktur_organ.ID AS struktur_id,
                            talenta.jenis_kelamin,
                            talenta.nik,
                            talenta.npwp,
                            talenta.email,
                            talenta.nomor_hp,
                            talenta.kewarganegaraan
                          FROM
                            view_organ_perusahaan
                            LEFT JOIN talenta ON talenta.ID = view_organ_perusahaan.id_talenta
                            LEFT JOIN struktur_organ ON struktur_organ.ID = view_organ_perusahaan.id_struktur_organ
                            LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN surat_keputusan ON surat_keputusan.ID = view_organ_perusahaan.id_surat_keputusan
                            LEFT JOIN instansi ON instansi.ID = talenta.id_asal_instansi
                            LEFT JOIN jenis_asal_instansi ON jenis_asal_instansi.ID = instansi.id_jenis_asal_instansi
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.
                            ID LEFT JOIN sk_kom_independen ON sk_kom_independen.id_struktur_organ = struktur_organ.ID
                          WHERE
                            surat_keputusan.save = 't' and struktur_organ.aktif = 't'
                          ORDER BY
                            perusahaan.ID ASC,
                            grup_jabatan.ID ASC,
                            struktur_organ.urut ASC";*/
         $isiadmin  = DB::select(DB::raw($id_sql));
         $collections = new Collection;

         foreach($isiadmin as $val){

                $collections->push([
                    'pejabat' => $val->pejabat,
                    'bumns' => $val->bumns,
                    'kelas_bumn' => $val->kelas_bumn,
                    'klaster_bumn' => $val->klaster_bumn,
                    'nama' => $val->nama_jabatan,
                    'grup_jabat_nama' => $val->grup_jabat_nama,
                    'periode' => $val->periode,
                    'nomor' => $val->nomor,
                    'tanggal_sk' => \Carbon\Carbon::createFromFormat('Y-m-d', $val->tanggal_sk)->format('d-m-Y'),
                    'tanggal_awal' => \Carbon\Carbon::createFromFormat('Y-m-d', $val->tanggal_awal)->format('d-m-Y'),
                    'tanggal_akhir' => \Carbon\Carbon::createFromFormat('Y-m-d', $val->tanggal_akhir)->format('d-m-Y'),
                    'instansi' => $val->instansi,
                    'asal_instansi' => $val->asal_instansi,
                    'jenis_kelamin' => $val->jenis_kelamin,
                    'nik' => !empty($val->nik)? "'".$val->nik : '',
                    'npwp' => !empty($val->npwp)? "'".$val->npwp : '',
                    'email' => $val->email,
                    'nomor_hp' => $val->nomor_hp,
                    'kewarganegaraan' => $val->kewarganegaraan,
                    'gol_darah' => $val->gol_darah,
                    'suku' => $val->suku,
                    'talenta_agama' => $val->talenta_agama,
                    'tanggal_lahir' => $val->tanggal_lahir,
                    'tempat_lahir' => $val->tempat_lahir,
                    'jenjang_pendidikan' => $val->jenjang_pendidikan,
                ]);
            }
        return $collections;
    }
}
