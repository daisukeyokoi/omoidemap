@extends('layout')
@section('body')
  <div class="wrapper">
    <div class="top_img" style="background-image: url('{{url('/top_img_2.jpg')}}')">
      <div class="top_img_content">
        <p>思い出を探してみよう!</p>
        <div class="input-group input-group-lg top_img_content_search">
          <input type="text" class="form-control" placeholder="検索">
          <span class="input-group-addon" id="basic-addon2"><i class="fa fa-search fa-lg" aria-hidden="true"></i></span>
        </div>
      </div>
    </div>
    <div class="top_description">
      <p>思い出の場所の写真とエピソードを投稿</p>
      <p>それを集めて地図にする。そしてその地図を頼りにみんなが旅をする</p>
    </div>
    <ul class="top_photo_list">
      <li class="top_photo_list_li"><img src="{{url('/top_img.jpg')}}" alt="" /></li>
      <li class="top_photo_list_space"></li>
      <li class="top_photo_list_li"><img src="{{url('/top_img.jpg')}}" alt="" /></li>
      <li class="top_photo_list_space"></li>
      <li class="top_photo_list_li"><img src="{{url('/top_img.jpg')}}" alt="" /></li>
    </ul>
  </div>
@stop
