<?php
namespace App\Exports;

use App\Talenta;
use App\User;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class RekapStatusTalent implements FromView , WithTitle
{
     public function view(): View
    {   
        $query = "SELECT all_data.bumn, 
                            count(all_data.bumn) as total_talenta,
                            COALESCE( SUM(CASE WHEN all_data.id_status_talenta = 1 THEN 1 END), 0) as jumlah_added,
                            COALESCE( SUM(CASE WHEN all_data.id_status_talenta = 2 THEN 1 END), 0) as jumlah_registered,
                            COALESCE( SUM(CASE WHEN all_data.id_status_talenta = 3 THEN 1 END), 0) as jumlah_non_talent,
                            COALESCE( SUM(CASE WHEN all_data.id_status_talenta = 4 THEN 1 END), 0) as jumlah_selected,
                            COALESCE( SUM(CASE WHEN all_data.id_status_talenta = 5 THEN 1 END), 0) as jumlah_nominated,
                            COALESCE( SUM(CASE WHEN all_data.id_status_talenta = 6 OR all_data.id_status_talenta = 7 THEN 1 END), 0) as jumlah_eligible,
                            COALESCE( SUM(CASE WHEN all_data.id_status_talenta = 8 THEN 1 END), 0) as jumlah_qualified
                    FROM 
                    (
                
                        SELECT distinct B.talenta_id, 
                                B.bumn, 
                                B.id_status_talenta
                        FROM 
                        (
                                SELECT  
                                    A.talenta_id,
                                    CASE 
                                    WHEN bumn_induk IS NULL THEN bumn
                                    ELSE bumn_induk
                                    END AS bumn,
                                    A.id_status_talenta
                                FROM 
                                (      
	                                SELECT 
                                            bumn_induk.nama_lengkap as bumn_induk,
                                            CASE 
                                            WHEN bumn_0.level = 0 THEN bumn_0.nama_lengkap 
                                            WHEN bumn_1.level = 0 THEN bumn_1.nama_lengkap 
                                            WHEN bumn_2.level = 0 THEN bumn_2.nama_lengkap 
                                            WHEN bumn_3.level = 0 THEN bumn_3.nama_lengkap 
                                            WHEN bumn_4.level = 0 THEN bumn_4.nama_lengkap 
                                            END AS bumn,
                                            talenta.id_perusahaan,
                                            talenta.id AS talenta_id, 	
                                            talenta.nama_lengkap, 
                                            talenta.id_status_talenta,
                                            jabatan.nomenklatur_jabatan,
                                            jabatan.nama_lengkap, 
                                            jabatan.id_perusahaan
                                    FROM talenta
                                    LEFT JOIN perusahaan AS bumn_induk ON bumn_induk.ID = talenta.id_perusahaan
                                    LEFT JOIN lateral (select s.nomenklatur_jabatan, p.nama_lengkap, p.id, skp.nomenklatur_baru, s.id_perusahaan, sk.id_grup_jabatan
                                                    from view_organ_perusahaan v
                                                    left join struktur_organ s on s.id = v.id_struktur_organ 
                                                    LEFT JOIN organ_perusahaan op ON op.id_struktur_organ = s.id
                                                    LEFT JOIN surat_keputusan sk ON sk.id = op.id_surat_keputusan
                                                    left join perusahaan p on p.id = s.id_perusahaan
                                                    LEFT JOIN sk_perubahan_nomenklatur skp ON skp.id_struktur_organ = s.ID
                                                    where v.id_talenta = talenta.id 
                                                    and v.aktif = 't'
                                                    and sk.id_grup_jabatan = 1
                                                    and (v.tanggal_akhir >= now( ) 
                                                            or v.tanggal_akhir is null)
                                                    order by v.id_struktur_organ ASC, s.urut ASC 
                                                    limit 1) jabatan ON TRUE
                                    LEFT JOIN perusahaan AS bumn_0 ON bumn_0.ID = jabatan.id_perusahaan
                                    LEFT JOIN perusahaan AS bumn_1 ON bumn_1.ID = bumn_0.induk
                                    LEFT JOIN perusahaan AS bumn_2 ON bumn_2.ID = bumn_1.induk
                                    LEFT JOIN perusahaan AS bumn_3 ON bumn_3.ID = bumn_2.induk
                                    LEFT JOIN perusahaan AS bumn_4 ON bumn_4.ID = bumn_3.induk
                                    WHERE 
                                        ((jabatan.id_grup_jabatan = 1 )
                                        OR talenta.is_talenta = true )
                                    GROUP BY talenta.id,
                                            bumn_0.nama_lengkap,
                                            bumn_1.nama_lengkap,
                                            bumn_2.nama_lengkap,
                                            bumn_3.nama_lengkap,
                                            bumn_4.nama_lengkap,
                                            bumn_0.level,
                                            bumn_1.level,
                                            bumn_2.level,
                                            bumn_3.level,
                                            bumn_4.level,
                                            bumn_induk.nama_lengkap,
                                            jabatan.nomenklatur_jabatan,
                                            jabatan.nama_lengkap, 
                                            jabatan.id_perusahaan
                                    ORDER BY talenta.id
                                ) A
                        ) B where B.bumn is not null
                
                    ) all_data 
                    GROUP BY all_data.bumn
                    ORDER BY all_data.bumn
                 ";
         
         $talenta  = DB::select(DB::raw($query));
        return view('talenta.dashboard.rekap_status_talent', [
            'talenta' => $talenta
        ]);
    }

    public function title(): string
    {
        return 'Rekapitulasi Status Talent' ;
    }
}
?>