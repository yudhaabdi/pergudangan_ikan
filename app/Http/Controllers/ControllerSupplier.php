<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Supplier;

class ControllerSupplier extends Controller
{
    public function index() {
        $supplier = Supplier::all();
        return view('supplier.index', compact(['supplier']));
    }

    public function tambah(Request $request){
        try {
            $supplier = new Supplier;
            $supplier->nama = $request->nama;
            $supplier->no_hp = $request->no_hp;
            $supplier->alamat = $request->alamat;
            $supplier->save();

            return redirect('/supplier');
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function getData($id){
        $supplier = Supplier::find($id);

        return response()->json($supplier);
    }

    public function editData($id, Request $request){
        try {
            $supplier = Supplier::find($id);

            $supplier->nama = $request->nama;
            $supplier->no_hp = $request->no_hp;
            $supplier->alamat = $request->alamat;
            $supplier->update();

            return redirect('/supplier');
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function hapus($id){
        try {
            $supplier = Supplier::find($id);

            $supplier->delete();

            return response()->json([
                'success' => true,
                'message' => 'Supplier Berhasil Dihapus!.',
            ]); 
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}
