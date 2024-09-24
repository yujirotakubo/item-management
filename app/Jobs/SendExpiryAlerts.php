<?php

namespace App\Jobs;

use App\Mail\ExpiryAlertMail;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SendExpiryAlerts
{
    public function handle()
    {
        $today = now();
        $threeMonthsLater = now()->addMonths(3);

        // 現在のユーザーを取得
        $user = Auth::user();

        // 期限が近いアイテムを取得
        $items = Item::where('date', '>=', $today)
                    ->where('date', '<=', $threeMonthsLater)
                    ->get();

        if ($items->isNotEmpty() && $user) {
            Mail::to($user->email)->send(new ExpiryAlertMail($items));
        }
    }
}
