@extends('admin.layout')
@section('body')
<h2>コメント情報</h2>
@include('parts.errormessage')
<div class="posts_list">
    <table class="table">
        <thead>
            <tr>
                <th>作成者</th>
                <th>内容</th>
                <th>コメント先</th>
                <th>投稿日時</th>
                <th>削除</th>
            </tr>
        </thead>
        <tbody>
            @if (count($comments))
                @foreach($comments as $comment)
                    <tr data-id="{{$comment->id}}">
                        <td>{{$comment->user->nickname}}</td>
                        <td style="max-width: 500px;">{!! nl2br(htmlspecialchars($comment->content)) !!}</td>
                        <td><a href="{{url('/admin/posts/detail', $comment->post->id)}}">{{$comment->post->id}}</a></td>
                        <td>{{$comment->created_at}}</td>
                        <td><button class="btn btn-danger comment_delete_btn" data-id="{{$comment->id}}" onclick="return">削除</button></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>コメントはありません</td>
                </tr>
            @endif
        </tobdy>
    </table>
    <div class="admin_pagination">
        {!! $comments->appends(Input::get())->render() !!}
    </div>
</div>
@stop
@section('js_partial')
<script>
$.ajaxSetup({headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
$(function() {
    $('.comment_delete_btn').click(function() {
        if(!confirm('本当に削除しますか？')){
    		/* キャンセルの時の処理 */
    		return false;
    	}else{
    		/*　OKの時の処理 */
            var comment_id = $(this).data('id');
            var this_tr = $(this).closest('tr');
            var url = "{{url('/admin/comments/ajax/delete_comment')}}"
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    'comment_id': comment_id
                },
                success:function(res) {
                    if (res.error) {
                        if (res.error == 'comment_id') {
                            alert('コメントIDが送信されていません');
                        }else {
                            alert('このコメントIDは存在しません。');
                        }
                    }else {
                        this_tr.fadeOut();
                    }
                }
            });
    	}
    });
});
</script>
@stop
