<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // 総アイテム数と総個数の計算
        $totalItems = Item::count();
        $totalIndividualCount = Item::sum('individual');

        // 期限が1ヶ月以内の商品を取得
        $nearExpiryItems = Item::where('date', '<=', now()->addMonth())->get();

        // 変数をビューに渡す
        return view('home', compact('totalItems', 'totalIndividualCount', 'nearExpiryItems'));
    }

    public function dashboardIndex()
    {
        // 備蓄品の総アイテム数（商品名がユニークなもの）
        $totalItems = Item::distinct('name')->count('name');

        // 商品名ごとの総個数を合計
        $totalQuantity = Item::select('name', \DB::raw('SUM(individual) as total_individual'))
            ->groupBy('name')
            ->get();

        // 全体の合計個数
        $totalIndividualCount = $totalQuantity->sum('total_individual');

        // 期限が1ヶ月以内の商品を取得
        //dd(now()->addMonth());
        $nearExpiryItems = Item::where('date', '<=', '2024-10-27'/*()->addMonth()*/)->get();

        // これをダッシュボードビューに渡す
        return view('home', compact('totalItems', 'totalIndividualCount', 'nearExpiryItems'));
    }
}

