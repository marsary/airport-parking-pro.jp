<!-- ダイナミックプライシング 料金表一覧 -->
@extends('layouts.manage.authenticated')

@section('content')    <main class="l-wrap__main l-container__main">
    <main class="l-wrap__main l-container__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">ダイナミックプライシング料金表一覧</li>
        </li>
      </ul>

      <div class="l-container__inner">
       <p>設定した在庫数に対しての割合</p>
        
      <table class="l-table-dynamic-pricing-list">
        <thead>
          <tr>
            <th> </th>
            <th><button type="" class="c-button__edit l-table-dynamic-pricing-list__button button_select">編集</button></th>
            <th><button type="" class="c-button__edit l-table-dynamic-pricing-list__button button_select">編集</button></th>
            <th><button type="" class="c-button__edit l-table-dynamic-pricing-list__button button_select">編集</button></th>
            <th><button type="" class="c-button__edit l-table-dynamic-pricing-list__button button_select">編集</button></th>
            <th><button type="" class="c-button__edit l-table-dynamic-pricing-list__button button_select">編集</button></th>
            <th><button type="" class="c-button__edit l-table-dynamic-pricing-list__button button_select">編集</button></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th></th>
            <th>閑散期</th>
            <th>週末</th>
            <th>年末年始</th>
            <th>DP料金4</th>
            <th>DP料金5</th>
            <th>DP料金6</th>
          </tr>
          <tr>
            <th>0-10%</th>
            <td>100</td>
            <td>500</td>
            <td>2000</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <th>11%-20%</th>
            <td>100</td>
            <td>500</td>
            <td>2000</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <th>21%-30%</th>
            <td>100</td>
            <td>500</td>
            <td>2000</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <th>31%-40%</th>
            <td>100</td>
            <td>500</td>
            <td>2000</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <th>41%-50%</th>
            <td>100</td>
            <td>500</td>
            <td>2000</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <th>51%-60%</th>
            <td>100</td>
            <td>500</td>
            <td>2000</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <th>61%-70%</th>
            <td>100</td>
            <td>500</td>
            <td>2000</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <th>71%-80%</th>
            <td>100</td>
            <td>500</td>
            <td>2000</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <th>81%-90%</th>
            <td>100</td>
            <td>500</td>
            <td>2000</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <th>91%-100%</th>
            <td>100</td>
            <td>500</td>
            <td>2000</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <th>101%-110%</th>
            <td>100</td>
            <td>500</td>
            <td>2000</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <th>111%-120%</th>
            <td>100</td>
            <td>500</td>
            <td>2000</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <th>121%-130%</th>
            <td>100</td>
            <td>500</td>
            <td>2000</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <th>131%～</th>
            <td>100</td>
            <td>500</td>
            <td>2000</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
        </tbody>
      </table>

      </div>
    </main><!-- /.l-container__main -->
  </div><!-- /.l-wrap -->

  <!-- 編集ボタンを押したら出てくるmodal -->
  <!-- 「編集」をクリックしたら出てくるmodal -->
  <div id="modalAreaOption" class="l-modal">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner l-modal--trash">
      <div class="l-modal__head">編集</div>
      <!-- close button -->
      <div class="l-modal__close modal_optionClose">×</div>
      <div class="l-modal__content">
        <form action="" method="POST" class="l-grid--pricing-modal__form">
          <div class="l-grid--pricing-modal__box u-mb2 u-mt3">
            <label for="name">名称</label>
            <input type="text" id="name" name="" class="u-w-full-wide">
          </div>
          <div class="l-grid--pricing-modal__inner">
            <div class="l-grid--pricing-modal__box">
              <label for="">0-10%</label>
              <input type="text" id="" name="">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="">11%-20%</label>
              <input type="text" id="" name="">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="">21%-30%</label>
              <input type="text" id="" name="">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="">31%-40%</label>
              <input type="text" id="" name="">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="">41%-50%</label>
              <input type="text" id="" name="">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="">51%-60%</label>
              <input type="text" id="" name="">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="">61%-70%</label>
              <input type="text" id="" name="">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="">71%-80%</label>
              <input type="text" id="" name="">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="">81%-90%</label>
              <input type="text" id="" name="">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="">91%-100%</label>
              <input type="text" id="" name="">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="">101%-110%</label>
              <input type="text" id="" name="">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="">111%-120%</label>
              <input type="text" id="" name="">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="">121%-130%</label>
              <input type="text" id="" name="">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="">131%-</label>
              <input type="text" id="" name="">
            </div>
          </div>
     
          <!-- 登録ボタン -->
          <div class="l-flex--center l-grid--gap1 u-mt4">
            <button type="button" class="c-button__submit--dark-gray modal_optionClose">閉じる</button>
            <button type="submit" class="c-button__submit">保存</button>
          </div>
        </form>
      </div><!-- ./l-modal__content -->

    </div><!-- ./l-modal inner -->
    <!-- 閉じる・追加ボタン -->
  </div>

  <!-- モーダル -->
  <script src="{{ asset('js/modalOption.js') }}"></script>
@endsection