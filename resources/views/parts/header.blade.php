<header>
<div class="clearfix">
    <div class="header_left">
        <a href="{{url('/')}}"><h1><i class="fa fa-picture-o" aria-hidden="true"></i>思い出MAP</h1></a>
        <button type="button" class="navbar-toggle offcanvas-toggle" data-toggle="offcanvas" data-target="#js-bootstrap-offcanvas">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div class="header_right">
        <ul>
            @if (!Auth::user())
                <a href="{{url('/login')}}"><li class="header_right_list_pc">ログイン</li></a>
            @else
                <a href="{{url('/logout')}}"><li class="header_right_list_pc">ログアウト</li></a>
            @endif
            @if (!Auth::user())
                <a href="{{url('/register')}}"><li class="header_right_list_pc">アカウント作成</li></a>
            @else
                <a href="{{url('/mypage')}}"><li class="header_right_list_pc">マイページ</li></a>
            @endif
            <a href="#"><li class="header_right_list_pc">Ranking</li></a>
            <div class="input-group header_search">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">
                        <i class='glyphicon glyphicon-search'></i>
                    </button>
                </span>
                <input type="text" class="form-control" placeholder="思い出をさがそう">
            </div>
        </ul>
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
        <a href="#"><li>Ranking</li></a>
        <a href="#"><li>このサイトについて</li></a>
    </ul>
</nav>
