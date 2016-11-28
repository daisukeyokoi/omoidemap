@extends('admin.layout')
@section('body')
<h2>記事情報</h2>
@include('parts.errormessage')
<div class="search_box">
    <div class="search_box_header">
        検索
    </div>
    <div class="search_box_main">
        <form action="{{url('/admin/posts')}}" method="get">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>
                            年齢
                        </td>
                        <td>
                            <select name="age" class="form-control">
                                <option value="">選択してください</option>
                                @foreach(AppUtil::photoAgeList() as $key => $value)
                                    <option value="{{$value}}">{{$key}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>気持ち</td>
                        <td>
                            <select name="feeling" class="form-control">
                                <option value="">選択してください</option>
                                @foreach(AppUtil::photoFeelingList() as $key => $value)
                                    <option value="{{$value}}">{{$key}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>タイトル</td>
                        <td>
                            <input type="text" class="form-control" name="title" placeholder="タイトルを入力してください">
                        </td>
                    </tr>
                    <tr>
                        <td>キーワード(エピソード中)</td>
                        <td>
                            <input type="text" class="form-control" name="episode" placeholder="キーワードを入力してください">
                        </td>
                    </tr>
                    <tr>
                        <td>都道府県</td>
                        <td>
                            <select name="prefecture" class="form-control">
                                <option value="">選択してください</option>
                                @foreach($prefectures as $prefecture)
                                    <option value="{{$prefecture->name}}">{{$prefecture->name}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" value="検索" class="btn btn-primary">
        </form>
    </div>
</div>
<div class="posts_list">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>タイトル</th>
                <th>撮影地</th>
            </tr>
        </thead>
        <tbody>
            @if (count($posts) != 0)
                @foreach ($posts as $post)
                    <tr>
                        <td>
                            <a href="{{url('/admin/posts/detail', $post->id)}}">{{$post->id}}</a>
                        </td>
                        <td>
                            {{$post->title}}
                        </td>
                        <td>
                            {{AppUtil::postNumberRemove($post->address)}}
                        </td>
                    </tr>
                @endforeach
            @else
                <td>該当する記事はありません</td>
            @endif
        </tbody>
    </table>
    <div class="admin_pagination">
        {!! $posts->appends(Input::get())->render() !!}
    </div>
</div>
@stop
