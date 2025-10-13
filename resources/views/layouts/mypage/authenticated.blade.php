<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>マイページ</title>
  <!-- description -->
  <meta name="description" content="" />
  <!-- favicon -->
  <link rel="icon" href="" type="image/svg+xml">

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap" rel="preload" as="style" />
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'" />

  <!-- css -->
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <link href="{{ asset('css/mypage/style.css') }}" rel="stylesheet">
  <!-- Optional - CSS SVG Icons (Font Awesome) -->
  <link rel="stylesheet" type='text/css' media='all' href="{{ asset('css/svg-icons.css') }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    @include('include.mypage.header')
    @yield('content')
    @include('include.mypage.footer')
    @stack('scripts')
  </body>
</html>
