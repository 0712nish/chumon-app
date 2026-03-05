<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="login-wrapper">
    <div class="login-card">
        <h2 class="login-title">注文アプリ</h2>
        <!--<p class="login-sub">ログインしてください</p>-->

        @if ($errors->any())
            <div class="login-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="login-field">
                <label>ID</label>
                <input type="text" name="hsid" required>
            </div>

            <div class="login-field">
                <label>パスワード</label>
                <input type="password" name="password" required>
            </div>

            <button class="login-btn">ログイン</button>
        </form>
    </div>
</div>
@endsection
