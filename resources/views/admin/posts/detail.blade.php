@extends('admin.layout')
@section('css_partial')
<style>
.post_detail_td {
    width: 15%;
}
</style>
@stop
@section('body')
<h2>記事詳細</h2>
<table class="table table-bordered">
    <tbody>
        <tr>
            <td>投稿したユーザー</td>
            <td><a href="{{url('/admin/user/detail', $post->user->id)}}">{{$post->user->nickname}}</a></td>
        </tr>
        <tr>
            <td class="post_detail_td">画像</td>
            <td>
                <img src="{{url(AppUtil::showPostImage($post))}}">
            </td>
        </tr>
        <tr>
            <td>撮影時の年齢</td>
            <td>{{AppUtil::photoAgeLabel()[$post->age]}}</td>
        </tr>
        <tr>
            <td>思い出の種類</td>
            <td>{{AppUtil::photoFeelingLabel()[$post->feeling]}}</td>
        </tr>
        <tr>
            <td>タグ</td>
            <td>
                @foreach($post->postsTags as $postsTag)
                    {{$postsTag->tag->name}}
                @endforeach
            </td>
        </tr>
        <tr>
            <td>エピソード</td>
            <td>{{$post->episode}}</td>
        </tr>
    </tbody>
</table>
<div class="btn_field">
    <a href="{{url('/admin/posts')}}">
        <input type="button" value="戻る" class="btn btn-warning">
    </a>
    <form action="{{url('/admin/posts/delete')}}" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="post_id" value="{{$post->id}}">
        <input type="submit" value="削除" class="btn btn-danger" onclick="return confirm_dialog(this, '削除してもよろしいですか？');">
    </form>
</div>
@stop
