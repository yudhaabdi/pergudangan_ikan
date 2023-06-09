<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $table = "transaksi_detail";

    public function transaksi(){
    	return $this->belongsTo('App\Model\Transaksi', 'id_transaksi');
    }

    public function dataBarang(){
    	return $this->belongsTo('App\DataBarang', 'id_data_barang');
    }
}
