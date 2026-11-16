<!-- G-1 到着便マスタ -->
@extends('layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list"><a href="{{ route('manage.master') }}">マスタ設定一覧</a></li>
        <li class="l-breadcrumb__list">到着便マスタ</li>
      </ul>

      @include('include.messages.errors')
      @include('include.messages.success')

      <div class="l-container__inner">
        {{-- 到着便CSVアップロード（Sun_Parking_YYYY-MM-DD.csv） --}}
        <div class="l-flex--end l-grid--gap1 upload c-button__csv--upload u-mb2">
          <button type="button" class="c-button__load --gray upload u-mt0 uploadButton" style="width:auto">到着便CSVアップロード</button>
          <a href="{{ route('manage.master.arrival_flights.legacy') }}" class="c-button__load link-white u-mt0" style="width:auto">現行データと比較</a>
        </div>
        <form id="csv-upload-form" action="{{ route('manage.master.arrival_flights.upload') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="c-button__csv--upload">
            <input type="file" name="csvFileInput" id="csvFileInput" accept=".csv,text/csv" hidden />
          </div>
        </form>

        @if ($importResult)
          @php
            $blockingIssues = collect($importResult['issues'])->where('blocking', true)->groupBy('type');
            $infoIssues = collect($importResult['issues'])->where('blocking', false);
          @endphp
          <section class="p-arrival-import u-mb2">
            <h3 class="u-mb1">
              @if ($importResult['status'] === 'blocked')
                取り込みを中止しました：{{ $importResult['file_name'] }}
              @else
                取り込み結果：{{ $importResult['file_name'] }}
              @endif
            </h3>
            <p class="u-mb1">
              CSV {{ number_format($importResult['total']) }} 行、対象 {{ number_format($importResult['target']) }} 便
              @if ($importResult['status'] === 'blocked')
                ／ 取り込んだ場合：新規 {{ number_format($importResult['inserted']) }} 件、更新 {{ number_format($importResult['updated']) }} 件（まだ反映していません）
              @else
                ／ 新規 <strong>{{ number_format($importResult['inserted']) }}</strong> 件、更新 <strong>{{ number_format($importResult['updated']) }}</strong> 件、変更なし {{ number_format($importResult['unchanged']) }} 件
              @endif
            </p>

            @if ($blockingIssues->isNotEmpty() || count($importResult['errors']))
              <h4 class="u-mb1">{{ $importResult['status'] === 'blocked' ? 'マスタに登録が必要なもの' : 'マスタにないため NULL で登録したもの' }}</h4>

              @foreach ($blockingIssues as $type => $items)
                <p class="u-mb1"><strong>{{ $type }}</strong>（{{ $items->count() }} 件）</p>
                <table class="l-table-list l-table-list--master u-mb2">
                  <thead class="l-table-list__head">
                    <tr>
                      <th style="width:10%">コード</th>
                      <th style="width:7%">便数</th>
                      <th style="width:20%">到着日</th>
                      <th style="width:10%">関連</th>
                      <th style="width:18%">例</th>
                      <th style="width:35%">やること</th>
                    </tr>
                  </thead>
                  <tbody class="l-table-list__body">
                    @foreach ($items as $issue)
                      <tr>
                        <td>{{ $issue['code'] }}</td>
                        <td>{{ number_format($issue['count']) }}</td>
                        <td>{{ $issue['first_date'] }} 〜 {{ $issue['last_date'] }}</td>
                        <td>{{ implode(' ', $issue['related']) }}</td>
                        <td>{{ implode('、', $issue['examples']) }}</td>
                        <td>{{ $issue['hint'] }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @endforeach

              @if (count($importResult['errors']))
                <p class="u-mb1"><strong>読めなかった行</strong>（{{ count($importResult['errors']) }} 行）</p>
                <table class="l-table-list l-table-list--master u-mb2">
                  <tbody class="l-table-list__body">
                    @foreach ($importResult['errors'] as $error)
                      <tr>
                        <td>行 {{ $error['line'] }}</td>
                        <td>{{ $error['reason'] }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @endif

              <div class="l-flex--end l-grid--gap1 u-mb2">
                <a href="{{ route('manage.master.arrival_flights.issues.download') }}" class="c-button__load link-white u-mt0" style="width:auto">一覧をダウンロード（CSV）</a>
                @if ($blockingIssues->except([\App\Services\ArrivalFlights\ArrivalFlightImportPlanner::ISSUE_AIRPORT_EMPTY])->isNotEmpty())
                  <a href="{{ route('manage.master.arrival_flights.issues.sql') }}" class="c-button__load link-white u-mt0" style="width:auto">マスタ登録用SQLをダウンロード</a>
                @endif
                @if ($importResult['status'] === 'blocked')
                  <form action="{{ route('manage.master.arrival_flights.upload.force') }}" method="POST"
                        onsubmit="return confirm('マスタにない航空会社・空港・ターミナルは NULL のまま取り込みます。よろしいですか？');">
                    @csrf
                    <button type="submit" class="c-button__load --gray u-mt0" style="width:auto">未登録のまま（NULLで）取り込む</button>
                  </form>
                @endif
              </div>
            @endif

            @if ($infoIssues->isNotEmpty())
              <h4 class="u-mb1">参考（対応は不要です）</h4>
              <table class="l-table-list l-table-list--master">
                <tbody class="l-table-list__body">
                  @foreach ($infoIssues as $issue)
                    <tr>
                      <td>{{ $issue['type'] }}</td>
                      <td>{{ $issue['code'] }}</td>
                      <td>{{ number_format($issue['count']) }} 便</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @endif
          </section>
        @endif

        {{-- 検索 --}}
        <form class="p-master-flight__head" id="flight-search" method="GET" action="{{ route('manage.arrival_flights') }}" style="margin-bottom:.5rem">
          <div class="l-flex p-master-flight__head-left">
            <dl>
              <dt class="u-mb025">空港・ターミナル</dt>
              <dd class="p-master-flight__head-desc">
                <div class="c-form-select-color">
                  <select name="airport_id" id="airport_id" style="min-width:150px">
                    @foreach ($airports as $airport)
                      <option value="{{ $airport->id }}" @selected($airport->id == $params['airport_id'])>{{ $airport->code }} {{ $airport->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="c-form-select-color">
                  <select name="terminal" id="terminal" style="min-width:120px">
                    <option value="">すべて</option>
                    @foreach ($terminalNos as $no)
                      <option value="{{ $no }}" @selected($no == ($params['terminal'] ?? null))>T{{ $no }}</option>
                    @endforeach
                  </select>
                </div>
              </dd>
            </dl>
            <dl>
              <dt class="u-mb025">到着日</dt>
              <dd class="p-master-flight__head-inputArea">
                <input type="date" name="date_from" id="date_from" style="width:140px" value="{{ $params['date_from'] ?? '' }}" aria-label="到着日（から）">〜<input type="date" name="date_to" id="date_to" style="width:140px" value="{{ $params['date_to'] ?? '' }}" aria-label="到着日（まで）">
              </dd>
            </dl>
          </div>
          <div class="l-flex p-master-flight__head-right">
            <dl>
              <dt class="u-mb025">到着便</dt>
              <dd class="p-master-flight__head-inputArea">
                <input type="text" name="carrier" id="carrier" value="{{ $params['carrier'] ?? '' }}" placeholder="JL" maxlength="3" aria-label="航空会社コード" style="width:60px">-<input type="text" name="flight_no" id="flight_no" value="{{ $params['flight_no'] ?? '' }}" placeholder="702" maxlength="10" aria-label="便名" style="width:70px">
              </dd>
            </dl>
            <div class="p-master-flight__head-desc">
              <dl>
                <dt class="u-mb025" style="white-space:nowrap">時</dt>
                <dd>
                  <div class="c-form-select-color">
                    <select name="hours" id="hours" style="min-width:80px">
                      <option value="">--</option>
                      @for ($h = 0; $h < 24; $h++)
                        <option value="{{ $h }}" @selected(isset($params['hours']) && (int) $params['hours'] === $h)>{{ sprintf('%02d', $h) }}</option>
                      @endfor
                    </select>
                  </div>
                </dd>
              </dl>
              <dl>
                <dt class="u-mb025" style="white-space:nowrap">分（以降）</dt>
                <dd>
                  <div class="c-form-select-color">
                    <select name="minutes" id="minutes" style="min-width:80px">
                      <option value="">--</option>
                      @for ($m = 0; $m < 60; $m++)
                        <option value="{{ $m }}" @selected(isset($params['minutes']) && (int) $params['minutes'] === $m)>{{ sprintf('%02d', $m) }}</option>
                      @endfor
                    </select>
                  </div>
                </dd>
              </dl>
            </div>
            <input type="hidden" name="sort" value="{{ $sort }}">
            <input type="hidden" name="dir" value="{{ $dir }}">
            <input type="hidden" name="limit" value="{{ request('limit') }}">
            <button type="submit" class="u-fw--normal c-button--deep-gray">絞込</button>
          </div>
        </form>

        <div class="l-flex--end" style="gap:1.5rem;align-items:center;margin-bottom:1rem">
          <label class="u-fw--normal" for="unset_only" style="display:flex;align-items:center;gap:.25rem;white-space:nowrap">
              <input type="checkbox" name="unset_only" id="unset_only" value="1" form="flight-search" style="width:auto" @checked(!empty($params['unset_only']))> マスタ未設定のみ
            </label>
          <a href="{{ route('manage.arrival_flights') }}" class="u-fw--normal" style="white-space:nowrap">条件をクリア</a>
        </div>

        <p class="u-mb1">検索結果 {{ number_format($flights->total()) }} 件</p>
        {{ $flights->links('vendor/pagination/manage-list') }}

        @php
          // 見出しのリンク：同じ列なら昇順⇔降順を切り替え、別の列なら昇順
          $sortLink = fn (string $col) => request()->fullUrlWithQuery([
            'sort' => $col,
            'dir' => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc',
            'page' => 1,
          ]);
          $sortClass = fn (string $col) => $sort === $col ? ' --' . $dir : '';
          $headers = [
            'airport' => '空港', 'terminal' => 'T', 'name' => '便名', 'arrive_date' => '到着日', 'arrive_time' => '時刻',
            'dep' => '出発地', 'airline' => '航空会社', 'codeshare' => 'コードシェア',
          ];
        @endphp

        <div class="p-master-flight__table-container">
          <table class="l-table-list l-table-list--master l-table-list--scroll-vertical">
            <thead class="l-table-list__head l-table-list--scroll-vertical__head">
              <tr>
                @foreach ($headers as $col => $label)
                  <th><a href="{{ $sortLink($col) }}" class="c-link-no-border"><div class="c-button-sort{{ $sortClass($col) }}">{{ $label }}</div></a></th>
                @endforeach
              </tr>
            </thead>
            <tbody class="l-table-list__body">
              @forelse ($flights as $flight)
                <tr>
                  <td>{{ $flight->arr_name ?? $flight->arr_code }}</td>
                  <td>{!! $flight->terminal_no ? 'T' . e($flight->terminal_no) : '<span class="u-font--red">未設定</span>' !!}</td>
                  <td>{{ $flight->name }}</td>
                  <td>{{ $flight->arrive_date?->isoFormat('YYYY/MM/DD(ddd)') }}</td>
                  <td>{{ $flight->arrive_time ? substr($flight->arrive_time, 0, 5) : '' }}</td>
                  <td>{!! $flight->dep_code ? e($flight->dep_code . ' ' . $flight->dep_name) : '<span class="u-font--red">未設定</span>' !!}</td>
                  <td>{!! $flight->airline_name ? e($flight->airline_name) : '<span class="u-font--red">未設定（' . e(substr($flight->name, 0, 2)) . '）</span>' !!}</td>
                  <td>{{ $flight->codeshare }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="8">条件に合う到着便はありません。</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{ $flights->links('vendor/pagination/manage-list') }}

        <!-- 登録ボタン -->
        <div class="l-flex--end">
          <a href="" class="c-link-no-border u-btn-h--m u-mt2 c-button__pagination--next c-button__pagination--next-green">マスタ登録</a>
        </div>
  
      </div><!-- ./l-container__inner -->

    </main>
  </div>
@endsection
@push("scripts")
  <!-- 到着便CSVアップロード：ファイルを選んだらそのまま送信 -->
  <script>
    (function () {
      const button = document.querySelector('.upload .uploadButton');
      const input = document.getElementById('csvFileInput');
      const form = document.getElementById('csv-upload-form');
      button.addEventListener('click', () => input.click());
      input.addEventListener('change', () => {
        if (!input.files.length) return;
        button.disabled = true;
        button.textContent = '取り込み中…';
        form.submit();
      });
    })();
  </script>
@endpush