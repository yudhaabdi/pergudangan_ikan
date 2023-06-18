<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Karyawan;
use App\Model\PembayaranKaryawan;
use App\Model\Pembayaran;
use App\Model\Transaksi;
use Carbon\Carbon;
use Auth;

class ControllerKaryawan extends Controller
{
    public function index()
    {
        if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
            $karyawan = Karyawan::where('gudang', 1)->get();
        }else {
            $karyawan = Karyawan::where('gudang', 2)->get();
        }
        return view('karyawan.index', compact(['karyawan']));
    }

    public function tambah(Request $request)
    {
        $karyawan = new Karyawan;
        $karyawan->nama = $request->nama;
        $karyawan->alamat = $request->alamat;
        $karyawan->no_hp = $request->no_hp;
        $karyawan->gaji = $request->gaji;
        if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
            $karyawan->gudang = 1;
        }else {
            $karyawan->gudang = 2;
        }
        $karyawan->save();

        return redirect('/data-karyawan');
    }

    public function getData($id)
    {
        $karyawan = Karyawan::find($id);

        return response()->json($karyawan);
    }

    public function edit($id, Request $request)
    {
        $karyawan = Karyawan::find($id);
        $karyawan->nama = $request->nama;
        $karyawan->alamat = $request->alamat;
        $karyawan->no_hp = $request->no_hp;
        $karyawan->gaji = $request->gaji;
        $karyawan->save();

        return redirect('/data-karyawan');
    }

    public function hapus($id)
    {
        $karyawan = Karyawan::find($id);
        $karyawan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang Berhasil Dihapus!.',
        ]); 
    }

    public function gajiSemua()
    {
        $karyawan = Karyawan::all();

        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $cek_pembayaran = PembayaranKaryawan::whereBetween('tanggal_pembayaran', [$start, $end])->get();
        
        $total_pembayaran = 0;
        foreach ($karyawan as $key => $item) {
            foreach ($cek_pembayaran as $keys => $items) {
                if ($item->id != $cek_pembayaran[$keys]->id_karyawan) {
                    $total_pembayaran = $total_pembayaran + $item->gaji;
                    $data[] = [
                        'id_karyawan' => $item->id,
                        'id_transaksi' => 1,
                        'tanggal_pembayaran' => Carbon::now()->toDateString(),
                    ];
                    $id_karyawan[] = [
                        'id' => $item->id,
                    ];
                }
            }
        };

        $pembayaran = new Pembayaran;
        $pembayaran->jumlah_uang = $total_pembayaran;
        $pembayaran->metode_pembayaran = 1;
        $pembayaran->pendapatan = 2;
        $pembayaran->keterangan = 'pembayaran semua gaji pegawai';
        $pembayaran->save();

        $transaksi = new Transaksi;
        $transaksi->id_pembayaran = $pembayaran->id;
        $transaksi->total_transaksi = $total_pembayaran;
        if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
            $transaksi->gudang = 1;
        }else{
            $transaksi->gudang = 2;
        }
        $transaksi->save();

        $pembayaran_karyawan = PembayaranKaryawan::insert($data);

        $ubah_data = PembayaranKaryawan::whereBetween('tanggal_pembayaran', [$start, $end])
            ->whereIn('id_karyawan', $id_karyawan)
            ->update(['id_transaksi' => $transaksi->id]);

        return response()->json([
            'success' => true,
            'message' => 'Semua Pegawai Berhasil Digaji',
        ]); 
    }

    public function gaji($id)
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $cek_pembayaran = PembayaranKaryawan::where('id_karyawan', $id)->whereBetween('tanggal_pembayaran', [$start, $end])->get();
        if (count($cek_pembayaran) > 0) {
            return response()->json([
                'info' => true,
                'success' => true,
                'message' => 'Pegawai Sudah Bayar',
            ]); 
        }else{
            $karyawan = Karyawan::find($id);

            $pembayaran = new Pembayaran;
            $pembayaran->jumlah_uang = $karyawan->gaji;
            $pembayaran->metode_pembayaran = 1;
            $pembayaran->pendapatan = 2;
            $pembayaran->keterangan = 'pembayaran gaji '.$karyawan->nama;
            $pembayaran->save();
    
            $transaksi = new Transaksi;
            $transaksi->id_pembayaran = $pembayaran->id;
            $transaksi->total_transaksi = $karyawan->gaji;
            if (Auth::User()->role == 'admin 1' || Auth::User()->role == 'kasir 1') {
                $transaksi->gudang = 1;
            }else{
                $transaksi->gudang = 2;
            }
            $transaksi->save();
    
            $tanggal = Carbon::now()->toDateString();
    
            $pembayaran_karyawan = new PembayaranKaryawan;
            $pembayaran_karyawan->id_karyawan = $karyawan->id;
            $pembayaran_karyawan->id_transaksi = $transaksi->id;
            $pembayaran_karyawan->tanggal_pembayaran = $tanggal;
            $pembayaran_karyawan->save();
    
            return response()->json([
                'info' => false,
                'success' => true,
                'message' => $karyawan->nama.' Berhasil Digaji',
            ]); 
        }
    }
}
