@extends('adminlte::page')

@section('title', '商品編集')

@section('content_header')
    <h1>商品編集</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card card-primary">
                <form method="POST" action="/items/update/{{ $item->id }}">
                    @csrf

                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">商品名</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $item->name }}">
                        </div>

                        <div class="form-group">
                            <label for="type">カテゴリー</label>
                            <select class="form-control" id="type" name="type">
                                <option value="">選択してください</option>
                                <option value="食品（保存食）" {{ $item->type == '食品（保存食）' ? 'selected' : '' }}>食品（保存食）</option>
                                <option value="食品（飲料）" {{ $item->type == '食品（飲料）' ? 'selected' : '' }}>食品（飲料）</option>
                                <option value="衛生用品" {{ $item->type == '衛生用品' ? 'selected' : '' }}>衛生用品</option>
                                <option value="医療用品" {{ $item->type == '医療用品' ? 'selected' : '' }}>医療用品</option>
                                <option value="生活用品" {{ $item->type == '生活用品' ? 'selected' : '' }}>生活用品</option>
                                <option value="防災用品" {{ $item->type == '防災用品' ? 'selected' : '' }}>防災用品</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date">期限</label>
                            <input type="text" class="form-control" id="date" name="date" value="{{ $item->date }}">
                        </div>

                        <div class="form-group">
                            <label for="individual">個数</label>
                            <input type="text" class="form-control" id="individual" name="individual" value="{{ $item->individual }}">
                        </div>

                        <div class="form-group">
                            <label for="location">保管場所</label> <!-- 保管場所の追加 -->
                            <input type="text" class="form-control" id="location" name="location" value="{{ $item->location }}">
                        </div>

                        <div class="form-group">
                            <label for="detail">備考</label>
                            <input type="text" class="form-control" id="detail" name="detail" value="{{ $item->detail }}">
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">編集</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
<script>
    function toggleExpiryField() {
        const typeSelect = document.getElementById('type');
        const expiryField = document.getElementById('date');

        // カテゴリーに応じて期限フィールドの表示切り替え
        if (typeSelect.value === '食品（保存食）' || typeSelect.value === '食品（飲料）') {
            expiryField.closest('.form-group').style.display = 'block';
        } else {
            expiryField.closest('.form-group').style.display = 'none';
        }
    }

    // 初期化
    document.addEventListener('DOMContentLoaded', function() {
        toggleExpiryField(); // 初回ロード時の表示設定
        document.getElementById('type').addEventListener('change', toggleExpiryField); // カテゴリー変更時の動作
    });
</script>
@stop
