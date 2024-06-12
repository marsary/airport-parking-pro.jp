<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- description -->
    <meta name="description" content="" />
    <!-- favicon -->
    <link rel="icon" href="" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap" rel="preload" as="style" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'" />

    <!-- 電卓で使用 -->
    <link href="https://fonts.googleapis.com/css?family=Reem+Kufi" rel="stylesheet">

    <!-- css -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/form/style.css') }}" rel="stylesheet">
    <!-- web slides -->
    <!-- CSS WebSlides -->
    <!-- <link rel="stylesheet" type='text/css' media='all' href="/css/webslides.css"> -->
    <!-- Optional - CSS SVG Icons (Font Awesome) -->
    <link rel="stylesheet" type='text/css' media='all' href="{{ asset('css/svg-icons.css') }}">
    @stack('css')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
  </head>
  <body>
    @include('include.form.header')
    @yield('content')
    @include('include.form.footer')

    @stack('scripts')
  </body>
</html>
