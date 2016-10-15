@extends('layout')
@section('body')
<div class="article_detail_box">
    <div class="article_img">
        <div class="article_detail_nickname">
            {{$article->user->nickname}}
            {{$article->title}}
        </div>
        <div class="article_img_content">
            <img src="{{url($article->oneImage->image)}}" alt="" />
        </div>
        <div class="article_img_footer">
            <div class="article_img_footer_good_field">
                <span>いいね！</span>
                <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                <span>0</span>
            </div>
            <div class="article_img_footer_comment_field">
                <span>コメント</span>
                <i class="fa fa-commenting-o" aria-hidden="true"></i>
                <span>0</span>
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
</div>
@stop
@section('js_partial')
@stop
