<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MonitoringSkGrup implements WithMultipleSheets
{
    use Exportable;

    protected $id_perusahaan;
    
    public function __construct(int $id_perusahaan=null)
    {
        $this->id_perusahaan = $id_perusahaan;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        for ($perusahaan_level = 0; $perusahaan_level <= 2; $perusahaan_level++) {
            $sheets[] = new MonitoringSkGrupSheet($this->id_perusahaan, $perusahaan_level);
        }

        return $sheets;
    }
}
