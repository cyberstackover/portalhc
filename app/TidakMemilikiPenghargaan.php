<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TidakMemilikiPenghargaan extends Model
{
    protected $table = 'tidak_memiliki_penghargaan';

    protected $fillable = [
        'id_talenta'
    ]; 
    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
