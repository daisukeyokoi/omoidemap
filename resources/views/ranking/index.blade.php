@extends('layout')
@section('body')
<div class="ranking_wrapper">
    <h1>RANKING</h1>
    <div class="ranking_main">
        <h2>総合</h2>
        <div class="ranking_total">
            @if (count($posts) != 0)
            <ul class="ranking_list">
                @foreach($posts as $post)
                    <li>
                        <a href="{{url('/article/detail', $post->id)}}">
                            <?php $rank += 1; ?>
                            <div class="rankmark_{{$rank}}">
                                <div class="rankmark_string">
                                    Rank{{$rank}}
                                </div>
                            </div>
                            <div class="search_result_img" style="background-image: url({{url(AppUtil::showPostImage($post))}})"></div>
                            <div class="search_result_data_left">
                                <div class="search_result_data">
                                    {{$post->title}}
                                </div>
                                <div class="search_result_data">
                                    {{AppUtil::postNumberRemove($post->address)}}
                                </div>
                            </div>
                            <div class="search_result_data_right">
                                <div class="search_result_user_img" style="background-image: url({{url('/show/user', $post->user_id)}})"></div>
                            </div>
                            <div class="search_result_good">
                                <span>いいね!</span>
                                <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                <span>{{count($post->goods)}}</span>
                                <span>コメント</span>
                                <i class="fa fa-commenting-o" aria-hidden="true"></i>
                                <span>{{count($post->comments)}}</span>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
            @else
                <p>ランキングはありません</p>
            @endif
        </div>
        <h2>都道府県別</h2>
        <select id="prefecture" class="form-control">
            @foreach ($prefectures as $prefecture)
                <option value="{{$prefecture->id}}" @if ($prefecture->id == 14) selected @endif>{{$prefecture->name}}</option>
            @endforeach
        </select>
        <div class="ranking_total">
            <ul class="ranking_list">
            </ul>
        </div>
        <h2>撮影時の気持ち</h2>
        <select id="feeling" class="form-control">
            @foreach (AppUtil::photoFeelingList() as $key => $value)
                <option value="{{$value}}" @if ($value == AppUtil::TRAVEL) selected @endif>{{$key}}</option>
            @endforeach
        </select>
        <div class="ranking_total">
            <ul class="ranking_list">
            </ul>
        </div>
        @if (count($tags) != 0)
            <h2>タグ別</h2>
            <div class="ranking_tag_field" id="tag">
                <ul>
                    @for($i = 0; $i < count($tags); $i++)
                        <li class="ranking_tags @if($i == 0) select_ranking_tag @endif" data-id="{{$tags[$i]->id}}">{{$tags[$i]->name}}</li>
                    @endfor
                </ul>
            </div>
            <div class="ranking_total">
                <ul class="ranking_list">
                </ul>
            </div>
        @endif
    </div>
</div>
@stop
@section('js_partial')
<script type="text/javascript">
    $.ajaxSetup({headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
    $(function() {
        $(document).ready(function() {
            createPrefectureRanking();
            createFeelingRanking();
            var tag_id = $(".select_ranking_tag").data('id');
            createTagsRanking(tag_id);
        });

        // 都道府県ランキング表示
        $("#prefecture").on('change', function() {
            createPrefectureRanking();
        });

        // 感情ランキング
        $("#feeling").on('change', function() {
            createFeelingRanking();
        });

        // タグ別ランキング
        $(".ranking_tags").on('click', function() {
            $(".ranking_tags").removeClass('select_ranking_tag');
            $(this).addClass('select_ranking_tag');
            var tag_id = $(this).data('id');
            createTagsRanking(tag_id);
        });

        function createPrefectureRanking() {
            var prefecture_id = $("#prefecture").val();
            $.ajax({
                url: "{{url('/ajax/ranking/prefectures')}}",
                type: 'POST',
                data: {
                    'prefecture_id': prefecture_id
                },
                success: function(res) {
                    $("#prefecture").next('div').children('ul').children('li').remove();
                    $("#prefecture").next('div').children('ul').children('p').remove();
                    createRanking('prefecture', res.posts, res.message);
                }
            });
        }

        function createFeelingRanking() {
            var feeling_id = $('#feeling').val();
            $.ajax({
                url: "{{url('/ajax/ranking/feeling')}}",
                type: 'POST',
                data: {
                    'feeling_id': feeling_id
                },
                success: function(res) {
                    $("#feeling").next('div').children('ul').children('li').remove();
                    $("#feeling").next('div').children('ul').children('p').remove();
                    createRanking('feeling', res.posts, res.message);
                }
            });
        }

        function createTagsRanking(tag_id) {
            $.ajax({
                url: "{{url('/ajax/ranking/tags')}}",
                type: 'POST',
                data: {
                    'tag_id': tag_id
                },
                success: function(res) {
                    $("#tag").next('div').children('ul').children('li').remove();
                    $("#tag").next('div').children('ul').children('p').remove();
                    createRanking('tag', res.posts, res.message);
                }
            });
        }

        function createRanking(type, posts, message) {
            if (message == 'success') {
                for (var i = 0; i < posts.length; i++) {
                    $("#" + type).next('div').children('ul').append(
                        '<li>'
                    +       '<a href="' + posts[i][0].url +  '">'
                    +           '<div class="rankmark_' + (i + 1) + '">'
                    +               '<div class="rankmark_string">'
                    +                   'Rank' + (i + 1)
                    +               '</div>'
                    +           '</div>'
                    +           '<div class="search_result_img" style="background-image: url(' + posts[i][0].image + ')"></div>'
                    +           '<div class="search_result_data_left">'
                    +               '<div class="search_result_data">' + posts[i][0].address + '</div>'
                    +           '</div>'
                    +           '<div class="search_result_data_right">'
                    +               '<div class="search_result_user_img" style="background-image: url(' + posts[i][0].user_image + ')"></div>'
                    +           '</div>'
                    +           '<div class="search_result_good">'
                    +               '<span>いいね!</span>'
                    +               '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>'
                    +               '<span>' + posts[i][0].goods + '</span>'
                    +               '<span>コメント</span>'
                    +               '<i class="fa fa-commenting-o" aria-hidden="true"></i>'
                    +               '<span>' + posts[i][0].comments + '</span>'
                    +           '</div>'
                    +       '</a>'
                    +   '</li>'
                    ).hide().fadeIn('normal');
                }
            } else {
                $("#" + type).next('div').children('ul').append(
                    '<p>ランキングはありません</p>'
                );
            }
        }
    });
</script>
@stop
