@extends('admin.layout')
@section('body')
<h2>ユーザー情報</h2>
@include('parts.errormessage')
<div class="search_box">
    <div class="search_box_header">
        検索
    </div>
    <div class="search_box_main">
        <form action="{{url('/admin/user')}}" method="get">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>
                            ニックネーム
                        </td>
                        <td>
                            <input type="text" class="form-control" name="nickname" placeholder="ニックネームを入力してください">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            メールアドレス
                        </td>
                        <td>
                            <input type="text" class="form-control" name="email" placeholder="メールアドレスを入力してください">
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" value="検索" class="btn btn-primary">
        </form>
    </div>
</div>
<div class="user_list">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>ニックネーム</th>
                <th>メールアドレス</th>
            </tr>
        </thead>
        <tbody>
            @if (count($users) != 0)
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <a href="{{url('/admin/user/detail', $user->id)}}">{{$user->id}}</a>
                        </td>
                        <td>
                            {{$user->nickname}}
                        </td>
                        <td>
                            {{$user->email}}
                        </td>
                    </tr>
                @endforeach
            @else
                <td>該当者はいません</td>
            @endif
        </tbody>
    </table>
    <div class="admin_pagination">
        {!! $users->appends(Input::get())->render() !!}
    </div>
</div>
@stop
