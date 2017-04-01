@extends('layout')
@section('css_partial')
<style>
ul {
	padding: 0;
	list-style: none;
}
a {
	color: black;
}
a:hover {
	color: black;
}
</style>
@stop
@section('body')
<div class="tag_img" style="background-image: url({{url('/background.jpg')}})">
	<h1>{{$tag->name}}</h1>
	@if (Auth::check())
		@if (in_array($tag->id, $followtag_ids))
			<button class="btn btn-default" id="unfollowBtn">フォロー中</button>
		@else
			<button class="btn btn-default" id="followBtn">フォロー</button>
		@endif
	@endif
</div>
<div class="tag_wrapper">
	<div class="relation_tag">
		<div class="relation_tag_header">
			<i class="fa fa-tags" aria-hidden="true"></i>
			<span>関連するタグ</span>
		</div>
		<div class="relation_tag_content">
			@if (count($relatePostTags) != 0)
				<ul>
					@foreach ($relatePostTags as $postTag)
						<a href="{{url('/tag', $postTag->tag->id)}}"><li>{{$postTag->tag->name}}</li></a>
					@endforeach
				</ul>
			@else
				<p>関連するタグはありません</p>
			@endif
		</div>
	</div>
	<article class="tag_article_list">
		@if (count($articles) != 0)
			<ul>
				@foreach($articles as $article)
					<li>
						<a href="{{url('/article/detail', $article->post->id)}}">
							<div class="tag_article_img" style="background-image: url({{url($article->post->oneImage->image)}})"></div>
							<div class="tag_article_data_left">
								<div class="tag_article_data">
									{{$article->post->title}}
								</div>
								<div class="tag_article_data">
									{{AppUtil::postNumberRemove($article->post->address)}}
								</div>
							</div>
							<div class="tag_article_data_right">
								<div class="tag_article_user_img" style="background-image: url({{url('/show/user', $article->post->user_id)}})"></div>
							</div>
							<div class="tag_article_good">
								<span>いいね!</span>
								<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
								<span>{{count($article->post->goods)}}</span>
								<span>コメント</span>
								<i class="fa fa-commenting-o" aria-hidden="true"></i>
								<span>{{count($article->post->comments)}}</span>
							</div>
						</a>
					</li>
				@endforeach
			</ul>
		@else
			<p>このタグが付けられている投稿はありません。</p>
		@endif
	</article>
	<div class="tag_article_pagination">
		{!! $articles->render() !!}
	</div>
</div>
@stop

@section('js_partial')
<script>
// タグフォロー
$(document).on('click', '#followBtn', function (){
	var tag_id = Number("{{$tag->id}}");
  $.ajax({
    type: 'POST',
    url: "{{url('/mypage/followtag/follow')}}",
    data: {
      'tag_id': tag_id,
      '_token': '{{csrf_token()}}'
    },
    success: function (res) {
      console.log(res.message);
      $('.btn').attr('id', 'unfollowBtn');
      $('.btn').text('フォロー中');
    }
  });
});

// タグフォロー解除
$(document).on('click', '#unfollowBtn', function (){
  var tag_id = Number("{{$tag->id}}");
  $.ajax({
    type: 'POST',
    url: "{{url('/mypage/followtag/unfollow')}}",
    data: {
      'tag_id': tag_id,
      '_token': '{{csrf_token()}}'
    },
    success: function (res) {
      console.log(res.message);
      $('.btn').attr('id', 'followBtn');
      $('.btn').text('フォロー');
    }
  });
});

</script>
@stop
