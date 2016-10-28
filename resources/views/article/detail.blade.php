@extends('layout')
@section('css_partial')
<style>
ul {
    list-style: none;
    padding: 0;
    overflow: hidden;
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
<div class="article_detail_box">
    <div class="article_img">
        @include('parts.errormessage')
        <div class="article_detail_nickname_title">
            <div class="article_detail_nickname_title_left">
                {{$article->title}}
            </div>
            <div class="article_detail_nickname_title_right">
                <div class="article_detail_nickname_title_right_name">
                    {{$article->user->nickname}}
                </div>
                <div class="article_detail_nickname_title_right_img">
                    <img src="{{url('/show/user', $article->user_id)}}" alt="画像" />
                </div>
            </div>
        </div>
        <div class="article_img_content">
            <img src="{{url($article->oneImage->image)}}" alt="" />
        </div>
        <div class="article_img_footer">
            @if (Auth::check())
                <div class="article_img_footer_good_field" id="good">
                    <span>いいね！</span>
                    <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                    <span id="good_count">{{count($article->goods)}}</span>
                </div>
            @else
                <a href="{{url('/login?a_d='. $article->id)}}">
                <div class="article_img_footer_good_field">
                    <span>いいね！</span>
                    <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                    <span id="good_count">{{count($article->goods)}}</span>
                </div>
                </a>
            @endif
            <div class="article_img_footer_comment_field">
                <span>コメント</span>
                <i class="fa fa-commenting-o" aria-hidden="true"></i>
                <span>{{count($article->comments)}}</span>
            </div>
        </div>
    </div>
    <div class="article_detail_description">
        <div class="article_detail_description_episode">
            <p>
                エピソード:&nbsp;{{$article->episode}}
            </p>
            <p>
                年代:&nbsp;{{AppUtil::photoAgeLabel()[$article->age]}}
            </p>
            <p>
                撮影時の気持ち:&nbsp;{{AppUtil::photoFeelingLabel()[$article->feeling]}}
            </p>
            <p>
                撮影場所:&nbsp;{{AppUtil::postNumberRemove($article->address)}}
            </p>
        </div>
    </div>
    <div class="article_detail_comment_tag_field">
        <div class="article_detail_tag">
            <div class="article_detail_tag_header">
                <i class="fa fa-tags" aria-hidden="true"></i>
                <span>タグ</span>
            </div>
            <div class="article_detail_tag_content">
                @if (count($article->postsTags) != 0)
                    <ul>
                        @foreach ($article->postsTags as $postTag)
                            <a href="{{url('/tag', $postTag->tag->id)}}"><li>{{$postTag->tag->name}}</li></a>
                        @endforeach
                    </ul>
                @else
                    <p>タグ付けされていません</p>
                @endif
            </div>
        </div>
        <div class="article_detail_comment">
            <div class="article_detail_comment_header">
                <i class="fa fa-commenting-o" aria-hidden="true"></i>
                <span>コメント</span>
            </div>
            @if (count($article->comments))
                @foreach ($article->comments as $comment)
                    <div class="article_detail_comment_body">
                        <img src="{{url('/show/user', $comment->user_id)}}" alt="画像" />
                        <div class="article_detail_comment_body_content">
                            <p>{{$comment->user->nickname}}</p>
                            {!! nl2br($comment->content) !!}
                            <p class="article_detail_comment_body_date">{{$comment->created_at}}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="article_detail_comment_body">
                    <p>コメントはありません。</p>
                </div>
            @endif
            <div class="article_detail_comment_footer">
                @if (Auth::user())
                    <p>コメントする</p>
                    <form action="{{url('/post/comment')}}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                        <input type="hidden" name="post_id" value="{{$article->id}}">
                        <textarea name="comment" rows="8" cols="40" class="form-control" placeholder="5文字以上500文字以内で投稿してください。" maxlength="500">{{old('comment')}}</textarea>
                        <input type="submit" value="コメント投稿" class="btn btn-primary pull-right" onclick="return confirm_dialog(this, '投稿してもよろしいですか？');">
                    </form>
                @else
                    <div style="text-align: center;">
                        <a href="{{url('/login?a_d='. $article->id)}}"><input type="button" value="ログインしてコメントする" class="btn btn-warning"></a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
@section('js_partial')
<script type="text/javascript">
$.ajaxSetup({headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
$(function() {
    $("#good").on('click', function(){
        var post_id = {{$article->id}};
        $.ajax({
            type: "POST",
            url: "{{url('/plus_good')}}",
            data: {
                "post_id": post_id
            },
            success: function(res) {
                if (res.message == 'success') {
                    $("#good_count").text(res.count);
                }else if (res.message == 'error'){
                    alert('エラーが発生したためいいねできませんでした。');
                }else if (res.message == 'already'){
                    alert('すでにいいねしています。');
                }else {
                    alert('自分の投稿にいいねはできません。');
                }
            },
        })
    });
});
</script>
@stop
