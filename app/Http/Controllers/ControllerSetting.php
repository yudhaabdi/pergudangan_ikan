<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ControllerSetting extends Controller
{
    public function index(Request $request){
        $session_gudang = session("gudang");
        $session_gudang = $request->gudang;
        session(["gudang" => $session_gudang]);

        return response()->json([
            'success' => true,
            'message' => 'Gudang Berhasil Diubah!.',
        ]); 
    }
}
