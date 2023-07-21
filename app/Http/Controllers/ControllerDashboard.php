<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataBarang;
use App\User;
use App\Model\Transaksi;
use Carbon\Carbon;
use Auth;

class ControllerDashboard extends Controller
{
    public function index() {
        if (session("gudang") == null) {
            if (Auth::User()->role == 'kasir 1') {
                $session_gudang = session("gudang");
                $session_gudang = 1;
                session(["gudang" => $session_gudang]);
            }elseif(Auth::User()->role == 'kasir 2'){
                $session_gudang = session("gudang");
                $session_gudang = 2;
                session(["gudang" => $session_gudang]);
            }else{
                $session_gudang = session("gudang");
                $session_gudang = 1;
                session(["gudang" => $session_gudang]);
            }
        }

        $start_date = Carbon::now()->startOfMonth();
        $end_date = Carbon::now()->endOfMonth();

        if (Auth::User()->role == 'admin') {
            $data_barang = DataBarang::all();
            $transaksi = Transaksi::whereBetween('created_at', [$start_date, $end_date])->get();
            $user = User::all();
            $user = count($user);
        }else{
            if (Auth::User()->role == 'kasir 2') {
                $data_barang = DataBarang::where('gudang', 2)->get();
                $transaksi = Transaksi::whereBetween('created_at', [$start_date, $end_date])->where('gudang', 2)->get();
                $user = User::all();  
                $user = count($user);
            } else {
                $data_barang = DataBarang::where('gudang', 1)->get();
                $transaksi = Transaksi::whereBetween('created_at', [$start_date, $end_date])->where('gudang', 1)->get();
                $user = User::all();  
                $user = count($user);
            }
           
        }
        return view('welcome', compact('data_barang', 'transaksi', 'user', 'session_gudang'));
    }
}
