@extends('layout')
@section('css_partial')
<style>
    ul {
        padding: 0;
        list-style: none;
    }
</style>
@stop
@section('body')
<div class="login_box">
    <div class="login_box_title">
        ログイン
    </div>
    <a href="{{url('/auth/twitter')}}">
        <div class="twitter_login btn">
            <i class="fa fa-twitter fa-lg" aria-hidden="true"></i>twitterでログインする
        </div>
    </a>
    <a href="{{url('/auth/facebook')}}">
        <div class="facebook_login btn">
            <i class="fa fa-facebook fa-lg" aria-hidden="true"></i>facebookでログインする
        </div>
    </a>
    @include('parts.errormessage')
    <form action="{{url('/login')}}" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        @if (Input::get('e_id'))
            <input type="hidden" name="event_id" value="{{Input::get('e_id')}}">
        @endif
        <div class="form-group has-error">
            <div class="login_text">
                <input type="text" name="email" placeholder="(必須)メールアドレスを入力してください" class="form-control">
            </div>
            <div class="login_text">
                <input type="password" name="password" placeholder="(必須)パスワードを入力してください。" class="form-control">
            </div>
            <div class="login_memory">
                <label>
                    <input type="checkbox" name="remember" value="{{AppUtil::FLG_ON}}">ログイン情報を記憶する
                </label>
                <span class="password_reset"><a href="{{url('/reminder')}}">パスワードを忘れた方はこちら</a></span>
            </div>
            <div class="login_btn">
                <input type="submit" value="ログイン" class="btn btn-success btn-lg btn-block">
            </div>
        </div>
    </form>
</div>
@stop
