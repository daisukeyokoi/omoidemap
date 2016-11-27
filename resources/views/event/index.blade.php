@extends('layout')
@section('body')
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
<div class="e_post_field">
    <div class="e_post_field_btn">
        @if (Auth::check())
            <a href="{{url('/mypage/a_post?e_id='.$event->id)}}"><input type="button" value="イベントに参加する" class="btn btn-warning"></a>
        @else
            <a href="{{url('/login?e_id='.$event->id)}}"><input type="button" value="イベントに参加する" class="btn btn-warning"></a>
        @endif
        <input type="button" value="みんなの投稿を見る" class="btn btn-success">
    </div>
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
    <div class="e_requirements_content">
        <div class="e_requirements_content_left">応募について</div>
        <div class="e_requirements_content_right">
            {{date('Y年m月d日', strtotime($event->start))}}~{{date('Y年m月d日', strtotime($event->end))}}に、このページのイベントに参加するから投稿した作品だけが対象になります。
        </div>
    </div>
    <div class="e_requirements_content">
        <div class="e_requirements_content_left">審査について</div>
        <div class="e_requirements_content_right">
            {{date('Y年m月d日', strtotime("$event->end + 1day"))}}~{{date('Y年m月d日', strtotime("$event->end + 3days"))}}の間を審査期間とします。
        </div>
    </div>
    <div class="e_requirements_content">
        <div class="e_requirements_content_left">発表について</div>
        <div class="e_requirements_content_right">
            {{date('Y年m月d日', strtotime("$event->end + 4days"))}}に結果を発表致します。
        </div>
    </div>
</div>
<div class="other_event_field">
    <div class="other_event_title">
        開催中の他のイベント
    </div>
    @if (count($other_events) != 0)
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
    @else
        <p>ただいま開催中のイベントはありません</p>
    @endif
</div>
@stop
