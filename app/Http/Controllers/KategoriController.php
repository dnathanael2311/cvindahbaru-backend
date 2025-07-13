<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function list_kategori(Request $request)
    {
        return response()->json(Kategori::all());
    }

    public function create(Request $request)
    {
        $kategori = Kategori::create([
            'nm_ktg' => $request->nm_ktg
        ]);

        return $kategori ? response('Success!') : response('Failed!');
    }

    public function update(Request $request)
    {
        $kategori = Kategori::find($request->id_ktg);

        if (!$kategori) {
            return response('Data not found', 404);
        }

        $kategori->nm_ktg = $request->nm_ktg;
        $kategori->save();

        return response('Success!');
    }

    public function delete(Request $request)
    {
        $kategori = Kategori::find($request->id_ktg);

        if (!$kategori) {
            return response('Data not found', 404);
        }

        $kategori->delete();

        return response('Success!');
    }
}
