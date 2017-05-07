@extends('admin.layout')
@section('css_partial')
<style>
.event_detail_td {
    width: 15%;
}
</style>
@stop
@section('body')
@include('parts.errormessage')
<h2 class="test">イベント詳細</h2>
<table class="table table-bordered">
    <tbody>
        <tr>
            <td class="event_detail_td">画像</td>
            <td>
                <img src="{{url($event->image)}}">
            </td>
        </tr>
        <tr>
            <td>タイトル</td>
            <td>{{$event->title}}</td>
        </tr>
        <tr>
            <td>説明文</td>
            <td style="word-break: break-all;">{{$event->description}}</td>
        </tr>
        <tr>
            <td>開催期間</td>
            <td>{{$event->start}}&nbsp;~&nbsp;{{$event->end}}</td>
        </tr>
    </tbody>
</table>
<div class="btn_field" style="margin-bottom: 30px;">
    <a href="{{url('/admin/event')}}">
        <input type="button" value="戻る" class="btn btn-warning">
    </a>
    <a href="{{url('/admin/event/edit', $event->id)}}">
        <input type="button" value="編集" class="btn btn-primary">
    </a>
    <form action="{{url('/admin/event/delete')}}" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="event_id" value="{{$event->id}}">
        <input type="submit" value="削除" class="btn btn-danger" onclick="return confirm_dialog(this, '削除してもよろしいですか？');">
    </form>
</div>
<a href="{{url('/admin/event/create/ranking', $event->id)}}"><input type="button" class="btn btn-success" value="順位付けをする"></a>
<h3 class="event_detail_post_title">このイベントの投稿一覧</h3>
<ul class="event_detail_post">
    @foreach ($posts as $post)
        <a href="{{url('/admin/posts/detail', $post->id)}}">
            <li>
                <div class="event_detail_post_image" style="background-image: url('{{url(AppUtil::showPostImage($post))}}')"></div>
                <div class="event_detail_post_description">{{$post->episode}}</div>
            </li>
        </a>
    @endforeach
</ul>
<div class="admin_pagination">
    {!! $posts->render() !!}
</div>
@stop
