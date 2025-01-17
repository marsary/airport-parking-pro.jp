<!-- ダイナミックプライシング 料金表一覧 -->
@extends('layouts.manage.authenticated')

@section('content')
  <div class="l-wrap">
    <main class="l-wrap__main l-container__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">ダイナミックプライシング料金表一覧</li>
      </ul>

      @include('include.messages.errors')



      <div class="l-container__inner">
        <p>設定した在庫数に対しての割合</p>

        <table class="l-table-dynamic-pricing-list">
          <thead>
            <tr>
              <th> </th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <th><button type="button" class="c-button__edit l-table-dynamic-pricing-list__button button_select" onclick="openEditModal({{$dynamicPricing->sort}})">編集</button></th>
              @endforeach
              {{--  <th><button type="" class="c-button__edit l-table-dynamic-pricing-list__button button_select">編集</button></th>
              <th><button type="" class="c-button__edit l-table-dynamic-pricing-list__button button_select">編集</button></th>  --}}
            </tr>
          </thead>
          <tbody>
            <tr>
              <th></th>
              @foreach ($dynamicPricings as $dynamicPricing)
                  <th>{{ $dynamicPricing->name }}</th>
              @endforeach
              {{--  <th>閑散期</th>
              <th>週末</th>
              <th>年末年始</th>
              <th>DP料金4</th>
              <th>DP料金5</th>
              <th>DP料金6</th>  --}}
            </tr>
            <tr>
              <th>0-10%</th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <td>{{ $dynamicPricing->p10 }}</td>
              @endforeach
            </tr>
            <tr>
              <th>11%-20%</th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <td>{{ $dynamicPricing->p20 }}</td>
              @endforeach
            </tr>
            <tr>
              <th>21%-30%</th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <td>{{ $dynamicPricing->p30 }}</td>
              @endforeach
            </tr>
            <tr>
              <th>31%-40%</th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <td>{{ $dynamicPricing->p40 }}</td>
              @endforeach
            </tr>
            <tr>
              <th>41%-50%</th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <td>{{ $dynamicPricing->p50 }}</td>
              @endforeach
            </tr>
            <tr>
              <th>51%-60%</th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <td>{{ $dynamicPricing->p60 }}</td>
              @endforeach
            </tr>
            <tr>
              <th>61%-70%</th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <td>{{ $dynamicPricing->p70 }}</td>
              @endforeach
            </tr>
            <tr>
              <th>71%-80%</th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <td>{{ $dynamicPricing->p80 }}</td>
              @endforeach
            </tr>
            <tr>
              <th>81%-90%</th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <td>{{ $dynamicPricing->p90 }}</td>
              @endforeach
            </tr>
            <tr>
              <th>91%-100%</th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <td>{{ $dynamicPricing->p100 }}</td>
              @endforeach
            </tr>
            <tr>
              <th>101%-110%</th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <td>{{ $dynamicPricing->p110 }}</td>
              @endforeach
            </tr>
            <tr>
              <th>111%-120%</th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <td>{{ $dynamicPricing->p120 }}</td>
              @endforeach
            </tr>
            <tr>
              <th>121%-130%</th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <td>{{ $dynamicPricing->p130 }}</td>
              @endforeach
            </tr>
            <tr>
              <th>131%～</th>
              @foreach ($dynamicPricings as $dynamicPricing)
                <td>{{ $dynamicPricing->p131 }}</td>
              @endforeach
            </tr>
          </tbody>
        </table>

      </div>
    </main><!-- /.l-container__main -->
  </div><!-- /.l-wrap -->

  <!-- 編集ボタンを押したら出てくるmodal -->
  @foreach ($dynamicPricings as $dynamicPricing)
    @if ($dynamicPricing->id)
      @include('manage.master.components.dynamic_pricings_modal', [
        'mode' => 'edit',
        'label' => '編集',
        'method' => 'PUT',
        'action' => route('manage.master.dynamic_pricings.update', [$dynamicPricing->id]),
        'dynamicPricing' => $dynamicPricing,
        ]
      )
    @else
      @include('manage.master.components.dynamic_pricings_modal', [
        'mode' => 'edit',
        'label' => '編集',
        'method' => 'POST',
        'action' => route('manage.master.dynamic_pricings.store'),
        'dynamicPricing' => $dynamicPricing,
        ]
      )
    @endif
  @endforeach


@endsection
@push("scripts")
<script>
  let modalCloseOption;
  let sortInput;
  function openEditModal(dynamicPricingId) {
    document.getElementById(`modalAreaOption_edit_${dynamicPricingId}`).classList.add('is-active');
  }
  function closeEditModal(dynamicPricingId) {
    document.getElementById(`modalAreaOption_edit_${dynamicPricingId}`).classList.remove('is-active');
  }

  window.addEventListener('DOMContentLoaded', function() {
    modalCloseOption = document.querySelectorAll('.modal_optionClose');
  })
</script>
@endpush
