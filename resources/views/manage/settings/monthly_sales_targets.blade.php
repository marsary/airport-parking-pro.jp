<!-- L-3 売上目標設定と実績​ -->
<!DOCTYPE html>
<html lang="ja">
<?php include_once '../include/head.php'; ?>

<body>
  <div class="l-wrap">
    <?php include_once '../include/header.php'; ?>

    <?php include_once '../include/nav.php'; ?>

    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">売上目標設定と実績​</li>
      </ul>

      <div class="l-container__inner">
        <form action="" class="u-mb2">
          <div class="l-flex--end l-grid--gap1 upload c-button__csv--upload">
            <div class="c-button__register button_select">設定</div>
            <a href="" class="c-button__load link-white u-mt0">CSVダウンロード</a>
            <button type="button" class="c-button__load --gray upload u-mt0 uploadButton">CSVアップロード</button>
            <input type="file" id="csvFileInput" />
            <input type="submit" value="CSV登録" class="c-button__register register u-mb0" disabled>
          </div>
          <div id="csvFileNameDisplay" class="text-right u-mt1"></div>
        </form>

        <!-- ページネーション -->
        <div class="c-pager__pagination-container u-mb2">
          <button class="c-pager__button c-button__prev" id="prevButton" onclick="prevPage()"></button>
          <div class="c-pager__year-list-wrapper">
              <div class="c-pager__year-list" id="yearList">
                  <div class="c-pager__year-item">2015</div>
                  <div class="c-pager__year-item">2016</div>
                  <div class="c-pager__year-item">2017</div>
                  <div class="c-pager__year-item">2018</div>
                  <div class="c-pager__year-item">2019</div>
                  <div class="c-pager__year-item">2020</div>
                  <div class="c-pager__year-item">2021</div>
                  <div class="c-pager__year-item">2022</div>
                  <div class="c-pager__year-item">2023</div>
                  <div class="c-pager__year-item --selected">2024</div>
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
              <div class="p-salesTarget__item">５）前年同月の売上実績</div>
              <div class="p-salesTarget__item">６）前年同月比と同月の売上差額</div>
              <div class="p-salesTarget__item">７）前年同月比に対する達成率</div>
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
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2014年1月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
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
            <dt class="p-salesTarget__left-dttl">駐車料金</dt>
            <dd class="p-salesTarget__list">
              <div class="p-salesTarget__item">１）売上目標​</div>
              <div class="p-salesTarget__item">２）売上実績​</div>
              <div class="p-salesTarget__item">３）目標と実績の差額​</div>
              <div class="p-salesTarget__item">４）売上目標に対する達成率​</div>
              <div class="p-salesTarget__item">５）前年同月の売上実績</div>
              <div class="p-salesTarget__item">６）前年同月比と同月の売上差額</div>
              <div class="p-salesTarget__item">７）前年同月比に対する達成率</div>
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
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2014年1月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
            <button type="button" class="p-salesTarget-table__button--right"></button>
           </div>
        </div>
        <!-- 3段目 -->
        <div class="p-salesTarget__wrap">
          <!-- 左側 項目-->
          <dl class="p-salesTarget__def">
            <dt class="p-salesTarget__left-dttl">月間総売上​</dt>
            <dd class="p-salesTarget__list">
              <div class="p-salesTarget__item">１）売上目標​</div>
              <div class="p-salesTarget__item">２）売上実績​</div>
              <div class="p-salesTarget__item">３）目標と実績の差額​</div>
              <div class="p-salesTarget__item">４）売上目標に対する達成率​</div>
              <div class="p-salesTarget__item">５）前年同月の売上実績</div>
              <div class="p-salesTarget__item">６）前年同月比と同月の売上差額</div>
              <div class="p-salesTarget__item">７）前年同月比に対する達成率</div>
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
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2023年12月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                      </ul>
                    </dd>
                  </dl>
                </div>
                <div class="p-salesTarget__table">
                  <dl>
                    <dt class="p-salesTarget__right-dttl">2014年1月​</dt>
                    <dd class="p-salesTarget__right-desc">
                      <ul class="p-salesTarget__right-list">
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">15000,000</li>
                        <li class="p-salesTarget__item p-salesTarget__right-item">100%</li>
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

  <!-- モーダルスクリプト -->
  <script src="../js/modalOption.js"></script>

  <!-- カレンダー（year選択） -->
  <script src="../js/yearList.js"></script>

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
          const fileName = file.name;
          fileNameDisplay.innerHTML = `
            <span>${fileName}</span>
            <img src="../images/icon/closeButton.svg" width="15px" height="15px" alt="削除" class="pointer delete-button">
          `;
          if (uploadedFiles === 0) {
            registerButton.removeAttribute('disabled');
          }
          uploadedFiles++;

          const deleteButton = fileNameDisplay.querySelector('.delete-button');
          deleteButton.addEventListener('click', function() {
            fileInput.value = '';
            fileNameDisplay.innerHTML = '';
            uploadedFiles--;
            if (uploadedFiles === 0) {
              registerButton.setAttribute('disabled', '');
            }
          });
        } else {
          fileNameDisplay.innerHTML = '';
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
});
  </script>
</body>

</html>