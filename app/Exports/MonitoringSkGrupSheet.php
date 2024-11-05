<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Perusahaan;
use DB;

class MonitoringSkGrupSheet implements FromCollection, WithTitle, WithHeadings
{
    private $perusahaan_level;
    private $id_perusahaan;

    public function __construct(int $id_perusahaan = null, int $perusahaan_level)
    {
        $this->perusahaan_level = $perusahaan_level;
        $this->id_perusahaan  = $id_perusahaan;
    }

    /**
     * @return Builder
     */
    public function headings(): array
    {
        switch ($this->perusahaan_level) {
            case 0:
                return ['No', 'BUMN Induk','Kepemilikan', 'Jumlah Kursi Direksi', 'Jumlah Kursi Dekomwas', 'Jumlah Direksi', 'Jumlah Dekomwas', 'Persentase Direksi', 'Persentase Dekomwas'];
                break;
            case 1:
                return ['No', 'BUMN Induk', 'BUMN','Kepemilikan', 'Jumlah Kursi Direksi', 'Jumlah Kursi Dekomwas', 'Jumlah Direksi', 'Jumlah Dekomwas', 'Persentase Direksi', 'Persentase Dekomwas'];
                break;
            case 2:
                return ['No', 'BUMN Induk', 'BUMN Anak','BUMN', 'Kepemilikan', 'Jumlah Kursi Direksi', 'Jumlah Kursi Dekomwas', 'Jumlah Direksi', 'Jumlah Dekomwas', 'Persentase Direksi', 'Persentase Dekomwas'];
                break;
        }
    }

    public function collection()
    {
        switch ($this->perusahaan_level) {
            case 0:
                if ($this->id_perusahaan) {
                    $where = " AND perusahaan.ID = " . $this->id_perusahaan;
                } else {
                    $where = '';
                }
                $id_sql = "SELECT
                perusahaan.ID AS ID,
                perusahaan.nama_lengkap AS bumn_nama,
                perusahaan.kepemilikan,
                COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 1 THEN 1 END ) AS jumlah_direksi,
                COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 4 THEN 1 END ) AS jumlah_dekomwas,
                jabatan_terisi.direksi,
                jabatan_terisi.dekom as dekomwas,
                 TRUNC(
                NULLIF(jabatan_terisi.direksi,0) / NULLIF(COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 1 THEN 1 END ) :: NUMERIC,0) * 100
                ) 
                AS presentase_direksi,
                     TRUNC(
                NULLIF(jabatan_terisi.dekom,0) / NULLIF(COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 4 THEN 1 END ) :: NUMERIC,0) * 100
                ) 
                AS presentase_dekomwas
                
            FROM
                perusahaan
                LEFT JOIN struktur_organ ON struktur_organ.id_perusahaan = perusahaan.
                ID LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                LEFT JOIN (select a.id_perusahaan, sum(a.direksi) as direksi, sum(a.dekom) as dekom 
                from (
                select
                id_perusahaan,
                count(id_perusahaan) as direksi, 
                0 as dekom
                from view_organ_perusahaan
                LEFT JOIN struktur_organ on struktur_organ.id = view_organ_perusahaan.id_struktur_organ
                LEFT JOIN jenis_jabatan on jenis_jabatan.id =struktur_organ.id_jenis_jabatan
                where jenis_jabatan.id_grup_jabatan = 1
                    AND view_organ_perusahaan.aktif IS TRUE 
                AND ( view_organ_perusahaan.tanggal_akhir >= now( ) OR view_organ_perusahaan.tanggal_akhir IS NULL )
                GROUP BY id_perusahaan
                
            union all 	 
                select
                id_perusahaan,
                0 as direksi,
                count(id_perusahaan) as dekom 
                from view_organ_perusahaan
                LEFT JOIN struktur_organ on struktur_organ.id = view_organ_perusahaan.id_struktur_organ
                LEFT JOIN jenis_jabatan on jenis_jabatan.id =struktur_organ.id_jenis_jabatan
                where jenis_jabatan.id_grup_jabatan = 4
                    AND view_organ_perusahaan.aktif IS TRUE 
                AND ( view_organ_perusahaan.tanggal_akhir >= now( ) OR view_organ_perusahaan.tanggal_akhir IS NULL 
                )
                GROUP BY id_perusahaan
                ) a 
                GROUP BY a.id_perusahaan) as jabatan_terisi on jabatan_terisi.id_perusahaan = perusahaan.id
            WHERE
                struktur_organ.aktif = 't'
                and perusahaan.is_active = 't'
                and perusahaan.LEVEL = 0
                ".$where."
            GROUP BY
                perusahaan.ID,
                perusahaan.nama_lengkap,
                jabatan_terisi.direksi,
                    jabatan_terisi.dekom
            ORDER BY
                perusahaan.ID ASC";
                $isiadmin  = DB::select(DB::raw($id_sql));
                $collections = new Collection;
                $no = 1;
                foreach ($isiadmin as $val) {
                    $collections->push([
                        'no' => $no,
                        'bumn_nama' => $val->bumn_nama,
                        'kepemilikan' => $val->kepemilikan,
                        'jumlah_direksi' => ($val->jumlah_direksi != '0') ? $val->jumlah_direksi : '0',
                        'jumlah_dekomwas' => ($val->jumlah_dekomwas != '0') ? $val->jumlah_dekomwas : '0',
                        'direksi' => ($val->direksi != '0') ? $val->direksi : '0',
                        'dekomwas' => ($val->dekomwas != '0') ? $val->dekomwas : '0',
                        'presentase_direksi' => ($val->presentase_direksi != '0') ? $val->presentase_direksi : '0',
                        'presentase_dekomwas' => ($val->presentase_dekomwas != '0') ? $val->presentase_dekomwas : '0',
                    ]);
                    $no++;
                }

                $header = ['No', 'BUMN', 'Jumlah Direksi', 'Jumlah Dirkomwas', 'Jumlah Organ Isi', 'Persentase Isi'];
                break;

            case 1:
                if ($this->id_perusahaan) {
                    $where = " AND perusahaan_induk.ID = " . $this->id_perusahaan;
                } else {
                    $where = '';
                }
                $id_sql = "SELECT
                perusahaan.ID AS ID,
                perusahaan_induk.nama_lengkap AS bumn_induk,
                perusahaan.nama_lengkap AS bumn_nama,
                perusahaan.kepemilikan,
                COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 1 THEN 1 END ) AS jumlah_direksi,
                COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 4 THEN 1 END ) AS jumlah_dekomwas,
                jabatan_terisi.direksi,
                jabatan_terisi.dekom as dekomwas,
                 TRUNC(
                NULLIF(jabatan_terisi.direksi,0) / NULLIF(COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 1 THEN 1 END ) :: NUMERIC,0) * 100
                ) 
                AS presentase_direksi,
                     TRUNC(
                NULLIF(jabatan_terisi.dekom,0) / NULLIF(COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 4 THEN 1 END ) :: NUMERIC,0) * 100
                ) 
                AS presentase_dekomwas 
            FROM
                perusahaan
                LEFT JOIN perusahaan as perusahaan_induk ON perusahaan.induk = perusahaan_induk.id
                LEFT JOIN struktur_organ ON struktur_organ.id_perusahaan = perusahaan.
                ID LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                LEFT JOIN (select a.id_perusahaan, sum(a.direksi) as direksi, sum(a.dekom) as dekom 
                from (
                select
                id_perusahaan,
                count(id_perusahaan) as direksi, 
                0 as dekom
                from view_organ_perusahaan
                LEFT JOIN struktur_organ on struktur_organ.id = view_organ_perusahaan.id_struktur_organ
                LEFT JOIN jenis_jabatan on jenis_jabatan.id =struktur_organ.id_jenis_jabatan
                where jenis_jabatan.id_grup_jabatan = 1
                    AND view_organ_perusahaan.aktif IS TRUE 
                AND ( view_organ_perusahaan.tanggal_akhir >= now( ) OR view_organ_perusahaan.tanggal_akhir IS NULL )
                GROUP BY id_perusahaan
                
            union all 	 
                select
                id_perusahaan,
                0 as direksi,
                count(id_perusahaan) as dekom 
                from view_organ_perusahaan
                LEFT JOIN struktur_organ on struktur_organ.id = view_organ_perusahaan.id_struktur_organ
                LEFT JOIN jenis_jabatan on jenis_jabatan.id =struktur_organ.id_jenis_jabatan
                where jenis_jabatan.id_grup_jabatan = 4
                    AND view_organ_perusahaan.aktif IS TRUE 
                AND ( view_organ_perusahaan.tanggal_akhir >= now( ) OR view_organ_perusahaan.tanggal_akhir IS NULL 
                )
                GROUP BY id_perusahaan
                ) a 
                GROUP BY a.id_perusahaan) as jabatan_terisi on jabatan_terisi.id_perusahaan = perusahaan.id 
            WHERE
                struktur_organ.aktif = 't'
                AND perusahaan_induk.is_active = 't' 
                AND perusahaan.LEVEL = 1
                AND perusahaan.is_active = 't'
                ".$where." 
            GROUP BY
                perusahaan.ID,
                perusahaan.nama_lengkap,
                perusahaan_induk.nama_lengkap,
                perusahaan_induk.level,
                        jabatan_terisi.direksi,
                    jabatan_terisi.dekom
            ORDER BY
                perusahaan.ID ASC";
                $isiadmin  = DB::select(DB::raw($id_sql));
                $collections = new Collection;
                $no = 1;
                foreach ($isiadmin as $val) {
                    $collections->push([
                        'no' => $no,
                        'bumn_induk' => $val->bumn_induk,
                        'bumn_nama' => $val->bumn_nama,
                        'kepemilikan' => $val->kepemilikan,
                        'jumlah_direksi' => ($val->jumlah_direksi != '0') ? $val->jumlah_direksi : '0',
                        'jumlah_dekomwas' => ($val->jumlah_dekomwas != '0') ? $val->jumlah_dekomwas : '0',
                        'direksi' => ($val->direksi != '0') ? $val->direksi : '0',
                        'dekomwas' => ($val->dekomwas != '0') ? $val->dekomwas : '0',
                        'presentase_direksi' => ($val->presentase_direksi != '0') ? $val->presentase_direksi : '0',
                        'presentase_dekomwas' => ($val->presentase_dekomwas != '0') ? $val->presentase_dekomwas : '0',
                    ]);
                    $no++;
                }
                $header = ['No', 'BUMN Induk', 'BUMN', 'Jumlah Direksi', 'Jumlah Dirkomwas', 'Jumlah Organ Isi', 'Persentase Isi'];
                break;

            case 2:
                if ($this->id_perusahaan) {
                    $where = " AND perusahaan_induk.ID = " . $this->id_perusahaan;
                } else {
                    $where = '';
                }
                $id_sql = "SELECT
                perusahaan.ID AS ID,
                perusahaan_induk.nama_lengkap AS bumn_induk,
                perusahaan_anak.nama_lengkap AS bumn_anak,
                perusahaan.nama_lengkap AS bumn_nama,
                perusahaan.kepemilikan,
                COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 1 THEN 1 END ) AS jumlah_direksi,
                COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 4 THEN 1 END ) AS jumlah_dekomwas,
                jabatan_terisi.direksi,
                jabatan_terisi.dekom as dekomwas,
                 TRUNC(
                NULLIF(jabatan_terisi.direksi,0) / NULLIF(COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 1 THEN 1 END ) :: NUMERIC,0) * 100
                ) 
                AS presentase_direksi,
                     TRUNC(
                NULLIF(jabatan_terisi.dekom,0) / NULLIF(COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 4 THEN 1 END ) :: NUMERIC,0) * 100
                ) 
                AS presentase_dekomwas 
            FROM
                perusahaan
                LEFT JOIN perusahaan as perusahaan_anak ON perusahaan.induk = perusahaan_anak.id
                LEFT JOIN perusahaan as perusahaan_induk ON perusahaan_anak.induk = perusahaan_induk.id
                LEFT JOIN struktur_organ ON struktur_organ.id_perusahaan = perusahaan.ID 
                LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                LEFT JOIN (select a.id_perusahaan, sum(a.direksi) as direksi, sum(a.dekom) as dekom 
                from (
                select
                id_perusahaan,
                count(id_perusahaan) as direksi, 
                0 as dekom
                from view_organ_perusahaan
                LEFT JOIN struktur_organ on struktur_organ.id = view_organ_perusahaan.id_struktur_organ
                LEFT JOIN jenis_jabatan on jenis_jabatan.id =struktur_organ.id_jenis_jabatan
                where jenis_jabatan.id_grup_jabatan = 1
                    AND view_organ_perusahaan.aktif IS TRUE 
                AND ( view_organ_perusahaan.tanggal_akhir >= now( ) OR view_organ_perusahaan.tanggal_akhir IS NULL )
                GROUP BY id_perusahaan
                
            union all 	 
                select
                id_perusahaan,
                0 as direksi,
                count(id_perusahaan) as dekom 
                from view_organ_perusahaan
                LEFT JOIN struktur_organ on struktur_organ.id = view_organ_perusahaan.id_struktur_organ
                LEFT JOIN jenis_jabatan on jenis_jabatan.id =struktur_organ.id_jenis_jabatan
                where jenis_jabatan.id_grup_jabatan = 4
                    AND view_organ_perusahaan.aktif IS TRUE 
                AND ( view_organ_perusahaan.tanggal_akhir >= now( ) OR view_organ_perusahaan.tanggal_akhir IS NULL 
                )
                GROUP BY id_perusahaan
                ) a 
                GROUP BY a.id_perusahaan) as jabatan_terisi on jabatan_terisi.id_perusahaan = perusahaan.id
            WHERE
                struktur_organ.aktif = 't' 
                AND perusahaan.LEVEL = 2 
                AND perusahaan_induk.is_active = 't'
                    AND perusahaan_anak.is_active = 't'
                AND perusahaan.is_active = 't'
                ".$where."
            GROUP BY
                perusahaan.ID,
                perusahaan.nama_lengkap,
                perusahaan_induk.nama_lengkap,
                perusahaan_anak.nama_lengkap,
                perusahaan_induk.level,
                perusahaan_anak.level,
                    jabatan_terisi.direksi,
                    jabatan_terisi.dekom
            ORDER BY
                perusahaan.ID ASC";
                $isiadmin  = DB::select(DB::raw($id_sql));
                $collections = new Collection;
                $no = 1;
                foreach ($isiadmin as $val) {
                    $collections->push([
                        'no' => $no,
                        'bumn_induk' => $val->bumn_induk,
                        'bumn_anak' => $val->bumn_anak,
                        'bumn_nama' => $val->bumn_nama,
                        'kepemilikan' => $val->kepemilikan,
                        'jumlah_direksi' => ($val->jumlah_direksi != '0') ? $val->jumlah_direksi : '0',
                        'jumlah_dekomwas' => ($val->jumlah_dekomwas != '0') ? $val->jumlah_dekomwas : '0',
                        'direksi' => ($val->direksi != '0') ? $val->direksi : '0',
                        'dekomwas' => ($val->dekomwas != '0') ? $val->dekomwas : '0',
                        'presentase_direksi' => ($val->presentase_direksi != '0') ? $val->presentase_direksi : '0',
                        'presentase_dekomwas' => ($val->presentase_dekomwas != '0') ? $val->presentase_dekomwas : '0',
                    ]);
                    $no++;
                }
                $header = ['No', 'BUMN Induk', 'BUMN Anak', 'BUMN', 'Jumlah Direksi', 'Jumlah Dirkomwas', 'Jumlah Organ Isi', 'Persentase Isi'];
                break;
        }
        return $collections;
    }
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Level ' . $this->perusahaan_level;
    }
}
