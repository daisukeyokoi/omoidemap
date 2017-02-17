@extends('layout')
@section('body')
<div class="inquiry_box">
    <h1>お問い合わせ内容確認</h1>
    <form action="{{url('/inquiry/complete')}}" method="post" id="confirm_form">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="email" value="{{Input::get('email')}}">
        <input type="hidden" name="subject" value="{{Input::get('subject')}}">
        <input type="hidden" name="content" value="{{Input::get('content')}}">
        <table class="table">
            <tbody>
                <tr>
                    <td>メールアドレス</td>
                    <td>{{Input::get('email')}}</td>
                </tr>
                <tr>
                    <td>件名</td>
                    <td>{{Input::get('subject')}}</td>
                </tr>
                <tr>
                    <td>本文</td>
                    <td>{!! nl2br(Input::get('content')) !!}</td>
                </tr>
            </tbody>
        </table>
        <div class="inquiry_btn_field">
            <input type="submit" name="next" value="送信" class="btn btn-success" id="submit_btn">
            <input type="submit" name="back" class="btn" value="修正する" id="modify_btn">
        </div>
    </form>
</div>
@stop
@section('js_partial')
<script>
$(function(){
    $('#confirm_form').submit(function(){
        $('#submit_btn, #modify_btn').prop('disabled', true);
    });
});
</script>
@stop
