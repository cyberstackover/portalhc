<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TidakMemilikiKaryaIlmiah extends Model
{
    protected $table = 'tidak_memiliki_karya_ilmiah';

    protected $fillable = [
        'id_talenta'
    ]; 
    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
