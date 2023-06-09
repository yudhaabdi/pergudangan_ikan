<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DaftarPiutang extends Model
{
    protected $table = "daftar_piutang";

    public function transaksi(){
    	return $this->hasMany('App\Model\Transaksi', 'id');
    }

    public function pembayaran(){
    	return $this->hasMany('App\Model\Pembayaran', 'id_piutang','id');
    }
}
