<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TidakMemilikiPengalamanLain extends Model
{
    protected $table = 'tidak_memiliki_pengalaman_lain';

    protected $fillable = [
        'id_talenta'
    ]; 
    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
