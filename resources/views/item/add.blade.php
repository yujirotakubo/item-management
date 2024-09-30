@extends('adminlte::page')

@section('title', '商品登録')

@section('content_header')
    <h1>商品登録</h1>
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
                <form method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">商品名</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="商品名">
                        </div>

                        <div class="form-group">
                            <label for="type">カテゴリー</label>
                            <select class="form-control" id="type" name="type" onchange="toggleExpiryField()">
                                <option value="">選択してください</option>
                                <option value="食品（保存食）">食品（保存食）</option>
                                <option value="食品（飲料）">食品（飲料）</option>
                                <option value="衛生用品">衛生用品</option>
                                <option value="医療用品">医療用品</option>
                                <option value="生活用品">生活用品</option>
                                <option value="防災用品">防災用品</option>
                            </select>
                        </div>

                        <div class="form-group" id="expiryField" style="display: none;">
                            <label for="date">期限</label>
                            <input type="text" class="form-control" id="date" name="date" placeholder="yyyy-mm-dd">
                        </div>

                        <div class="form-group">
                            <label for="individual">個数</label>
                            <input type="number" class="form-control" id="individual" name="individual" placeholder="個数を入力">
                        </div>

                        <div class="form-group">
                            <label for="location">保管場所</label> <!-- 追加された保管場所フィールド -->
                            <input type="text" class="form-control" id="location" name="location" placeholder="保管場所">
                        </div>

                        <div class="form-group">
                            <label for="detail">備考</label>
                            <input type="text" class="form-control" id="detail" name="detail" placeholder="備考">
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">登録</button>
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
    document.querySelector('form').addEventListener('submit', function(event) {
        const dateField = document.getElementById('date');
        const dateValue = dateField.value;
        const datePattern = /^\d{4}-\d{2}-\d{2}$/; // yyyy-mm-dd形式の正規表現

        if (dateValue && !datePattern.test(dateValue)) {
            event.preventDefault();
            alert('期限はyyyy-mm-dd形式で入力してください。');
        }
    });

    function toggleExpiryField() {
        const typeSelect = document.getElementById('type');
        const expiryField = document.getElementById('expiryField');
        
        if (typeSelect.value === '食品（保存食）' || typeSelect.value === '食品（飲料）') {
            expiryField.style.display = 'block';
        } else {
            expiryField.style.display = 'none';
        }
    }
</script>
@stop
