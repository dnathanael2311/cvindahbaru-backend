<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reward;

class RewardController extends Controller
{
    // Membuat reward baru
    public function create(Request $request)
    {
        $reward = Reward::create([
            'id_plg'      => $request->id_plg,
            'jenis_rwd'   => $request->jenis_rwd,
            'value_rwd'   => $request->value_rwd,
            'expired_rwd' => now()->addDays(30)
        ]);

        return $reward
            ? response()->json(['message' => 'Success!'])
            : response()->json(['message' => 'Failed!'], 500);
    }

    // Mengupdate reward berdasarkan id_rwd
    public function update(Request $request)
    {
        $reward = Reward::where('id_rwd', $request->id_rwd)->first();

        if (!$reward) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $reward->update([
            'jenis_rwd'   => $request->jenis_rwd,
            'value_rwd'   => $request->value_rwd,
            'expired_rwd' => $request->expired_rwd
        ]);

        return response()->json(['message' => 'Success!']);
    }

    // Menghapus reward berdasarkan id_rwd
    public function delete(Request $request)
    {
        $deleted = Reward::where('id_rwd', $request->id_rwd)->delete();

        return $deleted
            ? response()->json(['message' => 'Success!'])
            : response()->json(['message' => 'Failed!'], 500);
    }
}
