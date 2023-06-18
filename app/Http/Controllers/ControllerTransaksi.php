<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataBarang;
use App\Model\Transaksi;
use App\Model\TransaksiDetail;
use App\Model\DaftarPiutang;
use App\Model\Pendapatan;
use App\Model\Pembayaran;
use Auth;

class ControllerTransaksi extends Controller
{
    function index(){
        $cart = session('cart');
        if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
            $data_barang = DataBarang::where('stok_barang', '>', 0)->where('gudang', 1)->get();
        }else{
            $data_barang = DataBarang::where('stok_barang', '>', 0)->where('gudang', 2)->get();
        }
        // session()->forget('cart');
        return view('transaksi', compact('cart', 'data_barang'));
    }

    function shoppingChart(Request $request){
        $cart = session("cart");

        $barang = DataBarang::detail_barang($request->nama_barang);

        if (empty($cart)) {
            $nomor = 0;
        }else{
            $nomor = count($cart);
        }
        $cart [$nomor] = [
            "id" => $barang->id,
            "nama_barang" => $barang->nama_barang,
            "jumlah_barang" => $request->jumlah_barang,
            "harga_barang" => $request->harga_barang
        ];

        session(["cart" => $cart]);
        return redirect('/transaksi');
    }

    function shoppingChartDelete($id_barang)
    {
        $cart = session("cart");
        unset($cart[$id_barang]);
        session(["cart" => $cart]);

        return redirect('/transaksi');
    }

    function pembayaran(Request $request){
        $cart = session('cart');
        $hapus_id = [];

        $sisa = $request->total_belanja - $request->jumlah_uang;
        $piutang = DaftarPiutang::where('nama_pembeli', $request->nama_pembeli)->first();
        
        if(empty($piutang)){
            $tambah_piutang = new DaftarPiutang;
            $tambah_piutang->nama_pembeli = $request->nama_pembeli;
            $tambah_piutang->total_hutang = $sisa;
            if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
                $tambah_piutang->gudang = 1;
            }else{
                $tambah_piutang->gudang = 2;
            }
            $tambah_piutang->save();
        }else{
            $tambah_piutang = DaftarPiutang::where('nama_pembeli', $request->nama_pembeli)->first();
            $tambah_piutang->total_hutang = $piutang->total_hutang + $sisa;
            $tambah_piutang->save();
        }

        $pembayaran = new Pembayaran;
        $pembayaran->id_piutang = $tambah_piutang->id;
        $pembayaran->jumlah_uang = $request->jumlah_uang;
        $pembayaran->metode_pembayaran = $request->metode_bayar;
        $pembayaran->nama_bank = $request->nama_banK;
        if ($request->metode_bayar == 3) {
            $pembayaran->pendapatan = 3;
        }else {
            $pembayaran->pendapatan = 1;
        }
        $pembayaran->save();

        $transaksi = New Transaksi;
        $transaksi->total_transaksi = $request->total_belanja;
        $transaksi->id_piutang = $tambah_piutang->id;
        $transaksi->id_pembayaran = $pembayaran->id;

        if ($sisa > 0) {
            $transaksi->kekurangan = $sisa;
        }else{
            $transaksi->kekurangan = 0;
        }
        if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
            $transaksi->gudang = 1;
        }else{
            $transaksi->gudang = 2;
        }
        $transaksi->save();

        if (count($request->id_barang) > 1) {
            for($i = 0; $i < count($request->id_barang); $i++){
                $data[] = [
                    'id_transaksi' => $transaksi->id,
                    'id_data_barang' => $request->id_barang[$i],
                    'jumlah_barang' => $request->jumlah_barang[$i],
                    'harga_barang' => $request->harga_barang[$i],
                    'sub_total' => $request->sub_total[$i]
                ];
                $pengurangan = DataBarang::where('id', $request->id_barang[$i])->first();
                
                $hasil_pengurangan = $pengurangan->stok_barang - $request->jumlah_barang[$i];

                if($hasil_pengurangan == 0 || $hasil_pengurangan < 0){
                    $hapus_data = DataBarang::where('id', $pengurangan->id)->update([
                        'hapus' => 1,
                        'stok_barang' => 0
                    ]);
                }elseif($hasil_pengurangan > 0){
                    $ids[] = $pengurangan->id;
    
                    $data_barang = DataBarang::where('id', $pengurangan->id)->update(['stok_barang' => $hasil_pengurangan]);
                }
            }
        } else {
            $data = [
                'id_transaksi' => $transaksi->id,
                'id_data_barang' => $request->id_barang[0],
                'jumlah_barang' => $request->jumlah_barang[0],
                'harga_barang' => $request->harga_barang[0],
                'sub_total' => $request->sub_total[0]
            ];
            $pengurangan = DataBarang::where('id', $request->id_barang)->first();

            $hasil_pengurangan = $pengurangan->stok_barang - $request->jumlah_barang[0];

            if($hasil_pengurangan == 0){
                $hapus_data = DataBarang::where('id', $pengurangan->id)->update([
                    'hapus' => 1,
                    'stok_barang' => 0
                ]);
            }elseif($hasil_pengurangan > 0){
                $ids[] = $pengurangan->id;

                $data_barang = DataBarang::where('id', $pengurangan->id)->update(['stok_barang' => $hasil_pengurangan]);
            }
        }
        
        $detail = TransaksiDetail::insert($data);

        session()->forget('cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Melakukan Transaksi!.',
        ]); 
    }
}
