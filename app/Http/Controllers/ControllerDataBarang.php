<?php

namespace App\Http\Controllers;
use App\DataBarang;
use App\Model\Pembayaran;
use App\Model\Transaksi;
use App\Model\HutangPerusahaan;
use DataTables;
use Auth;

use Illuminate\Http\Request;

class ControllerDataBarang extends Controller
{
    public function index(){

        if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
            $data_barang = DataBarang::where('stok_barang', '>', 0)->where('hapus', 0)->where('gudang', 1)->get();
        }else {
            $data_barang = DataBarang::where('stok_barang', '>', 0)->where('hapus', 0)->where('gudang', 2)->get();
        }
      
        return view('data-barang', ['data_barang' => $data_barang]);
    }

    public function tambah(Request $request)
    {
        try {
            $data_barang = new DataBarang;
            $data_barang->nama_barang = $request->nama_barang;
            $data_barang->size = $request->size;
            $data_barang->kemasan = $request->kemasan;
            $data_barang->stok_barang = $request->jumlah_barang;
            $data_barang->kode = $request->kode_barang;
            $data_barang->no_kontener = $request->no_kontener;
            $data_barang->harga_barang = $request->harga_barang;
            $data_barang->hapus = 0;
            if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
                $data_barang->gudang = 1;
            }else {
                $data_barang->gudang = 2;
            }
            $data_barang->save();

            $total_transaksi = $request->jumlah_barang * $request->harga_barang;

            $pembayaran = New Pembayaran;
            $pembayaran->id_data_barang = $data_barang->id;
            $pembayaran->jumlah_uang = $request->jumlah_uang;
            $pembayaran->metode_pembayaran = $request->metode_bayar;
            $pembayaran->nama_bank = $request->nama_bank;
            $pembayaran->pendapatan = 2;
            $pembayaran->keterangan = 'Pembelian barang '.$request->nama_barang;
            $pembayaran->save();

            $hutang = new HutangPerusahaan;
            $hutang->nama_pemilik = $request->pemilik_barang;
            $hutang->id_barang = $data_barang->id;
            $hutang->hutang = $total_transaksi - $request->jumlah_uang;
            if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
                $hutang->gudang = 1;
            }else {
                $hutang->gudang = 2;
            }
            $hutang->save();

            $transaksi = new Transaksi;
            $transaksi->id_pembayaran = $pembayaran->id;
            $transaksi->id_hutang_perusahaan = $hutang->id;
            $transaksi->total_transaksi = $total_transaksi;
            $transaksi->kekurangan = $total_transaksi - $request->jumlah_uang;
            $transaksi->tunggakan = 1;
            $transaksi->qty = $request->jumlah_barang;
            if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
                $transaksi->gudang = 1;
            }else{
                $transaksi->gudang = 2;
            }
            $transaksi->save();
            
        } catch (\Exception $e) {
        
            return $e->getMessage();
        }
        
        return redirect('/data-barang');
    }

    public function edit($id)
    {
        $data_barang = DataBarang::find($id);
        if ($data_barang->lama == 1) {
            $transaksi = $data_barang;
        }else{
            $transaksi = Transaksi::with('pembayaran')
                ->join('pembayaran', function($join){
                    $join->on('pembayaran.id', '=', 'transaksi.id_pembayaran');
                    $join->join('data_barang', 'pembayaran.id_data_barang', '=', 'data_barang.id');
                })
                ->join('hutang_perusahaan', 'transaksi.id_hutang_perusahaan', '=', 'hutang_perusahaan.id')
                ->where('pembayaran.id_data_barang', $id)
                ->select('hutang_perusahaan.nama_pemilik', 'data_barang.*', 'pembayaran.jumlah_uang', 'pembayaran.metode_pembayaran', 'pembayaran.nama_bank')
                ->first();
        }

        return response()->json(['data_barang' => $transaksi]);
    }

    public function dataEdit($id, Request $request)
    {
        try {
            $data_barang = DataBarang::find($id);
            $data_barang->nama_barang = $request->nama_barang;
            $data_barang->size = $request->size;
            $data_barang->kemasan = $request->kemasan;
            $data_barang->stok_barang = $request->jumlah_barang;
            $data_barang->kode = $request->kode_barang;
            $data_barang->no_kontener = $request->no_kontener;
            $data_barang->harga_barang = $request->harga_barang;
            if ($data_barang->lama == 1) {
                $data_barang->save();
            } else {
                $data_barang->save();

                $total_transaksi = $request->jumlah_barang * $request->harga_barang;

                $pembayaran = Pembayaran::where('id_data_barang', $id)->first();
                
                $pembayaran->jumlah_uang = $request->jumlah_uang;
                $pembayaran->metode_pembayaran = $request->metode_bayar;
                $pembayaran->nama_bank = $request->nama_bank;
                $pembayaran->save();

                $hutang = HutangPerusahaan::where('id_barang', $data_barang->id)->first();

                $hutang->nama_pemilik = $request->pemilik_barang;
                $hutang->hutang = $total_transaksi - $request->jumlah_uang;
                $hutang->save();

                $transaksi = Transaksi::where('id_pembayaran', $pembayaran->id)->first();

                $transaksi->total_transaksi = $total_transaksi;
                $transaksi->kekurangan = $total_transaksi - $request->jumlah_uang;
                $transaksi->qty = $request->jumlah_barang;
                $transaksi->save();
            }
            
        } catch (\Exception $th) {
            return $e->getMessage();
        }

        return redirect('/data-barang');
    }

    public function hapus($id)
    {
        try {

            $data_barang = DataBarang::find($id);
        
            $pembayaran = Pembayaran::where('id_data_barang', $id)->first();
            
            $hutang = HutangPerusahaan::where('id_barang', $data_barang->id)->first();
            
            $transaksi = Transaksi::where('id_pembayaran', $pembayaran->id)->first();

            $data_barang->delete();
            $pembayaran->delete();
            $hutang->delete();
            $transaksi->delete();
        
        } catch (\Exception $e) {
        
            return $e->getMessage();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Barang Berhasil Dihapus!.',
        ]); 
    }

    public function tambahBarangLama(Request $request)
    {
        try {
            $data_barang = new DataBarang;
            $data_barang -> nama_barang = $request->nama_barang;
            $data_barang -> size = $request->size;
            $data_barang -> kemasan = $request->kemasan;
            $data_barang -> stok_barang = $request->jumlah_barang;
            $data_barang -> harga_barang = $request->harga_barang;
            $data_barang -> kode = $request->kode_barang;
            $data_barang -> no_kontener = $request->no_kontener;
            $data_barang -> hapus = 0;
            $data_barang -> lama = 1;
            if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
                $data_barang->gudang = 1;
            }else{
                $data_barang->gudang = 2;
            }
            $data_barang->save();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return redirect('/data-barang');
    }

    public function penyusutan($id)
    {
        $data_barang = DataBarang::find($id);

        $total_uang = $data_barang->stok_barang * $data_barang->harga_barang;
        
        $pembayaran = new Pembayaran;
        $pembayaran->id_data_barang = $data_barang->id;
        $pembayaran->jumlah_uang = $total_uang;
        $pembayaran->pendapatan = 2;
        $pembayaran->keterangan = 'Penyusutan barang '.$data_barang->nama_barang;
        $pembayaran->save();

        $transaksi = new Transaksi;
        $transaksi->id_pembayaran = $pembayaran->id;
        $transaksi->total_transaksi = $total_uang;
        $transaksi->penyusutan = 1;
        if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
            $transaksi->gudang = 1;
        }else{
            $transaksi->gudang = 2;
        }
        $transaksi->save();

        $data_barang->stok_barang = 0;
        $data_barang->save();

        return response()->json([
            'success' => true,
            'message' => 'Barang Berhasil Diubah!.',
        ]); 
    }
}
