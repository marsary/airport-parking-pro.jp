@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">
  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
    <!-- D-1-0にリンク -->
    <li class="l-breadcrumb__list"><a href="{{route('manage.ledger')}}">帳票印刷</a></li>
    <li class="l-breadcrumb__list">代理店別売上リスト</li>
  </ul>

  <div class="l-container__inner">
    <form action="{{route('manage.ledger.agency_sales_lists.download')}}" method="GET">
      <div class="u-mb2">
        <div class="l-flex l-grid--gap2 u-mb1">
          <!-- 代理店番号 桁数指示なし -->
          <label for="agency_code" class="l-flex--column l-flex--item-start l-grid--gap05">代理店番号
            <input type="text" id="agency_code" name="agency_code" value="{{old('agency_code')}}" class="u-mb0">
          </label>
          <!-- 入庫日  -->
          <label for="date_of_entry_exit" class="l-flex--column l-flex--item-start l-grid--gap05">入庫日
            <div class="l-flex l-grid--gap1">
              <!-- 年 -->
              <div class="l-flex l-grid--gap05">
                <div class="c-form-select-color u-mb0">
                  <select name="load_date_start_year" id="load_date_start_year" class="u-mb0 text-center u-w140">
                    @foreach ($years as $year)
                      <option value="{{$year}}" {{old('load_date_start_year', $currentYear)==$year ? 'selected':''}}>{{$year}}</option>
                    @endforeach

                    {{--  <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>  --}}
                  </select>
                </div>
                年
                <!-- 月 -->
                <div class="c-form-select-color u-mb0">
                  <select name="load_date_start_month" id="load_date_start_month" class="u-mb0 text-center">
                    @for ($month = 1; $month <= 12; $month++)
                        <option value="{{ $month }}" {{ old('load_date_start_month', $currentMonth) == $month ? 'selected' : '' }}>{{ $month }}</option>
                    @endfor
                  </select>
                </div>
                月
              </div>
              ～
              <div class="l-flex l-grid--gap05">
                <!-- 年 -->
                <div class="c-form-select-color u-mb0">
                  <select name="load_date_end_year" id="load_date_end_year" class="u-mb0 text-center u-w140">
                    @foreach ($years as $year)
                      <option value="{{$year}}" {{old('load_date_end_year', $currentYear)==$year ? 'selected':''}}>{{$year}}</option>
                    @endforeach
                    {{--  <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>  --}}
                  </select>
                </div>
                年
                <!-- 月 -->
                <div class="c-form-select-color u-mb0">
                  <select name="load_date_end_month" id="load_date_end_month" class="u-mb0 text-center">
                    @for ($month = 1; $month <= 12; $month++)
                        <option value="{{ $month }}" {{ old('load_date_end_month', $currentMonth) == $month ? 'selected' : '' }}>{{ $month }}</option>
                    @endfor
                  </select>
                </div>
                月
              </div>
            </div>
          </label>
        </div>

        <!-- マイル チェックボタン -->
        <div class="l-flex--column l-flex--item-start l-grid--gap05">
          マイル
          <label>
            <input type="checkbox" name="mile" value="1" {{old('mile')=="1" ? 'selected':''}} class="u-mb0">
            マイル順にソート
          </label>
        </div>
      </div>

      <input type="submit" value="CSVをダウンロード" class="c-button__submit u-w-auto u-horizontal-auto">
      <ul class="u-font--md">
        <li>・売上リストの計算方法は代理店実績表と同様です。</li>
        <li>・代理店実績表リストとは、1台単位の明細が表示される点のみ異なります。</li>
      </ul>
    </form>
  </div><!-- l-container__inner -->
</main>
@endsection
