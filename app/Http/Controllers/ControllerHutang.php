<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Transaksi;
use App\Model\DaftarPiutang;
use App\Model\Pembayaran;
use App\Model\HutangPerusahaan;
use Auth;

class ControllerHutang extends Controller
{
    public function index()
    {
        if (Auth::User()->role == 'admin') {
            $piutang = DaftarPiutang::with(['transaksi'])->where('total_hutang', '>', 0)->where('gudang', 1)->get();
        }else{
            $piutang = DaftarPiutang::with(['transaksi'])->where('total_hutang', '>', 0)->where('gudang', 2)->get();
        }

        // $hutang = Transaksi::whereNotNull('kekurangan')->distinct('nama_pembeli')->get(['nama_pembeli',]);
        // dd($hutang);

        return view('hutang.hutang', compact('piutang'));
    }

    public function bayar(Request $request, $id)
    {
        $piutang = DaftarPiutang::find($id);
        $total = $piutang->total_hutang - $request->bayar;
        
        $piutang->total_hutang = $total;
        $piutang->update();

        $pembayaran = new Pembayaran;
        $pembayaran->id_piutang = $id;
        $pembayaran->jumlah_uang = $request->bayar;
        $pembayaran->metode_pembayaran = $request->metode;
        $pembayaran->nama_bank = $request->nama_bank;
        $pembayaran->pendapatan = 1;
        $pembayaran->keterangan = 'Pembayaran Hutang';
        $pembayaran->save();

        $transaksi = new Transaksi;
        $transaksi->id_piutang = $id;
        $transaksi->id_pembayaran = $pembayaran->id;
        $transaksi->total_transaksi = $request->bayar;
        $transaksi->kekurangan = $total;
        $transaksi->hutang = 1;
        if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
            $transaksi->gudang = 1;
        }else{
            $transaksi->gudang = 2;
        }
        $transaksi->save();

        return redirect('/hutang');
    }

    public function tambah(Request $request)
    {
        $hutang = new DaftarPiutang;
        $hutang->nama_pembeli = $request->nama_pembeli;
        $hutang->total_hutang = $request->jumlah_uang;
        if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
            $hutang->gudang = 1;
        }else{
            $hutang->gudang = 2;
        }
        $hutang->save();
        return redirect('/hutang');
    }

    public function perusahaan()
    {
        if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
            $hutang = HutangPerusahaan::with(['transaksi', 'dataBarang'])->where('hutang', '>', 0)->where('gudang', 1)->get();
        }else{
            $hutang = HutangPerusahaan::with(['transaksi', 'dataBarang'])->where('hutang', '>', 0)->where('gudang', 2)->get();
        }
        return view('hutang.perusahaan', compact('hutang'));
    }

    public function perusahaanBayar(Request $request, $id)
    {
        try {
            $piutang = HutangPerusahaan::find($id);
            $total = $piutang->hutang - $request->bayar;
            
            $piutang->hutang = $total;
            $piutang->update();

            $pembayaran = new Pembayaran;
            $pembayaran->jumlah_uang = $request->bayar;
            $pembayaran->metode_pembayaran = $request->metode;
            $pembayaran->nama_bank = $request->nama_bank;
            $pembayaran->pendapatan = 2;
            $pembayaran->keterangan = 'Pembayaran Hutang Perusahaan';
            $pembayaran->save();

            $transaksi = new Transaksi;
            $transaksi->id_pembayaran = $pembayaran->id;
            $transaksi->id_hutang_perusahaan = $id;
            $transaksi->total_transaksi = $request->bayar;
            $transaksi->kekurangan = $total;
            $transaksi->hutang_perusahaan = 1;
            if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
                $transaksi->gudang = 1;
            }else{
                $transaksi->gudang = 2;
            }
            $transaksi->save();
        } catch (\Exception $th) {
            return $e->getMessage();
        }

        return redirect('/hutang/perusahaan');
    }
}
