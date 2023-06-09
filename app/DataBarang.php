<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Model\Pembayaran;


class DataBarang extends Model
{
    protected $table = "data_barang";
    
    static function detail_barang($id_barang){
        $data = DataBarang::where('id', $id_barang)->first();
    
        return $data;
    }

    public function transaksiDetail(){
    	return $this->hashMany('App\model\TransaksiDetail');
    }

    public function pendapatan(){
    	return $this->hashMany('App\model\Pendapatan');
    }

    public function pembayaran(){
    	return $this->hashMany('App\Model\Pembayaran', 'id');
    }

    public function hutangPerusahaan(){
    	return $this->hasMany('App\Model\HutangPerusahaan', 'id');
    }

}

