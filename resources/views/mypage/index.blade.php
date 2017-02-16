@extends('mypageLayout')

@section('mypage_content')
<div class="main-content">
	<div class="timeline">
		@if (count($posts) > 0)
			@foreach ($posts as $post)
				<div class="tweet">
					<div class="tweet-header">
						<div id="tweet-header-image">
							<img src="{{url('/show/user', $user->id)}}">
						</div>
						<div id="tweet-header-status">
							<div id="tweet-header-name">
								<p>{{$user->nickname}}</p>
							</div>
							<div id="tweet-header-date">
								<p>{{date('Y-m-d', strtotime($post->updated_at))}}</p>
							</div>
						</div>
						<div id="tweet-header-icon">
							<form method="POST" class="delete_post" id="delete_post_{{$post->id}}" action="/mypage/deletePost/{{$post->id}}">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<input type="hidden" name="post_id" value="{{$post->id}}">
								<i class="fa fa-trash fa-lg post_delete_btn" aria-hidden="true"></i>
							</form>
							<a href="{{url('/mypage/post', $post->id)}}"><i class="fa fa-cog fa-lg" aria-hidden="true"></i></a>
						</div>
					</div>
					<div class="tweet-main">
						<div class="tweet-tags">
							<ul>
								@foreach ($post->postsTags as $postsTag)
									<li>{{$postsTag->tag->name}}</li>
								@endforeach
							</ul>
						</div>
						<div class="tweet-image" style="background-image: url({{url(AppUtil::showPostImage($post))}});">
						</div>
						<div class="tweet-footer">
							@if (Auth::check())
								<div class="article_img_footer_good_field" id="good">
										<span>いいね！</span>
										<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
										<span id="good_count">{{count($post->goods)}}</span>
								</div>
							@else
								<a href="{{url('/login?a_d='. $post->id)}}">
								<div class="article_img_footer_good_field">
										<span>いいね！</span>
										<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
										<span id="good_count">{{count($post->goods)}}</span>
								</div>
								</a>
							@endif
								<div class="article_img_footer_comment_field">
										<span>コメント</span>
										<i class="fa fa-commenting-o" aria-hidden="true"></i>
										<span>{{count($post->comments)}}</span>
								</div>
						</div>
						<div class="tweet-episode">
							<p>
								エピソード:&nbsp;{{$post->episode}}
							</p>
							<p>
								年代:&nbsp;{{AppUtil::photoAgeLabel()[$post->age]}}
							</p>
							<p>
								撮影時の気持ち:&nbsp;{{AppUtil::photoFeelingLabel()[$post->feeling]}}
							</p>
							<p>
								撮影場所:&nbsp;{{AppUtil::postNumberRemove($post->address)}}
							</p>
							<p>
								撮影した日:&nbsp;{{date('Y年n月', strtotime($post->photo_date))}}
							</p>
						</div>
					</div>
					<div class="tweet-comment">
					</div>
				</div>
			@endforeach
		@else
			<div class="no-tweets">
				<p>まだ投稿がありません</p>
			</div>
		@endif
	</div>

	<div class="side">
		<div class="sidebar">
			<div class="empty-content">
			</div>
			<div class="side-content">
				<p>サイドコンテンツ1</p>
			</div>
			<div class="side-content">
				<p>サイドコンテンツ2</p>
			</div>
			<div class="empty-content">
			</div>
		</div>
	</div>
</div>
@endsection
