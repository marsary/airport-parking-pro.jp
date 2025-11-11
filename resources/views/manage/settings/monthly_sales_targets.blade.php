<!-- L-3 売上目標設定と実績​ -->
@extends('layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">売上目標設定と実績​</li>
      </ul>

      @include('include.messages.errors')
      @include('include.messages.success')

      <div class="l-container__inner">
        <form action="" class="u-mb2">
          <div class="l-flex--end l-grid--gap1 upload c-button__csv--upload">
            {{-- <div class="c-button__register button_select">設定</div> --}}
            <a href="#" id="csvDownloadLink" class="c-button__load link-white u-mt0">CSVダウンロード</a>
            <button type="button" class="c-button__load --gray upload u-mt0 uploadButton">CSVアップロード</button>
            {{-- <input type="submit" value="CSV登録" class="c-button__register register u-mb0" disabled> --}}
          </div>
          <div id="csvFileNameDisplay" class="text-right u-mt1"></div>
        </form>

        <form id="csv-upload-form" action="{{route('manage.monthly_sales_targets.upload')}}" method="POST" enctype="multipart/form-data">
          @method('POST')
          @csrf
          <div class="c-button__csv--upload">
            <input type="file" name="csvFileInput" id="csvFileInput" />
          </div>
        </form>

        <!-- ページネーション -->
        <div class="c-pager__pagination-container u-mb2">
          <button class="c-pager__button c-button__prev" id="prevButton" onclick="prevPage()"></button>
          <div class="c-pager__year-list-wrapper">
              <div class="c-pager__year-list" id="yearList">
                  {{--  <div class="c-pager__year-item">2020</div>
                  <div class="c-pager__year-item">2021</div>
                  <div class="c-pager__year-item">2022</div>
                  <div class="c-pager__year-item">2023</div>
                  <div class="c-pager__year-item">2024</div>
                  <div class="c-pager__year-item --selected">2025</div>
                  <div class="c-pager__year-item">2026</div>
                  <div class="c-pager__year-item">2027</div>  --}}
                  @foreach ($yearList as $year)
                    <div class="c-pager__year-item  {{ $year == (\Carbon\Carbon::today()->year) ? '--selected' : '' }}">{{$year}}</div>
                  @endforeach
              </div>
          </div>
          <button class="c-pager__button c-button__next" id="nextButton" onclick="nextPage()"></button>
        </div>

        <!-- 1段目 -->
        <div class="p-salesTarget__wrap">
          <!-- 左側 項目-->
          <dl class="p-salesTarget__def">
            <dt class="p-salesTarget__left-dttl">月間総売上​</dt>
            <dd class="p-salesTarget__list">
              <div class="p-salesTarget__item">１）売上目標​</div>
              <div class="p-salesTarget__item">２）売上実績​</div>
              <div class="p-salesTarget__item">３）目標と実績の差額​</div>
              <div class="p-salesTarget__item">４）売上目標に対する達成率​</div>
              {{-- <div class="p-salesTarget__item">５）前年同月の売上実績</div>
              <div class="p-salesTarget__item">６）前年同月比と同月の売上差額</div>
              <div class="p-salesTarget__item">７）前年同月比に対する達成率</div> --}}
            </dd>
          </dl>

          <!-- 右側 テーブル -->
          <!-- .--selectedで強調css付与 -->
           <div class="p-salesTarget-table__wrap">
            <button type="button" class="p-salesTarget-table__button--left"></button>
            <div class="p-salesTarget-table__container">
              <div class="p-salesTarget-table__inner">
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年1月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="1" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="1" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="1" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="1" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年2月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="2" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="2" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="2" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="2" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年3月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="3" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="3" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="3" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="3" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年4月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="4" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="4" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="4" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="4" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年5月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="5" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="5" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="5" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="5" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年6月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="6" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="6" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="6" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="6" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年7月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="7" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="7" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="7" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="7" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年8月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="8" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="8" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="8" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="8" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年9月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="9" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="9" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="9" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="9" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年10月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="10" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="10" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="10" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="10" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年11月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="11" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="11" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="11" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="11" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="12" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="12" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="12" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="total_sales" data-month="12" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
            <button type="button" class="p-salesTarget-table__button--right"></button>
           </div>
        </div>

        <!-- 2段目 -->
        <div class="p-salesTarget__wrap">
          <!-- 左側 項目-->
          <dl class="p-salesTarget__def">
            <dt class="p-salesTarget__left-dttl" data-table-title="parking_fee">駐車料金</dt>
            <dd class="p-salesTarget__list">
              <div class="p-salesTarget__item">１）売上目標​</div>
              <div class="p-salesTarget__item">２）売上実績​</div>
              <div class="p-salesTarget__item">３）目標と実績の差額​</div>
              <div class="p-salesTarget__item">４）売上目標に対する達成率​</div>
              {{-- <div class="p-salesTarget__item">５）前年同月の売上実績</div>
              <div class="p-salesTarget__item">６）前年同月比と同月の売上差額</div>
              <div class="p-salesTarget__item">７）前年同月比に対する達成率</div> --}}
            </dd>
          </dl>

          <!-- 右側 テーブル -->
          <!-- .--selectedで強調css付与 -->
           <div class="p-salesTarget-table__wrap">
            <button type="button" class="p-salesTarget-table__button--left"></button>
            <div class="p-salesTarget-table__container">
              <div class="p-salesTarget-table__inner">
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年1月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="1" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="1" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="1" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="1" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年2月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="2" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="2" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="2" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="2" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年3月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="3" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="3" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="3" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="3" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年4月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="4" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="4" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="4" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="4" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年5月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="5" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="5" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="5" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="5" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年6月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="6" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="6" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="6" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="6" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年7月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="7" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="7" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="7" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="7" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年8月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="8" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="8" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="8" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="8" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年9月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="9" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="9" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="9" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="9" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年10月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="10" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="10" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="10" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="10" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年11月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="11" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="11" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="11" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="11" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="12" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="12" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="12" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="parking_fee" data-month="12" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
            <button type="button" class="p-salesTarget-table__button--right"></button>
           </div>
        </div>

        <!-- 3段目(商品カテゴリ1) -->
        <div class="p-salesTarget__wrap">
          <!-- 左側 項目-->
          <dl class="p-salesTarget__def">
            <dt class="p-salesTarget__left-dttl" data-table-title="good_category_1">商品カテゴリー１月間総売上​</dt>
            <dd class="p-salesTarget__list">
              <div class="p-salesTarget__item">１）売上目標​</div>
              <div class="p-salesTarget__item">２）売上実績​</div>
              <div class="p-salesTarget__item">３）目標と実績の差額​</div>
              <div class="p-salesTarget__item">４）売上目標に対する達成率​</div>
              {{-- <div class="p-salesTarget__item">５）前年同月の売上実績</div>
              <div class="p-salesTarget__item">６）前年同月比と同月の売上差額</div>
              <div class="p-salesTarget__item">７）前年同月比に対する達成率</div> --}}
            </dd>
          </dl>

          <!-- 右側 テーブル -->
          <!-- .--selectedで強調css付与 -->
           <div class="p-salesTarget-table__wrap">
            <button type="button" class="p-salesTarget-table__button--left"></button>
            <div class="p-salesTarget-table__container">
              <div class="p-salesTarget-table__inner">
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年1月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="1" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="1" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="1" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="1" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年2月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="2" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="2" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="2" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="2" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年3月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="3" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="3" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="3" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="3" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年4月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="4" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="4" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="4" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="4" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年5月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="5" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="5" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="5" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="5" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年6月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="6" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="6" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="6" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="6" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年7月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="7" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="7" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="7" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="7" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年8月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="8" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="8" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="8" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="8" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年9月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="9" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="9" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="9" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="9" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年10月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="10" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="10" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="10" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="10" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年11月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="11" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="11" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="11" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="11" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="12" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="12" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="12" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_1" data-month="12" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
            <button type="button" class="p-salesTarget-table__button--right"></button>
           </div>
        </div>

        <!-- 4段目(商品カテゴリ2) -->
        <div class="p-salesTarget__wrap">
          <!-- 左側 項目-->
          <dl class="p-salesTarget__def">
            <dt class="p-salesTarget__left-dttl" data-table-title="good_category_2">商品カテゴリー２月間総売上​</dt>
            <dd class="p-salesTarget__list">
              <div class="p-salesTarget__item">１）売上目標​</div>
              <div class="p-salesTarget__item">２）売上実績​</div>
              <div class="p-salesTarget__item">３）目標と実績の差額​</div>
              <div class="p-salesTarget__item">４）売上目標に対する達成率​</div>
              {{-- <div class="p-salesTarget__item">５）前年同月の売上実績</div>
              <div class="p-salesTarget__item">６）前年同月比と同月の売上差額</div>
              <div class="p-salesTarget__item">７）前年同月比に対する達成率</div> --}}
            </dd>
          </dl>

          <!-- 右側 テーブル -->
          <!-- .--selectedで強調css付与 -->
           <div class="p-salesTarget-table__wrap">
            <button type="button" class="p-salesTarget-table__button--left"></button>
            <div class="p-salesTarget-table__container">
              <div class="p-salesTarget-table__inner">
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年1月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="1" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="1" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="1" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="1" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年2月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="2" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="2" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="2" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="2" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年3月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="3" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="3" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="3" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="3" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年4月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="4" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="4" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="4" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="4" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年5月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="5" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="5" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="5" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="5" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年6月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="6" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="6" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="6" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="6" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年7月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="7" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="7" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="7" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="7" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年8月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="8" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="8" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="8" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="8" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年9月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="9" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="9" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="9" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="9" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年10月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="10" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="10" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="10" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="10" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年11月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="11" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="11" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="11" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="11" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="12" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="12" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="12" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_2" data-month="12" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
            <button type="button" class="p-salesTarget-table__button--right"></button>
           </div>
        </div>

        <!-- 5段目(商品カテゴリ3) -->
        <div class="p-salesTarget__wrap">
          <!-- 左側 項目-->
          <dl class="p-salesTarget__def">
            <dt class="p-salesTarget__left-dttl" data-table-title="good_category_3">商品カテゴリー３月間総売上​</dt>
            <dd class="p-salesTarget__list">
              <div class="p-salesTarget__item">１）売上目標​</div>
              <div class="p-salesTarget__item">２）売上実績​</div>
              <div class="p-salesTarget__item">３）目標と実績の差額​</div>
              <div class="p-salesTarget__item">４）売上目標に対する達成率​</div>
              {{-- <div class="p-salesTarget__item">５）前年同月の売上実績</div>
              <div class="p-salesTarget__item">６）前年同月比と同月の売上差額</div>
              <div class="p-salesTarget__item">７）前年同月比に対する達成率</div> --}}
            </dd>
          </dl>

          <!-- 右側 テーブル -->
          <!-- .--selectedで強調css付与 -->
           <div class="p-salesTarget-table__wrap">
            <button type="button" class="p-salesTarget-table__button--left"></button>
            <div class="p-salesTarget-table__container">
              <div class="p-salesTarget-table__inner">
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年1月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="1" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="1" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="1" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="1" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年2月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="2" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="2" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="2" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="2" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年3月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="3" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="3" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="3" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="3" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年4月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="4" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="4" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="4" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="4" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年5月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="5" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="5" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="5" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="5" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年6月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="6" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="6" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="6" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="6" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年7月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="7" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="7" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="7" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="7" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年8月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="8" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="8" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="8" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="8" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年9月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="9" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="9" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="9" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="9" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年10月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="10" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="10" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="10" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="10" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年11月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="11" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="11" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="11" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="11" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="12" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="12" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="12" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_3" data-month="12" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
            <button type="button" class="p-salesTarget-table__button--right"></button>
           </div>
        </div>

        <!-- 6段目(商品カテゴリ4) -->
        <div class="p-salesTarget__wrap">
          <!-- 左側 項目-->
          <dl class="p-salesTarget__def">
            <dt class="p-salesTarget__left-dttl" data-table-title="good_category_4">商品カテゴリー４月間総売上​</dt>
            <dd class="p-salesTarget__list">
              <div class="p-salesTarget__item">１）売上目標​</div>
              <div class="p-salesTarget__item">２）売上実績​</div>
              <div class="p-salesTarget__item">３）目標と実績の差額​</div>
              <div class="p-salesTarget__item">４）売上目標に対する達成率​</div>
              {{-- <div class="p-salesTarget__item">５）前年同月の売上実績</div>
              <div class="p-salesTarget__item">６）前年同月比と同月の売上差額</div>
              <div class="p-salesTarget__item">７）前年同月比に対する達成率</div> --}}
            </dd>
          </dl>

          <!-- 右側 テーブル -->
          <!-- .--selectedで強調css付与 -->
           <div class="p-salesTarget-table__wrap">
            <button type="button" class="p-salesTarget-table__button--left"></button>
            <div class="p-salesTarget-table__container">
              <div class="p-salesTarget-table__inner">
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年1月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="1" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="1" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="1" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="1" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年2月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="2" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="2" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="2" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="2" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年3月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="3" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="3" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="3" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="3" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年4月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="4" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="4" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="4" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="4" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年5月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="5" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="5" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="5" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="5" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年6月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="6" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="6" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="6" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="6" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年7月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="7" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="7" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="7" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="7" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年8月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="8" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="8" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="8" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="8" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年9月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="9" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="9" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="9" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="9" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年10月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="10" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="10" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="10" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="10" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年11月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="11" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="11" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="11" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="11" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2025年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="12" data-row="target">-</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="12" data-row="result">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="12" data-row="diff">0</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item" data-table="good_category_4" data-month="12" data-row="achievement_rate">-</li>
                        {{-- <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li> --}}
                      </ul>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
            <button type="button" class="p-salesTarget-table__button--right"></button>
           </div>
        </div>

      </div><!-- ./l-container__inner -->

      <!-- サイド固定ボタン -->
      <button onclick="window.print(); return false;" class="c-button__right-fixed--gray"><img src="../images/icon/print.svg" width="30" height="32" /></button>

    </main>
  </div>
    <!-- オプションをクリックしたら出てくるmodal -->
    <div id="modalAreaOption" class="l-modal">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner l-modal__inner l-modal--trash">
      <div class="l-modal__head">編集</div>
      <!-- close button -->
      <div class="l-modal__close modal_optionClose">×</div>
      <div class="l-modal__content">
      <form class="l-flex--column l-flex--column l-flex--sb u-w-full">
        <div class="u-w-full-wide">
          <div class="c-title__modal--lv3">2024年03月​売上目標設定</div>
          <div>
            <!-- 1列目 -->
             <div class="l-grid--col3 l-grid--cgap1 l-grid--sales-target-setting">
               <div class="l-grid--sales-target-setting__category">
                 月間総売上
               </div>
               <div class="l-grid--col3 l-grid--gap05 u-font-nowrap">
                 売上目標
                 <input type="text">円
               </div>
               <div class="l-grid--col3 l-grid--gap05 u-font-nowrap">
                 前年同月比売上
                 <input type="text">円
               </div>
             </div>
            <!-- 2列目 -->
            <div class="l-grid--col3 l-grid--cgap1 l-grid--sales-target-setting">
              <div class="l-grid--sales-target-setting__category">
                駐車料金
              </div>
              <div class="l-grid--col3 l-grid--gap05 u-font-nowrap">
                売上目標
                <input type="text">円
              </div>
              <div class="l-grid--col3 l-grid--gap05 u-font-nowrap">
                前年同月比売上
                <input type="text">円
              </div>
            </div>
            <!-- 3列目 -->
            <div class="l-grid--col3 l-grid--cgap1 l-grid--sales-target-setting">
              <div>
                <div class="l-grid--sales-target-setting__category">
                  商品カテゴリー1:
                </div>
                  洗車
              </div>
              <div class="l-grid--col3 l-grid--gap05 u-font-nowrap">
                売上目標
                <input type="text">円
              </div>
              <div class="l-grid--col3 l-grid--gap05 u-font-nowrap">
                前年同月比売上
                <input type="text">円
              </div>
            </div>
            <!-- 4列目 -->
            <div class="l-grid--col3 l-grid--cgap1 l-grid--sales-target-setting">
              <div>
                <div class="l-grid--sales-target-setting__category">
                  商品カテゴリー2:
                </div>
                  洗車
              </div>
              <div class="l-grid--col3 l-grid--gap05 u-font-nowrap">
                売上目標
                <input type="text">円
              </div>
              <div class="l-grid--col3 l-grid--gap05 u-font-nowrap">
                前年同月比売上
                <input type="text">円
              </div>
            </div>
          </div>
        <div class="l-flex--center l-grid--gap1 u-mt2 u-mb2">
          <button type="button" class="c-button__submit--dark-gray modal_optionClose">閉じる</button>
          <button type="submit" id="modal_add" class="c-button__submit">上書き保存</button>
        </div>
      </form>

      </div><!-- ./l-modal__content -->

    </div><!-- ./l-modal inner -->
    <!-- 閉じる・追加ボタン -->
  </div>
@endsection
@push("scripts")
  <!-- モーダルスクリプト -->
  <script src="{{ asset('js/modalOption.js') }}"></script>

  <!-- カレンダー（year選択） -->
  <script src="{{ asset('js/yearList.js') }}"></script>

  <!-- ファイルアップロードの時スクリプト -->
  <script>
    let uploadedFiles = 0;

    function setupForm(itemClass, fileInputId, fileNameDisplayId) {
      const item = document.querySelector(itemClass);
      const fileInput = document.getElementById(fileInputId);
      const fileNameDisplay = document.getElementById(fileNameDisplayId);
      const uploadButton = item.querySelector('.uploadButton');
      const registerButton = document.querySelector('.register');

      uploadButton.addEventListener('click', () => {
        fileInput.click();
      });

      fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
          const uploadForm = document.getElementById('csv-upload-form');
          uploadForm.submit();
          // const fileName = file.name;
          // fileNameDisplay.innerHTML = `
          //   <span>${fileName}</span>
          //   <img src="../images/icon/closeButton.svg" width="15px" height="15px" alt="削除" class="pointer delete-button">
          // `;
          // if (uploadedFiles === 0) {
          //   registerButton.removeAttribute('disabled');
          // }
          // uploadedFiles++;

          // const deleteButton = fileNameDisplay.querySelector('.delete-button');
          // deleteButton.addEventListener('click', function() {
            fileInput.value = '';
            fileNameDisplay.innerHTML = '';
            uploadedFiles--;
            if (uploadedFiles === 0) {
              registerButton.setAttribute('disabled', '');
            }
          // });
        } else {
          //fileNameDisplay.innerHTML = '';
        }
      });
    }
    setupForm('.upload', 'csvFileInput', 'csvFileNameDisplay');
  </script>

  <!-- 表をずらすスクリプト -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
  const wrappers = document.querySelectorAll('.p-salesTarget__wrap');

  wrappers.forEach(wrapper => {
    const inner = wrapper.querySelector('.p-salesTarget-table__inner');
    const leftButton = wrapper.querySelector('.p-salesTarget-table__button--left');
    const rightButton = wrapper.querySelector('.p-salesTarget-table__button--right');
    let currentPosition = 0;

    if (!inner || !leftButton || !rightButton) {
      console.error('Required elements not found', wrapper);
      return;
    }

    function updateButtons() {
      leftButton.disabled = currentPosition >= 0;
      rightButton.disabled = currentPosition <= -(inner.scrollWidth - inner.clientWidth);
    }

    function scroll(direction) {
      const scrollAmount = 100 * direction;
      currentPosition += scrollAmount;

      // スクロール位置の制限
      const maxScroll = -(inner.scrollWidth - inner.clientWidth);
      currentPosition = Math.max(maxScroll, Math.min(0, currentPosition));

      console.log('Applying transform:', `translateX(${currentPosition}px)`);
      inner.style.setProperty('transform', `translateX(${currentPosition}px)`, 'important');
      updateButtons();
    }

    leftButton.addEventListener('click', () => {
      console.log('Left button clicked');
      scroll(1);
    });

    rightButton.addEventListener('click', () => {
      console.log('Right button clicked');
      scroll(-1);
    });

    // 初期状態でボタンの状態を更新
    updateButtons();
  });

  // --- データロードとテーブル更新 ---
  const loadUrl = "{{ route('manage.monthly_sales_targets.load_tables') }}";

  // 数値をフォーマットする関数
  const formatNumber = (num) => {
    if (typeof num !== 'number') return num;
    return new Intl.NumberFormat('ja-JP').format(num);
  };

  // テーブルを更新する関数
  const updateTables = (data, year) => {
    // 各テーブルの列見出しの年を更新
    const tableHeaders = document.querySelectorAll('.p-salesTarget__right-dttl');
    tableHeaders.forEach(header => {
      // e.g. "2025年1月" -> "1月"
      const monthText = header.textContent.trim().split('年')[1];
      if (monthText) {
        header.textContent = `${year}年${monthText}`;
      }
    });
    // 各テーブルのデータを更新
    Object.keys(data).forEach(tableKey => {
      const tableData = data[tableKey];
      for (let month = 1; month <= 12; month++) {
        if (tableData.target[month] !== undefined) {
          document.querySelector(`[data-table="${tableKey}"][data-month="${month}"][data-row="target"]`).textContent = formatNumber(tableData.target[month]);
        }
        if (tableData.result[month] !== undefined) {
          document.querySelector(`[data-table="${tableKey}"][data-month="${month}"][data-row="result"]`).textContent = formatNumber(tableData.result[month]);
        }
        if (tableData.difference[month] !== undefined) {
          document.querySelector(`[data-table="${tableKey}"][data-month="${month}"][data-row="diff"]`).textContent = formatNumber(tableData.difference[month]);
        }
        if (tableData.achievement_rate[month] !== undefined) {
          document.querySelector(`[data-table="${tableKey}"][data-month="${month}"][data-row="achievement_rate"]`).textContent = tableData.achievement_rate[month];
        }
      }
    });
  };

  // データを非同期でロードする関数
  const loadTableData = async (year) => {
    try {
      const data = await apiRequest.get(loadUrl, { year: year });
      updateTables(data, year);
    } catch (error) {
      console.error('Failed to load table data:', error);
      // エラー発生時にユーザーに通知する処理などをここに追加できます
    }
  };

  // 年ページャーのクリックイベントに処理をフック
  const yearItems = document.querySelectorAll('.c-pager__year-item');
  yearItems.forEach(item => {
    item.addEventListener('click', function() {
      // yearList.jsによって --selected クラスが付与された後で実行されるように少し遅延させる
      setTimeout(() => {
        const selectedYearEl = document.querySelector('.c-pager__year-item.--selected');
        if (selectedYearEl) {
          const selectedYear = selectedYearEl.textContent;
          loadTableData(selectedYear);
        }
      }, 10);
    });
  });

  // 初期表示時のデータロード
  const initialSelectedYearEl = document.querySelector('.c-pager__year-item.--selected');
  if (initialSelectedYearEl) {
    const initialYear = initialSelectedYearEl.textContent;
    loadTableData(initialYear);
  }

  // --- CSVダウンロード ---
  const downloadLink = document.getElementById('csvDownloadLink');
  if (downloadLink) {
    downloadLink.addEventListener('click', function(e) {
      e.preventDefault();
      const selectedYearEl = document.querySelector('.c-pager__year-item.--selected');
      if (selectedYearEl) {
        const selectedYear = selectedYearEl.textContent.trim();
        const downloadUrl = new URL("{{ route('manage.monthly_sales_targets.download') }}");
        downloadUrl.searchParams.append('year', selectedYear);
        window.location.href = downloadUrl.toString();
      }
    });
  }

});
  </script>
@endpush
