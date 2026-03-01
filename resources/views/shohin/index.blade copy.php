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
        <table class="product-table">
            <tr>
                {{--<th>No</th>--}}
                <th>商品名</th>
                <th>単価</th>
                <th>在庫</th>
                <th>数量</th>
                <th></th>
            </tr>
            @foreach($shohinList as $s)
            <tr class="
                {{ $s->stock <= 0 ? 'stock-zero' : '' }}
                {{ $s->stock > 0 && $s->stock <= 5 ? 'stock-low' : '' }}
            ">
                {{--<td class="product-no" data-label="No">{{ $s->shohinno }}</td>--}}
                <td class="product-name" data-label="商品名">{{ $s->shohinname2 }}</td>
                {{--<td data-label="単価">¥{{ number_format($s->tanka, 2) }}</td>--}}
                <td data-label="単価">{{ $s->hyojitanka }}</td>
                <td data-label="在庫">{{ $s->stock }} {{ $s->tani }}</td>

                <td data-label="数量">
                    @if($s->stock > 0)
                    <form method="POST" action="/chumon/add" id="form-{{ $s->shohinno }}" class="qty-form qty-add-wrap">
                        @csrf
                        <input type="number"
                            name="suryo"
                            class="qty-input"
                            min="{{ $s->min }}"
                            max="{{ $s->stock }}"
                            step="{{ $s->step }}"
                            value="0">
                        <span class="unit">{{ $s->tani }}</span>

                        <input type="hidden" name="shohinno" value="{{ $s->shohinno }}">

                    </form>
                    @else
                        <span class="sold-out">売切</span>
                    @endif
                </td>
                <td class="add-btn-cell qty-add-wrap">
                    @if($s->stock > 0)
                        <button type="submit" form="form-{{ $s->shohinno }}" class="btn-action">
                            かごに追加
                        </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

@endsection
