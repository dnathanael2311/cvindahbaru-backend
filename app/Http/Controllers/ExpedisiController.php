<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expedisi;

class ExpedisiController extends Controller
{
    public function list_expedisi()
    {
        $data = Expedisi::all();
        return response()->json($data);
    }

    public function create(Request $request)
    {
        // Cek duplikasi no_exp atau email_exp
        $exists = Expedisi::where('no_exp', $request->no_exp)
                    ->orWhere('email_exp', $request->email_exp)
                    ->exists();

        if ($exists) {
            return response()->json('Expedisi sudah terdaftar', 409);
        }

        $expedisi = Expedisi::create([
            'nm_exp'    => $request->nm_exp,
            'no_exp'    => $request->no_exp,
            'email_exp' => $request->email_exp
        ]);

        return $expedisi ? response('Success!') : response('Failed!');
    }

    public function update(Request $request)
    {
        $expedisi = Expedisi::find($request->id_exp);

        if (!$expedisi) {
            return response('Data not found', 404);
        }

        // Cek duplikasi pada entri lain
        $conflict = Expedisi::where(function ($query) use ($request) {
                            $query->where('no_exp', $request->no_exp)
                                  ->orWhere('email_exp', $request->email_exp);
                        })
                        ->where('id_exp', '!=', $request->id_exp)
                        ->exists();

        if ($conflict) {
            return response()->json('Expedisi sudah terdaftar', 409);
        }

        $expedisi->update([
            'nm_exp'    => $request->nm_exp,
            'no_exp'    => $request->no_exp,
            'email_exp' => $request->email_exp
        ]);

        return response('Success!');
    }

    public function delete(Request $request)
    {
        $deleted = Expedisi::where('id_exp', $request->id_exp)->delete();

        return $deleted ? response('Success!') : response('Failed!');
    }
}
