@extends('adminlte::page')

@section('title', '備蓄品一覧')

@section('content_header')
    <h1>備蓄品一覧</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">備蓄品一覧</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <div class="input-group-append">
                                <a href="{{ url('items/add') }}" class="btn btn-default">商品登録</a>
                                <a href="{{ url('items?near_expiry=true') }}" class="btn btn-warning" data-toggle="tooltip" title="期限が3ヶ月以内の食品を表示します">期限間近</a>
                                <a href="{{ route('items.export') }}" class="btn btn-info">CSVエクスポート</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>商品名</th>
                                <th>カテゴリー</th>
                                <th>期限</th>
                                <th>個数</th>
                                <th>保管場所</th> <!-- 保管場所追加 -->
                                <th>備考</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->date }}</td>
                                    <td>{{ $item->individual }}</td>
                                    <td>{{ $item->location }}</td> <!-- 保管場所追加 -->
                                    <td>{{ $item->detail }}</td>
                                    <td>
                                        <a href="{{ url('items/edit', $item->id) }}" class="btn btn-primary btn-sm">編集</a>
                                        <form action="{{ url('items/withdraw', $item->id) }}" method="POST" style="display:inline;" class="withdraw-form">
                                            @csrf
                                            <input type="number" name="withdraw_quantity" min="1" max="{{ $item->individual }}" placeholder="払出し数" required class="form-control form-control-sm withdraw-quantity" style="width: 100px; display: inline-block;">
                                            <button type="submit" class="btn btn-success btn-sm">払出し</button>
                                        </form>
                                        <form action="{{ url('items/delete', $item->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">削除</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        .blink {
            color: red; 
            font-weight: bold; 
            background-color: #ffc107; 
            animation: blinker 1.5s linear infinite; 
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
    </style>
@stop

@section('js')
    <script src="code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script>
        $(document).ready(function() {
            const table = $('.table').DataTable({
                "order": [[0, "desc"]], // IDの列を降順でソート
                "drawCallback": function(settings) {
                    const today = new Date();
                    const threeMonthsLater = new Date();
                    threeMonthsLater.setMonth(today.getMonth() + 3);

                    // 年がまたがる場合の対応
                    if (threeMonthsLater.getMonth() < today.getMonth()) {
                        threeMonthsLater.setFullYear(threeMonthsLater.getFullYear() + 1);
                    }

                    // 現在のページに表示されている行を取得
                    const api = this.api();
                    api.rows({ page: 'current' }).every(function() {
                        const rowData = this.data();
                        const expiryDate = new Date(rowData[3]); // 期限は4番目の列
                        const category = rowData[2]; // カテゴリーは3番目の列

                        const dateCell = $(this.node()).find('td:nth-child(4)'); // 期限セルを取得

                        // 期限が空白の場合はスキップ
                        if (!rowData[3]) return;

                        // カテゴリーが食品（保存食）または食品（飲料）の場合のみ
                        if ((category === '食品（保存食）' || category === '食品（飲料）') &&
                            expiryDate <= threeMonthsLater && expiryDate >= today) {
                            dateCell.addClass('blink'); // アラートを追加
                        } else {
                            dateCell.removeClass('blink'); // 条件に合わない場合はクラスを削除
                        }
                    });
                }
            });

            // ツールチップの初期化（表示の遅延を設定）
            $('[data-toggle="tooltip"]').tooltip({
                delay: { "show": 0, "hide": 100 } // 表示を即時、非表示の遅延を設定
            });

            // 払出し確認メッセージ
            $('.withdraw-form').on('submit', function(e) {
                const quantity = $(this).find('.withdraw-quantity').val(); // 入力された払出し数を取得
                if (!confirm(`本当に${quantity}個払出ししますか？`)) {
                    e.preventDefault(); // 確認がキャンセルされたら送信を中止
                }
            });
        });
    </script>
@stop




