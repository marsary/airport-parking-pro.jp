<!-- B-0-2 予約管理TOP-->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">
    <!-- パンくず -->
    <ul class="l-wrap__breadcrumb l-breadcrumb">
      <li class="l-breadcrumb__list">予約管理TOP</li>
    </ul>
    <div class="l-container__inner">
      <ul class="p-index__list">
        <li class="p-index__item">
          <a href="{{ route('reserves.entry_date') }}" target="_blank" rel="noreferrer noopener" class="p-index__link">新規予約<br>（お客さまで入力）</a>
        </li>
        <li class="p-index__item">
          <a href="{{ route('manage.reserves.entry_date') }}" class="p-index__link">新規予約<br>（スタッフが入力）</a>
        </li>
        <li class="p-index__item">
          <a href="{{ route('manage.deals.search') }}" class="p-index__link">検索</a>
        </li>
      </ul>

    </div>
  </main><!-- /.l-container__main -->


@endsection
@push("scripts")
<script>
</script>
@endpush
@push('css')
<style>
</style>
@endpush
