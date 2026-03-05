<!-- resources/views/chumon/index.blade.php -->
@extends('layouts.app')

@section('title', '買い物かご確認')

@section('content')

<div class="page-header-wrapper">
    <div class="page-header">
        <div class="page-header-left">
            <h2><br>買い物かご</h2>
            <p>現在選択の内容です。まだ、購入は完了していません。</p>
        </div>

        <a href="/shohin" class="btn-confirm">
            ← 商品一覧へ
        </a>
    </div>

    {{-- メッセージ --}}
    @if ($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
</div>

<div class="content-container">
    <div class="card">

        @if(!$kihon || $kihon->meisai->isEmpty())
            <p style="text-align:center; color:#666; padding:20px 0;">
                現在、選択中の商品はありません。
            </p>
            <div style="text-align:center;">
                <a href="/shohin" class="btn-add">商品を追加する</a>
            </div>
        @else
            <table class="product-table">
                <tr>
                    <th>No</th>
                    <th>商品名</th>
                    <th>販売開始</th>
                    <th>単価</th>
                    <th>数量</th>
                    <th>小計</th>
                    <th></th>
                </tr>

                @php $total = 0; @endphp

                @foreach($kihon->meisai as $m)
                    @php
                        $subtotal = $m->suryo * $m->tanka;
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td class="product-no" data-label="No">{{ $m->meisaino }}</td>
                        <td class="product-name" data-label="商品名">{{ $m->shohin->shohinname2 }}</td>
                        <td data-label="販売開始">
                            {{ \Carbon\Carbon::parse($m->startdate)->format('Y-m-d') }}
                        </td>
                        <td data-label="単価">{{ $m->hyojitanka }}</td>

                        <td data-label="数量">
                            <form method="POST" action="/chumon/update" class="qty-form">
                                @csrf
                                <input type="number"
                                       name="suryo"
                                       class="qty-input"
                                       min="{{ $m->shohin->min }}"
                                       step="{{ $m->shohin->step }}"
                                       value="{{ rtrim(rtrim(number_format($m->suryo,2), '0'), '.') }}"
                                       style="width:70px;">
                                <span class="unit">{{ $m->tani }}</span>
                                <input type="hidden" name="kihonno" value="{{ $kihon->kihonno }}">
                                <input type="hidden" name="meisaino" value="{{ $m->meisaino }}">
                                <button class="btn-add">変更</button>
                            </form>
                        </td>
                        {{-- floor切り捨て ceil切り上げ --}}
                        <td data-label="小計">{{number_format($subtotal, 0) }}円</td> {{-- これで四捨五入できている --}}

                        <td>
                            <form method="POST" action="/chumon/delete">
                                @csrf
                                <input type="hidden" name="kihonno" value="{{ $kihon->kihonno }}">
                                <input type="hidden" name="meisaino" value="{{ $m->meisaino }}">
                                <button class="btn-action btn-danger-action"
                                    onclick="return confirm('削除しますか？')">
                                    削除
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                <tr class="total-row">
                    <th colspan="4" style="text-align:right;">合計</th>
                    <th>{{ number_format($total, 0) }}円</th>
                    <th></th>
                </tr>

            </table>

            <div style="text-align:right; margin-top:15px;">
                <form method="POST" action="/chumon/confirm">
                    @csrf
                    <button class="btn-action btn-success-action"
                        onclick="return confirm('この内容で注文を確定しますか？')">
                        購入手続きへ
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>

@endsection
