<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $peminjaman = Peminjaman::with('users', 'barang')->orderBy('tgl_peminjaman', 'asc')->get();

        if(!$peminjaman){
            return response()->json([
                'status' => false,
                'message' => 'Peminjaman tidak ditemukan',
                'data' => $peminjaman->errors()
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Peminjaman berhasil ditemukan',
            'data' => $peminjaman
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $rules = [
        'id_user' => 'required',
        'id_barang' => 'required',
        'jumlah' => 'required' // corrected here
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Proses Validasi Gagal',
            'data' => $validator->errors()
        ]);
    }

    $date = Carbon::now('Asia/Jakarta')->format('d/m/Y');

    // Find the item and check stock
    $barang = Barang::find($request->id_barang);
    if ($barang) {
        $newStock = $barang->stok - $request->jumlah;
        if ($newStock < 0) {
            return response()->json([
                'status' => false,
                'message' => 'Stok barang tidak mencukupi',
            ]);
        }

        // Create peminjaman record
        $peminjaman = Peminjaman::create([
            'id_user' => $request->id_user,
            'id_barang' => $request->id_barang,
            'jumlah' => $request->jumlah,
            'tgl_peminjaman' => $date,
            'tgl_pengembalian' => null,
            'gambar' => null,
            'status' => 'Belum Dikembalikan',
        ]);

        // Update the stock
        $barang->update(['stok' => $newStock]);
    } else {
        return response()->json([
            'status' => false,
            'message' => 'Barang tidak ditemukan',
        ]);
    }

    return response()->json([
        'status' => true,
        'message' => 'Peminjaman berhasil disimpan',
        'data' => $peminjaman
    ]);
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with('users', 'barang')->find($id);

        if(!$peminjaman){
            return response()->json([
                'status' => false,
                'message' => 'Peminjaman tidak ditemukan',
                'data' => null
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Peminjaman Ditemukan',
            'data' => $peminjaman
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'status' => false,
                'message' => 'Peminjaman Tidak Ditemukan',
                'data' => null
            ]);
        }

        $rules = [
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) { // Use fails() instead of !$validator to properly check for validation errors
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'data' => $validator->errors()
            ]);
        }

        $imagePath = $peminjaman->gambar; // Keep the old image path if no new image is uploaded
        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('gambar-peminjaman', 'public');
        }

        $date = Carbon::now('Asia/Jakarta')->format('d/m/Y');

        $data = [
            'tgl_pengembalian' => $date,
            'gambar' => $imagePath,
            'status' => 'Sudah Dikembalikan'
        ];

        $peminjaman->update($data);

        $barang = Barang::find($peminjaman->id_barang);
        if ($barang) {
            $updateStok = $barang->stok + $peminjaman->jumlah;
            $barang->update(['stok' => $updateStok]); // Corrected syntax
        }

        return response()->json([
            'status' => true,
            'message' => 'Peminjaman Berhasil Diupdate',
            'data' => $peminjaman
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        //
    }

    public function laporanHTML(Request $request)
    {
        $request->validate([
            'mulai' => 'required|date',
            'sampai' => 'required|date|after_or_equal:mulai',
        ]);

        $mulai = Carbon::createFromFormat('Y-m-d', $request->mulai)->format('d/m/Y');
        $sampai = Carbon::createFromFormat('Y-m-d', $request->sampai)->format('d/m/Y');

        $peminjaman = Peminjaman::with('users')->whereBetween('tgl_peminjaman', [$mulai, $sampai])->get();

        return response()->json([
            'success' => true,
            'data' => $peminjaman
        ]);
    }
}
