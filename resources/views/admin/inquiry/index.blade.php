@extends('admin.layout')

@section('body')
<?php
use App\Inquiry;
 ?>
<h1>お問い合わせ一覧</h1>
<table class="table table-bordered">
    <thead>
        <tr>
            <td>ID</td>
            <td>状況</td>
            <td>件名</td>
            <td>メールアドレス</td>
            <td>日時</td>
            <td>詳細</td>
        </tr>
    </thead>
    <tbody>
        @if (count($inquiries) != 0)
            @foreach($inquiries as $inquiry)
                <tr>
                    <td>{{$inquiry->id}}</td>
                    <td>{{Inquiry::getState()[$inquiry->state]}}</td>
                    <td>{{$inquiry->subject}}</td>
                    <td>{{$inquiry->email}}</td>
                    <td>{{$inquiry->created_at}}</td>
                    <td><a href="{{url('/admin/inquiry', $inquiry->id)}}"><button class="btn btn-primary">詳細</button></a></td>
                </tr>
            @endforeach
        @else
            <tr>
                お問い合わせはありません。
            </tr>
        @endif
    </tbody>
</table>
{!! $inquiries->render() !!}
@stop
