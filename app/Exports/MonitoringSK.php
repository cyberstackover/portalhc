<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class MonitoringSK implements FromCollection, WithHeadings
{

	public function headings(): array
    {
        return [
           ['No', 'BUMN (Induk)', 'Perusahaan', 'Jumlah Direksi', 'Jumlah Dirkomwas', 'Jumlah Organ Kosong', 'Persentase Isi']
        ];
    }

    public function collection()
    {
    	$id_sql = "SELECT
        perusahaan.ID AS ID,
        perusahaan.nama_lengkap AS bumn,
        anak.nama_lengkap AS perusahaan,
        Case
				When perusahaan.level = 0 Then 'BUMN'
				When perusahaan.level = 1 Then 'ANAK'
				When perusahaan.level = 2 Then 'CUCU' End as level,
        COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 1 THEN 1 END ) AS jumlah_direksi,
        COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 4 THEN 1 END ) AS jumlah_dirkomwas,
        COUNT ( CASE organ_perusahaan.aktif WHEN FALSE THEN 1 END ) AS jumlah_organ_kosong,
        TRUNC(
        ( COUNT ( CASE organ_perusahaan.aktif WHEN TRUE THEN 1 END ) ) :: NUMERIC / ( COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 1 THEN 1 END ) + COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 4 THEN 1 END ) ) * 100,
        0 
        ) AS presentase_isi
      FROM
        perusahaan
        LEFT JOIN perusahaan AS anak ON anak.ID = perusahaan.induk
        LEFT JOIN struktur_organ ON struktur_organ.id_perusahaan = perusahaan.ID 
        LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
        LEFT JOIN organ_perusahaan ON organ_perusahaan.id_struktur_organ = struktur_organ.ID 
      WHERE
        struktur_organ.aktif = 't'
        and organ_perusahaan.aktif='t'
      GROUP BY
        perusahaan.ID,
        perusahaan.nama_lengkap,
        perusahaan.induk,
        anak.ID,
        anak.nama_lengkap
        ORDER BY
        perusahaan.induk ASC,
        perusahaan.ID ASC";
         $isiadmin  = DB::select(DB::raw($id_sql));
         $collections = new Collection;
         $no = 1;

         foreach($isiadmin as $val){

                $collections->push([
                    'no' => $no,
                    'bumns' => $val->bumn,
                    'perusahaan' => !empty($val->perusahaan) ? $val->perusahaan. " - " .$val->level : '',
                    'jumlah_direksi' => ($val->jumlah_direksi != '0') ? $val->jumlah_direksi : '0',
                    'jumlah_dirkomwas' => ($val->jumlah_dirkomwas != '0') ? $val->jumlah_dirkomwas : '0',
                    'jumlah_organ_kosong' => ($val->jumlah_organ_kosong != '0') ? $val->jumlah_organ_kosong : '0',
                    'presentase_isi' => ($val->presentase_isi != '0') ? $val->presentase_isi : '0',
                ]);
                $no++;
            }
        return $collections;
    }
}
