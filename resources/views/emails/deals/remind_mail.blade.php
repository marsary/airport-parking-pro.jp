<h1>リマインドメール</h1>

{{ $deal->name ?? '利用者' }} 様

<p>この度は、弊社のサンパーキング予約サービスをご利用いただき、誠にありがとうございます。
予約内容を以下に掲載しますので、再度ご確認ください。</p>

<h2>■予約詳細■</h2>

<p>予約ID: {{ $deal->id }}</p>

<p>ご不明な点がございましたら、お気軽にお問い合わせください。</p>


<p>
----------------------------- <br />
AIRPORT PARKING PRO <br />
{{ $office->name }} <br />
-----------------------------
</p>
