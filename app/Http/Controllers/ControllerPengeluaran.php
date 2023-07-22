<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Pembayaran;
use App\Model\Transaksi;
use App\Model\DaftarPiutang;
use Auth;

class ControllerPengeluaran extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with('transaksi', 'transaksi.daftarPiutang')->where('pembayaran.lain_lain', 2)
        ->join('transaksi', function($query){
            $query->on('transaksi.id_pembayaran', '=', 'pembayaran.id');
        })->select('pembayaran.*')
        ->get();
        
        return view('pengeluaran.index', compact(['pembayaran']));
    }

    public function tambah(Request $request)
    {
        try {
            $total_hutang = 0;
            if ($request->pengeluaran == 2) {
                $piutang = DaftarPiutang::where('nama_pembeli', $request->nama_pembeli)->first();
                
                if(empty($piutang)){
                    $tambah_piutang = new DaftarPiutang;
                    $tambah_piutang->nama_pembeli = $request->nama_pembeli;
                    $tambah_piutang->total_hutang = $request->jumlah_uang;
                    $tambah_piutang->save();
                    $total_hutang = $request->jumlah_uang;
                }else{
                    $tambah_piutang = DaftarPiutang::where('nama_pembeli', $request->nama_pembeli)->first();
                    $tambah_piutang->total_hutang = $piutang->total_hutang + $request->jumlah_uang;
                    $tambah_piutang->save();
                    $total_hutang = $piutang->total_hutang + $request->jumlah_uang;
                }
            }
    
            $pembayaran = new Pembayaran;
            if ($request->pengeluaran == 2) {
                $pembayaran->id_piutang = $tambah_piutang->id;
            }
            $pembayaran->jumlah_uang = $request->jumlah_uang;
            $pembayaran->metode_pembayaran = $request->metode;
            $pembayaran->nama_bank = $request->nama_bank;
            if ($request->metode == 3) {
                $pembayaran->pendapatan = 3;
            }else {
                $pembayaran->pendapatan = 2;
            }
            $pembayaran->keterangan = $request->keterangan;
            $pembayaran->lain_lain = 2;
            $pembayaran->save();
    
            $transaksi = new Transaksi;
            if ($request->pengeluaran == 2) {
                $transaksi->id_piutang = $tambah_piutang->id;
            }
            $transaksi->kekurangan = $total_hutang;
            $transaksi->id_pembayaran = $pembayaran->id;
            $transaksi->total_transaksi = $request->jumlah_uang;
            $transaksi->save();
    
            return redirect('/pengeluaran-lain');   
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
