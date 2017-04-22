@extends('layout')
@section('css_partial')
<style>
form {
margin-bottom: 0;
}
</style>
@stop
@section('body')
<div class='mypage_list'>
	<div class="status">
		<div id="image">
			<img src="{{url('/show/user', $user->id)}}">
			<div id="setting-image">
				<input type="file" id="file" style="display:none;">
				<a href="#"><i class="fa fa-cog fa-lg" aria-hidden="true" onClick="$('#file').click();"></i></a>
			</div>
		</div>
		<div id="name">
			<p>{{$user->nickname}}</p>
		</div>
		<div class="menu">
			<ul>
				<li><a href="{{url('/mypage')}}" class="@if (AppUtil::urlSlash(Request::url()) == 'mypage') selected @endif">タイムライン</a></li>
				<li><a href="{{url('/mypage/a_post')}}">投稿する</a></li>
				<li><a href="{{url('/mypage/good')}}" class="@if (AppUtil::urlSlash(Request::url()) == 'good') selected @endif">いいね</a></li>
				<li><a href="{{url('/mypage/favorite')}}" class="@if (AppUtil::urlSlash(Request::url()) == 'favorite') selected @endif">お気に入り</a></li>
				<li><a href="{{url('/mypage/goes')}}" class="@if (AppUtil::urlSlash(Request::url()) == 'goes') selected @endif">行きたい</a></li>
				<li><a href="{{url('/mypage/followtag')}}" class="@if (AppUtil::urlSlash(Request::url()) == 'followtag') selected @endif">タグ</a></li>
				<li><a href="{{url('/mypage/updateprofile')}}" class="@if (AppUtil::urlSlash(Request::url()) == 'updateprofile' or AppUtil::urlSlash(Request::url()) == 'updateprivacy') selected @endif">設定</a></li>
			</ul>
		</div>
	</div>

	<div class="smart_menu">
		<a href="{{url('/mypage')}}" class="@if (AppUtil::urlSlash(Request::url()) == 'mypage') selected @endif"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i></a>
		<a href="{{url('/mypage/a_post')}}"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
		<a href="{{url('/mypage/good')}}" class="@if (AppUtil::urlSlash(Request::url()) == 'good') selected @endif"><i class="fa fa-thumbs-o-up fa-lg" aria-hidden="true"></i></a>
		<a href="{{url('/mypage/followtag')}}" class="@if (AppUtil::urlSlash(Request::url()) == 'followtag') selected @endif"><i class="fa fa-tag fa-lg" aria-hidden="true"></i></a>
		<a href="{{url('/mypage/updateprofile')}}" class="@if (AppUtil::urlSlash(Request::url()) == 'updateprofile' or AppUtil::urlSlash(Request::url()) == 'updateprivacy') selected @endif"><i class="fa fa-cog fa-lg" aria-hidden="true"></i></a>
	</div>

	@yield('mypage_content')
</div>
@stop
@section('js_partial')
	<script src="{{url('/js/mypage.js')}}"></script>
@stop