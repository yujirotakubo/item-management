<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 商品名
            $table->string('type'); // カテゴリー
            $table->string('date')->nullable(); // 期限
            $table->integer('individual'); // 個数
            //$table->string('location'); // 保管場所（追加）
            $table->string('detail')->nullable(); // 備考
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
