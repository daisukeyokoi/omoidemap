@extends('layout')
@section('body')
<div class="new_wrapper">
    @foreach ($posts as $post)
        @if (count($post->Images) != 0)
            <div class="new_post_wrapper">
                <div class="new_post_list">
                    @for ($i = 0; $i < count($post->Images); $i++)
                        <img src="{{url($post->Images[$i]->image)}}" alt="" data-id="{{$i}}" class="@if ($i != 0) none @else current @endif">
                    @endfor
                    @if (count($post->Images) > 1)
                        <div class="img_scroll prev"><i class="fa fa-chevron-circle-left fa-2x" aria-hidden="true" style="color: gray;"></i></div>
                        <div class="img_scroll next"><i class="fa fa-chevron-circle-right fa-2x" aria-hidden="true" style="color: gray;"></i></div>
                    @endif
                </div>
                <div class="img_description">
                    <div class="good_field">
                        <div class="good_field_text">
                            いいね！
                        </div>
                        <div class="good_field_icon">
                            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                        </div>
                        <div class="good_field_number">
                            1
                        </div>
                    </div>
                    <div class="comment_field">
                        <div class="good_field_text">
                            コメント
                        </div>
                        <div class="good_field_icon">
                            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                        </div>
                        <div class="good_field_number">
                            1
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
@stop
@section('js_partial')
<script type="text/javascript" src="{{url('/js/jquery.imagefit.min.js')}}"></script>
<script type="text/javascript">
$(function() {
    $(window).load(function() {
        $('.new_post_list').imagefit({
        	mode: 'outside',
        	force : 'false',
        	halign : 'center',
        	valign : 'middle',
            onStart: function (index, container, imagecontainer) {
                /* Some code */
            },
            onLoad: function() {

            },
        });
    });
    $(".prev").on('click', function() {
        scrollImage($(this));
    });

    $(".next").on('click', function() {
        scrollImage($(this));
    });

    function scrollImage(type) {
        var current_num = 0;
        var files = type.parent(".new_post_list").children('img');
        files.each(function(i, e) {
            if ($(e).hasClass('current')) {
                current_num = $(e).data('id');
                $(e).addClass('none').removeClass('current');
                return false;
            }
        });

        if (type.hasClass('next')) {
            if (current_num == $(files).length - 1) {
                $(files[0]).addClass('current').removeClass('none');
            }else {
                $(files[current_num + 1]).addClass('current').removeClass('none');
            }
        }else {
            if (current_num == 0) {
                $(files[$(files).length - 1]).addClass('current').removeClass('none');
            }else {
                $(files[current_num - 1]).addClass('current').removeClass('none');
            }
        }
    }
});
</script>
@stop
