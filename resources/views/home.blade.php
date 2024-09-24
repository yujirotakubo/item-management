@extends('adminlte::page')

@section('title', '備蓄品管理システム')

@section('head')
    <link rel="icon" type="image/x-icon" href="{{ asset('img/AdminLTELogo.png') }}">
@stop

@section('content_header')
    <h1 class="text-center font-weight-bold" style="color: #2c3e50;">ようこそ、備蓄品管理システムへ</h1>
@stop

@section('content')
    <p class="lead" style="font-size: 1.2rem; color: #34495e;">本システムでは、災害に備えて必要な備蓄品を管理し、迅速な対応を可能にします。</p>

    <!-- 備蓄品の総アイテム数と合計個数の表示 -->
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-boxes"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">総アイテム数</span>
                    <span class="info-box-number" id="totalItems" style="font-size: 2rem;">0</span>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-cubes"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">総個数</span>
                    <span class="info-box-number" id="totalIndividualCount" style="font-size: 2rem;">0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 期限が1ヶ月以内の商品を表示 -->
    <h4 class="font-weight-bold" style="color: #2980b9;">期限が1ヶ月以内の備蓄品</h4>
    <div class="alert alert-info text-center" style="border-radius: 10px; background-color: #d1ecf1; color: #0c5460; font-weight: bold; padding: 10px; margin-bottom: 20px;">
        ご希望者にお譲りします
    </div>
    <div class="row">
        @foreach ($nearExpiryItems as $item) <!-- 修正: 変数名を単数形に変更 -->
            <div class="col-md-4 mb-3">
                <div class="card" style="background-color: #f8f9fa; border: 1px solid #ced4da;">
                    <div class="card-header" style="background-color: #e9ecef;">
                        <h5 class="card-title font-weight-bold">{{ $item->name }}</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2">カテゴリー: {{ $item->type }}</h6>
                        <p class="card-text">期限: {{ $item->date }}</p>
                        <p class="card-text">個数: {{ $item->individual }} 
                            @if ($item->individual <= 10) <!-- 10以下の場合 -->
                                <span class="text-danger font-weight-bold blinking">残りわずか</span> <!-- 点滅クラスを追加 -->
                            @endif
                        </p>
                        <p class="card-text">保管場所: {{ $item->location }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- 既存のダッシュボードコンテンツ -->
    <h4 class="font-weight-bold" style="color: #2980b9;">備蓄の重要性</h4>
    <p style="font-size: 1rem; color: #34495e;">自然災害や突発的な事態に備えるため、適切な備蓄品を用意することは非常に重要です。</p>
    <p style="font-size: 1rem; color: #34495e;">以下の理由から、備蓄品の管理が求められます：</p>
    <ul>
        <li>食料や水の確保：非常時に備え、最低限の食料や水を準備することが必要です。</li>
        <li>医療品の準備：急な怪我や病気に備えて、必要な医療品を用意することが大切です。</li>
        <li>避難時の安全：災害時に必要な物資を把握し、迅速に行動できるようにすることが必要です。</li>
    </ul>

    <h4 class="font-weight-bold" style="color: #2980b9;">教育動画</h4>
    <p style="font-size: 1rem; color: #34495e;">以下の動画では、家庭での備蓄品の選び方や管理方法についてのポイントを解説しています。ぜひご覧ください。</p>
    
    <div class="embed-responsive embed-responsive-16by9">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/7sOe5alE3jA?si=-xfpGm5UO4Z8GNT3" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
@stop

@section('css')
    <style>
        .embed-responsive {
            position: relative;
            display: block;
            height: 0;
            padding: 0;
            overflow: hidden;
        }
        .embed-responsive-16by9 {
            padding-top: 56.25%; /* 16:9 */
        }
        .embed-responsive iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        /* 点滅アニメーション */
        @keyframes blink {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0;
            }
        }
        .blinking {
            animation: blink 1s infinite; /* 1秒間隔で無限に点滅 */
            font-size: 1rem; /* 小さく設定 */
        }
    </style>
@stop

@section('js')
    <script>
        // カウンターアニメーションを実装
        function animateValue(id, start, end, duration) {
            const obj = document.getElementById(id);
            let startTime = null;

            function step(timestamp) {
                if (!startTime) startTime = timestamp;
                const progress = timestamp - startTime;
                const current = Math.min(Math.floor(start + (progress / duration) * (end - start)), end);
                obj.innerHTML = current;
                if (progress < duration) {
                    window.requestAnimationFrame(step);
                }
            }

            window.requestAnimationFrame(step);
        }

        // ページロード時にアニメーションを実行
        document.addEventListener('DOMContentLoaded', function() {
            // カウンターアニメーション
            animateValue('totalItems', 0, {{ $totalItems }}, 1500); // 1.5秒でカウントアップ
            animateValue('totalIndividualCount', 0, {{ $totalIndividualCount }}, 1500); // 1.5秒でカウントアップ
        });
    </script>
@stop
