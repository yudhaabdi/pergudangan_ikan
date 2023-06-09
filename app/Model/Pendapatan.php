<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    protected $table = "pendapatan";

    public function dataBarang(){
    	return $this->belongsTo('App\DataBarang', 'id_barang');
    }

    public function piutang(){
    	return $this->belongsTo('App\Model\Transaksi', 'id_piutang');
    }
}
