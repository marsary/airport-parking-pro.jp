<!-- B-1-1 受付入力 -->
@extends('layouts.form.authenticated')

@section('content')
@include('include.reserve.header_information')
@include('include.step', ['step' => "entry"])
@include('include.messages.errors')
<div class="p-user-input__inner--sm">
  <!-- 受付入力 -->
  <form action="{{route('form.reserves.post_entry_info')}}" method="POST">
    @csrf
    <!-- 基本情報 -->
    <div class="l-flex l-grid--gap1">
      <div>
        <label for="name">氏名※</label>
        <input type="text" id="name" name="name" value="{{old('name', $reserve->name)}}">
      </div>
      <div>
        <label for="kana">ふりがな※</label>
        <input type="text" id="kana" name="kana" value="{{old('kana', $reserve->kana)}}">
      </div>
    </div>

    <div>
      <label for="tel">携帯番号※</label>
      <input type="tel" id="tel" name="tel" value="{{old('tel', $reserve->tel)}}">
    </div>

    <!-- 郵便番号・メールアドレス・領収書の名前・備考 -->
    <label for="zip">郵便番号</label>
    <input type="text" id="zip" name="zip" value="{{old('zip', $reserve->zip)}}">

    <div class="l-grid--col2 l-grid--gap1">
      <div>
        <label for="email">メールアドレス※</label>
        <input type="email" id="email" name="email" class="u-w-full-wide" value="{{old('email', $reserve->email)}}">
      </div>
      <div>
        <label for="receipt_address">領収書の名前</label>
        <input type="text" id="receipt_address" name="receipt_address" class="u-w-full-wide" value="{{old('receipt_address', $reserve->receipt_address)}}">
      </div>
    </div>

    <label for="note">備考</label>
    <textarea name="remarks" id="remarks" cols="50" rows="3">{{old('remarks', $reserve->remarks)}}</textarea>

    <div class="l-flex--center l-grid--gap1 u-mt3">
      <button type="submit" class="c-button__pagination--next">次へ進む</button>
    </div>
  </form>
</div><!-- ./p-user-input__inner -->


@endsection
@push("scripts")
<script>
</script>
@endpush
@push('css')
<style>
</style>
@endpush
