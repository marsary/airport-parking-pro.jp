@extends('layouts.manage.app')

@section('content')


    <div class="l-container__admin">
        <div class="c-form__admin--title">パスワードを忘れた場合</div>
            <form method="POST" action="{{ route('form.password.email') }}" class="c-form__admin">
                @csrf
                <div>
                    <x-input-label for="email" :value="__('メールアドレスを入力してください')" />
                    <x-text-input id="email" class="c-input__admin" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                </div>
                <input type="submit" value="パスワード再設定用メール送信" class="c-button--yellow u-horizontal-auto" />
            </form>
            <p class="text-center">上記メールアドレスに再設定用のURLを送信します。</p>
        </div>
    <div class="text-center u-mt2">
        <a href="">ログイン画面に戻る</a>
    </div>

@endsection
@push("scripts")
<script>

</script>
@endpush
@push('css')
<style>
</style>
@endpush