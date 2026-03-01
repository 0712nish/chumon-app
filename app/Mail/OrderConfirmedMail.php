<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class OrderConfirmedMail extends Mailable
{
    public $kihon;
    public $meisaiList;

    public function __construct($kihon, $meisaiList)
    {
        $this->kihon = $kihon;
        $this->meisaiList = $meisaiList;
    }

    public function build()
    {
        return $this->subject('【注文完了】ご注文ありがとうございます')
                    ->view('emails.order_confirmed');
    }
}