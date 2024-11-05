<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TidakMemilikiOrganisasiNonformal extends Model
{
    protected $table = 'tidak_memiliki_organisasi_nonformal';

    protected $fillable = [
        'id_talenta'
    ]; 
    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
