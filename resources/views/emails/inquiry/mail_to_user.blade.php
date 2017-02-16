@extends('emails.layout')

@section('content')
<p>この度はOmoideMapをご利用いただきありがとうございます</p>

<p>以下の内容でお問い合わせをお受けいたしました。</p>

<p>お問い合わせいただいた内容につきましては、後日運営よりメールで回答がございますのでお待ちください。</p>

<p>なお、回答が遅れる場合もございます。予めご了承いただきますようお願い致します。</p>

<p>お問い合わせ内容</p>

<p>メールアドレス: {{$email}}</p>

<p>件名: {{$subject}}</p>

<p>内容: {{$content}}</p>
@stop
