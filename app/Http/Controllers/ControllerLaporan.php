<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Transaksi;
use App\Model\TransaksiDetail;
use App\Model\Pembayaran;
use App\Model\DaftarPiutang;
use App\DataBarang;
use Carbon\Carbon;
use Session;
use DB;

class ControllerLaporan extends Controller
{
    function index()
    {
        return view('laporan.laporan');
    }

    public function pemasukan()
    {
        $nama = DaftarPiutang::whereHas('pembayaran', function($query){
            $query->where('pendapatan', 1);
        })->get();
        return view('laporan.laporan-pendapatan', compact(['nama']));
    }

    public function getData(Request $request)
    {
        $pembayaran = Pembayaran::with(['transaksi', 'daftarPiutang', 'transaksi.transaksi_detail', 'transaksi.transaksi_detail.dataBarang'])
        ->where('pendapatan', 1);

        $start_date = Carbon::parse($request->start)->startOfDay();
        $end_date = Carbon::parse($request->end)->endOfDay();

        if ($request->nama != 'all') {
            $pembayaran->where('id_piutang', $request->nama);
        }

        if (!empty($request->start) && !empty($request->end)) {
            $pembayaran->whereBetween('created_at', [$start_date, $end_date]);
        }

        $data = $pembayaran->get();
        
        return response()->json($data);
    }

    public function pengeluaran()
    {
        $nama = DaftarPiutang::whereHas('pembayaran', function($query){
            $query->where('pendapatan', 2);
        })->get();
        return view('laporan.laporan-pengeluaran', compact(['nama']));
    }

    public function getDataPengeluaran(Request $request)
    {
        $pembayaran = Pembayaran::with(['transaksi', 'daftarPiutang', 'dataBarang'])->where('pendapatan', 2);

        $start_date = Carbon::parse($request->start)->startOfDay();
        $end_date = Carbon::parse($request->end)->endOfDay();

        if ($request->nama != 'all' && $request->nama != 'pabrik' && $request->nama != 'penyusutan') {
            $pembayaran->where('id_piutang', $request->nama);
        }

        if ($request->nama == 'pabrik') {
            $pembayaran->where('pembayaran.id_piutang', null)
                ->join('transaksi', function($query){
                    $query->on('transaksi.id_pembayaran', '=', 'pembayaran.id');
                    $query->where('transaksi.penyusutan', null);
                });
        }

        if ($request->nama == 'penyusutan') {
            $pembayaran->where('pembayaran.id_piutang', null)
                ->join('transaksi', function($query){
                    $query->on('transaksi.id_pembayaran', '=', 'pembayaran.id');
                    $query->where('transaksi.penyusutan', 1);
                });
        }

        if (!empty($request->start) && !empty($request->end)) {
            $pembayaran->whereBetween('created_at', [$start_date, $end_date]);
        }

        $data = $pembayaran->select('pembayaran.*')->get();

        return response()->json($data);
    }

    public function hutang()
    {
        $nama = DaftarPiutang::all();
        return view('laporan.laporan-hutang', compact(['nama']));

    }

    public function getDataHutang(Request $request)
    {
        $start_date = Carbon::parse($request->start)->startOfDay();
        $end_date = Carbon::parse($request->end)->endOfDay();

        $transaksi = Transaksi::with(['transaksi_detail', 'pembayaran', 'daftarPiutang', 'transaksi_detail.dataBarang'])
            ->whereNotNull('id_piutang');

        if ($request->nama != 'all') {
            $transaksi->where('id_piutang', $request->nama);
        }

        if (!empty($request->start) && !empty($request->end)) {
            $transaksi->whereBetween('created_at', [$start_date, $end_date]);
        }
        
        $data = $transaksi->orderBy('id_piutang', 'asc')->get();

        return response()->json($data);
    }

    public function labaRugi()
    {
        return view('laporan.laporan-laba-rugi');
    }

    public function getDataLabaRugi(Request $request)
    {
        $start_date = Carbon::parse($request->start)->startOfDay();
        $end_date = Carbon::parse($request->end)->endOfDay();

        $transaksi = Transaksi::with(['transaksi_detail', 'pembayaran', 'daftarPiutang', 'transaksi_detail.dataBarang'])
            ->whereHas('pembayaran', function($query){
                $query->where('pembayaran.metode_pembayaran', '!=', '3');
            });

        if (!empty($request->start) && !empty($request->end)) {
            $transaksi->whereBetween('created_at', [$start_date, $end_date]);
        }
        
        $data = $transaksi->get();

        return response()->json($data);
    }

    public function dataBarang()
    {
        $nama = DataBarang::select('id', 'nama_barang', 'kode', 'size', 'kemasan')->get();
        return view('laporan.laporan-data-barang', compact('nama'));
    }

    public function getDataDataBarang(Request $request)
    {
        $start_date = Carbon::parse($request->start)->startOfDay();
        $end_date = Carbon::parse($request->end)->endOfDay();

        if ($request->nama != '' || isset($request->start)) {
            $transaksi = Transaksi::join('transaksi_detail', function($query1) use($start_date, $end_date){
                $query1->on('transaksi.id', '=', 'transaksi_detail.id_transaksi');
                $query1->whereBetween('transaksi_detail.created_at', [$start_date, $end_date]);
                })
                ->join('data_barang', function($query) use($request){
                    $query->on('transaksi_detail.id_data_barang', '=', 'data_barang.id');
                    $query->whereRaw('data_barang.nama_barang like ?', ["%".$request->nama."%"]);
                })->select('transaksi.*')->DISTINCT('id');

            $transaksi_detail = TransaksiDetail::
                join('data_barang', function($query) use($request){
                    $query->on('transaksi_detail.id_data_barang', '=', 'data_barang.id');
                    $query->whereRaw('data_barang.nama_barang like ?', ["%".$request->nama."%"]);
                })
                ->whereBetween('transaksi_detail.created_at', [$start_date, $end_date]);
        }
        
        $data1 = $transaksi->with(['transaksi_detail.dataBarang', 'daftarPiutang'])->orderBy('id')->get();
        $data2 = $transaksi_detail->with(['dataBarang'])->select('transaksi_detail.*')->orderBy('id_transaksi')->get();
        $data3 = $transaksi_detail->with(['dataBarang'])
        ->select('transaksi_detail.id_data_barang', DB::raw("SUM(transaksi_detail.jumlah_barang) as total_barang"))
        ->groupBy('transaksi_detail.id_data_barang')
        ->get();

        $data = [
            'transaksi' => $data1, 
            'transaksi_detail' => $data2, 
            'total_item' => $data3, 
        ];

        return response()->json($data);
    }
}
