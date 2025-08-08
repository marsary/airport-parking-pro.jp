<!-- D-2-3 -->
<!-- レジ点検表 -->
@extends(request('print') ? 'layouts.manage.print' : 'layouts.manage.authenticated')

@section('content')    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb l-print__none">
        <!-- D-1-0にリンク -->
        <li class="l-breadcrumb__list"><a href="">帳票印刷</a></li>
        <li class="l-breadcrumb__list">レジ点検表</li>
      </ul>

      <div class="l-container__inner">
        <form action="" class="u-mb4 l-print__none">
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
            <li>・入金系の金額がマイナスの場合、件数もマイナスされます。</li>
            <li>・レジで現金の追加・返金が発生した場合に操作を誤ると現金の計算が正しく出来なくなります。</li>
          </ul>
        </form>

        @if (!request('print'))
          <ul class="l-flex l-grid--gap1 u-mb1 l-print__none no-print">
            <li class="c-button--green pointer hover">
              <button id="printButton" class="link-white">印刷</button>
            </li>
            <!-- 点検表へのリンクURL未設定 -->
            <li class="c-button--deep-gray"><a href="{{route('manage.ledger.regi_sales_account_books')}}" class="link-white">売上帳へ</a></li>
          </ul>
        @endif
        <div class="l-table-print__wrap l-print__wrap">
          @isset($data['officeTables'])
            @foreach ($data['officeTables'] as $officeName => $officeTable)
              <p>【売上 {{$officeName}}】</p>
              <table class="l-table-print">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>売上項目</th>
                    <th>処理回数</th>
                    <th>数量</th>
                    <th>金額</th>
                    <th>税抜金額</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td></td>
                    <td>駐車料金（前払い）</td>
                    <td>{{$officeTable->parkingFeeInAdvance->count}}</td>
                    <td>{{$officeTable->parkingFeeInAdvance->amount}}</td>
                    <td>{{number_format($officeTable->parkingFeeInAdvance->price)}}</td>
                    <td>{{number_format($officeTable->parkingFeeInAdvance->priceExcludingTax)}}</td>
                  </tr>
                  {{--  <tr>
                    <td></td>
                    <td>駐車料金（後払い）</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td>駐車料金（追加／返金）</td>
                    <td>1</td>
                    <td>1</td>
                    <td>770</td>
                    <td>700</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td>一日利用（チケット）</td>
                    <td></td>
                    <td>0</td>
                    <td>0</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td>一日利用（チケット以外）</td>
                    <td></td>
                    <td>4</td>
                    <td>0</td>
                    <td></td>
                  </tr>  --}}
                  @isset($officeTable->goodsSales)
                    @foreach ($officeTable->goodsSales->rows as $row)
                      <tr>
                        <td>{{$row->id}}</td>
                        <td>{{$row->itemName}}</td>
                        <td>{{$row->count}}</td>
                        <td>{{$row->amount}}</td>
                        <td>{{number_format($row->price)}}</td>
                        <td>{{number_format($row->priceExcludingTax)}}</td>
                      </tr>
                    @endforeach
                  @endisset
                  <tr>
                    <td></td>
                    <td>合計</td>
                    <td></td>
                    <td></td>
                    <td>{{number_format($officeTable->total->price)}}</td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            @endforeach
            {{--  <p>【売上 成田】</p>
            <table class="l-table-print">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>売上項目</th>
                  <th>処理回数</th>
                  <th>数量</th>
                  <th>金額</th>
                  <th>税抜金額</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td>駐車料金（前払い）</td>
                  <td>40</td>
                  <td>40</td>
                  <td>221,910</td>
                  <td>201,420</td>
                </tr>
                <tr>
                  <td></td>
                  <td>駐車料金（後払い）</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td></td>
                  <td>駐車料金（追加／返金）</td>
                  <td>1</td>
                  <td>1</td>
                  <td>770</td>
                  <td>700</td>
                </tr>
                <tr>
                  <td></td>
                  <td>一日利用（チケット）</td>
                  <td></td>
                  <td>0</td>
                  <td>0</td>
                  <td></td>
                </tr>
                <tr>
                  <td></td>
                  <td>一日利用（チケット以外）</td>
                  <td></td>
                  <td>4</td>
                  <td>0</td>
                  <td></td>
                </tr>
                <tr>
                  <td></td>
                  <td>合計</td>
                  <td></td>
                  <td></td>
                  <td>222,680</td>
                  <td></td>
                </tr>
              </tbody>
            </table>
            <p>【売上 プレ】</p>
            <table class="l-table-print">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>売上項目</th>
                  <th>処理回数</th>
                  <th>数量</th>
                  <th>金額</th>
                  <th>税抜金額</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td>駐車料金（前払い）</td>
                  <td>5</td>
                  <td>5</td>
                  <td>33,390</td>
                  <td>30,243</td>
                </tr>
                <tr>
                  <td></td>
                  <td>駐車料金（後払い）</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td></td>
                  <td>駐車料金（追加／返金）</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td></td>
                  <td>一日利用（チケット）</td>
                  <td></td>
                  <td>0</td>
                  <td>0</td>
                  <td></td>
                </tr>
                <tr>
                  <td></td>
                  <td>一日利用（チケット以外）</td>
                  <td></td>
                  <td>0</td>
                  <td>0</td>
                  <td></td>
                </tr>
                <tr>
                  <td></td>
                  <td>合計</td>
                  <td></td>
                  <td></td>
                  <td>33,390</td>
                  <td></td>
                </tr>
              </tbody>
            </table>  --}}
          @endisset
          @isset($data['purchaseOnlyTable'])
            <p>【売上 商品のみ】</p>
            <table class="l-table-print">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>売上項目</th>
                  <th>処理回数</th>
                  <th>数量</th>
                  <th>金額</th>
                  <th>税抜金額</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data['purchaseOnlyTable']->rows as $row)
                  <tr>
                    <td>{{$row->id}}</td>
                    <td>{{$row->itemName}}</td>
                    <td>{{$row->count}}</td>
                    <td>{{$row->amount}}</td>
                    <td>{{number_format($row->price)}}</td>
                    <td>{{number_format($row->priceExcludingTax)}}</td>
                  </tr>
                @endforeach
                <tr>
                  <td></td>
                  <td>合計</td>
                  <td></td>
                  <td></td>
                  <td>{{number_format($data['purchaseOnlyTable']->total->price)}}</td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          @endisset
          @isset($data['totalSalesTable'])
            <p>【売上 合計】</p>
            <table class="l-table-print">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>売上項目</th>
                  <th>処理回数</th>
                  <th>数量</th>
                  <th>金額</th>
                  <th>税抜金額</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td>駐車料金（前払い）</td>
                  <td>{{$data['totalSalesTable']->parkingFeeInAdvance->count}}</td>
                  <td>{{$data['totalSalesTable']->parkingFeeInAdvance->amount}}</td>
                  <td>{{number_format($data['totalSalesTable']->parkingFeeInAdvance->price)}}</td>
                  <td>{{number_format($data['totalSalesTable']->parkingFeeInAdvance->priceExcludingTax)}}</td>
                </tr>
                @isset($data['totalSalesTable']->goodsSales)
                  @foreach ($data['totalSalesTable']->goodsSales->rows as $row)
                    <tr>
                      <td>{{$row->id}}</td>
                      <td>{{$row->itemName}}</td>
                      <td>{{$row->count}}</td>
                      <td>{{$row->amount}}</td>
                      <td>{{number_format($row->price)}}</td>
                      <td>{{number_format($row->priceExcludingTax)}}</td>
                    </tr>
                  @endforeach
                @endisset
                {{--  <tr>
                  <td></td>
                  <td>駐車料金（前払い）</td>
                  <td>45</td>
                  <td>45</td>
                  <td>255,300</td>
                  <td>231,663</td>
                </tr>
                <tr>
                  <td></td>
                  <td>駐車料金（後払い）</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td></td>
                  <td>駐車料金（追加／返金）</td>
                  <td>1</td>
                  <td>1</td>
                  <td>770</td>
                  <td>700</td>
                </tr>
                <tr>
                  <td></td>
                  <td>一日利用（チケット）</td>
                  <td></td>
                  <td>0</td>
                  <td>0</td>
                  <td></td>
                </tr>
                <tr>
                  <td></td>
                  <td>一日利用（チケット以外）</td>
                  <td></td>
                  <td>4</td>
                  <td>0</td>
                  <td></td>
                </tr>  --}}
                <tr>
                  <td></td>
                  <td>合計</td>
                  <td></td>
                  <td></td>
                  <td>{{number_format($data['totalSalesTable']->total->price)}}</td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          @endisset
          @isset($data['cashTable'])
            <p>【現金】</p>
            <table class="l-table-print">
              <thead>
                <tr>
                  <th></th>
                  <th></th>
                  <th>処理回数</th>
                  <th></th>
                  <th>金額</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td>現金</td>
                  <td>{{$data['cashTable']->cash->count}}</td>
                  <td></td>
                  <td>{{number_format($data['cashTable']->cash->price)}}</td>
                </tr>
                <tr>
                  <td></td>
                  <td>入金</td>
                  <td>{{$data['cashTable']->deposit->count}}</td>
                  <td></td>
                  <td>{{number_format($data['cashTable']->deposit->price)}}</td>
                </tr>
                {{--  <tr>
                  <td></td>
                  <td>出金</td>
                  <td>0</td>
                  <td></td>
                  <td>0</td>
                </tr>  --}}
                {{--  <tr>
                  <td></td>
                  <td>両替</td>
                  <td>0</td>
                  <td></td>
                  <td>0</td>
                </tr>  --}}
                <tr>
                  <td></td>
                  <td>釣り銭</td>
                  <td></td>
                  <td></td>
                  <td>{{number_format($data['cashTable']->change->price)}}</td>
                </tr>
                <tr>
                  <td></td>
                  <td>現金残高</td>
                  <td></td>
                  <td></td>
                  <td>{{number_format($data['cashTable']->cashBalance->price)}}</td>
                </tr>
              </tbody>
            </table>
          @endisset
          @isset($data['creditsTable'])
            <p>【クレジット】</p>
            <table class="l-table-print">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>クレジット</th>
                    <th>処理回数</th>
                    <th>数量</th>
                    <th>金額</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($data['creditsTable']->rows as $row)
                  <tr>
                    <td>{{$row->id}}</td>
                    <td>{{$row->itemName}}</td>
                    <td>{{$row->count}}</td>
                    <td>{{$row->amount}}</td>
                    <td>{{number_format($row->price)}}</td>
                  </tr>
                @endforeach
                {{--  <tr>
                    <td>3</td>
                    <td>JCB</td>
                    <td>6</td>
                    <td>6</td>
                    <td>38,490</td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>イオンカード</td>
                    <td>2</td>
                    <td>2</td>
                    <td>13,310</td>
                </tr>
                <tr>
                    <td>23</td>
                    <td>PayPay</td>
                    <td>1</td>
                    <td>1</td>
                    <td>1,430</td>
                </tr>
                <tr>
                    <td>24</td>
                    <td>145 VISA</td>
                    <td>14</td>
                    <td>14</td>
                    <td>77,690</td>
                </tr>
                <tr>
                    <td></td>
                    <td>事前決済SBI</td>
                    <td>5</td>
                    <td>5</td>
                    <td>25,730</td>
                </tr>  --}}
                <tr>
                  <td></td>
                  <td>合計</td>
                  <td>{{$data['creditsTable']->total->count}}</td>
                  <td>{{$data['creditsTable']->total->amount}}</td>
                  <td>{{number_format($data['creditsTable']->total->price)}}</td>
                </tr>
                </tbody>
            </table>
          @endisset
          @isset($data['couponTable'])
            <p>【クーポン】</p>
            <table class="l-table-print">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>クーポン</th>
                  <th>処理回数</th>
                  <th>数量</th>
                  <th>金額</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data['couponTable']->rows as $row)
                  <tr>
                    <td>{{$row->id}}</td>
                    <td>{{$row->itemName}}</td>
                    <td>{{$row->count}}</td>
                    <td>{{$row->amount}}</td>
                    <td>{{number_format($row->price)}}</td>
                  </tr>
                @endforeach
                <tr>
                  <td></td>
                  <td>合計</td>
                  <td>{{$data['couponTable']->total->count}}</td>
                  <td>{{$data['couponTable']->total->amount}}</td>
                  <td>{{number_format($data['couponTable']->total->price)}}</td>
                </tr>
              </tbody>
            </table>
          @endisset
          {{--  <p>【割引券】<span style="font-size:small;">バグ: 割引券の種類を修正すると表示がおかしくなる</span></p>
          <table class="l-table-print">
            <thead>
              <tr>
                <th>ID</th>
                <th>割引券</th>
                <th></th>
                <th>数量</th>
                <th>金額</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>28</td>
                <td>LINE300円引き</td>
                <td></td>
                <td>9</td>
                <td>2,700</td>
              </tr>
              <tr>
                <td>32</td>
                <td>プレミア300円券</td>
                <td></td>
                <td>4</td>
                <td>1,200</td>
              </tr>
              <tr>
                <td>22</td>
                <td>Mカード一日券</td>
                <td></td>
                <td>1</td>
                <td>710</td>
              </tr>
              <tr>
                <td></td>
                <td>合計</td>
                <td></td>
                <td>14</td>
                <td>4,610</td>
              </tr>
            </tbody>
          </table>  --}}
          @isset($data['giftCertificatesTable'])
            <p>【商品券】</p>
            <table class="l-table-print">
              <thead>
                <tr>
                  <th></th>
                  <th></th>
                  <th>処理回数</th>
                  <th>数量</th>
                  <th>金額</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td>合計</td>
                  <td>{{$data['giftCertificatesTable']->total->count}}</td>
                  <td>{{$data['giftCertificatesTable']->total->amount}}</td>
                  <td>{{number_format($data['giftCertificatesTable']->total->price)}}</td>
                </tr>
              </tbody>
            </table>
          @endisset
        </div>
      </div><!-- l-container__inner -->
    </main>
@endsection
@push("scripts")
  <script>
    document.addEventListener('DOMContentLoaded', function() {

      // URLに ?print=1 が含まれているかどうかで処理を分岐します
      const isPrintView = new URLSearchParams(window.location.search).has('print');

      if (isPrintView) {
        // --- 印刷用ページの処理 ---
        const params = new URLSearchParams(window.location.search);
        const pageSize = params.get('page_size');

        // @page スタイルを動的に設定
        if (pageSize) {
            setPrintStyle(pageSize.replace('_', ' '))
        }

        // ページの読み込みが完了したら印刷ダイアログを開く
        window.addEventListener('load', () => {
            window.print();
        });

      } else {
        // --- 通常表示ページの処理 ---
        const buttonInfo = { id: 'printButton', size: 'A4 portrait'};

        const button = document.getElementById(buttonInfo.id);
        if (button) {
          button.addEventListener('click', function() {
            // 現在のURLとパラメータを取得
            const currentUrl = new URL(window.location.href);

            // 印刷用のパラメータを追加
            currentUrl.searchParams.set('print', '1');
            // CSSの 'size' プロパティ値に半角スペースが含まれるため '_' に置換
            currentUrl.searchParams.set('page_size', buttonInfo.size.replace(' ', '_'));

            // 新しいタブで印刷用URLを開く
            window.open(currentUrl.href, '_blank');
          });
        }
      }

      function setPrintStyle(size) {
        const style = document.createElement('style');
        style.media = 'print';
        style.innerHTML = '@page { size: ' + size + '; margin: 10mm; }';
        document.head.appendChild(style);
      }
    });
  </script>
@endpush
