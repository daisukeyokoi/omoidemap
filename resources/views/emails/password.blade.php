<p>
    ようこそ、{{ $user['nickname'] }} さん
</p>

<p>
    以下のリンクをクリックしてパスワードの再設定をお願いします。
    有効期限は {{config('auth.password.expire')}}分です。
</p>

<p>
    <a href="{{url('reminder/reset/'.$token)}}">パスワードを再設定する</a>
</p>
