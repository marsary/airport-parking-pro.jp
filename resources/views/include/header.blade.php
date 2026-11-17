@php
  $now = \Carbon\Carbon::now();
@endphp
<header class="l-wrap__header l-header">
  <!-- logo -->
  <div class="l-header-logo">
    <div class="l-header-logo__inner">
      <img src="{{ asset('images/logo/logo.png') }}" width="100%" alt="logo">
      <p class="u-font--medium u-font--14">{{$office->name}}</p>
      <div class="l-header-logo--right">
        <span>（事業再構築　R2128E00068000）</span>
        <!-- 歯車メニュー（パスワード変更・ログアウト） -->
        <details class="p-header-menu" id="headerMenu">
          <summary class="p-header-menu__toggle" aria-label="メニュー">
            <img src="{{ asset('images/icon/gear.svg') }}" width="14" height="14" alt="歯車">
          </summary>
          <ul class="p-header-menu__list">
            <li class="p-header-menu__user">{{ $user->name }}</li>
            <li><a href="{{ route('manage.password.edit') }}" class="p-header-menu__item">パスワード変更</a></li>
            <li>
              <form method="POST" action="{{ route('manage.logout') }}">
                @csrf
                <button type="submit" class="p-header-menu__item p-header-menu__item--logout">ログアウト</button>
              </form>
            </li>
          </ul>
        </details>
      </div>
      <style>
        .p-header-menu { position: relative; display: inline-block; margin-left: 4px; }
        .p-header-menu__toggle { list-style: none; cursor: pointer; display: flex; align-items: center; padding: 4px; border-radius: 4px; }
        .p-header-menu__toggle::-webkit-details-marker { display: none; }
        .p-header-menu__toggle:hover, .p-header-menu[open] .p-header-menu__toggle { background: rgba(255,255,255,.6); }
        .p-header-menu__list { position: absolute; right: 0; top: calc(100% + 6px); z-index: 1000; min-width: 180px; margin: 0; padding: 6px 0; list-style: none; background: #fff; border: 1px solid #d8e6e3; border-radius: 6px; box-shadow: 0 4px 14px rgba(0,0,0,.15); text-align: left; animation: headerMenuOpen .15s ease-out; }
        @keyframes headerMenuOpen { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: none; } }
        .p-header-menu__user { padding: 6px 16px 8px; font-size: 12px; color: #777; border-bottom: 1px solid #eee; margin-bottom: 4px; white-space: nowrap; }
        .p-header-menu__item { display: block; width: 100%; padding: 10px 16px; font-size: 14px; color: #333; text-decoration: none; background: none; border: 0; text-align: left; cursor: pointer; white-space: nowrap; font-family: inherit; }
        .p-header-menu__item:hover { background: #eef8f6; }
        .p-header-menu__item--logout { color: #c0392b; }
      </style>
      <script>
        // メニューの外側をクリック、または Esc キーで閉じる
        document.addEventListener('click', function (e) {
          var menu = document.getElementById('headerMenu');
          if (menu && menu.open && !menu.contains(e.target)) { menu.open = false; }
        });
        document.addEventListener('keydown', function (e) {
          var menu = document.getElementById('headerMenu');
          if (e.key === 'Escape' && menu) { menu.open = false; }
        });
      </script>
    </div>
  </div>

  <div class="l-header__wrapper">
    <!-- 4column left center center right  -->
    <div class="l-header__left">
      <div class="l-header__left-item">
        <span class="c-label-header-admin-info">日時</span>
        <div class="l-header-admin-info__date">
          <span>{{ $now->format('Y') }}</span><span class="u-font--28">{{ $now->format('m/d') }}</span>
          <span class="l-header-admin-info__dayOfWeek ">{{ $now->shortDayName }}</span>
        </div>
      </div>
      <div class="l-header__left-item"><span class="c-label-header-admin-info--lg">現在時刻</span><span class="u-font--28 u-font--medium">{{ $now->format('H:i') }}</span></div>
      <div class="l-header__left-item l-header-admin-info__open-close">
        <div>
          <span class="c-label-header-admin-info">OPEN</span><span>{{$office->start_time}}</span>
        </div>
        <div>
          <span class="c-label-header-admin-info">CLOSE</span><span>{{$office->end_time}}</span>
        </div>
      </div>
      <div class="l-header__left-item l-header-admin-info__loginUserName"><span class="c-label-header-admin-info--lg">担当者名</span>{{$user->name}}</div>
    </div>

    <!-- 入出子台数 -->
    <div class="l-header__center l-header__center-left">
      <div class="c-label__light-green u-font--green">入庫台数</div>

      <div class="l-header__center--info">
        <div>入庫予定<span class="u-font--20 u-font--medium">{{zeroPadding($stats->loadDateCount, 4)}}</span>/</div>
        <div class="text-right">残り<span class="u-font--20 u-font--medium">{{zeroPadding($stats->loadDateRemainingCount, 4)}}</span></div>
      </div>
      <!-- メーター（ゲージエリア） -->
      <div style="background: #FFF; width:100%;height:22px;display:flex;justify-content:end;margin-top:10px">
        <div style="background: #ffa869; width:{{$stats->loadCountRate()}}%;margin:1px;"></div>
      </div>
    </div>
    <div class="l-header__center l-header__center-right">
      <div class="c-label__light-green u-font--green">出庫台数</div>
      <div class="l-header__center--info">
        <div>出庫予定<span class="u-font--20 u-font--medium">{{zeroPadding($stats->unloadDateCount, 4)}}</span>/</div>
        <div class="text-right">残り<span class="u-font--20 u-font--medium">{{zeroPadding($stats->unloadDateRemainingCount, 4)}}</span></div>
      </div>
      <!-- メーター（ゲージエリア） -->
      <div style="background: #FFF; width:100%;height:22px;display:flex;justify-content:end;margin-top:10px">
        <div style="background: #ffa869; width:{{$stats->unloadCountRate()}}%;margin:1px;"></div>
      </div>
    </div>

    <!-- 進捗 -->
    <div class="l-header__right">
      <dl class="l-header__right-item">
        <dt>月間売上目標</dt>
        <dd class="u-font--medium text-right">{{$stats->rows[\App\Models\MonthlySalesTarget::TOTAL_SALES_ORDER]->renderPrices()}}</dd>
        <dt>駐車料金</dt>
        <dd class="u-font--medium text-right">{{$stats->rows[\App\Models\MonthlySalesTarget::PARKING_FEE]->renderPrices()}}</dd>
        @foreach ($stats->rows as $order => $row)
          @if (in_array($order, \App\Models\MonthlySalesTarget::GOOD_CATEGORY_ORDERS))
            <dt>{{$row->label}}</dt>
            <dd class="u-font--medium text-right">{{$row->renderPrices()}}</dd>
          @endif
        @endforeach
      </dl>
    </div>
  </div>
</header>
