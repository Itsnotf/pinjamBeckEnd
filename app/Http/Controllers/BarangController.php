<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            $data = Barang::get();

            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil diambil',
                'data' => $data
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
            'nama_barang' => 'required',
            'merk' => 'required',
            'lokasi' => 'required',
            'stok' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data barang gagal diinput',
                'data' => $validator->errors()
            ]);
        }

        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('gambar-barang', 'public');
        }

        $barang = Barang::create([
            'nama_barang' => $request->nama_barang,
            'merk' => $request->merk,
            'lokasi' => $request->lokasi,
            'stok' => $request->stok,
            'gambar' => $imagePath
        ]);


        return response()->json([
            'status' => true,
            'message' => 'Data barang berhasil diinput',
            'data' => $barang
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => false,
                'message' => 'data barang tidak ditemukan',
                'data' => $barang->errors()
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data barang berhasil ditampilkan',
            'data' => $barang
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => false,
                'message' => 'Data barang tidak ditemukan',
            ], 404);
        }

        $rules = [
            'nama_barang' => 'sometimes|required',
            'merk' => 'sometimes|required',
            'lokasi' => 'sometimes|required',
            'stok' => 'sometimes|required|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ];



        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data barang gagal di validasi',
                'data' => $validator->errors()
            ]);
        }

        $updateData = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $newImagePath = $request->file('gambar')->store('gambar-barang', 'public');
            $updateData['gambar'] = $newImagePath;
        }

        $barang->update($updateData);

        return response()->json([
            'status' => true,
            'message' => 'Data barang berhasil diupdate',
            'data' => $barang
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $barang = Barang::find($id);
        if(!$barang){
            return response()->json([
                'status' => false,
                'message' => 'Data barang tidak ditemukan',
            ]);
        }

        $barang->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data barang berhasil dihapus',
        ]);
    }
}
