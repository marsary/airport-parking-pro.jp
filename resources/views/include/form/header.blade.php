<header class="l-header-user">
  <div class="l-header-user__left">
    <img src="{{ asset('images/svg/logo.svg') }}" width="100%" alt="logo">
    <p class="is-none--sm u-font--medium u-font--14 text-center">サンパーキング成田店</p>
  </div>

  <!-- 予約情報 -->
  <h1 class="l-header-userReservation__title">受付入力</h1>
  @if ($reserve)
    <div class="l-header-userReservation__info">
      <div>利用日：{{$reserve->load_date?->isoFormat('YYYY/MM/DD(ddd)')}}〜{{$reserve->unload_date_plan?->isoFormat('YYYY/MM/DD(ddd)')}}</div>
      <div>利用料金：{{number_format($reserve->price)}}円</div>
    </div>
  @endif
</header>

<!-- マイページ等利用日・利用料金が非表示の場合レイアウトが崩れないようにするための調整が必要 -->
<!-- 上記の作業は、名ページに何を表示するか決定後作業 -->