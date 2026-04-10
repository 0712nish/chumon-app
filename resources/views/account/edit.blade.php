@extends('layouts.app')

@section('title', 'アカウント設定')

@section('content')

<div class="account-page">
<div class="page-header-wrapper">
    <div class="page-header-inner">
    <div class="page-header">
        <div class="page-header-left">
            <h2><br>アカウント設定</h2>
            <p>メールアドレスやパスワードを変更できます。</p>
        </div>
        <a href="/shohin" class="btn-confirm">
            ← 商品一覧
        </a>
    </div>
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

        <form method="POST" action="/account/update-email">
            @csrf

            <div class="login-field">
                <label>メールアドレス</label>
                <input type="email" name="email" autocomplete="email"
                       value="{{ auth()->user()->email }}" required>
            </div>

            <button class="login-btn">メールアドレスを更新</button>
        </form>
    </div>
    <div class="card" style="margin-top:20px;">
        <form method="POST" action="/account/update-password">
            @csrf

            <div class="login-field">
                <label>新しいパスワード（４桁以上）</label>
                <input type="password" name="password" autocomplete="new-password">
            </div>

            <div class="login-field">
                <label>新しいパスワード（確認）</label>
                <input type="password" name="password_confirmation" autocomplete="new-password">
            </div>

            <button class="login-btn">パスワードを更新</button>
        </form>
    </div>

</div>
</div>
@endsection
