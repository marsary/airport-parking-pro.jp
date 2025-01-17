<!-- ページャー -->
<div class="c-pager">
  @if ($paginator->hasPages())
    <ul class="c-pager__list">
      @if ($paginator->onFirstPage())
      @else
        <li class="c-pager__item--first"><a href="{{ $paginator->url(1) }}" class="c-pager__link"><img src="{{ asset('images/icon/pager-last.svg') }}" width="15.5" height="18" /></a></li>
        <li class="c-pager__item--prev"><a href="{{ $paginator->previousPageUrl() }}" class="c-pager__link"><img src="{{ asset('images/icon/pager-next.svg') }}" width="12" height="18" /></a></li>
      @endif
      @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
          <li class="c-pager__item">{{$element}}...</li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <li class="c-pager__item">{{ $page }}</li>
            @else
              <li class="c-pager__item"><a href="{{ $url }}" class="c-pager__link">{{ $page }}</a></li>
            @endif
          @endforeach
        @endif
      @endforeach

      @if ($paginator->hasMorePages())
        <li class="c-pager__item--next"><a href="{{ $paginator->nextPageUrl() }}" class="c-pager__link"><img src="{{ asset('images/icon/pager-next.svg') }}" width="12" height="18" /></a></li>
        <li class="c-pager__item--last"><a href="{{ $paginator->url($paginator->lastPage()) }}" class="c-pager__link"><img src="{{ asset('images/icon/pager-last.svg') }}" width="15.5" height="18" /></a></li>
      @else
      @endif
    </ul>
    <input type="text" id="page_number" name="page_number" class="c-pager__input-pageNumber" value="{{request('page')}}">/{{$paginator->total()}}
  @endif
  <div class="c-form-select-wrap">
    <select name="limit" id="limit">
      <option value="25" {{(25 == request('limit') ) ? 'selected':''}}>25件</option>
      <option value="50" {{(50 == request('limit') ) ? 'selected':''}}>50件</option>
      <option value="75" {{(75 == request('limit') ) ? 'selected':''}}>75件</option>
      <option value="100" {{(100 == request('limit') ) ? 'selected':''}}>100件</option>
      <option value="150" {{(150 == request('limit') ) ? 'selected':''}}>150件</option>
    </select>
  </div>
</div>

@push("scripts")
<script>
  if(!window.currentUrl) {
    window.currentUrl = location.pathname;
  }
  window.addEventListener('DOMContentLoaded', function() {
    const limitSelect = document.getElementById('limit');
    const pageNumberInput = document.getElementById('page_number');
    limitSelect.addEventListener('change', function() {
      const limitVal = limitSelect.value;
      getToUrl(window.currentUrl, {'limit' : limitVal, 'page' : 1});
    })

    if(pageNumberInput) {
      pageNumberInput.addEventListener('keydown', function(event) {
        if(event.key === 'Enter'){
          //console.log('Press your Enter key.')
          const pageNumber = pageNumberInput.value;
          getToUrl(window.currentUrl, {'page' : pageNumber});
        }
      });
    }
  });
</script>
@endpush
