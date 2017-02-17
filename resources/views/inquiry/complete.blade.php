@extends('layout')

@section('body')
<div class="inquiry_box">
    <h1>送信が完了いたしました。</h1>

    <p>お問い合わせ頂きまして誠に有難うございます。内容を確認の上、管理者よりご連絡をさせていただきます。</p>
    <div class="inquiry_btn_field">
        <a href="{{url('/')}}"><input type="button" class="btn btn-primary" value="Topへ戻る"></a>
    </div>
</div>


@stop
