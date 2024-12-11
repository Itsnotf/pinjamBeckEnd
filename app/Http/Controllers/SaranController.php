<?php

namespace App\Http\Controllers;

use App\Models\Saran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $saran = Saran::with('users')->get();

        if(!$saran){
            return response()->json([
                'status' => false,
                'message' => 'Saran Masih Kosong'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data Saran',
            'data' => $saran
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'deskripsi' => 'required',
            'id_user' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if(!$validator){
            return response()->json([
                'status' => false,
                'message' => 'Proses Validasi Gagal',
                'data' => $validator->errors()
            ]);
        }

        $date = Carbon::now('Asia/Jakarta')->format('d/m/Y');

        $data = [
            'deskripsi' => $request->deskripsi,
            'id_user' => $request->id_user,
            'date' => $date
        ];

        $saran = Saran::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Saran berhasil di buat',
            'data' => $saran
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $saran = Saran::with('users')->find($id);

        if (!$saran) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $saran
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Saran $saran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Saran $saran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $saran = Saran::find($id);
        if(!$saran){
            return response()->json([
                'status' => false,
                'message' => 'Saran tidak ditemukan',
                'data' => $saran->errors()
            ]);
        }
        $saran->delete();

        return response()->json([
            'status' => true,
            'message' => 'Saran berhasil dihapus',
        ]);
    }
}
