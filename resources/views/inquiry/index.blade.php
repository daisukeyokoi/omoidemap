@extends('layout')
@section('body')
<div class="inquiry_box">
    @include('parts.errormessage')
    <h1>お問い合わせ</h1>
    <form action="{{url('/inquiry')}}" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <table class="table">
            <tbody>
                <tr>
                    <td>メールアドレス<span class="need_mark btn btn-danger">必須</span></td>
                    <td><input type="text" name="email" class="form-control" value="@if (Auth::check()){{Auth::user()->email}}@else{{old('email')}}@endif" placeholder="メールアドレスを入力してください。" maxlength="50" required></td>
                </tr>
                <tr>
                    <td>件名<span class="need_mark btn btn-danger">必須</span></td>
                    <td><input type="text" name="subject" class="form-control" value="{{old('subject')}}" placeholder="件名を入力してください。" maxlength="50" required></td>
                </tr>
                <tr>
                    <td>本文<span class="need_mark btn btn-danger">必須</span></td>
                    <td><textarea name="content" class="form-control" placeholder="本文を入力してください。" rows="5" maxlength="500" required>{{old('content')}}</textarea></td>
                </tr>
            </tbody>
        </table>
        <div class="inquiry_btn_field">
            <input type="submit" value="送信確認" class="btn btn-success">
        </div>
    </form>
    <div class="inquiry_btn_field">
        <a href="{{url('/')}}"><button class="btn">Topへ戻る</button></a>
    </div>
</div>
@stop
