<div id="modalAreaOption_{{$mode}}_{{$dynamicPricing?->sort}}" class="l-modal">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner l-modal--trash">
      <div class="l-modal__head">{{$label}}</div>
      <!-- close button -->
      <div class="l-modal__close modal_optionClose" onclick="closeEditModal({{$dynamicPricing->sort}})">×</div>
      <div class="l-modal__content">
        <form action="{{$action}}" method="POST" class="l-grid--pricing-modal__form">
          @csrf
          @method($method)
          <div class="l-grid--pricing-modal__box u-mb2 u-mt3">
            <label for="name{{$dynamicPricing?->sort ?? 0}}">名称</label>
            <input type="text" id="name{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[name]" class="u-w-full-wide" value="{{old('record_' . $dynamicPricing->sort . '.name', $dynamicPricing?->name)}}">
          </div>
          <div class="l-grid--pricing-modal__inner">
            <div class="l-grid--pricing-modal__box">
              <label for="p10{{$dynamicPricing?->sort ?? 0}}">0-10%</label>
              <input type="text" id="p10{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[p10]" value="{{old('record_'. $dynamicPricing->sort . '.p10', $dynamicPricing?->p10)}}">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="p20{{$dynamicPricing?->sort ?? 0}}">11%-20%</label>
              <input type="text" id="p20{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[p20]" value="{{old('record_'. $dynamicPricing->sort . '.p20', $dynamicPricing?->p20)}}">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="p30{{$dynamicPricing?->sort ?? 0}}">21%-30%</label>
              <input type="text" id="p30{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[p30]" value="{{old('record_'. $dynamicPricing->sort . '.p30', $dynamicPricing?->p30)}}">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="p40{{$dynamicPricing?->sort ?? 0}}">31%-40%</label>
              <input type="text" id="p40{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[p40]" value="{{old('record_'. $dynamicPricing->sort . '.p40', $dynamicPricing?->p40)}}">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="p50{{$dynamicPricing?->sort ?? 0}}">41%-50%</label>
              <input type="text" id="p50{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[p50]" value="{{old('record_'. $dynamicPricing->sort . '.p50', $dynamicPricing?->p50)}}">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="p60{{$dynamicPricing?->sort ?? 0}}">51%-60%</label>
              <input type="text" id="p60{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[p60]" value="{{old('record_'. $dynamicPricing->sort . '.p60', $dynamicPricing?->p60)}}">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="p70{{$dynamicPricing?->sort ?? 0}}">61%-70%</label>
              <input type="text" id="p70{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[p70]" value="{{old('record_'. $dynamicPricing->sort . '.p70', $dynamicPricing?->p70)}}">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="p80{{$dynamicPricing?->sort ?? 0}}">71%-80%</label>
              <input type="text" id="p80{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[p80]" value="{{old('record_'. $dynamicPricing->sort . '.p80', $dynamicPricing?->p80)}}">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="p90{{$dynamicPricing?->sort ?? 0}}">81%-90%</label>
              <input type="text" id="p90{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[p90]" value="{{old('record_'. $dynamicPricing->sort . '.p90', $dynamicPricing?->p90)}}">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="p100{{$dynamicPricing?->sort ?? 0}}">91%-100%</label>
              <input type="text" id="p100{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[p100]" value="{{old('record_'. $dynamicPricing->sort . '.p100', $dynamicPricing?->p100)}}">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="p110{{$dynamicPricing?->sort ?? 0}}">101%-110%</label>
              <input type="text" id="p110{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[p110]" value="{{old('record_'. $dynamicPricing->sort . '.p110', $dynamicPricing?->p110)}}">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="p120{{$dynamicPricing?->sort ?? 0}}">111%-120%</label>
              <input type="text" id="p120{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[p120]" value="{{old('record_'. $dynamicPricing->sort . '.p120', $dynamicPricing?->p120)}}">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="p130{{$dynamicPricing?->sort ?? 0}}">121%-130%</label>
              <input type="text" id="p130{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[p130]" value="{{old('record_'. $dynamicPricing->sort . '.p130', $dynamicPricing?->p130)}}">
            </div>
            <div class="l-grid--pricing-modal__box">
              <label for="p131{{$dynamicPricing?->sort ?? 0}}">131%-</label>
              <input type="text" id="p131{{$dynamicPricing?->sort ?? 0}}" name="record_{{ $dynamicPricing->sort }}[p131]" value="{{old('record_'. $dynamicPricing->sort . '.p131', $dynamicPricing?->p131)}}">
            </div>
          </div>
          <input type="hidden" name="sort" value="{{$dynamicPricing?->sort ?? 0}}">

          <!-- 登録ボタン -->
          <div class="l-flex--center l-grid--gap1 u-mt4">
            <button type="button" class="c-button__submit--dark-gray modal_optionClose" onclick="closeEditModal({{$dynamicPricing->sort}})">閉じる</button>
            <button type="submit" id="modal_{{$mode}}_{{$dynamicPricing?->sort}}" class="c-button__submit">保存</button>
          </div>
        </form>
      </div><!-- ./l-modal__content -->

    </div><!-- ./l-modal inner -->
    <!-- 閉じる・追加ボタン -->
  </div>
