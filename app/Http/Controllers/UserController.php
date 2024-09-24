<?php

// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function sendTestEmail()
    {
        $user = auth()->user(); // ログインユーザーの取得

        if ($user) {
            Mail::to($user->email)->send(new TestEmail());
            return response()->json(['message' => 'テストメールが送信されました。']);
        } else {
            return response()->json(['message' => 'ユーザーがログインしていません。'], 401);
        }
    }
}
