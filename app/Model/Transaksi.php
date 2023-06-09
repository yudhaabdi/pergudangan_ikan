<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = "transaksi";

    public function transaksi_detail(){
    	return $this->hasMany('App\Model\TransaksiDetail', 'id_transaksi', 'id');
    }

    public function pembayaran(){
    	return $this->belongsTo('App\Model\Pembayaran', 'id_pembayaran');
    }

    public function daftarPiutang(){
    	return $this->belongsTo('App\Model\DaftarPiutang', 'id_piutang');
    }

    public function hutangPerusahaan(){
    	return $this->belongsTo('App\Model\HutangPerusahaan', 'id_hutang_perusahaan');
    }

}
