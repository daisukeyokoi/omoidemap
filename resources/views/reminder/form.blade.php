@extends('layout')
@section('body')
  <div class="login_box">
    <div class="login_box_title">
      パスワードを忘れた方へ
    </div>
    @include('parts.errormessage')
    <div class="login_message">
      入力したメールアドレス宛にパスワード再設定手続きのメールをお送りいたします。
    </div>
    <form action="{{url('/reminder')}}" method="post">
      <input type="hidden" name="_token" value="{{csrf_token()}}">
      <div class="form-group has-error">
        <div class="login_text">
          <input type="text" name="email" placeholder="(必須)メールアドレスを入力してください" class="form-control" value="{{old('email')}}">
        </div>
        <div class="login_btn">
          <input type="submit" value="送信する" class="btn btn-success btn-lg btn-block">
        </div>
      </div>
    </form>
  </div>
@stop
