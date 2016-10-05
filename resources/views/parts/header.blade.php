<header>
  <div class="clearfix">
    <div class="header_left">
      <a href="{{url('/')}}"><h1><i class="fa fa-picture-o" aria-hidden="true"></i>思い出MAP</h1></a>
    </div>
    <div class="header_right">
      <ul>
        @if (!Auth::user())
          <a href="{{url('/login')}}"><li>ログイン</li></a>
        @else
          <a href="{{url('/logout')}}"><li>ログアウト</li></a>
        @endif
        <a href="{{url('/register')}}"><li>アカウント作成</li></a>
        <a href="#"><li>Ranking</li></a>
        <a href="#"><li>New</li></a>
      </ul>
    </div>
  </div>
</header>
