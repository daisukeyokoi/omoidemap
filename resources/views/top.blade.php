@extends('layout')
@section('css_partial')
<style>
.top_wrapper {
	width: 90%;
	margin: 0 auto;
	overflow: hidden;
}
.map-embed
{
	max-width: 100% ;
	height: 0 ;
	margin: 0 ;
	padding: 0 0 56.25% ;

	overflow: hidden ;

	position: relative ;
	top: 0 ;
	left: 0 ;
}

.map-embed > div
{
	position: absolute ;
	top: 0 ;
	left: 0 ;

	width: 100%;
	height: 100%;

	margin: 0 ;
	padding: 0 ;
}

.map-embed img
{
	width: 100%;
    height: 100%;
}
ul {
	list-style: none;
	padding: 0;
}
h2 {
	margin: 0;
}
a {
	color: black;
}
a:hover {
	color: black;
}
.top_pagination {
	text-align: center;
	overflow: hidden;
}
.top_article_list li:hover {
	background-color: #E9F8EF;
}
</style>
@stop
@section('body')
<div class="top_wrapper">
	<div class="top_img" style="background-image: url({{url('/top_img_4.jpg')}})">
		<h1>思い出を旅する。誰かの特別を、みんなの特別に。</h1>
		<a href="{{url('/register')}}">
			<input type="button" value="思い出を投稿する" class="btn btn-warning">
		</a>
	</div>
	<div class="top_map">
		<h2>思い出マップ</h2>
	    <div class="top_map_left">
	        <div class="map-embed">
	        	<div id="map-canvas"></div>
	        </div>
	    </div>
	    <div class="top_map_right">
			<div class="keyword_search_text">
				キーワードで検索
			</div>
			<input type="text" name="keyword" placeholder="キーワードを入力してください" class="form-control keyword_search" id="keyword">
			<div class="place_search_text">
				場所から検索
			</div>
			<select name="regions" id="regions" class="form-control place_search">
				<option value="">地域を選択してください</option>
				@foreach($regions as $region)
					<option value="{{$region->id}}">{{$region->name}}</option>
				@endforeach
			</select>
			<select name="prefecture" id="prefectures" class="form-control place_search">
				<option value="">都道府県を選択してください。</option>
			</select>
			<input type="submit" value="検索" class="btn btn-primary search_submit" id="submit_button">
	    </div>
	</div>
	<article class="top_article_list">
		<h2>思い出</h2>
		<ul>
			@foreach($posts as $post)
				<li class="article_list">
					<a href="{{url('/article/detail', $post->id)}}">
						<div class="top_article_list_left">
							<div class="top_article_list_left_left">
								<div class="top_article_list_photo_age">
									<p>年代</p>
									<p>
										{{AppUtil::photoAgeLabel()[$post->age]}}
									</p>
								</div>
								<div class="top_article_list_photo_feeling">
									<p>
										撮影時の気持ち
									</p>
									<p>
										{{AppUtil::photoFeelingLabel()[$post->feeling]}}
									</p>
								</div>
							</div>
							<div class="top_article_list_left_right">
								<h2 class="top_article_title">{{$post->title}}</h2>
								<p>
									{{$post->episode}}
								</p>
								<div class="top_article_place">
									<p>
										撮影場所
									</p>
									<p>
										{{preg_replace("/(〒|ZIP：)\d{3}-\d{4}/", '', $post->address)}}
									</p>
								</div>
								<div class="good_field_text">
		                            <span>いいね！</span>
									<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
									<span>1</span>
									<span>コメント</span>
									<i class="fa fa-commenting-o" aria-hidden="true"></i>
									<span>1</span>
									<span>by&nbsp;{{$post->user->nickname}}</span>
		                        </div>
								<div class="top_tag_field">
									<span>タグ:</span>
									@foreach($post->postsTags as $post_tag)
									 	<span>{{$post_tag->tag->name}}</span>
									@endforeach
								</div>
							</div>
						</div>
						<div class="top_article_list_img">
							<img src="{{url($post->oneImage->image)}}">
						</div>
					</a>
				</li>
			@endforeach
		</ul>
	</article>
	<div class="top_pagination">
		{!! $posts->render() !!}
	</div>
</div>
@stop
@section('js_partial')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsACv6SiwiUKM1YnUg2_nIfrjSnYzFke0" type="text/javascript"></script>
<script type="text/javascript" src="{{url('/js/jquery.imagefit.min.js')}}"></script>
<script type="text/javascript">
$.ajaxSetup({headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
var markerData = [
	@foreach ($posts as $post)
		{
			lat: {{$post->lat}},
			lng: {{$post->lng}},
			image: "{{url($post->oneImage->image)}}",
			title: "{{$post->title}}",
			good: 1,
			comment: 1,
			url: "{{url('/article/detail', $post->id)}}",
		},
	@endforeach
];
var marker = [];
var infoWindow = [];
var map;
init();
function init() {
    // キャンパスの要素を取得する
    var canvas = document.getElementById( 'map-canvas' ) ;

    // 中心の位置座標を指定する
    var latlng = new google.maps.LatLng( 37.6510589 , 139.72682550000002 );

    // 地図のオプションを設定する
    var mapOptions = {
        zoom: 5,
        center: latlng ,		// 中心座標 [latlng]
		scrollwheel: false,
    };

    // [canvas]に、[mapOptions]の内容の、地図のインスタンス([map])を作成する
    var map = new google.maps.Map( canvas , mapOptions ) ;

	for (var i = 0; i < markerData.length; i++) {
		markerLatLng = new google.maps.LatLng({lat: markerData[i]['lat'], lng: markerData[i]['lng']});
		marker[i] = new google.maps.Marker({ // マーカーの追加
		   position: markerLatLng, // マーカーを立てる位置を指定
		   map: map // マーカーを立てる地図を指定
	   });
	   infoWindow[i] = new google.maps.InfoWindow({ // 吹き出しの追加
            content: '<a href="' + markerData[i]['url'] + '"><div class="marker_img"><img src="' + markerData[i]['image'] + '"></div>'
					+ '<div class="marker_title">' + markerData[i]['title'] + '</div>'
					+ '<div class="marker_good_field"><span>いいね!</span><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><span>' + markerData[i]['good'] + '</span>'
					+ '<span>コメント</span><i class="fa fa-commenting-o" aria-hidden="true"></i><span>' + markerData[i]['comment'] + '</span></div></a>'
        });

		markerEvent(i);
	}
	google.maps.event.addListener(map, 'click', function(){
		for (var i = 0; i < infoWindow.length; i++) {
			infoWindow[i].close();
		}
	}) ;
    google.maps.event.addDomListener(window, 'resize', function(){
      map.panTo(latlng);//地図のインスタンス([map])
    });

}

// マーカーにクリックイベントを追加
function markerEvent(i) {
    marker[i].addListener('click', function() { // マーカーをクリックしたとき
        infoWindow[i].open(map, marker[i]); // 吹き出しの表示
		$('.marker_img').imagefit({
        	mode: 'outside',
        	force : 'false',
        	halign : 'center',
        	valign : 'middle',
            onStart: function (index, container, imagecontainer) {
                /* Some code */
            },
            onLoad: function() {

            },
        });
    });
}
$(function(){
	$(window).load(function() {
        $('.top_article_list_img').imagefit({
        	mode: 'outside',
        	force : 'false',
        	halign : 'center',
        	valign : 'middle',
            onStart: function (index, container, imagecontainer) {
                /* Some code */
            },
            onLoad: function() {

            },
        });
    });

	$("#regions").on('change', function() {
		var region_id = $(this).val();
		$.ajax({
			type: "POST",
			url: "{{url('/get_prefectures')}}",
			data: {
				"region_id": region_id
			},
			success: function(res) {
				for(var i = 0; i < res.prefectures.length; i ++) {
					$("#prefectures").append("<option value='" + res.prefectures[i].id + "'>" + res.prefectures[i].name + "</option>");
				}
			}
		});
	});

	// $("#submit_button").on('click', function() {
	// 	var keyword = $("#keyword").val();
	// 	var region = $("#regions").val();
	// 	var prefecture = $("#prefectures").val();
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "{{url('/get/article_list')}}",
	// 		data: {
	// 			"keyword": keyword,
	// 			"region": region,
	// 			"prefecture": prefecture
	// 		},
	// 		success: function(res) {
	// 			if (res.message == 'success') {
	// 				var $list = $("#article_clone").clone();
	// 				$(".article_list").remove();
	// 				if (res.articles.length == 0) {
	// 					$(".top_article_list").children('ul').append('<p>該当する思い出はありません</p>');
	// 				}else {
	//
	// 				}
	// 			}
	// 		}
	// 	});
	// });
});
</script>
@stop
