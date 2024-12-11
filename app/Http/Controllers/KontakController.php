<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KontakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Kontak::get();

        return response()->json([
            'status' => true,
            'message' => 'Data Kontak',
            'data' => $data
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
            'nama_staf' => 'required',
            'badge' => 'required',
            'nohp' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif'
        ];

        $validator = Validator::make($request->all(), $rules);

        if(!$validator){
            return response()->json([
                'status' => false,
                'message' => 'Proses Validasi Gagal',
                'data' => $validator->errors()
            ]);
        }

        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('gambar-kontak', 'public');
        }

        $kontak = Kontak::create([
            'nama_staf' => $request->nama_staf,
            'badge' => $request->badge,
            'nohp' => $request->nohp,
            'gambar' => $imagePath
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data Kontak Berhasil Ditambahkan',
            'data' => $kontak
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kontak = Kontak::find($id);

        // dd($kontak);

        if(!$kontak){
            return response()->json([
                'status' => false,
                'message' => 'Kontak tidak ditemukan',
                'data' => $kontak->errors()
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Kontak Ditemukan',
            'data' => $kontak
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kontak $kontak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kontak = Kontak::find($id);
        $rules = [
            'nama_staf' => 'required',
            'badge' => 'required',
            'nohp' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ];

        $validator = Validator::make($request->all(), $rules);

        if(!$validator){
            return response()->json([
                'status' => false,
                'message' => 'Proses Validasi Gagal',
            ]);
        }

        $updateData = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            if ($kontak->gambar && Storage::disk('public')->exists($kontak->gambar)) {
                Storage::disk('public')->delete($kontak->gambar);
            }
            $newImagePath = $request->file('gambar')->store('gambar-kontak', 'public');
            $updateData['gambar'] = $newImagePath;
        }

        $kontak->update($updateData);


        return response()->json([
            'status' => true,
            'message' => 'Kontak Berhasil Di update'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kontak = Kontak::find($id);
        if(!$kontak){
            return response()->json([
                'status' => false,
                'message' => 'Kontak tidak ditemukan',
            ]);
        }

        $kontak->delete();

        return response()->json([
            'status' => true,
            'message' => 'Kontak Berhasil Di Hapus'
        ]);
    }
}
