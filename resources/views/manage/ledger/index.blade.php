@extends('layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">帳票印刷</li>
      </ul>

      <div class="l-container__inner">

        <!-- 文字数多い場合、レイアウト崩れ修正のためCSSを変更 -->
        <div class="l-grid--col5-1fr l-grid--rgap2 l-grid--cgap1 u-font--white">
          <button class="c-button--yellow c-link-no-border">レジ清算集計</button><!-- d-2-2.html を印刷　※作成中 -->
          <a href="d-2-11.php" class="c-button--yellow c-link-no-border">代理店別売上リスト</a>
          <a href="d-1-5.php" class="c-button--yellow c-link-no-border">代理店実績表</a>
        </div>

      </div><!-- ./l-container__inner -->
    </main>

@endsection