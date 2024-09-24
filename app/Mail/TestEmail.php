<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
        // コンストラクタの処理（必要があれば）
    }

    public function build()
    {
        return $this
            ->subject('テストメール')
            ->view('emails.test'); // ビュー名を指定
    }
}
