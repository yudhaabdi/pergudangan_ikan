<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = "pembayaran";

    public function transaksi(){
    	return $this->hasOne('App\Model\Transaksi', 'id_pembayaran', 'id');
    }

    public function dataBarang(){
    	return $this->belongsTo('App\DataBarang', 'id_data_barang');
    }

    public function daftarPiutang(){
    	return $this->belongsTo('App\Model\DaftarPiutang', 'id_piutang');
    }
}
