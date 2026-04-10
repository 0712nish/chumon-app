<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function edit()
    {
        return view('account.edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email' => 'required|email',
            'password' => 'nullable|min:4|confirmed',
        ]);

        $messages = [];

        // メール変更チェック
        if ($user->email !== $request->email) {
            $messages[] = 'メールアドレスを変更しました';
            $user->email = $request->email;
        }

        // パスワード変更チェック
        if ($request->filled('password')) {
            $messages[] = 'パスワードを変更しました';
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // 何も変更がなかった場合
        if (empty($messages)) {
            $messages[] = '変更はありませんでした';
        }

        return redirect('/shohin')->with('success', implode(' / ', $messages));
    }
}
