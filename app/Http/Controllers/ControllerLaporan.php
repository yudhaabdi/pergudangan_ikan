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
        if (Auth::User()->role == 'admin') {
            $nama = DaftarPiutang::whereHas('pembayaran', function($query){
                $query->where('pendapatan', 1);
            })
            ->get();
        }else{
            if (Auth::User()->role == 'kasir 1') {
                $nama = DaftarPiutang::whereHas('pembayaran', function($query){
                    $query->where('pendapatan', 1);
                })->where('gudang', 1)
                ->get();
            } else {
                $nama = DaftarPiutang::whereHas('pembayaran', function($query){
                    $query->where('pendapatan', 1);
                })->where('gudang', 2)
                ->get();
            }
        }
        return view('laporan.laporan-pendapatan', compact(['nama']));
    }

    public function getData(Request $request)
    {
        if (Auth::User()->role == 'admin') {
            $pembayaran = Pembayaran::with(['transaksi', 'daftarPiutang', 'transaksi.transaksi_detail', 'transaksi.transaksi_detail.dataBarang'])
            ->where('pembayaran.pendapatan', 1)
            ->join('transaksi', function($query) use($request){
                $query->on('transaksi.id_pembayaran', '=', 'pembayaran.id');
                $query->where('transaksi.gudang', $request->gudang);
            })->select('pembayaran.*');            
        }else{
            $pembayaran = Pembayaran::with(['transaksi', 'daftarPiutang', 'transaksi.transaksi_detail', 'transaksi.transaksi_detail.dataBarang'])
            ->where('pembayaran.pendapatan', 1)
            ->join('transaksi', function($query){
                $query->on('transaksi.id_pembayaran', '=', 'pembayaran.id');
                if (Auth::User()->role == 'kasir 1') {
                    $query->where('transaksi.gudang', 1);
                } else {
                    $query->where('transaksi.gudang', 2);
                }
                
            })->select('pembayaran.*');            
        }

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
        if (Auth::User()->role == 'admin') {
            $nama = DaftarPiutang::whereHas('pembayaran', function($query){
                $query->where('pendapatan', 2);
            })->get();
        }else{
            if (Auth::User()->role == 'kasir 1') {
                $nama = DaftarPiutang::whereHas('pembayaran', function($query){
                    $query->where('pendapatan', 2);
                })->where('gudang', 1)
                ->get();
            } else {
                $nama = DaftarPiutang::whereHas('pembayaran', function($query){
                    $query->where('pendapatan', 2);
                })->where('gudang', 2)
                ->get();
            }
        }
        return view('laporan.laporan-pengeluaran', compact(['nama']));
    }

    public function getDataPengeluaran(Request $request)
    {
        if (Auth::User()->role == 'admin') {
            $pembayaran = Pembayaran::with(['transaksi', 'daftarPiutang', 'transaksi.transaksi_detail', 'transaksi.transaksi_detail.dataBarang', 'dataBarang'])
            ->where('pembayaran.pendapatan', 2)
            ->join('transaksi', function($query) use($request){
                $query->on('transaksi.id_pembayaran', '=', 'pembayaran.id');
                $query->where('transaksi.gudang', $request->gudang);
            });            
        }else{
            $pembayaran = Pembayaran::with(['transaksi', 'daftarPiutang', 'transaksi.transaksi_detail', 'transaksi.transaksi_detail.dataBarang', 'dataBarang'])
            ->where('pembayaran.pendapatan', 2)
            ->join('transaksi', function($query){
                $query->on('transaksi.id_pembayaran', '=', 'pembayaran.id');
                if (Auth::User()->role == 'kasir 1') {
                    $query->where('transaksi.gudang', 1);
                } else {
                    $query->where('transaksi.gudang', 2);
                }
            });            
        }

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
        if (Auth::User()->role == 'admin') {
            $nama = DaftarPiutang::all();
        }else {
            if (Auth::User()->role == 'kasir 1') {
                $nama = DaftarPiutang::where('gudang', 1)->get();
            } else {
                $nama = DaftarPiutang::where('gudang', 2)->get();
            }
        }
        return view('laporan.laporan-hutang', compact(['nama']));

    }

    public function getDataHutang(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->endOfDay();

        if (Auth::User()->role == 'admin') {
            $transaksi = Transaksi::with(['transaksi_detail', 'pembayaran', 'daftarPiutang', 'transaksi_detail.dataBarang'])
                ->whereNotNull('id_piutang')->where('gudang', $request->gudang);
        }else{
            $transaksi = Transaksi::with(['transaksi_detail', 'pembayaran', 'daftarPiutang', 'transaksi_detail.dataBarang'])
                ->whereNotNull('id_piutang')->where('gudang', $request->gudang);
        }

        if ($request->nama_pembeli != 'all') {
            $transaksi->where('id_piutang', $request->nama_pembeli);
        }

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $transaksi->whereBetween('created_at', [$start_date, $end_date]);
        }
        
        $data = $transaksi->orderBy('id_piutang', 'asc')->get();

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
            })->where('transaksi.gudang', $request->gudang)
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

        if (Auth::User()->role == 'admin') {
            $transaksi = Transaksi::join('transaksi_detail', function($query1) use($start_date, $end_date, $request){
                $query1->on('transaksi.id', '=', 'transaksi_detail.id_transaksi');
                if (!empty($request->start_date) && !empty($request->end_date)) {
                    $query1->whereBetween('transaksi_detail.created_at', [$start_date, $end_date]);
                }
                })
                ->where('transaksi.gudang', $request->gudang)->select('transaksi.*')->DISTINCT('id');

            $transaksi_detail = TransaksiDetail::
                crossJoin('transaksi', function($query) use($request){
                    $query->on('transaksi.id', '=', 'transaksi_detail.id_transaksi');
                    $query->where('transaksi.gudang', $request->gudang);
                });
        }else{
            $transaksi = Transaksi::join('transaksi_detail', function($query1) use($start_date, $end_date){
                $query1->on('transaksi.id', '=', 'transaksi_detail.id_transaksi');
                if (!empty($request->start_date) && !empty($request->end_date)) {
                    $query1->whereBetween('transaksi_detail.created_at', [$start_date, $end_date]);
                }
                })
                ->where('transaksi.gudang', $request->gudang)->select('transaksi.*')->DISTINCT('id');

            $transaksi_detail = TransaksiDetail::
                crossJoin('transaksi', function($query) use($request){
                    $query->on('transaksi.id', '=', 'transaksi_detail.id_transaksi');
                    $query->where('transaksi.gudang', $request->gudang);
                });
        }

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
}
