<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionTalentaKeahlian extends Model
{
    protected $table = 'transaction_talenta_keahlian';

    protected $fillable = [
        'id_talenta', 'id_keahlian'
    ];

    public function keahlian()
    {
        return $this->belongsTo('App\Keahlian', 'id_keahlian');
    }
}
