<?php

namespace App\Http\Controllers;

use App\Models\Katagori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KatagoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Katagori::get();
        return response()->json([
            'status' => true,
            'message' => 'Data katagori berhasil diambil',
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
        $katagori = new Katagori();

        $rules = [
            'nama' => 'required',
            'desk' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data katagori gagal ditambahkan',
                'data' => $validator->errors()
            ]);
        }

        $katagori->nama = $request->nama;
        $katagori->desk = $request->desk;
        $katagori->save();

        return response()->json([
            'status' => true,
            'message' => 'Data katagori berhasil ditambahkan',
            'data' => $katagori
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $katagori = Katagori::find($id);

        if (!$katagori) {
            return response()->json([
                'status' => false,
                'message' => 'data katagori tidak ditemukan',
                'data' => $katagori->errors()
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data katagori berhasil ditampilkan',
            'data' => $katagori
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Katagori $katagori)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $katagori = Katagori::find($id);

        if (!$katagori) {
            return response()->json([
                'status' => false,
                'message' => 'Data katagori tidak ditemukan',
            ], 404);
        }

        $rules = [
            'nama' => 'sometimes|required',
            'desk' => 'sometimes|required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data barang gagal di validasi',
                'data' => $validator->errors()
            ]);
        }

        $katagori->update($request->all());


        return response()->json([
            'status' => true,
            'message' => 'Data barang berhasil diupdate',
            'data' => $katagori
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $katagori = Katagori::find($id);
        if(!$katagori){
            return response()->json([
                'status' => false,
                'message' => 'Data katagori tidak ditemukan',
            ]);
        }

        $katagori->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data katagori berhasil dihapus',
        ]);
    }
}
