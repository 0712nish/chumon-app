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

//    public function update(Request $request)
//    {
//      　$user = Auth::user();
//
//        $request->validate([
//            'email' => 'required|email',
//            'password' => 'nullable|min:4|confirmed',
//        ]);
//
//        $messages = [];
//
//        // メール変更チェック
//        if ($user->email !== $request->email) {
//            $messages[] = 'メールアドレスを変更しました';
//            $user->email = $request->email;
//        }
//
//       // パスワード変更チェック
//        if ($request->filled('password')) {
//            $messages[] = 'パスワードを変更しました';
//            $user->password = Hash::make($request->password);
//        }
//
//        $user->save();
//
//        // 何も変更がなかった場合
//        if (empty($messages)) {
//            $messages[] = '変更はありませんでした';
//        }
//
//        return redirect('/shohin')->with('success', implode(' / ', $messages));
//    }

    // メール更新
    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email' => 'required|email',
        ]);

        if ($user->email === $request->email) {
            return redirect('/shohin')->with('success', '変更はありませんでした');
        }

        $old = $user->email;

        $user->email = $request->email;
        $user->save();

        return redirect('/shohin')
            ->with('success', "メールアドレスを変更しました（{$old} → {$request->email}）");
    }

    // パスワード更新
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:4|confirmed',
        ]);

        $user = Auth::user();

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/shohin')
            ->with('success', 'パスワードを変更しました');
    }

}
