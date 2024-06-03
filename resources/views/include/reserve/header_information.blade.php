<div class="p-user-input-header">
  <h1 class="p-user-input-header__title">受付入力</h1>
  @if ($reserve)
    <div class="p-user-input-header__info">
      <div>利用日：{{$reserve->load_date?->isoFormat('YYYY/MM/DD(ddd)')}}〜{{$reserve->unload_date_plan?->isoFormat('YYYY/MM/DD(ddd)')}}</div>
      <div>利用料金：{{number_format($reserve->price)}}円</div>
    </div>
  @endif
</div>
