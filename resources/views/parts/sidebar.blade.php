<div class="sidebar">
    <div class="sidebar_ranking">
        <div class="sidebar_ranking_title">
            ランキング
        </div>
        <ul>
            <a href="#">
                <li>
                    <img src="{{url('/japan_icon.jpg')}}">
                    <span>都道府県</span>
                </li>
            </a>
        </ul>
        <ul>
            <a href="#">
                <li>
                    <i class="fa fa-heart fa-2x" aria-hidden="true" style="color: #FA4000"></i>
                    <span>エピソード</span>
                </li>
            </a>
        </ul>
    </div>
    <div class="sidebar_tag">
        <div class="sidebar_ranking_title">
            人気おすすめタグ
        </div>
        <ul>
            @foreach(AppUtil::popularTags() as $tag)
                <a href="{{url('/tag', $tag->id)}}">
                    <li>
                        <span class="hash_tag">#</span>
                        <span>{{$tag->name}}</span>
                    </li>
                </a>
            @endforeach
        </ul>
        <a href="#"><p>人気おすすめタグの一覧&nbsp;&nbsp;></p></a>
    </div>
    <div class="sidebar_advertising">広告</div>
    <div class="sidebar_article">
        <div class="sidebar_ranking_title">
            共感されたエピソード
        </div>
        <ul>
            @for($i = 0; $i < count(AppUtil::popularPosts()); $i++)
                <a href="#">
                    <li>
                        <div class="sidebar_article_img" style="background-image: url({{url(AppUtil::popularPosts()[$i]->oneImage->image)}})">
                            <div class="sidebar_rankmark_{{$i + 1}}">
                                <div class="sidebar_rankmark_string">
                                    {{$i + 1}}
                                </div>
                            </div>
                        </div>
                        <div class="sidebar_article_content">
                            <div class="sidebar_article_content_string">
                                {{AppUtil::popularPosts()[$i]->episode}}
                            </div>
                        </div>
                    </li>
                </a>
            @endfor
        </ul>
    </div>
</div>
