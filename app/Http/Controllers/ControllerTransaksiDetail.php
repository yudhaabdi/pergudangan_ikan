<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Transaksi;
use App\Model\TransaksiDetail;
use App\Model\DaftarPiutang;
use App\Model\Pendapatan;
use App\Model\Pembayaran;
use App\DataBarang;
use Exception;
use PDF;

class ControllerTransaksiDetail extends Controller
{
    public function index()
    {
        $cart_return = session("cart_return");

        $transaksi = Transaksi::with(['daftarPiutang', 'pembayaran'])->whereNotNull('id_piutang')->where('hutang', null)->whereHas('pembayaran', function($query){
            $query->where('lain_lain', null);
        })->get();

        session()->forget('cart_return');
        session()->forget('cart_delete');
        return view('transaksi.transaksi-detail', compact('cart_return', 'transaksi'));
    }

    public function detail($id)
    {
        $cart_return = session("cart_return");

        $transaksi = Transaksi::find($id);
        $transaksi_detail = TransaksiDetail::where('id_transaksi', $id)->with('dataBarang', 'transaksi')->get();
        return view('transaksi.transaksi-detail-view', compact('cart_return', 'transaksi_detail', 'transaksi'));
    }

    public function return($id)
    {
        $cart_return = session('cart_return');
        $cart_delete = session('cart_delete');

        $transaksi = TransaksiDetail::where('id_transaksi', $id)->with('transaksi.daftarPiutang', 'dataBarang', 'transaksi.pembayaran')->get();
        if ($cart_delete == false) {
            for($i=0; $i < count($transaksi); $i++){
                $cart_return [ $transaksi[$i]->id_data_barang ] = [
                    "id" => $transaksi[$i]->id_data_barang,
                    "nama_barang" => $transaksi[$i]->dataBarang->nama_barang,
                    "jumlah_barang" => $transaksi[$i]->jumlah_barang,
                    "harga_barang" => $transaksi[$i]->harga_barang
                ];
            }
        }

        $data_barang = DataBarang::where('stok_barang', '>', 0)->get();
        session(["cart_return" => $cart_return]);
        return view('transaksi.transaksi-detail-return', compact('cart_return', 'transaksi', 'data_barang'));
    }

    public function shoppingChart($id, Request $request)
    {
        $cart_return = session("cart_return");

        $barang = DataBarang::detail_barang($request->nama_barang);

        $cart_return [$request->nama_barang] = [
            "id" => $barang->id,
            "nama_barang" => $barang->nama_barang,
            "jumlah_barang" => $request->jumlah_barang,
            "harga_barang" => $request->harga_barang
        ];

        session(["cart_return" => $cart_return]);
        return redirect('/transaksi-detail-return/'.$id);
    }

    public function shoppingChartDetele($id, $id_barang)
    {
        $cart_delete = session("cart_delete");
        $cart_return = session("cart_return");

        $cart_delete = true;

        unset($cart_return[$id_barang]);
        session(["cart_return" => $cart_return]);
        session(["cart_delete" => $cart_delete]);

        return redirect('/transaksi-detail-return/'.$id);
    }

    public function bayar($id_transaksi, Request $request)
    {
        $cart_return = session('cart_return');
        $hapus_id = [];

        $sisa = $request->total_belanja - $request->jumlah_uang;
        $piutang = DaftarPiutang::where('nama_pembeli', $request->nama_pembeli)->first();
        
        if(empty($piutang)){
            $tambah_piutang = new DaftarPiutang;
            $tambah_piutang->nama_pembeli = $request->nama_pembeli;
            $tambah_piutang->total_hutang = $sisa;
            $tambah_piutang->save();
        }else{
            $tambah_piutang = DaftarPiutang::where('nama_pembeli', $request->nama_pembeli)->first();
            $tambah_piutang->total_hutang = $sisa;
            $tambah_piutang->save();
        }
        
        $transaksi = Transaksi::find($id_transaksi);
        $transaksi->total_transaksi = $request->total_belanja;
        $transaksi->id_piutang = $tambah_piutang->id;
        
        $pembayaran = Pembayaran::find($transaksi->id_pembayaran);
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

        if ($sisa > 0) {
            $transaksi->kekurangan = $sisa;
        }else{
            $transaksi->kekurangan = 0;
        }
        $transaksi->save();

        if (count($request->id_barang) > 1) {
            for($i = 0; $i < count($request->id_barang); $i++){
                $data[] = [
                    'id_transaksi' => $transaksi->id,
                    'id_data_barang' => $request->id_barang[$i],
                    'jumlah_barang' => $request->jumlah_barang[$i],
                    'harga_barang' => $request->harga_barang[$i],
                    'sub_total' => $request->sub_total[$i],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $barang_lama = TransaksiDetail::where('id_data_barang', $request->id_barang)->where('id_transaksi', $id_transaksi)->first();
                $data_barang = DataBarang::where('id', $request->id_barang[$i])->first();
                
                if(empty($barang_lama)){
                    $hasil_pengurangan = $data_barang->stok_barang - $request->jumlah_barang[$i];
                }else{
                    $hasil_pengurangan = $barang_lama->jumlah_barang + $data_barang->stok_barang - $request->jumlah_barang[$i];
                }

                if($hasil_pengurangan == 0){
                    $hapus_data = DataBarang::where('id', $data_barang->id)->update([
                        'hapus' => 1,
                        'stok_barang' => 0
                    ]);
                }elseif($hasil_pengurangan > 0){
                    $ids[] = $data_barang->id;
    
                    $data_barang = DataBarang::where('id', $data_barang->id)->update(['stok_barang' => $hasil_pengurangan]);
                }
            }
        } else {
            $data = [
                'id_transaksi' => $transaksi->id,
                'id_data_barang' => $request->id_barang[0],
                'jumlah_barang' => $request->jumlah_barang[0],
                'harga_barang' => $request->harga_barang[0],
                'sub_total' => $request->sub_total[0],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $barang_lama = TransaksiDetail::where('id_data_barang', $request->id_barang)->where('id_transaksi', $id_transaksi)->first();
            $data_barang = DataBarang::where('id', $request->id_barang)->first();

            if(empty($barang_lama)){
                $hasil_pengurangan = $data_barang->stok_barang - $request->jumlah_barang[0];
            }else{
                $hasil_pengurangan = $barang_lama->jumlah_barang + $data_barang->stok_barang - $request->jumlah_barang[0];
            }

            if($hasil_pengurangan == 0){
                $hapus_data = DataBarang::where('id', $data_barang->id)->update([
                    'hapus' => 1,
                    'stok_barang' => 0
                ]);
            }elseif($hasil_pengurangan > 0){
                $ids[] = $data_barang->id;

                $data_barang = DataBarang::where('id', $data_barang->id)->update(['stok_barang' => $hasil_pengurangan]);
            }
        }
        
        
        $detail_delete = TransaksiDetail::where('id_transaksi', $id_transaksi)->delete();
        $detail = TransaksiDetail::insert($data);

    //    $print = TransaksiDetail::where('id_transaksi', $id_transaksi)->with('transaksi.daftarPiutang', 'dataBarang')->get();

        session()->forget('cart_return');

        // $pdf = PDF::loadView('print.nota', ['transaksi'=>$print]);
        // return $pdf->stream();
        
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Melakukan Transaksi!.',
        ]); 
    }

    public function print($id_transaksi)
    {
        $transaksi_detail = TransaksiDetail::where('id_transaksi', $id_transaksi)->with('transaksi.daftarPiutang', 'dataBarang')->get();
        // return view('print.nota', compact('transaksi_detail'));

        $pdf = PDF::loadView('print.nota', ['transaksi_detail'=>$transaksi_detail]);

        return $pdf->stream();
    }

    public function hapus($id)
    {
        $transaksi = Transaksi::find($id);
        $transaksi->delete();
        
        $pembayaran = Pembayaran::find($transaksi->id_pembayaran);
        $pembayaran->delete();

        $detail = TransaksiDetail::where('id_transaksi', $id)->get();
        for ($a=0; $a < count($detail); $a++) { 
            $ids[] = [
                'id' => $detail[$a]->id_data_barang,
            ];
        }

        $data_barang = DataBarang::whereIn('id', $ids)->get();

        for ($i=0; $i < count($detail); $i++) { 
            $stok = $data_barang[$i]->stok_barang + $detail[$i]->jumlah_barang;
            $barang = DataBarang::where('id', $detail[$i]->id_data_barang)->update(['stok_barang' => $stok]);
        }
        
        $piutang = DaftarPiutang::where('id', $transaksi->id_piutang)->first();
        // $piutang->total_hutang = $piutang->total_hutang - $transaksi->total_transaksi;
        $piutang->save();
        
        $detail = TransaksiDetail::where('id_transaksi', $id)->delete();
        
        return redirect('/transaksi-detail');
    }
}
