@extends('layout')
@section('body')
<div class="ranking_header">
    <ul>
        <a href="{{url('/ranking')}}"><li class="ranking_header_good btn @if (!Input::get('r_type')) active @endif" id="h_good">いいね！</li></a>
        <a href="{{url('/ranking?r_type=go')}}"><li class="ranking_header_go btn @if (Input::get('r_type') == 'go') active @endif" id="h_go">行きたい！</li></a>
        <a href="{{url('/ranking?r_type=prefectures&prefecture=東京都')}}"><li class="ranking_header_prefecture btn @if (Input::get('r_type') == 'prefectures') active @endif" id="h_prefecture">都道府県</li></a>
        <a href="{{url('/ranking?r_type=category&feeling=1')}}"><li class="ranking_header_category btn @if (Input::get('r_type') == 'category') active @endif" id="h_category">カテゴリー</li></a>
    </ul>
</div>
<div class="ranking_wrapper">
    <h1>RANKING</h1>
    @if (Input::get('r_type') == 'prefectures')
        <select id="prefecture" class="form-control">
            @foreach ($prefectures as $prefecture)
                <option value="{{$prefecture->name}}" @if ($prefecture->name == Input::get('prefecture', '東京都')) selected @endif>{{$prefecture->name}}</option>
            @endforeach
        </select>
    @endif
    @if (Input::get('r_type') == 'category')
        <select id="feeling" class="form-control">
            @foreach (AppUtil::photoFeelingList() as $key => $value)
                <option value="{{$value}}" @if ($value == Input::get('feeling', 1)) selected @endif>{{$key}}</option>
            @endforeach
        </select>
    @endif
    <div class="ranking_main">
        @if (count($posts) != 0)
            <ul class="ranking_list">
                @foreach($posts as $post)
                    <li>
                        <a href="{{url('/article/detail', $post->id)}}">
                            <div class="ranking_list_top">
                                <div class="ranking_list_top_header">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <span class="ranking_list_top_header_nickname">{{$post->user->nickname}}</span>
                                    <span class="btn btn-danger">{{AppUtil::photoFeelingLabel()[$post->feeling]}}</span>
                                    <span class="btn btn-success">{{AppUtil::photoAgeLabel()[$post->age]}}</span>
                                    <span class="ranking_list_top_header_address">{{AppUtil::postNumberRemove(AppUtil::wordRound($post->address, 18))}}</span>
                                </div>
                                <div class="ranking_list_top_image" style="background-image: url({{url(AppUtil::showPostImage($post))}})"></div>
                                <div class="ranking_list_top_episode">{{$post->episode}}</div>
                            </div>
                        </a>
                        <div class="ranking_list_under">
                            <i class="fa fa-heart @if(Auth::check() && Auth::user()->favPosts()->find($post->id)) fav_flag @endif" aria-hidden="true" data-id="{{$post->id}}" id="fav_{{$post->id}}"></i>
                            <div class="ranking_list_under_go">
                                <span>行きたい！</span>
                                <span>{{count($post->goes)}}</span>
                            </div>
                            <div class="ranking_list_under_comment">
                                <span>コメント</span>
                                <span>{{count($post->comments)}}</span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p>ランキングはありません</p>
        @endif
    </div>
</div>
@stop
@section('js_partial')
<script type="text/javascript">
    $.ajaxSetup({headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
    $(function () {
        $('#prefecture').on('change', function () {
            var prefecture = $(this).val();
            var search = window.location.search;
            search = search.split('&');
            if (search.length != 1) {
                var href = window.location.pathname + search[0] + '&' + search[1].substr(0, 11) + prefecture;
            }else {
                var href = window.location.pathname + search[0] + '&prefecture=' + prefecture;
            }
            window.location.href = href;
        });

        $('#feeling').on('change', function() {
            var category = $(this).val();
            var search = window.location.search;
            search = search.split('&');
            if (search.length != 1) {
                var href = window.location.pathname + search[0] + '&' + search[1].substr(0, 8) + category;
            }else {
                var href = window.location.pathname + search[0] + '&feeling=' + category;
            }
            window.location.href = href;
        });

        $('.fa-heart').on('click', function(){
        	var post_id = $(this).data('id');
        	var url = "{{url('/ajax/favorite/post')}}";
        	var clicked = $(this);
            console.log(post_id);
        	$.ajax({
        		type: 'POST',
        		url: url,
        		data: {
        			'post_id': post_id,
        		},
        		success: function (res) {
        			if (typeof(res.error) == 'undefined') {
        				if (clicked.hasClass('fav_flag')) {
        					clicked.removeClass('fav_flag');
        				}else {
        					clicked.addClass('fav_flag');
        				}
        			}
        		}
        	});
        });
    });


    // $(function() {
    //


    //     $(document).ready(function() {
    //         createPrefectureRanking();
    //         createFeelingRanking();
    //         var tag_id = $(".select_ranking_tag").data('id');
    //         createTagsRanking(tag_id);
    //     });
    //
    //     // 都道府県ランキング表示
    //     $("#prefecture").on('change', function() {
    //         createPrefectureRanking();
    //     });
    //
    //     // 感情ランキング
    //     $("#feeling").on('change', function() {
    //         createFeelingRanking();
    //     });
    //
    //     // タグ別ランキング
    //     $(".ranking_tags").on('click', function() {
    //         $(".ranking_tags").removeClass('select_ranking_tag');
    //         $(this).addClass('select_ranking_tag');
    //         var tag_id = $(this).data('id');
    //         createTagsRanking(tag_id);
    //     });
    //
    //     function createPrefectureRanking() {
    //         var prefecture_id = $("#prefecture").val();
    //         $.ajax({
    //             url: "{{url('/ajax/ranking/prefectures')}}",
    //             type: 'POST',
    //             data: {
    //                 'prefecture_id': prefecture_id
    //             },
    //             success: function(res) {
    //                 $("#prefecture").next('div').children('ul').children('li').remove();
    //                 $("#prefecture").next('div').children('ul').children('p').remove();
    //                 createRanking('prefecture', res.posts, res.message);
    //             }
    //         });
    //     }
    //
    //     function createFeelingRanking() {
    //         var feeling_id = $('#feeling').val();
    //         $.ajax({
    //             url: "{{url('/ajax/ranking/feeling')}}",
    //             type: 'POST',
    //             data: {
    //                 'feeling_id': feeling_id
    //             },
    //             success: function(res) {
    //                 $("#feeling").next('div').children('ul').children('li').remove();
    //                 $("#feeling").next('div').children('ul').children('p').remove();
    //                 createRanking('feeling', res.posts, res.message);
    //             }
    //         });
    //     }
    //
    //     function createTagsRanking(tag_id) {
    //         $.ajax({
    //             url: "{{url('/ajax/ranking/tags')}}",
    //             type: 'POST',
    //             data: {
    //                 'tag_id': tag_id
    //             },
    //             success: function(res) {
    //                 $("#tag").next('div').children('ul').children('li').remove();
    //                 $("#tag").next('div').children('ul').children('p').remove();
    //                 createRanking('tag', res.posts, res.message);
    //             }
    //         });
    //     }
    //
    //     function createRanking(type, posts, message) {
    //         if (message == 'success') {
    //             for (var i = 0; i < posts.length; i++) {
    //                 $("#" + type).next('div').children('ul').append(
    //                     '<li>'
    //                 +       '<a href="' + posts[i][0].url +  '">'
    //                 +           '<div class="rankmark_' + (i + 1) + '">'
    //                 +               '<div class="rankmark_string">'
    //                 +                   'Rank' + (i + 1)
    //                 +               '</div>'
    //                 +           '</div>'
    //                 +           '<div class="search_result_img" style="background-image: url(' + posts[i][0].image + ')"></div>'
    //                 +           '<div class="search_result_data_left">'
    //                 +               '<div class="search_result_data">' + posts[i][0].address + '</div>'
    //                 +           '</div>'
    //                 +           '<div class="search_result_data_right">'
    //                 +               '<div class="search_result_user_img" style="background-image: url(' + posts[i][0].user_image + ')"></div>'
    //                 +           '</div>'
    //                 +           '<div class="search_result_good">'
    //                 +               '<span>いいね!</span>'
    //                 +               '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>'
    //                 +               '<span>' + posts[i][0].goods + '</span>'
    //                 +               '<span>コメント</span>'
    //                 +               '<i class="fa fa-commenting-o" aria-hidden="true"></i>'
    //                 +               '<span>' + posts[i][0].comments + '</span>'
    //                 +           '</div>'
    //                 +       '</a>'
    //                 +   '</li>'
    //                 ).hide().fadeIn('normal');
    //             }
    //         } else {
    //             $("#" + type).next('div').children('ul').append(
    //                 '<p>ランキングはありません</p>'
    //             );
    //         }
    //     }
    // });
</script>
@stop
