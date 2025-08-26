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
          <a href="{{route('manage.ledger.regi_sales_account_books')}}" class="c-button--yellow c-link-no-border">レジ売上帳</a>
          <a href="{{route('manage.ledger.regi_check_lists')}}" class="c-button--yellow c-link-no-border">レジ点検表</a>
          <a href="{{route('manage.ledger.regi_payment_summaries')}}" class="c-button--yellow c-link-no-border">レジ清算集計</a>
          <a href="{{route('manage.ledger.agency_sales_lists')}}" class="c-button--yellow c-link-no-border">代理店別売上リスト</a>
          <a href="{{route('manage.ledger.agency_records')}}" class="c-button--yellow c-link-no-border">代理店実績表</a>
        </div>

      </div><!-- ./l-container__inner -->
    </main>

@endsection
