ご注文ありがとうございます。
下記の内容で注文を承りました。
────────────────────────────
■ 注文番号：{{ $kihon->kihonno }}
■ 注文日　：{{ $kihon->shoridate }}
■ ご注文者：{{ auth()->user()->name2 }} 様
────────────────────────────
【ご注文内容】
@php $total = 0; @endphp
@foreach($meisaiList as $m)
@php
    //$subtotal = $m->suryo * $m->tanka;
    //$total += $subtotal;

    $hyojisuryo = rtrim(rtrim($m->suryo, '0'), '.')
    $hyojitani = $m->chumontani;
    
    // 数量が1kg未満の場合、g表示にする
    if ($m->suryo < 1) {
        if ($m->chumontani === 'kg') {
            $hyojisuryo = $m->suryo * 1000;
            $hyojitani = 'g';
        }
    }

@endphp
--------------------------------------------
商品名 ： {{ $m->shohinname2 }}
単価　 ： {{ $m->hyojitanka }}{{-- 円--}}
数量　 ： {{ $hyojisuryo }} {{ $hyojitani }}{{--小計　 ： {{ number_format($subtotal, 0) }} 円--}}
備考　 ： {{ $m->biko }}
--------------------------------------------
@endforeach{{--＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝■ 合計金額：{{ number_format($total, 0) }} 円＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
--}}
本メールは自動送信されています。
ご不明な点がございましたら担当者までお問い合わせください。
----------------------------------------------------
（有）秀明ナチュラルファーム
----------------------------------------------------