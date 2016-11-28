@extends('admin.layout')
@section('body')
<h2>ユーザー編集</h2>
@include('parts.errormessage')
<div class="user_edit_box">
    <form action="{{url('/admin/user/edit')}}" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="user_id" value="{{$user->id}}">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>
                        ニックネーム
                    </td>
                    <td>
                        <input type="text" class="form-control" name="nickname" value="{{$user->nickname}}" placeholder="ニックネームを入力してください">
                    </td>
                </tr>
                <tr>
                    <td>
                        メールアドレス
                    </td>
                    <td>
                        <input type="text" class="form-control" name="email" value="{{$user->email}}" placeholder="メールアドレスを入力してください">
                    </td>
                </tr>
            </tbody>
        </table>
        <a href="{{url('/admin/user/detail', $user->id)}}">
            <input type="button" value="戻る" class="btn btn-warning">
        </a>
        <input type="button" value="編集" class="btn btn-primary" onclick="return confirm_dialog(this, '編集してよろしいですか？');">
    </form>
</div>
@stop
