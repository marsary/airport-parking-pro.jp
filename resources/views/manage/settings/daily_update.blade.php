<!-- J-1 日次更新  -->
@extends('layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">予約管理TOP</li>
      </ul>

      <div class="l-container__inner">
        <p class="u-mb1">現在のサーバ日付: 2024-02-23 09:16</p>
        <table class="l-table-date u-mb1">
          <thead class="l-table-date__head">
            <tr>
              <th>事業所名</th>
              <th>更新日</th>
              <th>操作</th>
              <th>操作</th>
            </tr>
          </thead>

          <tbody class="l-table-date__body">
            <tr>
              <td>サンパーキング成田店</td>
              <td>2023-11-15</td>
              <td><a href="" class="c-link--update">日付を更新</a></td>
              <td><a href="" class="c-link--return">前日に戻す</a></td>
            </tr>
            <tr>
              <td>サンパーキング成田店</td>
              <td>2023-11-15</td>
              <td><a href="" class="c-link--update">日付を更新</a></td>
              <td><a href="" class="c-link--return">前日に戻す</a></td>
            </tr>
          </tbody>
        </table>

        <ul class="u-font--14">
          <li>· 事業所ごとにシステムの日付を更新します。</li>
          <li>· 更新時には、先に該当する事業所の全てのレジを落として下さい。</li>
          <li>· 保留車両の保留日数を更新し、同時に利用料金、合計料金等を計算し直します。</li>
          <li>· 割引券も再計算されますので、<span class="note">割引額を変更している場合、元にもどされてしまいます。</span></li>
          <li>· 日数ベースの割引券の場合、末日分の料金が変わるため、割引額が変わることがあります。</li>
        </ul>
      </div>
    </main>
  </div>
@endsection
