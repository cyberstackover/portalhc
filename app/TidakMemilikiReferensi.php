<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TidakMemilikiReferensi extends Model
{
    protected $table = 'tidak_memiliki_referensi';

    protected $fillable = [
        'id_talenta'
    ]; 
    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
