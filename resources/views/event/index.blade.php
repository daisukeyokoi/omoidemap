@extends('layout')
@section('body')
<?php
use App\Event;
?>
@if ($event->state != Event::YET && $event->state != Event::OPEN)
    <div class="e_attention">
        @if ($event->state == Event::REVIEW || $event->state == Event::WAIT_CLOSE)
            このイベントはただいま審査中です。
        @elseif ($event->state == Event::CLOSE)
            このイベントは終了しました。
        @endif
    </div>
@endif
<div class="e_img" style="background-image: url({{url($event->image)}})"></div>
<div class="e_data">
    <div class="e_title">
        {{$event->title}}
    </div>
    <div class="e_date">
        {{str_replace('-', '/', $event->start)}}&nbsp;~&nbsp;{{str_replace('-', '/', $event->end)}}
    </div>
    <div class="e_description">
        {!! nl2br($event->description) !!}
    </div>
</div>
@if ($event->state == Event::CLOSE)
<div class="e_result">
    <div class="e_result_title">
        結果発表
    </div>
    <ul class="e_result_box">
        @foreach($event->eventPosts()->where('ranking', '>=', 1)->orderBy('ranking', 'asc')->get() as $eventPost)
            <li>
                <div class="rankmark_{{$eventPost->ranking}}">
                    <div class="rankmark_string">
                        Rank{{$eventPost->ranking}}
                    </div>
                </div>
                <div class="e_result_box_image" style="background-image: url({{url(AppUtil::showPostImage($eventPost->post))}})"></div>
                <div class="e_result_box_left">
                    <div class="e_result_box_left_content">
                        {{$eventPost->post->episode}}
                    </div>
                </div>
                <div class="e_result_box_right">
                    <div class="e_result_box_user_image" style="background-image: url({{url('/show/user', $eventPost->post->user_id)}})"></div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endif
<div class="e_post_field">
    @if ($event->state == Event::OPEN)
        <div class="e_post_field_btn">
            @if (Auth::check())
                <a href="{{url('/mypage/a_post?e_id='.$event->id)}}"><input type="button" value="イベントに参加する" class="btn btn-warning"></a>
            @else
                <a href="{{url('/login?e_id='.$event->id)}}"><input type="button" value="イベントに参加する" class="btn btn-warning"></a>
            @endif
        </div>
    @endif
    <div class="e_post">
        <ul>
            @foreach ($posts as $post)
                <li><a href="{{url('/article/detail', $post->id)}}"><div class="e_post_img" style="background-image: url({{url($post->oneImage->image)}})"></div></a></li>
            @endforeach
        </ul>
    </div>
</div>
<div class="e_requirements">
    <div class="e_requirements_title">募集要項</div>
    <ul>
        <li class="e_requirements_content">
            <div class="e_requirements_content_left">応募について</div>
            <div class="e_requirements_content_right">
                {{date('Y年m月d日', strtotime($event->start))}}~{{date('Y年m月d日', strtotime($event->end))}}に、このページのイベントに参加するから投稿した作品だけが対象になります。<br/>
                本キャンペーンの趣旨に外れている場合や、利用規約を破っている場合応募資格を失うことがあります。
            </div>
        </li>
        <li class="e_requirements_content">
            <div class="e_requirements_content_left">審査について</div>
            <div class="e_requirements_content_right">
                {{date('Y年m月d日', strtotime("$event->end + 1day"))}}~{{date('Y年m月d日', strtotime("$event->end + 3days"))}}の間を審査期間とします。
            </div>
        </li>
        <li class="e_requirements_content">
            <div class="e_requirements_content_left">発表について</div>
            <div class="e_requirements_content_right">
                {{date('Y年m月d日', strtotime("$event->end + 4days"))}}に結果発表を予定しております。
            </div>
        </li>
    </ul>
</div>
@if (count($other_events) != 0)
    <div class="other_event_field">
        <div class="other_event_title">
            開催中の他のイベント
        </div>
        <ul class="e_content">
            @foreach ($other_events as $event)
                <li>
                    <a href="{{url('/event', $event->id)}}">
                        <div class="e_content_list_top">
                            <div class="e_content_list_top_title">
                                {{$event->title}}
                            </div>
                            <div class="e_content_list_top_img" style="background-image: url({{url($event->image)}})"></div>
                        </div>
                        <div class="e_content_list_bottom">
                            <p>{{$event->title}}</p>
                            <p>{{str_replace('-', '/', $event->start)}}~{{str_replace('-', '/', $event->end)}}</p>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif

@if (count($end_events) != 0)
    <div class="other_event_field">
        <div class="other_event_title">
            終了したイベント
        </div>
        <ul class="e_content">
            @foreach ($end_events as $event)
                <li>
                    <a href="{{url('/event', $event->id)}}">
                        <div class="e_content_list_top">
                            <div class="e_content_list_top_title">
                                {{$event->title}}
                            </div>
                            <div class="e_content_list_top_img" style="background-image: url({{url($event->image)}})"></div>
                        </div>
                        <div class="e_content_list_bottom">
                            <p>{{$event->title}}</p>
                            <p>{{str_replace('-', '/', $event->start)}}~{{str_replace('-', '/', $event->end)}}</p>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
@stop
