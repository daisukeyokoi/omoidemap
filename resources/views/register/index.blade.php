@extends('layout')
@section('body')
  <div class="login_box">
    <div class="login_box_title">
      会員登録
    </div>
    @include('parts.errormessage')
    <form action="{{url('/register')}}" method="post">
      <input type="hidden" name="_token" value="{{csrf_token()}}">
      <div class="form-group has-error">
        <div class="login_text">
          <input type="text" name="nickname" placeholder="(必須)ニックネームを入力してください" class="form-control" value="{{old('nickname')}}">
        </div>
        <div class="login_text">
          <input type="text" name="email" placeholder="(必須)メールアドレスを入力してください" class="form-control" value="{{old('email')}}">
        </div>
        <div class="login_text">
          <input type="password" name="password" placeholder="(必須)パスワード（6文字以上/64文字以下/半角英数）" class="form-control">
        </div>
        <div class="login_text">
          <input type="password" name="password_confirmation" placeholder="(必須)パスワード再入力" class="form-control">
        </div>
        <div class="login_btn">
          <input type="submit" value="登録する" class="btn btn-success btn-lg btn-block">
        </div>
      </div>
    </form>
  </div>
@stop
