@extends('admin.layout')
@section('css_partial')
<style>
.event_create_ranking_main {
    width: 70%;
    float: left;
    overflow: hidden;
}
.event_create_ranking_post_image {
    width: 20%;
    height: 100px;
    margin: 10px 1%;
    float: left;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
}
.event_create_ranking_post_description {
    width: 65%;
    float: left;
    margin: 10px 1%;
    color: black;
    word-break: break-all;
}
.event_create_ranking_side {
    width: 14%;
    float: right;
    margin-right: 5%;
    height: 1200px;
}
.event_create_ranking_side h3 {
    border-bottom: 1px solid gray;
}
#event_ranking_side li img{
    width: 100px;
    height: 100px;
}

.event_create_ranking_post_image_side {
    width: 100%;
    height: 100px;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    margin-bottom: 10px;
    position: relative;
}
.detail_btn {
    width: 9%;
    float: left;
    margin: 10px 1%;
}
.sidebar_rankmark_1 {
	color: black;
	position: absolute;
    left: 0px;
	width: 0;
	height: 0;
	border-top: 20px solid gold;
	border-right: 20px solid transparent;
	border-bottom: 20px solid transparent;
	border-left: 20px solid gold;
}
.sidebar_rankmark_2 {
	color: black;
	position: absolute;
    left: 0px;
	width: 0;
	height: 0;
	border-top: 20px solid #F64F47;
	border-right: 20px solid transparent;
	border-bottom: 20px solid transparent;
	border-left: 20px solid #F64F47;
}
.sidebar_rankmark_3 {
	color: black;
	position: absolute;
    left: 0px;
	width: 0;
	height: 0;
	border-top: 20px solid #1788B7;
	border-right: 20px solid transparent;
	border-bottom: 20px solid transparent;
	border-left: 20px solid #1788B7;
}
.sidebar_rankmark_4 {
	color: black;
	position: absolute;
    left: 0px;
	width: 0;
	height: 0;
	border-top: 20px solid #5A4080;
	border-right: 20px solid transparent;
	border-bottom: 20px solid transparent;
	border-left: 20px solid #5A4080;
}
.sidebar_rankmark_5 {
	color: black;
	position: absolute;
    left: 0px;
	width: 0;
	height: 0;
	border-top: 20px solid #5A4080;
	border-right: 20px solid transparent;
	border-bottom: 20px solid transparent;
	border-left: 20px solid #5A4080;
}
.sidebar_rankmark_6 {
	color: black;
	position: absolute;
    left: 0px;
	width: 0;
	height: 0;
	border-top: 20px solid #5A4080;
	border-right: 20px solid transparent;
	border-bottom: 20px solid transparent;
	border-left: 20px solid #5A4080;
}
.sidebar_rankmark_7 {
	color: black;
	position: absolute;
    left: 0px;
	width: 0;
	height: 0;
	border-top: 20px solid #5A4080;
	border-right: 20px solid transparent;
	border-bottom: 20px solid transparent;
	border-left: 20px solid #5A4080;
}
.sidebar_rankmark_8 {
	color: black;
	position: absolute;
    left: 0px;
	width: 0;
	height: 0;
	border-top: 20px solid #5A4080;
	border-right: 20px solid transparent;
	border-bottom: 20px solid transparent;
	border-left: 20px solid #5A4080;
}
.sidebar_rankmark_9 {
	color: black;
	position: absolute;
    left: 0px;
	width: 0;
	height: 0;
	border-top: 20px solid #5A4080;
	border-right: 20px solid transparent;
	border-bottom: 20px solid transparent;
	border-left: 20px solid #5A4080;
}
.sidebar_rankmark_10 {
	color: black;
	position: absolute;
    left: 0px;
	width: 0;
	height: 0;
	border-top: 20px solid #5A4080;
	border-right: 20px solid transparent;
	border-bottom: 20px solid transparent;
	border-left: 20px solid #5A4080;
}
.sidebar_rankmark_string {
	color: white;
    position: absolute;
    left: -13px;
    top: -20px;
    font-size: 16px;
    font-weight: bold;
}
.event_detail_post li {
    cursor: pointer;
}
</style>
@stop
@section('body')
<div class="event_create_ranking_main" id="main_column">
    <p>クリックすると順位のところに追加されます。</p>
    <h2>{{$event->title}}の順位付け</h2>
    <ul class="event_detail_post">
        @foreach($posts as $post)
            <li class="event_detail_post_list" data-id="{{$post->id}}" data-image="{{url(AppUtil::showPostImage($post))}}">
                <div class="event_create_ranking_post_image" style="background-image: url('{{url(AppUtil::showPostImage($post))}}')"></div>
                <div class="event_create_ranking_post_description">{{AppUtil::wordRound($post->episode, 130)}}</div>
                <a href="{{url('/admin/posts/detail', $post->id)}}"><input type="button" class="btn btn-primary detail_btn" value="詳細"></a>
            </li>
        @endforeach
    </ul>
</div>
<div class="event_create_ranking_side" id="sidebar">
    <h3>順位</h3>
    <ul id="event_ranking_side">
        @foreach ($eventPosts as $eventPost)
            <li data-id="{{$eventPost->post->id}}">
                <div class="event_create_ranking_post_image_side" style="background-image: url('{{url(AppUtil::showPostImage($eventPost->post))}}')">
                    <div class="sidebar_rankmark_{{$eventPost->ranking}}">
                        <div class="sidebar_rankmark_string">{{$eventPost->ranking}}</div>
                    </div>
                    <img class="event_ranking_remove" data-id="{{$eventPost->post->id}}" src="{{url('/remove.png')}}" style="width: 30px; height: 30px; z-index: 999; right: -10px; top: -10px; position: absolute;">
                </div>
            </li>
        @endforeach
    </ul>
</div>
@stop
@section('js_partial')
<script src="{{url('/js/sticky-kit.min.js')}}"></script>
<script>
$.ajaxSetup({headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
$(function() {
    var count = Number("{{$event->eventPosts->count()}}");
    var event_id = Number("{{$event->id}}");
    var remove_url = "{{url('/remove.png')}}";
    $("#sidebar, #main_column").stick_in_parent();
    $('.event_detail_post_list').on('click', function() {
        count += 1;
        console.log(count);
        var post_id = $(this).data('id');
        var url_img = $(this).data('image');
        $.ajax({
            type: "POST",
            url: "{{url('/admin/event/ajax/create/ranking')}}",
            data: {
                'event_id': event_id,
                'post_id': post_id,
                'ranking': count
            },
            success: function(res) {
                if (res.message == 'success') {
                    recreateSideRanking(res);
                }else {
                    count -= 1;
                }
            }
        });

        // if (count < 11) {
        //     var post_id = $(this).data('id');
        //     var url = $(this).data('image');
        //     $("#event_ranking_side")
        //     .append(
        //          '<li>'
        //         +   '<div class="event_create_ranking_post_image_side" style="background-image: url(' + url + ')">'
        //         +       '<div class="sidebar_rankmark_' + count + '">'
        //         +           '<div class="sidebar_rankmark_string">' + count + '</div>'
        //         +       '</div>'
        //         +   '</div>'
        //         +'</li>'
        //     );
        // }else {
        //     alert('すでに10位まで選択されています。')
        // }
    });
    $(document).on('click', '.event_ranking_remove', function() {
        var parent = $(this).closest('li');
        var post_id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: "{{url('/admin/event/ajax/delete/ranking')}}",
            data: {
                'event_id': event_id,
                'post_id': post_id
            },
            success: function(res) {
                recreateSideRanking(res);
                count -= 1;
            }
        })
    });

    function recreateSideRanking(res) {
        $("#event_ranking_side").children().remove();
        for (var i = 0; i < res.posts.length; i++) {
            $("#event_ranking_side")
            .append(
                 '<li>'
                +   '<div class="event_create_ranking_post_image_side" style="background-image: url(' + res.posts[i].url_img + ')">'
                +       '<div class="sidebar_rankmark_' + res.posts[i].ranking + '">'
                +           '<div class="sidebar_rankmark_string">' + res.posts[i].ranking + '</div>'
                +       '</div>'
                +       '<img class="event_ranking_remove" data-id="' + res.posts[i].post_id + '" src="' + remove_url + '" style="width: 30px; height: 30px; z-index: 999; right: -10px; top: -10px; position: absolute;">'
                +   '</div>'
                +'</li>'
            );
        }
    }
});
</script>
@stop
