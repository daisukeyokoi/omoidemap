@extends('layout')
@section('css_partial')
<style>
ul {
	padding: 0;
	list-style: none;
}
a {
	color: black;
}
a:hover {
	color: black;
}
</style>
@stop
@section('body')
<div class="search_wrapper">
    <h1>「{{Input::get('h_keyword')}}」の検索結果({{$posts->total()}}件)</h1>
    <div class="relation_tag">
		<div class="relation_tag_header">
			<i class="fa fa-tags" aria-hidden="true"></i>
			<span>関連するタグ</span>
		</div>
        <div class="relation_tag_content">
			@if (count($relate_tags) != 0)
				<ul>
					@foreach ($relate_tags as $relate_tag)
						<a href="{{url('/tag', $relate_tag->id)}}"><li>{{$relate_tag->name}}</li></a>
					@endforeach
				</ul>
			@else
				<p>関連するタグはありません</p>
			@endif
		</div>
	</div>
    <div class="search_result">
        <div class="search_result_list">
            @if (count($posts) != 0)
    			<ul>
    				@foreach($posts as $post)
    					<li>
    						<a href="{{url('/article/detail', $post->id)}}">
    							<div class="search_result_img" style="background-image: url({{url($post->oneImage->image)}})"></div>
                                <div class="search_result_data_all">
                                    <div class="search_result_data_left">
        								<div class="search_result_data">
        									{{$post->title}}
        								</div>
        								<div class="search_result_data">
        									{{AppUtil::postNumberRemove($post->address)}}
        								</div>
        							</div>
        							<div class="search_result_data_right">
        								<div class="search_result_user_img" style="background-image: url({{url('/show/user', $post->user_id)}})"></div>
        							</div>
        							<div class="search_result_good">
        								<span>いいね!</span>
        								<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
        								<span>{{count($post->goods)}}</span>
        								<span>コメント</span>
        								<i class="fa fa-commenting-o" aria-hidden="true"></i>
        								<span>{{count($post->comments)}}</span>
        							</div>
                                </div>
    						</a>
    					</li>
    				@endforeach
    			</ul>
    		@else
    			<p>このキーワードに一致する思い出はありませんでした。</p>
    		@endif
        </div>
        <div class="tag_article_pagination">
            {!! $posts->appends(Input::get())->render() !!}
        </div>
    </div>
</div>
@stop
