<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HutangPerusahaan extends Model
{
    protected $table = "hutang_perusahaan";

    public function transaksi(){
    	return $this->hasMany('App\Model\Transaksi', 'id');
    }

    public function dataBarang(){
    	return $this->belongsTo('App\DataBarang', 'id_barang');
    }
}
