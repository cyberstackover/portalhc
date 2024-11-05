<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionTalentaSocialMedia extends Model
{
    protected $table = 'transaction_talenta_social_media';

    protected $fillable = [
        'id_talenta', 'id_social_media', 'name_social_media'
    ];

    public function socialMedia()
    {
        return $this->belongsTo('App\SocialMedia', 'id_social_media');
    }

    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}