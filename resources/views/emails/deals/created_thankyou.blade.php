@php

@endphp

<p>予約登録いただき誠にありがとうございます。</p>
以下の内容で受け付けました。</p>

<p>予約コード<br>
{{ $deal->reserve_code }}
</p>

<p>予約日時：<br>
{{ $deal->reserve_date?->format('Y/m/d H:i') }}
</p>

<p>代理店名：<br>
{{ $deal->agency?->name }}
</p>

<p>受付コード：<br>
{{ $deal->receipt_code }}
</p>

<p>入庫日時：<br>
{{ $deal->loadDateTime() }}
</p>

<p>出庫予定日：<br>
{{ $deal->unload_date_plan?->format("Y/m/d") }}
</p>

<p>利用日数：<br>
{{ $deal->num_days }}
</p>

<p>顧客コード：<br>
{{ $deal->member?->member_code }}
</p>

<p>お客様氏名：<br>
{{ $deal->name }}
</p>

<p>ふりがな：<br>
{{ $deal->kana }}
</p>

<p>利用回数：<br>
{{ $deal->member?->used_num }}
</p>
