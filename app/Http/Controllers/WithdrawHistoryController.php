<?php

namespace App\Http\Controllers;

use App\Models\WithdrawHistory;
use Illuminate\Http\Request;

class WithdrawHistoryController extends Controller
{
    public function index()
    {
        $histories = WithdrawHistory::all(); // モデルから全ての履歴を取得
        return view('withdraw_histories.index', compact('histories'));
    }
}