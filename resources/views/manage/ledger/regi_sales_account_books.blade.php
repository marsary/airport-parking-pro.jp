<!-- D-2-2 -->
<!-- レジ売上帳 -->
@extends(request('print') ? 'layouts.manage.print' : 'layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb l-print__none">
        <!-- D-1-0にリンク -->
        <li class="l-breadcrumb__list"><a href="{{route('manage.ledger')}}">帳票印刷</a></li>
        <li class="l-breadcrumb__list">レジ売上帳</li>
      </ul>

      <div class="l-container__inner">
        <form action="{{route('manage.ledger.regi_sales_account_books')}}" method="GET" class="u-mb4 l-print__none">
          @csrf
          <div class="l-flex--column l-flex--item-start l-grid--gap05 u-mb2">
            レジ番号
            <!-- チェックボックス レジ番号 -->
            <div class="l-flex l-grid--gap2">
              @foreach ($registers as $register)
                <label class="l-flex l-grid--gap05">
                  <input class="u-mb0" type="radio" name="register" value="{{$register->id}}"
                     {{request('register') == $register->id ? "checked":""}}
                  >レジ{{str_pad($register->id, 2, '0', STR_PAD_LEFT)}}
                </label>
              @endforeach
              {{--  <label class="l-flex l-grid--gap05">
                <input class="u-mb0" type="radio" name="register" value="2">レジ02
              </label>
              <label class="l-flex l-grid--gap05">
                <input class="u-mb0" type="radio" name="register" value="3">レジ03
              </label>
              <label class="l-flex l-grid--gap05">
                <input class="u-mb0" type="radio" name="register" value="4">レジ04
              </label>
              <label class="l-flex l-grid--gap05">
                <input class="u-mb0" type="radio" name="register" value="5">レジ05
              </label>  --}}
            </div>
          </div>
          <div class="l-flex--start l-flex--item-end l-grid--gap2">
            <div class="l-flex l-grid--gap2">
              <!-- 日付  -->
              <div class="l-flex--column l-flex--item-start l-grid--gap025">
                <label for="entry_date" class="l-flex l-grid--gap05">日付</label>
                <input type="date" id="entry_date" name="entry_date" placeholder="例) 2021/01/01" value="{{request('entry_date')}}">
              </div>
              <!-- 時刻  -->
              <div class="l-flex--column l-flex--item-start l-grid--gap025">
                <label for="entry_time" class="l-flex l-grid--gap05">時刻</label>
                <select name="entry_time" id="entry_time">
                  <option value="all" selected>全て</option>
                  <option value="00:00" {{request('entry_time')=="00:00" ? 'selected':''}}>00:00</option>
                  <option value="01:00" {{request('entry_time')=="01:00" ? 'selected':''}}>01:00</option>
                  <option value="02:00" {{request('entry_time')=="02:00" ? 'selected':''}}>02:00</option>
                  <option value="03:00" {{request('entry_time')=="03:00" ? 'selected':''}}>03:00</option>
                  <option value="04:00" {{request('entry_time')=="04:00" ? 'selected':''}}>04:00</option>
                  <option value="05:00" {{request('entry_time')=="05:00" ? 'selected':''}}>05:00</option>
                  <option value="06:00" {{request('entry_time')=="06:00" ? 'selected':''}}>06:00</option>
                  <option value="07:00" {{request('entry_time')=="07:00" ? 'selected':''}}>07:00</option>
                  <option value="08:00" {{request('entry_time')=="08:00" ? 'selected':''}}>08:00</option>
                  <option value="09:00" {{request('entry_time')=="09:00" ? 'selected':''}}>09:00</option>
                  <option value="10:00" {{request('entry_time')=="10:00" ? 'selected':''}}>10:00</option>
                  <option value="11:00" {{request('entry_time')=="11:00" ? 'selected':''}}>11:00</option>
                  <option value="12:00" {{request('entry_time')=="12:00" ? 'selected':''}}>12:00</option>
                  <option value="13:00" {{request('entry_time')=="13:00" ? 'selected':''}}>13:00</option>
                  <option value="14:00" {{request('entry_time')=="14:00" ? 'selected':''}}>14:00</option>
                  <option value="15:00" {{request('entry_time')=="15:00" ? 'selected':''}}>15:00</option>
                  <option value="16:00" {{request('entry_time')=="16:00" ? 'selected':''}}>16:00</option>
                  <option value="17:00" {{request('entry_time')=="17:00" ? 'selected':''}}>17:00</option>
                  <option value="18:00" {{request('entry_time')=="18:00" ? 'selected':''}}>18:00</option>
                  <option value="19:00" {{request('entry_time')=="19:00" ? 'selected':''}}>19:00</option>
                  <option value="20:00" {{request('entry_time')=="20:00" ? 'selected':''}}>20:00</option>
                  <option value="21:00" {{request('entry_time')=="21:00" ? 'selected':''}}>21:00</option>
                  <option value="22:00" {{request('entry_time')=="22:00" ? 'selected':''}}>22:00</option>
                  <option value="23:00" {{request('entry_time')=="23:00" ? 'selected':''}}>23:00</option>
                </select>
              </div>
            </div>
            @if (!request('print'))
              <input type="submit" value="印刷プレビュー" class="c-button c-button--deep-gray hover">
            @endif
          </div>
          <ul class="u-font--md">
            <li>・入庫時に未収が発生したデータは赤字で表示され、名前の後ろに「後」と表示されます。<br>
              正確な意味での後払いではないのでご注意ください。
            <li>・レジで現金の追加・返金が発生した場合に操作を誤ると現金の計算が正しく出来なくなります（入金の現金の預り・釣りが表示されなくなります）。
            </li>
          </ul>
        </form>
        @if (!request('print'))
          <ul class="l-flex l-grid--gap1 u-mb1 l-print__none no-print">
            <li class="c-button--green pointer hover" id="a4-vertical">A4縦</li>
            <li class="c-button--yellow pointer hover" id="b4-horizontal">B4ヨコ</li>
            <li class="c-button--yellow pointer hover" id="a3-horizontal">A3ヨコ</li>
            <li class="c-button--yellow pointer hover" id="a4-horizontal">A4ヨコ</li>
            <li class="c-button--deep-gray"><a href="{{route('manage.ledger.regi_check_lists')}}" class="link-white">点検表へ</a></li>
          </ul>
        @endif
        <div class="l-table-print__wrap l-print__wrap">
          <table class="l-table-print">
            <thead>
              <tr>
                <th></th>
                <th>受付ID</th>
                <th>時刻</th>
                <th>氏名</th>
                <th>修</th>
                <th>帰</th>
                <th>事</th>
                <th>日</th>
                <th>代理店</th>
                <th>率</th>
                <th>駐車</th>
                <th>券</th>
                <th>マイル</th>
                <th>WAX</th>
                <th>保険</th>
                <th>他</th>
                <th>合計</th>
                <th>現金</th>
                <th>預り</th>
                <th>釣り</th>
                <th>クレ</th>
                <th>クーポ</th>
                <th>前力</th>
                <th>SBI</th>
                <th>他</th>
                <th>合計</th>
                <th>担当</th>
              </tr>
            </thead>
            <tbody>
              @if (empty($data))
                <tr>
                  <td colspan="17">
                  </td>
                </tr>
              @else
                @php
                  /** @var array{rows: \App\Services\Ledger\RegiSalesAccountBooksRow[], bottomLine: null|\App\Services\Ledger\RegiSalesBottomLineRow} $data */
                  /** @var \App\Services\Ledger\RegiSalesAccountBooksRow $row */
                @endphp
                @foreach ($data['rows'] as $idx => $row)
                  <tr>
                    <td>{{$idx+1}}</td>
                    <td>
                        <a href="{{route('manage.deals.show', $row->dealId)}}" target="_blank">{{$row->dealId}}</a>
                    </td>
                    <td>{{$row->paymentTime->format("H:i")}}</td>
                    <td>{{$row->memberName}}</td>
                    <td>{{$row->isUpdated ? '修':''}}</td>
                    <td>{{$row->isUnloaded ? '帰':''}}</td>
                    <td>{{$row->officeName}}</td>
                    <td>{{$row->days}}</td>
                    <td>{{$row->agencyName}}</td>
                    <td>{{$row->dscRate}}</td>
                    <td>{{number_format($row->dealPrice + $row->dealTax)}}({{number_format($row->dealPrice)}})</td>
                    <td>{{$row->discountTicketName}}</td>
                    <td>{{$row->mile}}</td>
                    <td>
                      @foreach ($row->waxPrices as $waxName => $waxPrice)
                        {{$waxName}} {{number_format($waxPrice)}} <br />
                      @endforeach
                    </td>
                    <td>
                      @foreach ($row->insurancePrices as $insName => $insPrice)
                        {{$insName}} {{number_format($insPrice)}} <br />
                      @endforeach
                    </td>
                    <td>
                      @foreach ($row->otherPrices as $otherName => $otherPrice)
                        {{$otherName}} {{number_format($otherPrice)}} <br />
                      @endforeach
                    </td>
                    <td>{{number_format($row->dealTotalPrice)}}</td>
                    <td>{{number_format($row->cash)}}</td>
                    <td>{{number_format($row->cashEnter)}}</td>
                    <td>{{number_format($row->cashChange)}}</td>
                    <td>
                      @foreach ($row->credits as $creditName => $creditPrice)
                        {{$creditName}} {{number_format($creditPrice)}} <br />
                      @endforeach
                    </td>
                    <td>
                      @foreach ($row->coupons as $couponName => $couponPrice)
                        {{$couponName}} {{number_format($couponPrice)}} <br />
                      @endforeach
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                      @foreach ($row->others as $otherPaymentName => $otherPaymentPrice)
                        @if ($otherPaymentPrice > 0)
                          {{$otherPaymentName}} {{number_format($otherPaymentPrice)}} <br />
                        @endif
                      @endforeach
                    </td>
                    <td>{{number_format($row->totalPay)}}</td>
                    <td>{{$row->userName}}</td>
                  </tr>
                @endforeach
                @if (isset($data['bottomLine']))
                  <tr>
                    <td colspan="10">
                      合計 {{count($data['rows'])}} 件
                    </td>
                    <td>{{number_format($data['bottomLine']->dealPriceTaxed)}}</td>
                    <td></td>
                    <td></td>
                    <td>{{number_format($data['bottomLine']->waxPrice)}}</td>
                    <td>{{number_format($data['bottomLine']->insurancePrice)}}</td>
                    <td>{{number_format($data['bottomLine']->otherPrice)}}</td>
                    <td>{{number_format($data['bottomLine']->dealTotalPrice)}}</td>
                    <td>{{number_format($data['bottomLine']->cash)}}</td>
                    <td></td>
                    <td></td>
                    <td>{{number_format($data['bottomLine']->credits)}}</td>
                    <td>{{number_format($data['bottomLine']->coupons)}}</td>
                    <td></td>
                    <td></td>
                    <td>{{number_format($data['bottomLine']->others)}}</td>
                    <td>{{number_format($data['bottomLine']->totalPay)}}</td>
                    <td></td>
                  </tr>
                @endif

              @endif
            </tbody>
          </table>
        </div>
      </div><!-- l-container__inner -->
    </main>
@endsection
@push("scripts")
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      {{--  const a4VerticalButton = document.getElementById('a4-vertical');
      const b4HorizontalButton = document.getElementById('b4-horizontal');
      const a3HorizontalButton = document.getElementById('a3-horizontal');
      const a4HorizontalButton = document.getElementById('a4-horizontal');

      a4VerticalButton.addEventListener('click', function() {
        setPrintStyle('A4 portrait');
        addPrintClass('a4-vertical-print');
        window.print();
      });

      b4HorizontalButton.addEventListener('click', function() {
        setPrintStyle('B4 landscape');
        addPrintClass('b4-horizontal-print');
        window.print();
      });

      a3HorizontalButton.addEventListener('click', function() {
        setPrintStyle('A3 landscape');
        addPrintClass('a3-horizontal-print');
        window.print();
      });

      a4HorizontalButton.addEventListener('click', function() {
        setPrintStyle('A4 landscape');
        addPrintClass('a4-horizontal-print');
        window.print();
      });  --}}


    // URLに ?print=1 が含まれているかどうかで処理を分岐します
    const isPrintView = new URLSearchParams(window.location.search).has('print');

    if (isPrintView) {
        // --- 印刷用ページの処理 ---
        const params = new URLSearchParams(window.location.search);
        const pageSize = params.get('page_size');
        const printClass = params.get('print_class');

        // @page スタイルを動的に設定
        if (pageSize) {
            setPrintStyle(pageSize.replace('_', ' '))
        }

        // 印刷用のCSSクラスをhtml要素に付与
        if (printClass) {
            addPrintClass(printClass)
        }

        // ページの読み込みが完了したら印刷ダイアログを開く
        window.addEventListener('load', () => {
            window.print();
        });

    } else {
        // --- 通常表示ページの処理 ---
        const printButtons = [
            { id: 'a4-vertical', size: 'A4 portrait', class: 'a4-vertical-print' },
            { id: 'b4-horizontal', size: 'B4 landscape', class: 'b4-horizontal-print' },
            { id: 'a3-horizontal', size: 'A3 landscape', class: 'a3-horizontal-print' },
            { id: 'a4-horizontal', size: 'A4 landscape', class: 'a4-horizontal-print' }
        ];

        printButtons.forEach(buttonInfo => {
            const button = document.getElementById(buttonInfo.id);
            if (button) {
                button.addEventListener('click', function() {
                    // 現在のURLとパラメータを取得
                    const currentUrl = new URL(window.location.href);

                    // 印刷用のパラメータを追加
                    currentUrl.searchParams.set('print', '1');
                    // CSSの 'size' プロパティ値に半角スペースが含まれるため '_' に置換
                    currentUrl.searchParams.set('page_size', buttonInfo.size.replace(' ', '_'));
                    currentUrl.searchParams.set('print_class', buttonInfo.class);

                    // 新しいタブで印刷用URLを開く
                    window.open(currentUrl.href, '_blank');
                });
            }
        });
    }

      function setPrintStyle(size) {
        const style = document.createElement('style');
        style.media = 'print';
        style.innerHTML = '@page { size: ' + size + '; margin: 10mm; }';
        document.head.appendChild(style);
      }

      function addPrintClass(className) {
        document.documentElement.classList.remove('a4-vertical-print', 'b4-horizontal-print', 'a3-horizontal-print', 'a4-horizontal-print');
        document.documentElement.classList.add(className);
      }
    });
  </script>
@endpush
