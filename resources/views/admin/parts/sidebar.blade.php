<div class="admin_sidebar">
    <a href="{{url('/admin')}}">
        <div class="admin_sidebar_title">
            <i class="fa fa-dashboard fa-lg" aria-hidden="true"></i>
            <span>Top</span>
        </div>
    </a>
    <div class="admin_sidebar_title" data-target="#collapseTable" data-toggle="collapse">
        <i class="fa fa-table fa-lg" aria-hidden="true"></i>
        <a>テーブル</a>
    </div>
    <div class="collapse" id="collapseTable">
        <ul class="card-block">
            <a href="{{url('/admin/user')}}"><li><i class="fa fa-circle-o" aria-hidden="true"></i>ユーザー</li></a>
            <a href="#"><li><i class="fa fa-circle-o" aria-hidden="true"></i>記事</li></a>
        </ul>
    </div>
    <div class="admin_sidebar_title" data-target="#collapseEvent" data-toggle="collapse">
        <i class="fa fa-gift fa-lg" aria-hidden="true"></i>
        <a>イベント</a>
    </div>
    <div class="collapse" id="collapseEvent">
        <ul class="card-block">
            <a href="#"><li><i class="fa fa-circle-o" aria-hidden="true"></i>ユーザー</li></a>
            <a href="#"><li><i class="fa fa-circle-o" aria-hidden="true"></i>記事</li></a>
        </ul>
    </div>
    <div class="admin_sidebar_title" data-target="#collapseInquiry" data-toggle="collapse">
        <i class="fa fa-envelope fa-lg" aria-hidden="true"></i>
        <a>お問い合わせ</a>
    </div>
    <div class="collapse" id="collapseInquiry">
        <ul class="card-block">
            <a href="#"><li><i class="fa fa-circle-o" aria-hidden="true"></i>ユーザー</li></a>
            <a href="#"><li><i class="fa fa-circle-o" aria-hidden="true"></i>記事</li></a>
        </ul>
    </div>
</div>
