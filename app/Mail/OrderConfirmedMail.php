<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmedMail extends Mailable
{
    use SerializesModels;

    public $kihon;
    public $meisaiList;

    public function __construct($kihon, $meisaiList)
    {
        $this->kihon = $kihon;
        $this->meisaiList = $meisaiList;
    }

    public function build()
    {
        return $this->subject('【注文確定】ご注文ありがとうございます')
                    ->text('emails.order_confirmed'); // ← テキスト形式
    }
}
