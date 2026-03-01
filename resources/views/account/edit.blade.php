@extends('layouts.app')

@section('title', 'アカウント設定')

@section('content')

<div class="login-wrapper">
    <div class="login-card">
        <h2 class="login-title">アカウント設定</h2>

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

        <form method="POST" action="/account/update">
            @csrf

            <div class="login-field">
                <label>メールアドレス</label>
                <input type="email" name="email"
                       value="{{ auth()->user()->email }}" required>
            </div>

            <div class="login-field">
                <label>新しいパスワード</label>
                <input type="password" name="password">
            </div>

            <div class="login-field">
                <label>新しいパスワード（確認）</label>
                <input type="password" name="password_confirmation">
            </div>

            <button class="login-btn">更新する</button>
        </form>
    </div>
</div>

@endsection
