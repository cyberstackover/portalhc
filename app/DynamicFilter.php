<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DynamicFilter extends Model
{
    protected $table = 'dynamic_filter';

    protected $fillable = [
        'menu', 'dynamic_tabel_sumber_id', 'dynamic_standar_value_id', 'submenu','tipe','aktif','is_number','keterangan'
    ];

    public function tabelSumber()
    {
        return $this->belongsTo('App\DynamicTabelSumber', 'dynamic_tabel_sumber_id');
    }
    public function standarValue()
    {
        return $this->belongsTo('App\DynamicStandarValue', 'dynamic_standar_value_id');
    }
}
