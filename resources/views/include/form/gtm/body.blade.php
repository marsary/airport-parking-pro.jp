{{-- Googleタグマネージャー（<body>開始タグの直後）。GTM_ID が設定された環境（本番）のみ出力 --}}
@if (config('services.gtm.id'))
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('services.gtm.id') }}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@endif
