<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reward;
use Illuminate\Support\Facades\Log;

class RewardController extends Controller
{
    // Membuat reward baru
    public function create(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'id_plg'     => 'required|exists:pelanggan,id_plg',
                'jenis_rwd'  => 'required|string|max:30',
                'value_rwd'  => 'required|numeric',
            ]);

            // Cek apakah reward sudah diklaim hari ini oleh pelanggan
            $alreadyClaimed = Reward::where('id_plg', $request->id_plg)
                ->where('jenis_rwd', 'LIKE', 'Roulette Birthday%')
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if ($alreadyClaimed) {
                return response()->json([
                    'message' => 'Reward hari ini sudah diklaim'
                ], 409); // 409 = Conflict
            }

            // Buat reward baru
            $reward = Reward::create([
                'id_plg'      => $request->id_plg,
                'jenis_rwd'   => $request->jenis_rwd,
                'value_rwd'   => $request->value_rwd,
                'expired_rwd' => now()->addDays(30),
            ]);

            return response()->json([
                'message' => 'Success!',
                'data'    => $reward
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create reward',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    // Mengupdate reward berdasarkan id_reward
    public function update(Request $request)
    {
        $reward = Reward::find($request->id_reward);

        if (!$reward) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $reward->update([
            'jenis_rwd' => $request->jenis_rwd,
            'value_rwd' => $request->value_rwd,
            'expired_rwd' => $request->expired_rwd
        ]);

        return response()->json(['message' => 'Success!']);
    }

    // Menghapus reward berdasarkan id_reward
    public function delete(Request $request)
    {
        $deleted = Reward::where('id_reward', $request->id_reward)->delete();

        return $deleted
            ? response()->json(['message' => 'Success!'])
            : response()->json(['message' => 'Failed!'], 500);
    }

    // Cek apakah sudah claim reward hari ini
    public function checkToday(Request $request)
    {
        $reward = Reward::where('id_plg', $request->id_plg)
            ->where('jenis_rwd', 'LIKE', 'Roulette Birthday%')
            ->whereDate('created_at', now()->toDateString())
            ->first();

        return response()->json(['alreadyClaimed' => $reward ? true : false]);
    }

    public function latest(Request $request)
    {
        $id_plg = $request->id_plg;

        $reward = Reward::where('id_plg', $id_plg)
            ->latest('created_at')
            ->first();

        // if (!$reward) {
        //     return response()->json(['message' => 'No reward found'], 404);
        // }

        return response()->json($reward);
    }
}
