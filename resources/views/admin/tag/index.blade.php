@extends('admin.layout')
@section('body')
<h2>タグ情報</h2>
@include('parts.errormessage')
<div class="search_box">
    <div class="search_box_header">
        検索
    </div>
    <div class="search_box_main">
        <form action="{{url('/admin/tags')}}" method="get">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>
                            タグ名
                        </td>
                        <td>
                            <input type="text" name="name" class="form-control" placeholder="タグ名を入力してください">
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" value="検索" class="btn btn-primary">
        </form>
    </div>
</div>
<div class="new_tag_create">
    <h3>タグ作成</h3>
    <div class="row">
        <div class="col-lg-6">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="タグ名を入力してください" id="create_tag">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" id="create_tag_btn">タグ作成</button>
                </span>
            </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->
<div class="posts_list">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>タグ名</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="tag_tbody">
            @if (count($tags) != 0)
                @foreach ($tags as $tag)
                    <tr class="tag_list" id="{{$tag->id}}">
                        <td>{{$tag->id}}</td>
                        <td>{{$tag->name}}</td>
                        <td>
                            <input type="button" class="btn btn-danger tag_delete_btn" value="削除" data-id="{{$tag->id}}">
                        </td>
                    </tr>
                @endforeach
            @else
                <td>該当するタグはありません</td>
            @endif
        </tbody>
    </table>
    <div class="admin_pagination">
        {!! $tags->appends(Input::get())->render() !!}
    </div>
</div>
@stop
@section('js_partial')
<script>
$.ajaxSetup({headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
$(function(){
    $('#create_tag_btn').on('click', function() {
        var tag_name = $('#create_tag').val();
        var tag_list = $('.tag_list:first').clone();
        $.ajax({
            type: 'POST',
            url: "{{url('/admin/tags/ajax/create_tag')}}",
            data: {
                'tag_name': tag_name
            },
            success: function (res) {
                if (res.message == 'success') {
                    tag_list.children('td:first').text(res.tag.id);
                    tag_list.children('td:nth-child(2)').text(res.tag.name);
                    tag_list.children('td:nth-child(3)').children('input').attr('data-id', res.tag.id);
                    $(".tag_list").parent('tbody').prepend(tag_list).hide().fadeIn('slow');
                    $('#create_tag').val('');
                    $('#create_tag').focus();
                }
            }
        });
    });

    $('.tag_delete_btn').on('click', function() {
        if (confirm('タグを削除しますか？')) {
            var tag_id = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: "{{url('/admin/tags/ajax/delete_tag')}}",
                data: {
                    'tag_id': tag_id
                },
                success: function(res) {
                    if (res.message == 'success') {
                        $('#' + res.tag_id).fadeOut('slow');
                    }
                }
            });
        }else {
            return false;
        }
    });
});
</script>
@stop
