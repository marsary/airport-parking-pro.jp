<header class="l-wrap__header l-header">
  <!-- logo -->
  <div class="l-header-logo">
    <div class="l-header-logo__inner">
      <img src="{{ asset('images/svg/logo.svg') }}" width="100%" alt="logo">
      <p class="u-font--medium u-font--14">ダミーパーキング成田店</p>
      <p class="l-header-logo--right">
        （事業再構築　機-１ R2128E00068000）
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
          <span>2023</span><span class="u-font--28">12/25</span>
          <span class="l-header-admin-info__dayOfWeek ">火</span>
        </div>
      </div>
      <div class="l-header__left-item"><span class="c-label-header-admin-info--lg">現在時刻</span><span class="u-font--28 u-font--medium">10:10</span></div>
      <div class="l-header__left-item l-header-admin-info__open-close">
        <div>
          <span class="c-label-header-admin-info">OPEN</span><span>8:00</span>
        </div>
        <div>
          <span class="c-label-header-admin-info">CLOSE</span><span>22:00</span>
        </div>
      </div>
      <div class="l-header__left-item l-header-admin-info__loginUserName"><span class="c-label-header-admin-info--lg">担当者名</span>山田 太郎</div>
    </div>

    <!-- 入出子台数 -->
    <div class="l-header__center l-header__center-left">
      <div class="c-label__light-green u-font--green">入庫台数</div>

      <div class="l-header__center--info">
        <div>入庫予定<span class="u-font--20 u-font--medium">0000</span>/</div>
        <div class="text-right">残り<span class="u-font--20 u-font--medium">0000</span></div>
      </div>
      <!-- メーター（ゲージエリア） -->
      <div style="background: #FFF; width:100%;height:22px;display:block;margin-top:10px"></div>
    </div>
    <div class="l-header__center l-header__center-right">
      <div class="c-label__light-green u-font--green">出庫台数</div>
      <div class="l-header__center--info">
        <div>入庫予定<span class="u-font--20 u-font--medium">0000</span>/</div>
        <div class="text-right">残り<span class="u-font--20 u-font--medium">0000</span></div>
      </div>
      <!-- メーター（ゲージエリア） -->
      <div style="background: #FFF; width:100%;height:22px;display:block;margin-top:10px"></div>
    </div>

    <!-- 進捗 -->
    <div class="l-header__right">
      <dl class="l-header__right-item">
        <dt>月間売上目標</dt>
        <dd class="u-font--medium text-right">¥0- / ¥10000,000-</dd>
        <dt>駐車料金</dt>
        <dd class="u-font--medium text-right">¥1,000- / ¥1000,000-</dd>
        <dt>保険</dt>
        <dd class="u-font--medium text-right">¥100,000- / ¥100,000-</dd>
        <dt>洗車</dt>
        <dd class="u-font--medium text-right">¥00,000- / ¥50,000-</dd>
      </dl>
    </div>
  </div>
</header>
