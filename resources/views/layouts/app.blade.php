<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <title>@yield('title', '注文アプリ')</title>
    <style>
        body {
            font-family: "Meiryo", "Hiragino Kaku Gothic Pro", sans-serif;
            /*background: linear-gradient(135deg, #dff3ff, #f5fbff);*/
            background: linear-gradient(135deg, #dffff8, #f5fffc);
            margin: 0;
        }

        header{
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        header {
            /*background: #4aa3df;*/
            background: #ffffff;
            color: #000000;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-logo img{
            height:50px;
            width:auto;
        }

        .header-user{
            display:flex;
            align-items:center;
            gap:15px;
        }

        .header-buttons{
            display:flex;
            gap:10px;
        }

        .header-buttons form{
            display:flex;
            margin:0;
        }

        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 80px);
        }

        .login-card {
            background: #fff;
            width: 300px;
            padding: 5px 30px 30px 20px; /* 上 右 下 左 */
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,.15);
            text-align: center;
        }

        .login-title {
            color: #4adfa6;
            margin-bottom: 10px;
            font-size: 26px;
        }

        .login-sub {
            font-size: 18px;
            color: #777;
            margin-bottom: 1px;
        }

        .login-field {
            text-align: left;
            margin-bottom: 30px;
        }

        .login-field label {
            font-size: 18px;
            color: #555;
        }

        .login-field input {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-top: 4px;
            font-size: 25px;
        }

        .login-field input:focus {
            outline: none;
            border-color: #4adfa6;
            box-shadow: 0 0 0 2px rgba(74,163,223,.2);
        }

.login-btn {
    width: 100%;
    padding: 14px;
    background: #4adfa6;
    border: none;
    color: #fff;
    border-radius: 8px;
    font-size: 18px;
    font-weight: bold;
}

        .login-btn:hover {
            background: #45c39a;
        }

        .login-error {
            background: #ffecec;
            color: #c0392b;
            padding: 8px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .page-header {
            margin-bottom: 15px;
        }

        .page-header h2 {
            color: #4adfa6;
            margin-bottom: 4px;
        }

        .page-header p {
            color: #666;
            font-size: 14px;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-table th,
        .product-table td {
            text-align: left;
        }

         /*   
        .product-table th:nth-child(5),
        .product-table th:nth-child(6),
        .product-table td:nth-child(5),
        .product-table td:nth-child(6) {
            text-align: center;
        }
        */
        .product-table th:nth-child(5) {
            padding-left: 30px;
        }

        .product-table th {
            background: #eaf6ff;
            color: #333;
        }
        
        .product-table th {
            padding-left: 10px;
        }
        

        .product-table tr:hover {
            background: #f5fbff;
        }

        .product-table input[type=number] {
            width: 60px;
            padding: 4px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .qty-form {
            display: inline;
        }

        .alert-error {
            background: #ffecec;
            color: #c0392b;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .alert-success {
            background: #eafaf1;
            color: #27ae60;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .stock-low {
            background: #fff6e5;
        }

        .stock-zero {
            background: #fdecea;
            color: #c0392b;
        }

        .sold-out {
            color: #c0392b;
            font-weight: bold;
        }

        .content-container {
            max-width: 1100px;     /* 横幅の上限 */
            margin: 0 auto;       /* 中央寄せ */
            padding: 0 20px;      /* 左右余白 */
        }

        .product-table {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        .card {
            background: rgba(255,255,255,0.9);
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(74,163,223,.18);
        }

        .product-table {
            border-collapse: separate;
            border-spacing: 0 8px; /* 行の間に隙間 */
        }

        .product-table tr {
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
        }

        .product-table th {
            background: transparent;
            border: none;
            /*color: #4aa3df;*/
            color: #4adfa6;
            font-weight: bold;
        }

        .product-table td {
            border: none;
            padding: 10px 8px;
            vertical-align: middle;
        }

        .product-name {
            font-weight: 600;
            font-size: 15px;
        }

        .product-no {
            font-size: 12px;
            color: #888;
        }

        .product-table input[type=number] {
            width: 70px;
            padding: 6px;
            text-align: center;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .product-table input[type=number]:focus {
            border-color: #4adfa6;
            box-shadow: 0 0 0 2px rgba(74,163,223,.2);
        }

        .btn-add {
            /*background: linear-gradient(135deg, #4aa3df, #3498db);*/
            background: linear-gradient(135deg, #4adfa6, #4adfa6);
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(74,163,223,.4);
            /*追加*/
            transition:all .15s ease;

            display:inline-flex;
            align-items:center;
            justify-content:center;

            line-height:1;
        }

        .btn-add:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-action.btn-logout{
            /*background:linear-gradient(135deg,#e74c3c,#c0392b);*/
            /*box-shadow:0 3px 8px rgba(231,76,60,.4);*/
            background: linear-gradient(135deg,#f5f5f5,#e0e0e0);
            color:#555;
            border:1px solid #d0d0d0;
        }

        .page-header h2 {
            font-size: 22px;
            color: #000000;
            /*color: #4aa3df;*/
        }

        .page-header p {
            color: #666;
        }

        .page-header-wrapper {
            max-width: 1100px;
            margin: 0 auto 10px;
            padding: 0 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header-left h2 {
            margin: 0;
        }

        .page-header-left p {
            margin: 4px 0 0;
        }

        .qty-input {
            font-size: 20px;
            font-weight: 600;
            text-align: center;
            padding: 6px;
        }

        .badge-success {
            background:#28a745;
            color:white;
            padding:4px 8px;
            border-radius:12px;
        }

        .badge-wait {
            background:#ccc;
            color:#333;
            padding:4px 8px;
            border-radius:12px;
        }

        
        /* =========================
        共通ボタン（追加ボタン系）
        ========================= */
        /*.btn-action {
            background: linear-gradient(135deg, #4adfa6, #4adfa6);
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(74,163,223,.4);
            transition: all .15s ease;
        }*/
.btn-action{
    background: linear-gradient(135deg,#4adfa6,#4adfa6);
    color:#fff;
    border:none;

    padding:0 16px;
    height:36px;

    border-radius:20px;
    font-size:13px;
    cursor:pointer;

    display:flex;
    align-items:center;
    justify-content:center;

    box-shadow:0 3px 8px rgba(74,163,223,.4);
}

        .btn-action:hover {
            opacity: 0.7;
            transform: translateY(-1px);
        }

        /* 削除 */
        .btn-danger-action {
            /*background: linear-gradient(135deg, #e74c3c, #c0392b);*/
            background: linear-gradient(135deg,#e57373,#d32f2f);
            /*box-shadow: 0 3px 8px rgba(231,76,60,.4);*/
            border:none;
            color:#fff;
            font-size: 12px;     /* 文字サイズを小さく */
            padding: 5px 10px;   /* ボタンの余白を小さく */
            border-radius: 20px;
            line-height:1.2;
            
        }

        /* 注文確定 */
        .btn-success-action {
            background: linear-gradient(135deg, #4adfa6, #4adfa6);
            box-shadow: 0 3px 8px rgba(46,204,113,.4);
        }

        .header-buttons {
            display: flex;
            gap: 10px;
        }

.qty-box{
    display:flex;
    align-items:center;
    gap:6px;
}

.qty-input{
    width:70px;
    height:34px;
    text-align:center;
    font-size:16px;
}

.qty-plus,
.qty-minus{
    width:34px;
    height:34px;
    border:1px solid #ccc;
    background:#f5f5f5;
    border-radius:6px;
    font-size:18px;
    cursor:pointer;
}

/* =========================
   スマホ最適化
========================= */
@media screen and (max-width: 768px) {

    header {
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }

    .header-logo img{
        height:35px;
    }

    .header-user{
        display:flex;
        flex-direction:column;
        align-items:flex-start;
        gap:4px;
    }

    .header-buttons{
        display:flex;
        gap:10px;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .page-header a {
        align-self: flex-end;
    }

    .content-container {
        padding: 0 10px;
    }

    /* テーブルをカード化 */
    .product-table,
    .product-table thead,
    .product-table tbody,
    .product-table th,
    .product-table td,
    .product-table tr {
        display: block;
    }

    .product-table tr {
        margin-bottom: 12px;
        border-radius: 12px;
        padding: 10px;
    }

    .product-table th {
        display: none;
    }

    .product-table td {
        display: flex;
        justify-content: space-between;
        padding: 6px 4px;
        font-size: 14px;
    }

    .product-table td::before {
        content: attr(data-label);
        font-weight: bold;
        /*color: #4aa3df;*/
        color: #4adfa6;
        padding-right: 10px;
    }

    .product-name {
        font-size: 16px;
    }

    /* 数量入力 */
    .product-table input[type=number] {
        width: 80px;
    }

    /* ボタンは押しやすく */
    .btn-add,
    .btn-danger,
    .btn-success {
        width: 100%;
        margin-top: 6px;
        padding: 10px;
        font-size: 14px;
        height:30px;
    }

    .btn-success-action{
        font-size:18px;
        padding:12px 28px;
    }

    /*
    .btn-action{
        white-space:nowrap;
        display:inline-flex;
        align-items:center;
        height:32px;
        padding:6px 14px;
    }
    */

    /* 合計行 */
    .product-table tr:last-child td {
        font-weight: normal;
    }

    /* 数量＋追加ボタンを横並び */
    .qty-add-wrap {
        display: flex;
        align-items: center;
        gap: 8px;           /* 数量とボタンの間隔 */
    }

    /* 単位表示 */
    .qty-add-wrap .unit {
        font-size: 13px;
        color: #666;
    }

    /* ボタンを数量寄りに */
    .qty-add-wrap .btn-add {
        margin-left: 4px;
        padding: 6px 12px;
    }

    /* 合計行は通常表示に戻す */
    .total-row {
        display: table-row;
        background: #eaf6ff;
        box-shadow: none;
    }

    .total-row th,
    .total-row td {
        display: table-cell;
        font-weight: bold;
        font-size: 16px;
        padding: 10px;
    }

    .total-row th::before,
    .total-row td::before {
        content: none;
    }
   
    .product-table {
        border-collapse: collapse;
    }

    .product-table tr {
        margin: 0 0 12px 0;
    }
    
    .product-table {
        border-spacing: 0 !important;
    }

    .product-table tbody tr:first-child {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
    
    .page-header-wrapper {
        margin-bottom: 0;
    }

    .content-container {
        padding-top: 0;
    }

    .card {
        padding-top: 0;
        padding: 0 12px 12px;  /* 上だけ0、左右下は残す */
        box-shadow: none;
    }
    
}

    </style>
</head>
<body>

<header>
    <div class="header-logo">
        <img src="{{ asset('images/logo.png') }}" alt="logo">
    </div>

    @auth
        <div class="header-user">

            <div class="user-name">
                {{ auth()->user()->hsid }}（{{ auth()->user()->name2 }}）
            </div>

            <div class="header-buttons">
                <a href="/account" class="btn-action">⚙ アカウント設定</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn-action btn-logout">ログアウト</button>
                </form>
            </div>

        </div>
    @endauth
</header>

<main>
    @yield('content')
</main>

<script>

document.querySelectorAll('.qty-box').forEach(box => {

    const input = box.querySelector('.qty-input');
    const plus = box.querySelector('.qty-plus');
    const minus = box.querySelector('.qty-minus');

    plus.addEventListener('click', () => {
        input.stepUp();
    });

    minus.addEventListener('click', () => {
        input.stepDown();
    });

});

</script>

</body>
</html>
