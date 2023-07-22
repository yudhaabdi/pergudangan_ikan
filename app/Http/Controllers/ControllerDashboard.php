<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataBarang;
use App\User;
use App\Model\Transaksi;
use Carbon\Carbon;

class ControllerDashboard extends Controller
{
    public function index() {
        $start_date = Carbon::now()->startOfMonth();
        $end_date = Carbon::now()->endOfMonth();

        $data_barang = DataBarang::all();
        $transaksi = Transaksi::whereBetween('created_at', [$start_date, $end_date])->get();
        $user = User::all();
        $user = count($user);
        
        return view('welcome', compact('data_barang', 'transaksi', 'user'));
    }
}
