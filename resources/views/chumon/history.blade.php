@extends('layouts.app')

@section('title', '購入履歴')

@section('content')

<div class="page-header-wrapper">
    <div class="page-header">
        <div class="page-header-left">
            <h2><br>購入履歴</h2>
            <p>過去に確定した注文一覧です。</p>
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
        <h2>購入履歴</h2>

        @forelse($historyList as $kihon)
            <div class="history-box">
                <h3>
                    注文日：{{ $kihon->shoridate }}
                    （注文番号：{{ $kihon->kihonno }}）
                </h3>

                <table class="product-table">
                    <tr>
                        <th>商品名</th>
                        <th>販売開始</th>
                        <th>単価</th>
                        <th>数量</th>
                        <th>小計</th>
                        {{--<th>受取状況</th>--}}
                    </tr>

                    @php $total = 0; @endphp

                    @foreach($kihon->meisai as $m)
                        @php
                            $subtotal = $m->tanka * $m->suryo;
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td class="product-name" data-label="商品名">{{ $m->shohin->shohinname2 }}</td>
                            <td data-label="販売開始">
                                @if($m->startdate)
                                    {{ \Carbon\Carbon::parse($m->startdate)->format('Y-m-d') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td data-label="単価">{{ number_format($m->tanka, 0) }}円</td> {{-- これで四捨五入できている --}}
                            <td data-label="数量">{{ $m->suryo }} {{ $m->tani }}</td>
                            <td data-label="小計">{{ number_format($subtotal, 0) }}円</td> {{-- これで四捨五入できている --}}
                            {{-- <td data-label="受取状況">
                                @if($m->uriage)
                                    　<span class="badge-success">受取済</span>
                                @else
                                    　<span class="badge-wait">未受取</span>
                                @endif
                            </td>--}}
                        </tr>
                    @endforeach

                    <tr>
                        <td colspan="4" style="text-align:right;"><strong>合計</strong></td>
                        <td><strong>{{ number_format($total, 0) }}円</strong></td>
                    </tr>
                </table>
            </div>
        @empty
            <p>購入履歴はありません。</p>
        @endforelse

    </div>
</div>

@endsection
