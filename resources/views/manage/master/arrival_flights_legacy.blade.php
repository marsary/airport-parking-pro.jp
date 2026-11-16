<!-- 到着便マスタ > 現行データと比較 -->
@extends('layouts.manage.authenticated')

@section('content')
  <main class="l-wrap__main">
    <ul class="l-wrap__breadcrumb l-breadcrumb">
      <li class="l-breadcrumb__list"><a href="{{ route('manage.arrival_flights') }}">到着便マスタ</a></li>
      <li class="l-breadcrumb__list">現行データと比較</li>
    </ul>

    @include('include.messages.errors')
    @include('include.messages.success')

    <div class="l-container__inner">
      <section class="u-mb2">
        <p class="u-mb1">現行システムのCSV（4ファイル）と今のデータを比較し、現行に合わせるための追加・変更のSQLを作ります。<strong>この画面ではデータは変更しません。</strong></p>
        <ul class="u-mb1" style="padding-left:.5em">
          <li>・対象は成田だけです（羽田の行は件数だけ表示します）。</li>
          <li>・削除のSQLは作りません。DBにだけあるものは一覧に出すだけです。</li>
          <li>・現行の削除フラグ（del_flg）はSQLに反映しません（今のデータを優先）。該当するものは「お知らせ」に出します。</li>
          <li>・Sun_Parking_yyyy-mm-dd.csv を取り込んだあとに実行してください。</li>
        </ul>

        <form action="{{ route('manage.master.arrival_flights.legacy.compare') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <table class="l-table-list l-table-list--master u-mb1">
            <tbody class="l-table-list__body">
              @foreach ($files as $kind => $def)
                <tr>
                  <th style="width:30%;text-align:left"><label for="file-{{ $kind }}">{{ $def['label'] }}</label></th>
                  <td style="text-align:left"><input type="file" name="{{ $kind }}" id="file-{{ $kind }}" accept=".csv,text/csv" required></td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="l-flex--end">
            <button type="submit" class="c-button__load --gray u-mt0" style="width:auto" id="legacy-submit">比較する</button>
          </div>
        </form>
      </section>

      @if ($result)
        @php
          $f = $result['flights'];
          $rows = [
            ['航空会社（airlines）', $result['airlines']['insert']['count'], $result['airlines']['update']['count'], $result['airlines']['dbOnly']['count'], ''],
            ['出発空港（airports）', $result['airports']['insert']['count'], $result['airports']['update']['count'], $result['airports']['dbOnly']['count'], ''],
            ['成田のターミナル（airport_terminals）', $result['terminals']['insert']['count'], $result['terminals']['default']['count'], $result['terminals']['dbOnly']['count'], '羽田 ' . $result['terminals']['hndCount'] . ' 件は対象外'],
            ['成田の到着便（arrival_flights）', $f['insert']['count'], $f['update']['count'], $f['dbOnly']['count'],
              ($f['range'] ? '期間 ' . $f['range'][0] . '〜' . $f['range'][1] . '、変更なし ' . number_format($f['unchanged']) . ' 件' : '') . ($f['hndCount'] ? '、羽田 ' . $f['hndCount'] . ' 件は対象外' : '')],
          ];
          $label = ['name' => '名前', 'kana' => 'ふりがな', 'name_kana' => 'ふりがな', 'japan_flg' => 'japan_flg', 'memo' => 'memo',
            'dep' => '出発地', 'arrive_time' => '時刻', 'terminal' => 'ターミナル', 'airline' => '航空会社'];
          $fmt = function ($col, $v) {
            if ($v === null || $v === '') return '(空)';
            if ($col === 'terminal') return '第' . $v;
            if ($col === 'arrive_time') return substr($v, 0, 5);
            return $v;
          };
          $changes = fn ($c) => collect($c)->map(fn ($v, $col) => ($label[$col] ?? $col) . '：' . $fmt($col, $v[0]) . ' → ' . $fmt($col, $v[1]))->implode('、');
          $more = fn ($list) => $list['count'] > count($list['items']) ? '（先頭 ' . count($list['items']) . ' 件を表示。全件はSQLにあります）' : '';
        @endphp

        <section class="u-mb2">
          <h3 class="u-mb1">比較結果（{{ $result['created_at'] }}）</h3>
          <p class="u-mb1" style="font-size:.9em">
            @foreach ($result['files'] as $lab => $name){{ $lab }}：{{ $name }}@if (!$loop->last)／@endif @endforeach
          </p>

          <table class="l-table-list l-table-list--master u-mb1">
            <thead class="l-table-list__head">
              <tr><th style="width:30%">対象</th><th style="width:9%">追加</th><th style="width:9%">変更</th><th style="width:12%">DBにだけある<br>（削除しません）</th><th style="width:40%">備考</th></tr>
            </thead>
            <tbody class="l-table-list__body">
              @foreach ($rows as [$name, $ins, $upd, $only, $note])
                <tr>
                  <td style="text-align:left">{{ $name }}</td>
                  <td>{{ number_format($ins) }}</td>
                  <td>{{ number_format($upd) }}</td>
                  <td>{{ number_format($only) }}</td>
                  <td style="text-align:left">{{ $note }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <div class="l-flex--end u-mb2">
            @if ($result['sql_path'])
              <a href="{{ route('manage.master.arrival_flights.legacy.sql') }}" class="c-button__load link-white u-mt0" style="width:auto">修正・新規作成SQLをダウンロード</a>
            @else
              <p>現行データとの差異はありません。SQLは作っていません。</p>
            @endif
          </div>

          @if (count($f['missing']))
            <h4 class="u-mb1 u-font--red">マスタがないため NULL になるもの</h4>
            <ul class="u-mb2" style="padding-left:.5em">
              @foreach ($f['missing'] as $what => $n)
                <li>・{{ $what }}：{{ number_format($n) }} 便</li>
              @endforeach
            </ul>
          @endif

          @if (count($result['messages']))
            <h4 class="u-mb1">お知らせ（{{ count($result['messages']) }} 件）</h4>
            <table class="l-table-list l-table-list--master u-mb2">
              <tbody class="l-table-list__body">
                @foreach ($result['messages'] as $m)
                  <tr><td style="width:12%">{{ $m['type'] }}</td><td style="width:15%">{{ $m['code'] }}</td><td style="text-align:left">{{ $m['text'] }}</td></tr>
                @endforeach
              </tbody>
            </table>
          @endif

          @if (count($f['filled']))
            <p class="u-mb2">参考：現行のターミナルが0の便のうち、DBのターミナルが空だった便は航空会社の既定ターミナルで補います（{{ collect($f['filled'])->map(fn ($n, $c) => "$c $n 便")->implode('、') }}）。</p>
          @endif
        </section>

        {{-- 明細 --}}
        @foreach ([
          ['到着便の変更', $f['update'], fn ($x) => [$x['name'], $x['date'], $x['dep'] ?? '', $changes($x['changes'])], ['便名', '到着日', '出発地', '変更内容']],
          ['到着便の追加', $f['insert'], fn ($x) => [$x['name'], $x['date'], $x['dep'] ?? '', substr($x['time'], 0, 5) . ($x['terminal_no'] ? '　第' . $x['terminal_no'] : '　ターミナル未設定')], ['便名', '到着日', '出発地', '時刻・ターミナル']],
          ['DBにだけある到着便（削除しません）', $f['dbOnly'], fn ($x) => [$x['name'], $x['date'], $x['dep'] ?? '', $x['time'] ? substr($x['time'], 0, 5) : ''], ['便名', '到着日', '出発地', '時刻']],
          ['航空会社の追加', $result['airlines']['insert'], fn ($x) => [$x['code'], $x['name'], $x['kana'], $x['japan_flg'] ? '日本' : ''], ['コード', '名前', 'ふりがな', '']],
          ['航空会社の変更', $result['airlines']['update'], fn ($x) => [$x['code'], $changes($x['changes']), '', ''], ['コード', '変更内容', '', '']],
          ['DBにだけある航空会社（削除しません）', $result['airlines']['dbOnly'], fn ($x) => [$x['code'], $x['name'], '', ''], ['コード', '名前', '', '']],
          ['出発空港の追加', $result['airports']['insert'], fn ($x) => [$x['code'], $x['name'], $x['name_kana'], ''], ['コード', '名前', 'ふりがな', '']],
          ['出発空港の変更', $result['airports']['update'], fn ($x) => [$x['code'], $changes($x['changes']), '', ''], ['コード', '変更内容', '', '']],
          ['DBにだけある出発空港（削除しません）', $result['airports']['dbOnly'], fn ($x) => [$x['code'], $x['name'], '', ''], ['コード', '名前', '', '']],
          ['成田のターミナルの追加', $result['terminals']['insert'], fn ($x) => [$x['code'], '第' . $x['terminal_no'], $x['default_flg'] ? '既定' : '', $x['note']], ['航空会社', 'ターミナル', '', '備考']],
          ['既定ターミナルの変更', $result['terminals']['default'], fn ($x) => [$x['code'], '第' . $x['terminal_no'] . 'を既定に', '', ''], ['航空会社', '内容', '', '']],
          ['DBにだけある成田のターミナル（削除しません）', $result['terminals']['dbOnly'], fn ($x) => [$x['code'], '第' . $x['terminal_no'], '', ''], ['航空会社', 'ターミナル', '', '']],
        ] as [$title, $list, $row, $heads])
          @if ($list['count'])
            <section class="u-mb2">
              <h4 class="u-mb1">{{ $title }}（{{ number_format($list['count']) }} 件）<span style="font-weight:normal;font-size:.85em">{{ $more($list) }}</span></h4>
              <div style="max-height:360px;overflow:auto">
                <table class="l-table-list l-table-list--master">
                  <colgroup><col style="width:13%"><col style="width:17%"><col style="width:15%"><col style="width:55%"></colgroup>
                  <thead class="l-table-list__head"><tr>@foreach ($heads as $h)<th>{{ $h }}</th>@endforeach</tr></thead>
                  <tbody class="l-table-list__body">
                    @foreach ($list['items'] as $x)
                      <tr>@foreach ($row($x) as $i => $cell)<td @if ($i === 3 || $i === 1) style="text-align:left" @endif>{{ $cell }}</td>@endforeach</tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </section>
          @endif
        @endforeach
      @endif

      <div class="l-flex--start u-mt2">
        <a href="{{ route('manage.arrival_flights') }}">到着便マスタに戻る</a>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    document.querySelector('#legacy-submit')?.closest('form').addEventListener('submit', function () {
      const b = document.getElementById('legacy-submit');
      b.disabled = true;
      b.textContent = '比較中…';
    });
  </script>
@endpush
