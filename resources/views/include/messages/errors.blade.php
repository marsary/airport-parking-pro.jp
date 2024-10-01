{{--  フラッシュメッセージ  --}}
@if (session('failure'))
  <div class="alert">
    <span class="closebtn">&times;</span>
    {{ session('failure') }}
  </div>
@endif
{{--  バリデーションエラー  --}}
@if (count($errors) > 0)
  <div class="alert">
    <span class="closebtn">&times;</span>
    <strong>入力エラー!</strong>
    <ul class="alert" role="alert">
      @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

@push('css')
<style>
  /* The alert message box */
  .alert {
    padding: 8px 30px;
    background-color: #f44336 !important; /* Red */
    color: white !important;
    margin-bottom: 15px;
    opacity: 1;
    transition: opacity 0.6s; /* 600ms to fade out */
  }
  .alert.success {
    background-color: #04AA6D !important;;
  }
  .alert.info {
    background-color: #2196F3 !important;;
  }
  .alert.warning {
    background-color: #ff9800 !important;;
  }
  /* The close button */
  .closebtn {
    margin-left: 15px;
    color: white;
    font-weight: bold;
    float: right;
    font-size: 22px;
    line-height: 20px;
    cursor: pointer;
    transition: 0.3s;
  }

  /* When moving the mouse over the close button */
  .closebtn:hover {
    color: black;
  }
</style>
@endpush

@push('scripts')
  <script>
    let close = document.getElementsByClassName("closebtn");
    let i;

    for (i = 0; i < close.length; i++) {
      close[i].onclick = function(){
        let div = this.parentElement;
        div.style.opacity = "0";
        setTimeout(function(){ div.style.display = "none"; }, 600);
      }
    }
  </script>
@endpush
