<!-- ナビゲーション -->
<nav class="l-wrap__nav c-nav__wrap">
  <ul class="c-nav__list">
    <li class="c-nav__item">
      <a href="{{route('manage.home')}}" class="c-nav__link --reserve {{Request::routeIs('manage.home', 'manage.deals.*') ? '--current' : '' }}">取引管理</a>
    </li>
    <li class="c-nav__item c-nav__item--current">
      <a href="{{route('manage.ledger.inventories')}}" class="c-nav__link --list {{Request::routeIs('manage.ledger.inventories') ? '--current' : '' }}">入出庫一覧</a>
    </li>
    <li class="c-nav__item">
      <a href="{{route('manage.registers.index')}}" class="c-nav__link --en {{Request::routeIs('manage.registers.index') ? '--current' : '' }}">レジ</a>
    </li>
    <li class="c-nav__item">
      <a href="{{route('manage.master')}}" class="c-nav__link --setting {{Request::routeIs('manage.master', 'manage.master.*') ? '--current' : '' }}">設定</a>
    </li>
    <li class="c-nav__item">
      <a href="{{route('manage.marketing')}}" class="c-nav__link --support {{Request::routeIs('manage.marketing', 'manage.marketing.*') ? '--current' : '' }}">営業支援</a>
    </li>
    <li class="c-nav__item">
      <a href="{{route('manage.ledger')}}" class="c-nav__link --print {{Request::routeIs('manage.ledger', 'manage.ledger.*') ? '--current' : '' }}">帳票印刷</a>
    </li>
  </ul>

  <figure class="c-nav__qr">
    <img src="{{ asset('images/dummy/dummy_qr.jpg') }}" alt="" width="100%" height="auto">
    <figcaption>取引QR読込</figcaption>
  </figure>

</nav>
