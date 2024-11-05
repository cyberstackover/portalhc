<?php
namespace App\Exports;

use App\Talenta;
use App\User;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class RekapCV implements FromView , WithTitle
{
     public function view(): View
    {   
        $id_users = \Auth::user()->id;
        $id_users_bumn = \Auth::user()->id_bumn;
        $users = User::where('id', $id_users)->first();

        $talenta = DB::table('talenta')
                      ->leftJoin('view_organ_perusahaan', 'talenta.id', '=', 'view_organ_perusahaan.id_talenta')
                      ->leftJoin('struktur_organ', 'struktur_organ.id', '=', 'view_organ_perusahaan.id_struktur_organ')
                      ->leftJoin('perusahaan', 'perusahaan.id', '=', 'struktur_organ.id_perusahaan')
                      ->leftJoin(DB::raw("lateral (select s.nomenklatur_jabatan, p.nama_lengkap, p.level, s.id_perusahaan
                                      from view_organ_perusahaan v
                                      left join struktur_organ s on v.id_struktur_organ = s.id
                                      left join perusahaan p on p.id = s.id_perusahaan
                                      where v.id_talenta = talenta.id 
                                      and v.aktif = 't'
                                      order by s.urut ASC 
                                      limit 1) jabatan"), 'talenta.id', '=', 'talenta.id')
                      ->leftJoin('perusahaan AS bumn_0', 'bumn_0.id', '=', 'jabatan.id_perusahaan')
                      ->leftJoin('perusahaan AS bumn_1', 'bumn_1.id', '=', 'bumn_0.induk')
                      ->leftJoin('perusahaan AS bumn_2', 'bumn_2.id', '=', 'bumn_1.induk')
                      ->leftJoin('perusahaan AS bumn_3', 'bumn_3.id', '=', 'bumn_2.induk')
                      ->leftJoin('perusahaan AS bumn_4', 'bumn_4.id', '=', 'bumn_3.induk')
                      ->select(DB::raw("talenta.*, 
                                    jabatan.nama_lengkap as nama_perusahaan,
                                    CASE 
                                      WHEN jabatan.level = 0 THEN bumn_0.nama_lengkap 
                                      WHEN jabatan.level = 1 THEN bumn_1.nama_lengkap 
                                      WHEN jabatan.level = 2 THEN bumn_2.nama_lengkap 
                                      WHEN jabatan.level = 3 THEN bumn_3.nama_lengkap 
                                      WHEN jabatan.level = 4 THEN bumn_4.nama_lengkap 
                                    END AS bumn_induk,
                                    CASE 
                                      WHEN jabatan.level = 1 THEN bumn_0.nama_lengkap 
                                      WHEN jabatan.level = 2 THEN bumn_1.nama_lengkap
                                      WHEN jabatan.level = 3 THEN bumn_2.nama_lengkap
                                      WHEN jabatan.level = 4 THEN bumn_3.nama_lengkap
                                    END AS bumn_anak,
                                    CASE 
                                      WHEN jabatan.level >= 2 THEN bumn_0.nama_lengkap 
                                    END AS bumn_cucu, 
                                    jabatan.level,
                                    jabatan.nomenklatur_jabatan as jabatan,
                                    talenta.id_jenis_asal_instansi"))
                      ->GroupBy('talenta.id', 'jabatan.nomenklatur_jabatan', 'jabatan.nama_lengkap', 'jabatan.level', 'bumn_0.nama_lengkap', 'bumn_1.nama_lengkap', 'bumn_2.nama_lengkap', 'bumn_3.nama_lengkap', 'bumn_4.nama_lengkap')
                      ->orderBy('jabatan.level', 'ASC')
                      ->orderBy('jabatan.nama_lengkap', 'ASC')
                      ->orderBy('talenta.nama_lengkap', 'ASC');

       if($users->kategori_user_id != 1){
          $talenta = $talenta->whereRaw("(( (perusahaan.id in (
                                        WITH RECURSIVE anak AS (
                                        SELECT
                                          perusahaan_id,
                                          perusahaan_induk_id,
                                          tmt_awal,
                                          tmt_akhir 
                                        FROM
                                          perusahaan_relasi 
                                        WHERE
                                          perusahaan_induk_id = ".$id_users_bumn." 
                                          AND (
                                            tmt_awal <= NOW( ) :: DATE 
                                            AND (
                                            CASE
                                                
                                                WHEN tmt_akhir IS NOT NULL THEN
                                                tmt_akhir >= NOW( ) :: DATE ELSE NOW( ) :: DATE = NOW( ) :: DATE 
                                              END 
                                              ) 
                                            ) UNION
                                          SELECT
                                            pr.perusahaan_id,
                                            pr.perusahaan_induk_id,
                                            pr.tmt_awal,
                                            pr.tmt_akhir 
                                          FROM
                                            perusahaan_relasi pr
                                            INNER JOIN anak A ON A.perusahaan_id = pr.perusahaan_induk_id 
                                          ) SELECT
                                          P.ID
                                        FROM
                                          anak ak
                                          LEFT JOIN perusahaan P ON ak.perusahaan_id = P.ID 
                                        WHERE
                                          (
                                            ak.tmt_awal <= NOW( ) :: DATE 
                                            AND (
                                            CASE
                                                
                                                WHEN ak.tmt_akhir IS NOT NULL THEN
                                                ak.tmt_akhir >= NOW( ) :: DATE ELSE NOW( ) :: DATE = NOW( ) :: DATE 
                                              END 
                                              ) 
                                            ) 
                                          GROUP BY
                                            P.urut,
                                            P.ID,
                                            P.nama_lengkap,
                                            ak.perusahaan_induk_id,
                                            ak.perusahaan_id,
                                            ak.tmt_awal,
                                            ak.tmt_akhir 
                                          ORDER BY
                                          P.urut ASC,
                                        P.ID ASC 
                                    ))
                                  ) OR perusahaan.id = ".$id_users_bumn." ) ");
        }
        return view('cv.board.rekap_cv', [
            'talenta' => $talenta->whereNotNull('fill_biodata')->get()
        ]);
    }

    public function title(): string
    {
        return 'Rekap pengisian CV Talenta' ;
    }
}
?>