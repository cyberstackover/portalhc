<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionTalentaKelas extends Model
{
    protected $table = 'transaction_talenta_kelas';

    protected $fillable = [
        'id_talenta', 'id_kelas'
    ];

    public function kelas()
    {
        return $this->hasOne('App\KelasBumn', 'id_kelas');
    }
}
