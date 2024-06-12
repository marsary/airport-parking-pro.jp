@php
  $now = \Carbon\Carbon::now();
@endphp
<header class="l-wrap__header l-header">
  <!-- logo -->
  <div class="l-header-logo">
    <div class="l-header-logo__inner">
      <img src="{{ asset('images/svg/logo.svg') }}" width="100%" alt="logo">
      <p class="u-font--medium u-font--14">{{$office->name}}</p>
      <p class="l-header-logo--right">
        （事業再構築　R2128E00068000）
        <img src="{{ asset('images/icon/gear.svg') }}" width="14" height="14" alt="歯車">
      </p>
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
