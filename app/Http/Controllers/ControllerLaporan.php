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
use PDF;
use DB;
use Auth;

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
        })
        ->get();
        
        return view('laporan.laporan-pendapatan', compact(['nama']));
    }

    public function getData(Request $request)
    {
        $pembayaran = Pembayaran::with(['transaksi', 'daftarPiutang', 'transaksi.transaksi_detail', 'transaksi.transaksi_detail.dataBarang'])
        ->where('pembayaran.pendapatan', 1)
        ->join('transaksi', function($query){
            $query->on('transaksi.id_pembayaran', '=', 'pembayaran.id');
        })->select('pembayaran.*');            

        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->endOfDay();

        if ($request->nama_pembeli != 'all') {
            $pembayaran->where('pembayaran.id_piutang', $request->nama_pembeli);
        }

        if (!empty($request->start_date) && !empty($request->start_date)) {
            $pembayaran->whereBetween('pembayaran.created_at', [$start_date, $end_date]);
        }

        $data = $pembayaran->get();

        if (!empty($request->print)) {
            $pdf = PDF::loadView('print.laporan-pemasukan', ['data'=>$data, 'start_date'=>$start_date, 'end_date'=>$end_date, 'start'=>$request->start_date]);
            return $pdf->stream();
        }else {
            return response()->json($data);
        }
        
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
        $pembayaran = Pembayaran::with(['transaksi', 'daftarPiutang', 'transaksi.transaksi_detail', 'transaksi.transaksi_detail.dataBarang', 'dataBarang'])
        ->where('pembayaran.pendapatan', 2)
        ->join('transaksi', function($query){
            $query->on('transaksi.id_pembayaran', '=', 'pembayaran.id');
        });            
        
        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->endOfDay();

        if ($request->nama_pembeli != 'all' && $request->nama_pembeli != 'pabrik' && $request->nama_pembeli != 'penyusutan') {
            $pembayaran->where('pembayaran.id_piutang', $request->nama_pembeli);
        }

        if ($request->nama_pembeli == 'pabrik') {
            $pembayaran->where('pembayaran.id_piutang', null)
                ->where('transaksi.penyusutan', null);
        }

        if ($request->nama_pembeli == 'penyusutan') {
            $pembayaran->where('pembayaran.id_piutang', null)
                ->where('transaksi.penyusutan', 1);
        }

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $pembayaran->whereBetween('pembayaran.created_at', [$start_date, $end_date]);
        }

        $data = $pembayaran->select('pembayaran.*')->get();

        if (!empty($request->print)) {
            $pdf = PDF::loadView('print.laporan-pengeluaran', ['data'=>$data, 'start_date'=>$start_date, 'end_date'=>$end_date, 'start'=>$request->start_date]);
            return $pdf->stream();
        }else {
            return response()->json($data);
        }
    }

    public function hutang()
    {
        $nama = DaftarPiutang::all();
        
        return view('laporan.laporan-hutang', compact(['nama']));

    }

    public function getDataHutang(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->endOfDay();

        $transaksi = Transaksi::with(['transaksi_detail', 'pembayaran', 'daftarPiutang', 'transaksi_detail.dataBarang'])
            ->whereNotNull('id_piutang');

        if ($request->nama_pembeli != 'all') {
            $transaksi->where('id_piutang', $request->nama_pembeli);
        }

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $transaksi->whereBetween('created_at', [$start_date, $end_date]);
        }
        
        $data = $transaksi->orderBy('id_piutang', 'asc')->orderBy('created_at', 'asc')->get();

        if (!empty($request->print)) {
            $pdf = PDF::loadView('print.laporan-hutang', 
                ['data'=>$data, 'start_date'=>$start_date, 
                'end_date'=>$end_date, 
                'start'=>$request->start_date
            ])->setPaper('a4', 'landscape');
            return $pdf->stream();
        }else {
            return response()->json($data);
        }
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
            ->join('pembayaran', function($query){
                $query->on('transaksi.id_pembayaran', '=', 'pembayaran.id');
                $query->where('pembayaran.metode_pembayaran', '!=', '3');
            })
            ->select('transaksi.*');

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $transaksi->whereBetween('transaksi.created_at', [$start_date, $end_date]);
        }
        
        $data = $transaksi->get();

        if (!empty($request->print)) {
            $pdf = PDF::loadView('print.laporan-laba-rugi', 
                ['data'=>$data, 'start_date'=>$start_date, 
                'end_date'=>$end_date, 
                'start'=>$request->start_date
            ])->setPaper('a4', 'landscape');
            return $pdf->stream();
        }else {
            return response()->json($data);
        }
    }

    public function dataBarang()
    {
        $nama = DataBarang::select('id', 'nama_barang', 'kode', 'size', 'kemasan')->get();
        return view('laporan.laporan-data-barang', compact('nama'));
    }

    public function getDataDataBarang(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->endOfDay();

        $transaksi = Transaksi::join('transaksi_detail', function($query1) use($start_date, $end_date, $request){
            $query1->on('transaksi.id', '=', 'transaksi_detail.id_transaksi');
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $query1->whereBetween('transaksi_detail.created_at', [$start_date, $end_date]);
            }
            })
            ->select('transaksi.*')->DISTINCT('id');

        $transaksi_detail = TransaksiDetail::
            crossJoin('transaksi', function($query) use($request){
                $query->on('transaksi.id', '=', 'transaksi_detail.id_transaksi');
            });

        if ($request->nama_barang != '' || $request->nama_barang == '') {
            $transaksi->join('data_barang', function($query) use($request){
                $query->on('transaksi_detail.id_data_barang', '=', 'data_barang.id');
                $query->whereRaw('data_barang.nama_barang like ?', ["%".$request->nama_barang."%"]);
            });

            $transaksi_detail->join('data_barang', function($query) use($request){
                $query->on('transaksi_detail.id_data_barang', '=', 'data_barang.id');
                $query->whereRaw('data_barang.nama_barang like ?', ["%".$request->nama_barang."%"]);
            });
        }

        if (!empty($request->start_date) && !empty($request->start_date)) {
            $transaksi_detail->whereBetween('transaksi_detail.created_at', [$start_date, $end_date]);
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

        if (!empty($request->print)) {
            $pdf = PDF::loadView('print.laporan-data-barang', 
                ['data'=>$data, 'start_date'=>$start_date, 
                'end_date'=>$end_date, 
                'start'=>$request->start_date
            ])->setPaper('a4');
            return $pdf->stream();
        }else {
            return response()->json($data);
        }
    }

    public function stokBarang(){
        $nama = DataBarang::select('id', 'nama_barang', 'kode', 'size', 'kemasan')->get();
        return view('laporan.laporan-stok-barang', compact('nama'));
    }

    public function getDataStokBarang(Request $request){
        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->endOfDay();

        $transaksi_pemasukan = Transaksi::join('pembayaran', 'pembayaran.id', '=', 'transaksi.id_pembayaran')
            ->join('data_barang', 'data_barang.id', '=', 'pembayaran.id_data_barang')
            ->join('hutang_perusahaan', 'transaksi.id_hutang_perusahaan', '=', 'hutang_perusahaan.id')
            ->with(['transaksi_detail.dataBarang', 'daftarPiutang', 'pembayaran.dataBarang', 'hutangPerusahaan'])->orderBy('id');
        
        $transaksi_pengeluaran = TransaksiDetail::join('transaksi', 'transaksi.id', '=', 'transaksi_detail.id_transaksi')
            ->join('daftar_piutang', 'transaksi.id_piutang', '=', 'daftar_piutang.id')
            ->join('data_barang', 'data_barang.id', '=', 'transaksi_detail.id_data_barang')
            ->with(['dataBarang', 'transaksi.daftarPiutang']);

        if ($request->nama_barang != 'all') {
            $transaksi_pemasukan = $transaksi_pemasukan->join('pembayaran as bayar', function($query) use($request){
                $query->on('bayar.id', '=', 'transaksi.id_pembayaran');
                $query->where('bayar.id_data_barang', $request->nama_barang);
            });

            $transaksi_pengeluaran = $transaksi_pengeluaran->join('transaksi as beli', function($query) use($request){
                $query->on('beli.id', '=', 'transaksi_detail.id_transaksi');
                $query->where('transaksi_detail.id_data_barang', $request->nama_barang);
            });

        }

        if (!empty($request->start_date) && !empty($request->start_date)) {
            $transaksi_pemasukan = $transaksi_pemasukan->whereBetween('transaksi.created_at', [$start_date, $end_date]);

            $transaksi_pengeluaran = $transaksi_pengeluaran->join('transaksi as belis', function($query) use($start_date, $end_date){
                $query->on('belis.id', '=', 'transaksi_detail.id_transaksi');
                $query->whereBetween('belis.created_at', [$start_date, $end_date]);
            });
        }

        $pemasukan = $transaksi_pemasukan->select(
            'transaksi.id',
            'hutang_perusahaan.nama_pemilik',
            'data_barang.nama_barang', 
            'data_barang.kode', 
            'transaksi.total_transaksi',
            'transaksi.kekurangan',
            'data_barang.harga_barang as harga_beli',
            'transaksi.created_at'
             
            )->get();
        $pengeluaran = $transaksi_pengeluaran->select(
            'transaksi_detail.id', 
            'data_barang.nama_barang',
            'data_barang.kode',
            'transaksi_detail.jumlah_barang', 
            'transaksi_detail.harga_barang as harga_jual',
            'data_barang.harga_barang as harga_beli',
            'daftar_piutang.nama_pembeli',
            'transaksi.created_at'
            )->get();

        $collection = collect($pemasukan);
        $merged     = $collection->merge($pengeluaran);
        $result   = $merged->all();

        $data = [
            'transaksi' => $result,
            'data_barang' => DataBarang::all()
        ];

        if (!empty($request->print)) {
            $pdf = PDF::loadView('print.laporan-stok-barang', 
                ['data'=>$data, 'start_date'=>$start_date, 
                'end_date'=>$end_date, 
                'start'=>$request->start_date
            ])->setPaper('a4', 'landscape');
            return $pdf->stream();
        }else {
            return response()->json($data);
        }
    }
}
