@extends('mypageLayout')

@section('mypage_content')
<div class="main-content">
	@include('parts/errormessage')
	<div class="timeline">
		@if (count($posts) > 0)
			@foreach ($posts as $post)
				<div class="tweet" id="{{$post->id}}">
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
							<a href="{{url('/mypage/post', $post->id)}}"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></a>
						</div>
					</div>
					<div class="tweet-main">
						<div class="tweet-tags">
							<ul>
								@foreach ($post->postsTags as $postsTag)
									<li>
										<a href="/tag/{{$postsTag->tag->id}}">{{$postsTag->tag->name}}</a>
									</li>
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
						<?php $i=count($post->comments); ?>
						<!-- 押したコメント表示ボタンのみ消す，そのコメントのみをshowする -->
						@if (count($post->comments) >= 5)
							<div class="more_comments" value="{{$post->id}}">
								<p>コメントを全て表示{{$post->id}}</p>
							</div>
						@endif
						@foreach ($post->comments as $comment)
							<?php if ($i > 5): ?>
								<div class="comment" style="display: none;">
									<div class="comment_user_image">
										<img src="{{url('/show/user', $comment->user_id)}}">
									</div>
									<div class="comment_detail">
										<p>{{$comment->user->nickname}}</p>
										<p>{{$comment->content}}</p>
										<div class="comment_update" id="{{$comment->id}}">
											<form action="{{url('/mypage/updateComment')}}" method="post">
												<input type="hidden" name="_token" value="{{csrf_token()}}">
												<input type="hidden" name="id" value="{{$comment->id}}">
												<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
												<input type="hidden" name="post_id" value="{{$post->id}}">
												<input type="hidden" name="url" value="{{AppUtil::urlSlash(Request::url())}}">
												<textarea name="comment" rows="2" cols="40" class="form-control" maxlength="500">{{$comment->content}}</textarea>
												<input type="submit" value="保存" class="btn btn-primary pull-right" onclick="return confirm_dialog(this, 'コメントを編集しますか？');">
											</form>
										</div>
										<div class="comment_created_at">
											<p>{{$comment->created_at}}</p>
										</div>
									</div>
									<div class="comment_setting">
										<form method="POST" class="delete_comment" id="delete_comment_{{$comment->id}}" action="/mypage/deleteComment/{{$post->id}}">
											<input type="hidden" name="_token" value="{{csrf_token()}}">
											<input type="hidden" name="comment_id" value="{{$comment->id}}">
											<i class="fa fa-trash comment_delete_btn" aria-hidden="true"></i>
										</form>
										@if ($comment->user_id == $user->id)
											<i class="fa fa-pencil edit" aria-hidden="true" value="{{$comment->id}}"></i>
										@endif
									</div>
								</div>
								<?php $i--;?>
							<?php else: ?>
								<div class="comment">
									<div class="comment_user_image">
										<img src="{{url('/show/user', $comment->user_id)}}">
									</div>
									<div class="comment_detail">
										<p>{{$comment->user->nickname}}</p>
										<p>{{$comment->content}}</p>
										<div class="comment_update" id="{{$comment->id}}">
											<form action="{{url('/mypage/updateComment')}}" method="post">
												<input type="hidden" name="_token" value="{{csrf_token()}}">
												<input type="hidden" name="id" value="{{$comment->id}}">
												<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
												<input type="hidden" name="post_id" value="{{$post->id}}">
												<input type="hidden" name="url" value="{{AppUtil::urlSlash(Request::url())}}">
												<textarea name="comment" rows="2" cols="40" class="form-control" maxlength="500">{{$comment->content}}</textarea>
												<input type="submit" value="保存" class="btn btn-primary pull-right" onclick="return confirm_dialog(this, 'コメントを編集しますか？');">
											</form>
										</div>
										<div class="comment_created_at">
											<p>{{$comment->created_at}}</p>
										</div>
									</div>
									<div class="comment_setting">
										<form method="POST" class="delete_comment" id="delete_comment_{{$comment->id}}" action="/mypage/deleteComment/{{$post->id}}">
											<input type="hidden" name="_token" value="{{csrf_token()}}">
											<input type="hidden" name="comment_id" value="{{$comment->id}}">
											<i class="fa fa-trash comment_delete_btn" aria-hidden="true"></i>
										</form>
										@if ($comment->user_id == $user->id)
											<i class="fa fa-pencil edit" aria-hidden="true" value="{{$comment->id}}"></i>
										@endif
									</div>
								</div>
							<?php endif ?>
						@endforeach
						<div class="new_comment">
							<div class="comment_user_image">
								<img src="{{url('/show/user', $user->id)}}">
							</div>
							<form action="{{url('/post/comment')}}" method="post">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
								<input type="hidden" name="post_id" value="{{$post->id}}">
								<input type="hidden" name="url" value="{{AppUtil::urlSlash(Request::url())}}">
								<textarea name="comment" rows="3" cols="40" class="form-control" placeholder="コメントする（5文字以上500文字以内）" maxlength="500">{{old('comment')}}</textarea>
								<input type="submit" value="コメント投稿" class="btn btn-primary pull-right" onclick="return confirm_dialog(this, '投稿してもよろしいですか？');">
							</form>
						</div>
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

@section('js_partial')
<script>
	// マイページの投稿のいいねが押されるとモーダルが開く
	$(document).on('click', '.article_img_footer_good_field', function(){
		// いいねモーダルを追加
		var tweet_id = $(this).parents('.tweet').attr("id");
		$.ajax({
			type: 'POST',
			url: "{{url('/mypage/getPost')}}",
			data: {
				'tweet_id': tweet_id,
				'_token': '{{csrf_token()}}'
			},
			success: function (res) {
				console.log(res.message);
				$('.tweet').append("<div class='good_modal'><h4><i class='fa fa-thumbs-o-up' aria-hidden='true'></i><span>いいね！した人</span><a id='modal_close' class='button_link'>閉じる</a></h4><div class='good_users'></div></div>");
				if ( res.good_users.length > 0 ) {
					for (var i = 0; i < res.good_users.length; i++) {
						$('.good_users').append("<div class='good_user'><img src=" + res.url_images[i] + "><p>" + res.good_users[i]["nickname"] + "</p></div>")
					}
				} else {
					$('.good_users').append("<div class='no_good'><p>まだいいねがありません</p></div>")
				}

				// キーボード操作などにより，オーバーレイが多重起動するのを防ぐ
				$(this).blur();
				if($('.modal_overlay')[0]) return false;

				$('body').append('<div class="modal_overlay"></div>');
				$('.modal_overlay').fadeIn(300);

				centeringGoodModal();
				$('.good_modal').fadeIn(300);
			}
		});
	});

	// いいねモーダルをセンタリングする関数
	function centeringGoodModal() {
		// var pxleft = (($(window).width() - $(".good_modal").width()) / 2);
		var pxleft = (($(window).width() - $(".good_modal").outerWidth()) / 2);
		// var pxtop = (($(window).height() - $(".good_modal").height()) / 2);
		var pxtop = (($(window).height() - $(".good_modal").outerHeight()) / 2);
		$(".good_modal").css({"left": pxleft + "px"});
		$(".good_modal").css({"top": pxtop + "px"});
	}

	// ウィンドウがリサイズされたら，センタリングする
	$(window).resize(centeringGoodModal);

	// マイページのモーダルウィンドウ終了
	// $(".modal_overlay, #modal_close").unbind().click(function(){
	$(document).on('click', '.modal_overlay, #modal_close', function(){
		$(".good_modal, .modal_overlay").fadeOut(300, function(){
			$(".modal_overlay").remove();
			$(".good_modal").remove();
		});
	});

	// 投稿削除時のモーダル
	$('.post_delete_btn').on('click', function(){
		confirm_dialog($(this), 'この投稿を削除してもよろしいですか？');
	});

	// コメント削除時のモーダル
	$('.comment_delete_btn').on('click', function(){
		confirm_dialog($(this), 'このコメントを削除してもよろしいですか？');
	});

	// コメントを全て表示
	$('.tweet-comment .more_comments').on('click', function(){
		var post_id = $(this).attr('value');
		$('.tweet#' + post_id + ' .comment').show();
		$('.tweet#' + post_id + ' .more_comments').hide();
	});

	// コメント全て表示ボタンのマウス処理
	$('.tweet-comment .more_comments').mouseover(function() {
		$(this).addClass("mouseovered");
	});
	$('.tweet-comment .more_comments').mouseout(function() {
		$(this).removeClass("mouseovered");
	});

	// コメント編集エリア表示切り替え
	$('.comment_setting .edit').on('click', function(){
		var click_value = $(this).attr("value");
		console.log(click_value);
		if($('.comment_update#' + click_value).css('display') == 'none'){
			console.log($('.comment_update#' + click_value).css('display'));
			$('.comment_update#' + click_value).show();
		} else {
			console.log($('.comment_update#' + click_value).css('display'));
			$('.comment_update#' + click_value).hide();
		}
	});

	// サイドバー固定
	if (window.location.href.split("/").pop() != "updateprofile") {
		var fixedSidebar = (function() {
		var navi,
			main,
			main_scroll,
			fixed_start,
			fixpx_top,
			fixpx_end_top;
		return {
			run : function() {
				navi = $('.side');      //固定するレイヤー
				main = $('.timeline');  //メインのレイヤー
				this.refresh();
			},
			// 基準になる数値の計算
			refresh : function() {
				navi.css({
					position : 'relative',
					top : 'auto',
					width : $('.side').width(),
					width : $('.main-content').width() * 0.35
				});
				// メインコンテンツとナビの上限
				var navi_top = navi.offset().top - parseInt(navi.css('margin-top'));
				var main_top = main.offset().top - parseInt(main.css('margin-top'));
				if(navi_top + navi.outerHeight(true) < main_top + main.outerHeight(true)) {
					fixpx_top = Math.max(navi.outerHeight(true) - $(window).height(), 0);
					if($(window).height() > navi.outerHeight(true)) {
					main_scroll = main_top + main.outerHeight(true) - $(window).height() - (navi.outerHeight(true) - $(window).height());
					fixed_start = navi.offset().top - parseInt(navi.css('margin-top'));
					fixpx_end_top = main_scroll;
					}
					else {
					main_scroll = main_top + main.outerHeight(true) - $(window).height();
					fixed_start = (navi.offset().top + navi.outerHeight(true)) - $(window).height() - parseInt(navi.css('margin-top'));
					fixpx_end_top = main_scroll - (navi.outerHeight(true) - $(window).height());
					}
					$(window).off('scroll', _onScroll).on('scroll', _onScroll);
				} else {
					$(window).off('scroll', _onScroll);
				}
				$(window).trigger('scroll');
			}
		};

		function _onScroll() {
			var ws = $(window).scrollTop();
			var width_timeline = $('.timeline').width();
			var width_outside_margin = $(window).width() - $('.main-content').width();
			var width_inside_margin  = $('.main-content').width() - width_timeline - $('.side').width();
			var width = width_timeline + width_outside_margin/2 + width_inside_margin
			if(ws > main_scroll) {
				navi.css({
					position : 'fixed',
					top : (fixpx_end_top - ws) + 'px',
					left : width
				});
			} else if(ws > fixed_start) {
				navi.css({
					position : 'fixed',
					top : -fixpx_top + 'px',
					left : width
				});
			} else {
				navi.css({
					position : 'relative',
					top : 'auto',
					left : '0'
				});
			}
		}
		})();

		$(window).on('load', function() {
			fixedSidebar.run();
		}).on('resize', function() {
			fixedSidebar.refresh();
		});
	};

</script>
@stop