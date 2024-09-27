<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use Illuminate\Support\Facades\Response;
use App\Models\WithdrawHistory;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 商品一覧
     */
    public function index(Request $request)
    {
        // 商品一覧取得
        $items = Item::all();

        // 「期限間近」ボタンが押された場合
        if ($request->has('near_expiry')) {
            $today = new \DateTime();
            $threeMonthsLater = (clone $today)->modify('+3 months');

            // 期限が設定されていて、3ヶ月以内のアイテムのみを表示
            $items = $items->filter(function ($item) use ($today, $threeMonthsLater) {
                return !empty($item->date) &&
                    ($item->type === '食品（保存食）' || 
                    $item->type === '食品（飲料）') &&
                    (new \DateTime($item->date) >= $today && new \DateTime($item->date) <= $threeMonthsLater);
            });
        }

        return view('item.index', compact('items'));
    }
    
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'name' => 'required|max:100',
                'individual' => 'required|integer',

            ]);

            Item::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'type' => $request->type,
                'date' => $request->date,
                'individual' => $request->individual,
                'location' => $request->location,
                'detail' => $request->detail,
            ]);

            return redirect('/items')->with('success', '商品を登録しました');
        }

        return view('item.add');
    }

    /**
     * 商品編集
     */
    public function edit(Request $request, $id)
    {
        $item = Item::find($id);
        return view('item.edit' ,compact('item'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'individual' => 'required|integer',
            'location' => 'required|string|max:255',
            'detail' => 'nullable|string',
        ];

        if ($request->input('type') === '食品（保存食）' || $request->input('type') === '食品（飲料）') {
            $rules['date'] = 'required|date';
        } else {
            $rules['date'] = 'nullable|date';
        }

        $validatedData = $request->validate($rules);
        $item = Item::findOrFail($id);
        $item->update($validatedData);

        return redirect('/items')->with('success', '商品を編集しました');
    }

    public function withdraw(Request $request, $id)
    {
        $request->validate(['withdraw_quantity' => 'required|integer|min:1']);

        $item = Item::findOrFail($id);
        $withdrawQuantity = $request->input('withdraw_quantity');

        if ($withdrawQuantity > $item->individual) {
            return redirect()->back()->withErrors(['withdraw_quantity' => '払出し数が在庫を超えています。']);
        }

        $item->individual -= $withdrawQuantity;
        $item->save();

        WithdrawHistory::create([
            'item_id' => $item->id,
            'quantity' => $withdrawQuantity,
        ]);

        return redirect('/items')->with('success', '商品を払出ししました');        
    }

    public function delete(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect('/items')->with('success', '商品を削除しました');
    }

    public function exportCsv()
    {
        $items = Item::all();
        $csvFileName = 'items_' . date('Ymd') . '.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['ID', '商品名', 'カテゴリー', '期限', '個数', '保管場所', '備考']);

        foreach ($items as $item) {
            fputcsv($handle, [
                $item->id,
                $item->name,
                $item->type,
                $item->date,
                $item->individual,
                $item->location,
                $item->detail,
            ]);
        }

        return Response::stream(function() use ($handle) {
            fclose($handle);
        }, 200, $headers);
    }

    public function exportHistory()
    {
        $histories = WithdrawHistory::with('item')->get();
        $csvFileName = 'withdraw_history_' . date('Ymd') . '.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['ID', '商品名', '払出し数', '保管場所', '作成日時']);

        foreach ($histories as $history) {
            fputcsv($handle, [
                $history->id,
                $history->item->name,
                $history->quantity,
                $history->item->location,
                $history->created_at,
            ]);
        }

        return Response::stream(function() use ($handle) {
            fclose($handle);
        }, 200, $headers);
    }
}
