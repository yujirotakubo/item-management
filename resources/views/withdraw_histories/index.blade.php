@extends('adminlte::page')

@section('title', '払出し履歴')

@section('content_header')
    <h1>払出し履歴</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">払出し履歴</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <div class="input-group-append">
                                <a href="{{ route('items.export.history') }}" class="btn btn-info">CSVエクスポート</a>
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
                                <th>払出し個数</th>
                                <th>保管場所</th> <!-- 保管場所を追加 -->
                                <th>日時</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histories as $history)
                                <tr>
                                    <td>{{ $history->id }}</td>
                                    <td>{{ $history->item->name }}</td>
                                    <td>{{ $history->item->type }}</td>
                                    <td>{{ $history->quantity }}</td>
                                    <td>{{ $history->item->location }}</td> <!-- 保管場所を追加 -->
                                    <td>{{ $history->created_at }}</td>
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
            animation: blinker 1s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            const table = $('.table').DataTable({
                "order": [[0, "desc"]], // IDの列を降順でソート
            });
        });
    </script>
@stop
