<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Pembayaran;
use App\Model\Transaksi;
use App\Model\DaftarPiutang;
use Auth;

class ControllerPendapatan extends Controller
{
    public function index()
    {
        if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
            $pembayaran = Pembayaran::with('transaksi', 'transaksi.daftarPiutang')->where('pembayaran.lain_lain', 1)
            ->join('transaksi', function($query){
                $query->on('transaksi.id_pembayaran', '=', 'pembayaran.id');
                $query->where('transaksi.gudang', 1);
            })->select('pembayaran.*')
            ->get();
        }else{
            $pembayaran = Pembayaran::with('transaksi', 'transaksi.daftarPiutang')->where('pembayaran.lain_lain', 1)
            ->join('transaksi', function($query){
                $query->on('transaksi.id_pembayaran', '=', 'pembayaran.id');
                $query->where('transaksi.gudang', 2);
            })->select('pembayaran.*')
            ->get();
        }
        return view('pendapatan.index', compact(['pembayaran']));
    }

    public function tambah(Request $request)
    {
        $piutang = DaftarPiutang::where('nama_pembeli', $request->nama_pembeli)->first();
        
        if(empty($piutang)){
            $tambah_piutang = new DaftarPiutang;
            $tambah_piutang->nama_pembeli = $request->nama_pembeli;
            if ($request->metode == 3) {
                $tambah_piutang->total_hutang = $jumlah_uang;
            }else {
                $tambah_piutang->total_hutang = 0;
            }
            if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
                $tambah_piutang->gudang = 1;
            }else{
                $tambah_piutang->gudang = 2;
            }
            $tambah_piutang->save();
        }else{
            $tambah_piutang = DaftarPiutang::where('nama_pembeli', $request->nama_pembeli)->first();
            if ($request->metode == 3) {
                $tambah_piutang->total_hutang = $piutang->total_hutang + $jumlah_uang;
            }
            $tambah_piutang->save();
        }

        $pembayaran = new Pembayaran;
        $pembayaran->id_piutang = $tambah_piutang->id;
        $pembayaran->jumlah_uang = $request->jumlah_uang;
        $pembayaran->metode_pembayaran = $request->metode;
        $pembayaran->nama_bank = $request->nama_bank;
        if ($request->metode == 3) {
            $pembayaran->pendapatan = 3;
        }else {
            $pembayaran->pendapatan = 1;
        }
        $pembayaran->keterangan = $request->keterangan;
        $pembayaran->lain_lain = 1;
        $pembayaran->save();

        $transaksi = new Transaksi;
        $transaksi->id_piutang = $tambah_piutang->id;
        $transaksi->id_pembayaran = $pembayaran->id;
        $transaksi->total_transaksi = $request->jumlah_uang;
        $transaksi->kekurangan = $tambah_piutang->total_hutang;
        if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
            $transaksi->gudang = 1;
        }else{
            $transaksi->gudang = 2;
        }
        $transaksi->save();

        return redirect('/pendapatan-lain');
    }
}
