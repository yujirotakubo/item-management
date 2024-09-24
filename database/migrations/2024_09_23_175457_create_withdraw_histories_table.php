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
        Schema::create('withdraw_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ログインユーザーを識別
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // itemsテーブルとのリレーション
            $table->integer('quantity'); // 払出し個数
            $table->timestamps(); // 払出し日時

             // 外部キー制約
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
             $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraw_histories');

    }

};
