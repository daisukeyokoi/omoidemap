@extends('admin.layout')
@section('body')
<h2>ユーザー詳細</h2>
<div class="user_detail_box">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>
                    プロフィール画像
                </td>
                <td>
                    <img src="{{url('/show/user', $user->id)}}">
                </td>
            </tr>
            <tr>
                <td>
                    ニックネーム
                </td>
                <td>
                    {{$user->nickname}}
                </td>
            </tr>
            <tr>
                <td>
                    メールアドレス
                </td>
                <td>
                    {{$user->email}}
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="btn_field">
    <a href="{{url('/admin/user')}}">
        <input type="button" value="戻る" class="btn btn-warning">
    </a>
    <a href="{{url('/admin/user/edit', $user->id)}}">
        <input type="button" value="編集" class="btn btn-primary">
    </a>
    <form action="{{url('/admin/user/delete')}}" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="user_id" value="{{$user->id}}">
        <input type="submit" value="削除" class="btn btn-danger" onclick="return confirm_dialog(this, '削除してもよろしいですか？');">
    </form>
</div>
@stop
