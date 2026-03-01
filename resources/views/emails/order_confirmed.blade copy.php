<h2>ご注文ありがとうございます</h2>

<p>注文番号：{{ $kihon->kihonno }}</p>
<p>注文日：{{ $kihon->shoridate }}</p>

<table border="1" cellpadding="5">
    <tr>
        <th>商品名</th>
        <th>数量</th>
        <th>単価</th>
    </tr>

    @foreach($meisaiList as $m)
    <tr>
        <td>{{ $m->shohin->shohinname2 }}</td>
        <td>{{ $m->suryo }}</td>
        <td>{{ number_format($m->tanka) }}円</td>
    </tr>
    @endforeach
</table>

<p>以上、ご確認ください。</p>
