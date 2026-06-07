@extends('layouts.form.authenticated')

@section('content')
<div class="p-user-input-header">
  <h1 class="p-user-input-header__title">受付終了</h1>
</div>


<div class="l-user-input__inner">
  <p class="p-user-input-complete__text u-font--green u-font--bold ">
    予約が完了しました。<br>
  </p>
  <p class="text-center u-mb2">入力いただいたメールアドレスへ<br class="br--sp" />予約内容を送信いたしました。<br>お客様のお越しをスタッフ一同、<br class="br--sp" />心よりお待ちしております。</p>

  <div class="p-user-input-complete__message u-horizontal-auto ">
    <p class="text-center u-font--bold u-font--18 u-mb1">ご予約の変更やご不明な点がありましたら、お気軽にお問い合わせください。</p>
    <p class="text-center u-font--bold u-mb2 u-font--18">サン予約センター<br>TEL.0476-33-1123<br>［受付時間］9:00〜18:00</p>
    <!-- 画面を閉じる -->
    <button class="c-button__select button_select c-button" onclick="closeOrRedirect()">画面を閉じる</button>
    <img class="p-user-input-complete__image" src="{{ asset('images/reserve/airplane_thankspage.png') }}" width="100%" height="auto" alt="空港の画像" class="is-block u-horizontal-auto">
    <div class="p-user-input-complete__after"></div>
  </div>

</div>

@endsection
@push("scripts")
<script>
  function closeOrRedirect() {
    window.close(); // 画面を閉じる
    // もし画面が閉じられない場合は、トップページにリダイレクトする
    setTimeout(function() {
      location.href = '/'; // または任意のURL
    }, 100);
  }
</script>
@endpush
@push('css')
<style>
  #qrcodeLink svg {
    display: block;
    margin-top: 4rem;
    margin-left: auto;
    margin-right: auto;
    width: 150px;
    height: 150px;
  }
</style>
@endpush
