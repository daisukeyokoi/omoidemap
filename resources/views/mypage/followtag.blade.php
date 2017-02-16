@extends('mypageLayout')

@section('mypage_content')

<div class="tag_search clearfix">
  <input type="text" id="tag_name" class="form-control" placeholder="お気に入りのタグを探そう">
  <button class="btn btn-primary" id="submit_btn">検索</button>
</div>

<ul class="search_tags clearfix">
</ul>

<div class="follow-block">
  <i class="fa fa-tags fa-lg" aria-hidden="true"></i>フォロー中のタグ
</div>

<ul class="follow_tags clearfix">
  @foreach ($follow_tags as $follow_tag)
    <li class="tag">
      <i class='fa fa-tag fa-lg' aria-hidden='true'></i>
      <p>
        {{$follow_tag->tag->name}}
      </p>
      <a href="#" class="unfollow" id="{{$follow_tag->id}}">
        <p>×</p>
      </a>
    </li>
  @endforeach
</ul>

<div class="tag-timeline">
</div>

@stop
@section('js_partial')
<script>
// タグ検索
$(function () {
  $('#submit_btn').click(function (){
    var tag_name = $('#tag_name').val();
    $.ajax({
      type: 'POST',
      url: "{{url('/mypage/followtag')}}",
      data: {
        'tag_name': tag_name,
        '_token': '{{csrf_token()}}'
      },
      success: function (res) {
        console.log(res.message);
        if (res.search_tags.length > 0) {
          $('.search_tags .tag').remove();
          for (var i=0; i < res.search_tags.length; i++) {
            $(".search_tags").append("<li class='tag'><i class='fa fa-tag fa-lg' aria-hidden='true'></i><p>" + res.search_tags[i]["name"] + "</p><a href='#' class='follow' id='" + res.search_tags[i]["id"] + "'><p>+</p></a></li>");
          }
        } else {
          $('.search_tags .tag').remove();
          // $(".search_tags").append("<div class='tag'><p>タグがありませんでした</p></div>");
        }
      }
    });
  });
});

// タグ追加
$(document).on('click', '.follow', function (){
  var follow_tag_name = $(this).parent().children("p").text();
  var follow_tag_id   = $(this).attr("id");
  $.ajax({
    type: 'POST',
    url: "{{url('/mypage/followtag/follow')}}",
    data: {
      'tag_id': follow_tag_id,
      '_token': '{{csrf_token()}}'
    },
    success: function (res) {
      console.log(res.message);
      $(".follow_tags").append("<li class='tag'><i class='fa fa-tag fa-lg' aria-hidden='true'></i><p>" + follow_tag_name + "</p><a href='#' class='unfollow' id='" + follow_tag_id + "'><p>×</p></a></li>");
      $('.search_tags #' + follow_tag_id).parent().remove();
    }
  });
});

// タグ削除
$(document).on('click', '.unfollow', function (){
  var unfollow_tag_id = $(this).attr("id");
  $.ajax({
    type: 'POST',
    url: "{{url('/mypage/followtag/unfollow')}}",
    data: {
      'tag_id': unfollow_tag_id,
      '_token': '{{csrf_token()}}'
    },
    success: function (res) {
      console.log(res.message);
      $('.follow_tags #' + unfollow_tag_id).parent().remove();
    }
  });
});

// タグ追加部分の背景色変更
$(document).on('mouseover', '.follow', function (){
  $(this).addClass("mouseovered");
});
$(document).on('mouseout', '.follow', function (){
  $(this).removeClass("mouseovered");
});
$(document).on('mouseover', '.unfollow', function (){
  $(this).addClass("mouseovered");
});
$(document).on('mouseout', '.unfollow', function (){
  $(this).removeClass("mouseovered");
});
</script>
@stop