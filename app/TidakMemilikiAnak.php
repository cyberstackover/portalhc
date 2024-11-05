<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TidakMemilikiAnak extends Model
{
    protected $table = 'tidak_memiliki_anak';

    protected $fillable = [
        'id_talenta'
    ]; 
    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
