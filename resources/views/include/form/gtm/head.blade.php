{{-- Googleタグマネージャー（<head>内のなるべく上）。GTM_ID が設定された環境（本番）のみ出力 --}}
@if (config('services.gtm.id'))
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer',@json(config('services.gtm.id')));</script>
<!-- End Google Tag Manager -->
@endif
