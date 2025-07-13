<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
class ForgotPasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:pelanggan,email_plg',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        $resetLink = env('FRONTEND_URL', 'http://localhost:5173') . '/reset-password?token=' . $token . '&email=' . urlencode($request->email);

        // ✅ Ganti Mail::raw dengan ini:
        Mail::send([], [], function ($message) use ($request, $resetLink) {
    $message->to($request->email);
    $message->subject('Reset Password');
    $message->html("
        <h2>Permintaan Reset Password</h2>
        <p>Klik link di bawah ini untuk mengganti password Anda:</p>
        <p><a href='{$resetLink}'>{$resetLink}</a></p>
        <p>Abaikan email ini jika Anda tidak merasa melakukan permintaan ini.</p>
    ");
});


        return response()->json(['message' => 'Link reset password telah dikirim ke email Anda.']);
    }
    public function reset(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:pelanggan,email_plg',
        'token' => 'required',
        'password' => 'required|min:6|confirmed',
    ]);

    $reset = DB::table('password_resets')
        ->where('email', $request->email)
        ->where('token', $request->token)
        ->first();

    if (!$reset) {
        return response()->json(['message' => 'Token tidak valid atau kadaluarsa.'], 400);
    }

    DB::table('pelanggan')
        ->where('email_plg', $request->email)
        ->update([
            'password' => bcrypt($request->password),
        ]);

    DB::table('password_resets')->where('email', $request->email)->delete();

    return response()->json(['message' => 'Password berhasil diubah.']);
}

}
