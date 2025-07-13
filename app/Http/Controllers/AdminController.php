<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function list_admin(Request $request)
    {
        return response()->json(Admin::all());
    }

    public function create(Request $request)
    {
        $request->validate([
            'nm_adm'     => 'required|string',
            'user_adm'   => 'required|string|unique:admin,user_adm',
            'no_adm'     => 'required|string|unique:admin,no_adm',
            'email_adm'  => 'required|email|unique:admin,email_adm',
            'pass_adm'   => 'required|string|size:8',
        ]);

        $admin = Admin::create([
            'nm_adm'    => $request->nm_adm,
            'user_adm'  => $request->user_adm,
            'no_adm'    => $request->no_adm,
            'email_adm' => $request->email_adm,
            'pass_adm'  => Hash::make($request->pass_adm),
        ]);

        return $admin->wasRecentlyCreated
            ? response()->json(['message' => 'Success!'])
            : response()->json(['message' => 'Failed!'], 500);
    }

    public function show($id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin not found!'], 404);
        }

        return response()->json([
            'id_adm'    => $admin->id_adm,
            'nm_adm'    => $admin->nm_adm,
            'user_adm'  => $admin->user_adm,
            'email_adm' => $admin->email_adm,
            'no_adm'    => $admin->no_adm,
        ]);
    }

    public function update(Request $request)
    {
        $admin = Admin::find($request->id_adm);

        if (!$admin) {
            return response()->json(['message' => 'Admin not found!'], 404);
        }

        if ($request->filled('nm_adm')) {
            $admin->nm_adm = $request->nm_adm;
        }

        if ($request->filled('email_adm')) {
            $admin->email_adm = $request->email_adm;
        }

        if ($request->filled('no_adm')) {
            $admin->no_adm = $request->no_adm;
        }

        if ($request->filled('pass_adm')) {
            if (strlen($request->pass_adm) !== 8) {
                return response()->json(['message' => 'Password harus tepat 8 karakter!'], 400);
            }
            $admin->pass_adm = Hash::make($request->pass_adm);
        }

        return $admin->save()
            ? response()->json(['message' => 'Success!'])
            : response()->json(['message' => 'Failed!'], 500);
    }

    public function delete(Request $request)
    {
        $admin = Admin::find($request->id_adm);
        if (!$admin) {
            return response()->json(['message' => 'Admin not found!'], 404);
        }

        return $admin->delete()
            ? response()->json(['message' => 'Success!'])
            : response()->json(['message' => 'Failed!'], 500);
    }

    public function login(Request $request)
    {
        $request->validate([
            'user_adm' => 'required|string',
            'pass_adm' => 'required|string',
        ]);

        $admin = Admin::where('user_adm', $request->user_adm)->first();

        if (!$admin || !Hash::check($request->pass_adm, $admin->pass_adm)) {
            return response()->json(['message' => 'Username atau password salah'], 401);
        }

        return response()->json([
            'message'   => 'Login berhasil',
            'id_adm'    => $admin->id_adm,
            'nm_adm'    => $admin->nm_adm,
            'user_adm'  => $admin->user_adm,
            'email_adm' => $admin->email_adm,
            'no_adm'    => $admin->no_adm,
        ]);
    }
}
