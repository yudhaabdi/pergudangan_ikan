<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class ControllerTambahAkun extends Controller
{
    public function index(){
        $user = User::all();
        return view('akun.index', compact('user'));
    }

    public function tambah(Request $request) {
        try {
            $user = new User;
            $user->name = $request->nama;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            $user->save();

            return redirect('/pengaturan-akun');
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getData($id) {
        try {
            $user = User::find($id);

            return response()->json($user);
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editData($id, Request $request) {
        try {
            $user = User::find($id);
            $user->name = $request->nama;
            $user->email = $request->email;
            if ($request->password != null) {
                $user->password = Hash::make($request->password);
            }
            $user->role = $request->role;
            $user->save();

            return redirect('/pengaturan-akun');
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function hapus($id) {
        try {
            $user = User::find($id);
            
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Akun Berhasil Dihapus!.',
            ]); 
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
