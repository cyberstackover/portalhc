<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\TalentaExport;

class ExcelSheet implements WithMultipleSheets
{
    use Exportable;
    
     public function __construct($id = "" ){
        $this->id = $id ;
     }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new TalentaExport($this->id);
        $sheets[] = new ReferensiAgama();
        $sheets[] = new ReferensiKotaDN();
        $sheets[] = new ReferensiKotaLN();
        $sheets[] = new ReferensiPendidikan();
        $sheets[] = new ReferensiKawin();
        $sheets[] = new ReferensiGolonganDarah();
        $sheets[] = new ReferensiUniversitas();
        $sheets[] = new ReferensiSuku();
        return $sheets;
    }
}
?>