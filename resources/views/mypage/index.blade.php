@extends('layout')
@section('body')
<div class="mypage_list">
  ようこそ{{$user->nickname}}さん
  <ul>
    <li><a href="{{url('/mypage/a_post')}}">投稿する</a></li>
    <li><a href="#">自分の投稿したものの管理</a></li>
    <li><a href="#">いいねした投稿を見る</a></li>
    <li><a href="#">自分のプロフィールの編集</a></li>
    <li><a href="#">フォローしているタグの投稿一覧</a></li>
  </ul>
</div>
@stop
