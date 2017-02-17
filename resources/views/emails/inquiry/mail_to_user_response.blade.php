@extends('emails.layout')

@section('content')
<p>この度はお問い合わせいただきありがとうございます。</p>

<p>お問い合わせ内容</p>

<p>{{$inquiry_content}}</p>

<p>返信内容</p>

<p>{{$response_content}}</p>

<p>もしご不明な点がございましたらお手数ですが再度お問い合わせをお願いいたします。</p>
<p><a href="{{url('/inquiry')}}">{{url('/inquiry')}}</a></p>
@stop
