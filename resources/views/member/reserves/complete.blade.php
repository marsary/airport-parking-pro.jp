@extends('layouts.member.authenticated')

@section('content')
<div class="p-user-input-header">
  <h1 class="p-user-input-header__title">受付終了</h1>
</div>


<div class="l-user-input__inner">
  <p class="text-center u-font--green u-font--36 u-font--bold u-font-lh--2 u-mb4">受付入力は以上です。<br>スタッフへ端末をお返しください。</p>

  <div class="p-user-input-complete__message u-horizontal-auto ">
    <p class="text-center">お客様の予約コードは {{$reserveCode}} です。</p>
    <div id="qrcodeLink">
      {!! QrCode::size(150)->generate(route('manage.deals.show', [$deal->id])) !!}
      {{--  <img src="{{ asset('images/dummy/dummy_qr.jpg') }}" width="150" height="150" alt="QRコード" class="is-block u-mt4 u-horizontal-auto">  --}}
    </div>
  </div>

</div>


@endsection
@push("scripts")
<script>
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
