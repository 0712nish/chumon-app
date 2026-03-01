<!-- resources/views/shohin/index.blade.php -->
@extends('layouts.app')

@section('title', '商品一覧')

@section('content')

<div class="page-header-wrapper">
    <div class="page-header">
        <div class="page-header-left">
            <h2><br>販売中商品一覧</h2>
            <p>必要数量を入力し、「かごに追加」してください</p>
        </div>
        <div class="header-buttons">
            <a href="/chumon/history" class="btn-confirm">
                📦 購入履歴
            </a>

            <a href="/chumon" class="btn-confirm">
                🧾 買い物かご確認
            </a>
        </div>
    </div>

    {{-- ★ メッセージはここ --}}
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

        <form method="POST" action="/chumon/add-multi">
            @csrf

            <table class="product-table">
                <tr>
                    <th>商品名</th>
                    <th>単価</th>
                    <th>在庫</th>
                    <th>数量</th>
                    <th>備考</th>
                </tr>

                @foreach($shohinList as $s)
                <tr class="
                    {{ $s->stock <= 0 ? 'stock-zero' : '' }}
                    {{ $s->stock > 0 && $s->stock <= 5 ? 'stock-low' : '' }}
                ">
                    <td class="product-name" data-label="商品名">{{ $s->shohinname2 }}</td>
                    <td data-label="単価">{{ $s->hyojitanka }}</td>
                    <td data-label="在庫">{{ $s->stock }} {{ $s->tani }}</td>

                    <td data-label="数量">
                        @if($s->stock > 0)
                            <input type="number"
                                   name="items[{{ $s->shohinno }}]"
                                   class="qty-input"
                                   min="0"
                                   max="{{ $s->stock }}"
                                   step="{{ $s->step }}"
                                   value="0">
                            <span class="unit">{{ $s->tani }}</span>
                        @else
                            <span class="sold-out">売切</span>
                        @endif
                    </td>
                    <td data-label="備考">{{ $s->biko }}</td>
                </tr>
                @endforeach
            </table>

            <div style="text-align:right; margin-top:20px;">
                <button class="btn-action btn-success-action">
                    まとめてかごに追加
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
