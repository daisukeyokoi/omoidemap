<header>
<div class="clearfix">
    <a href="{{url('/')}}">
        <div class="header_left">
            <img src="{{url('/header_icon_1.jpg')}}">
        </div>
    </a>
    <div class="header_search">
        <i class='glyphicon glyphicon-search'></i>
        <form action="{{url('/search')}}" method="get">
            <input type="text" name="h_keyword" placeholder="思い出をさがそう" value="@if (Input::get('h_keyword')){{Input::get('h_keyword')}}@endif">
        </form>
    </div>
    <div class="header_right">
        @if (!Auth::user())
            <a href="{{url('/login')}}"><div class="header_right_list_pc pull-right header_login">ログイン</div></a>
        @else
            <a href="{{url('/logout')}}"><div class="header_right_list_pc pull-right header_login">ログアウト</div></a>
        @endif
        @if (!Auth::user())
            <a href="{{url('/register')}}"><div class="header_right_list_pc pull-right header_account">アカウント作成</div></a>
        @else
            <a href="{{url('/mypage')}}"><div class="header_right_list_pc pull-right header_mypage">マイページ</div></a>
        @endif
        <a href="{{url('/ranking')}}"><div class="header_right_list_pc pull-right header_ranking">Ranking</div></a>
        <button type="button" class="navbar-toggle offcanvas-toggle" data-toggle="offcanvas" data-target="#js-bootstrap-offcanvas">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
</div>
</header>
<nav class="navbar navbar-default navbar-offcanvas" role="navigation" id="js-bootstrap-offcanvas" style="background-image: url({{url('/background.jpg')}})">
    <ul>
        <a href="{{url('/')}}"><li>Top</li></a>
        @if (!Auth::user())
            <a href="{{url('/login')}}"><li>ログイン</li></a>
        @else
            <a href="{{url('/logout')}}"><li>ログアウト</li></a>
        @endif
        @if (!Auth::user())
            <a href="{{url('/register')}}"><li>アカウント作成</li></a>
        @else
            <a href="{{url('/mypage')}}"><li>マイページ</li></a>
        @endif
        <a href="{{url('/ranking')}}"><li>Ranking</li></a>
        <a href="#"><li>このサイトについて</li></a>
    </ul>
</nav>
