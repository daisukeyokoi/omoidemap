@extends('layout')
@section('css_partial')
<style>
.map-embed
{
	max-width: 100% ;
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
	text-decoration: none;
}
a:hover {
	color: black;
}
.top_pagination {
	text-align: center;
	overflow: hidden;
}
.article_list_header:hover {
	background-color: white;
	opacity: 0.7;
}
.marker {
	position: absolute;
	cursor: pointer;
	width: 40px;
	height: 40px;
	border-radius: 20px;
	border: 2px solid white;
	background-size: cover;
	background-repeat: no-repeat;
	background-position: center;
}
#next {
	float: right;
}
#prev {
	float: left;
}
.top_loading_img {
	text-align: center;
}
@media all and (max-width: 768px) {
	footer  {
		display: none;
	}
}
@media all and (max-width: 480px) {
	footer {
		display: none;
	}
}
</style>
@if (count($events) == 0)
<style>
@media all and (max-width: 768px) {
	.sp_posting_btn {
		bottom: 120px;
	}
}
@media all and (max-width: 480px) {
	.sp_posting_btn {
		bottom: 60px;
	}
}
</style>
@endif
@stop
@section('body')
<?php
use App\Post;
?>
<div id="search_type" data-id="0" style="display:none;"></div>
<div id="episode_flg" data-id="0" style="display:none;"></div>
<div class="sp_feeling_btn_field">
	<div class="btn-group btn-group-justified" role="group">
		<div class="btn-group" role="group">
			<button type="button" class="btn map_btn_all active_border">全て表示</button>
		</div>
		<div class="btn-group" role="group">
			<button type="button" class="btn map_btn_travel">旅行</button>
		</div>
		<div class="btn-group" role="group">
			<button type="button" class="btn map_btn_date">デート</button>
		</div>
		<div class="btn-group" role="group">
			<button type="button" class="btn map_btn_everyday">日常</button>
		</div>
		<div class="btn-group" role="group">
			<button type="button" class="btn map_btn_eat">食事</button>
		</div>
		<div class="btn-group" role="group">
			<button type="button" class="btn map_btn_lodging">宿泊</button>
		</div>
	</div>
</div>
<div class="top_img">
	<img src="{{url('/top_img_6.jpg')}}" alt="" >
</div>
<div class="top_wrapper">
	<div class="top_left_contents">
		<div class="top_main">
			<article class="top_article_list">
				<div class="top_article_list_title">
					<h3>思い出</h3>
				</div>
				<ul>
					<div class="top_loading_img">
						<img src="{{url('/loading.gif')}}">
					</div>
				</ul>
			</article>
			@if (count($events) != 0)
				<div class="event_field">
					<div class="event_title">
						<div class="event_title_left">
							イベント
						</div>
						<div class="event_title_right"></div>
					</div>
					<ul class="event_content">
						@foreach ($events as $event)
							<li>
								<a href="{{url('/event', $event->id)}}">
									<div class="event_content_list_top">
										<div class="event_content_list_top_title">
											{{$event->title}}
										</div>
										<div class="event_content_list_top_img" style="background-image: url({{url($event->image)}})"></div>
									</div>
									<div class="event_content_list_bottom">
										<p>{{$event->title}}</p>
										<p>{{str_replace('-', '/', $event->start)}}~{{str_replace('-', '/', $event->end)}}</p>
									</div>
								</a>
							</li>
						@endforeach
					</ul>
				</div>
			@endif
		</div>
	</div>
	<div class="top_right_contents">
		<div class="top_map">
			<div class="top_map_search">
				<form action="{{url('/')}}" onsubmit="keywordSubmit(); return false;">
					<div class="input-group">
						<input type="text" class="form-control keyword_search" id="keyword" placeholder="場所やキーワードで検索">
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit">
								<i class='glyphicon glyphicon-search'></i>
							</button>
						</span>
					</div>
				</form>
		    </div>
		    <div class="top_map_main">
		        <div class="map-embed">
		        	<div id="map-canvas"></div>
		        </div>
				<div class="btn-group btn-group-justified under_map" role="group">
					<div class="btn-group" role="group">
						<button type="button" class="btn map_btn_all active_border">全て表示</button>
					</div>
					<div class="btn-group" role="group">
						<button type="button" class="btn map_btn_travel">旅行</button>
					</div>
					<div class="btn-group" role="group">
						<button type="button" class="btn map_btn_date">デート</button>
					</div>
					<div class="btn-group" role="group">
						<button type="button" class="btn map_btn_everyday">日常</button>
					</div>
					<div class="btn-group" role="group">
						<button type="button" class="btn map_btn_eat">食事</button>
					</div>
					<div class="btn-group" role="group">
						<button type="button" class="btn map_btn_lodging">宿泊</button>
					</div>
				</div>
		    </div>
			<div class="top_post_btn">
				<a href="@if (Auth::check()){{url('/mypage/a_post')}} @else {{url('/login?post_article=1')}} @endif"><button class="btn btn-primary">思い出を投稿する</button></a>
			</div>
			<a href="@if (Auth::check()){{url('/mypage/a_post')}} @else {{url('/login?post_article=1')}} @endif"><div class="sp_posting_btn"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></div></a>
			@if (count($events) != 0)
				<div class="sp_event">
					<div class="sp_event_main">
						<div class="sp_event_left">
							<div class="sp_event_img" style="background-image: url({{url($events->first()->image)}})"></div>
						</div>
						<div class="sp_event_right">
							<div class="sp_event_right_title">{{$events->first()->title}}コンテスト開催中！</div>
							<div class="sp_event_right_detail_field">
								<a href="{{url('/event', $events->first()->id)}}">
									<div class="sp_event_right_detail">詳細を見る</div>
								</a>
								@if (Auth::check())
									<a href="{{url('/mypage/a_post?e_id='.$events->first()->id)}}">
										<div class="sp_event_right_join">参加する</div>
									</a>
								@else
									<a href="{{url('/login?e_id='.$events->first()->id)}}">
										<div class="sp_event_right_join">参加する</div>
									</a>
								@endif
							</div>
						</div>
						<span class="sp_event_cros"><i class="fa fa-times-circle fa-lg" aria-hidden="true"></i></span>
					</div>
				</div>
			@endif
			<div class="sp_under_menu">
				<div class="btn-group btn-group-justified" role="group">
					<div class="btn-group" role="group">
						<button type="button" class="btn sp_under_map">思い出マップ</button>
					</div>
					<div class="btn-group" role="group">
						<button type="button" class="btn sp_under_episode">エピソード</button>
					</div>
					<div class="btn-group" role="group">
						<button type="button" class="btn sp_under_mypage">マイページ</button>
					</div>
				</div>
			</div>
		</div>
		<div class="top_prefectures">
			<div class="top_region">
				@foreach($regions as $region)
					<div class="region_title">
						{{$region->name}}
					</div>
					<ul>
						@foreach($region->prefectures as $prefecture)
							<li class="prefecture_name" data-name="{{$prefecture->name}}">{{$prefecture->name}}</li>
						@endforeach
					</ul>
				@endforeach
			</div>
		</div>
	</div>
</div>
@stop
@section('js_partial')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsACv6SiwiUKM1YnUg2_nIfrjSnYzFke0" type="text/javascript"></script>
<script type="text/javascript" src="{{url('/js/jquery.imagefit.min.js')}}"></script>
<script type="text/javascript">
$.ajaxSetup({headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
$(window).resize(function() {
	if ($(window).width() > 768) {
		$('.map-embed').show();
		$('.top_map_search').show();
		$('.top_left_contents').show();
	}else {
		var episode_flg = $("#episode_flg").data('id');
		if (episode_flg) {
			$('.top_left_contents').show();
			$('.map-embed').hide();
			$('.top_map_search').hide();
		}else {
			$('.top_left_contents').hide();
			$('.map-embed').show();
			$('.top_map_search').show();
		}
	}
});
$('.sp_event_cros').click(function() {
	$('.sp_event').hide();
	if ($(window).width() > 480) {
		$('.sp_posting_btn').css({'bottom': '120px'});
	}else {
		$('.sp_posting_btn').css({'bottom': '60px'});
	}
});
// マーカーのデータ
var markerData = <?php echo json_encode(AppUtil::createMarkerData($posts), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
var marker = [];
var infoWindow = [];
var map;
var current_num = 0;
var res_articles = [];
var page_num = 1;
var list_num = 20;
var basic_lat = Number(37.6510589);
var basic_lng = Number(139.72682550000002);
var basic_zoom = Number(5);
var basic_sw_lat;
var basic_sw_lng;
var basic_ne_lat;
var basic_ne_lng;
////////////////// カスタムマーカー
function CustomMarker(latlng, map, args, markerData) {
	this.latlng = latlng;
	this.args = args;
	this.setMap(map);
	this.markerData = markerData;
	this.map = map;
}

CustomMarker.prototype = new google.maps.OverlayView();

CustomMarker.prototype.draw = function() {

	var self = this;
	var div = this.div;
	var span = this.span;
	var markerData = this.markerData;
	var map = this.map;

	if (!div) {

		div = this.div = document.createElement('div');
		div.className = 'marker';
		div.style.backgroundImage = 'url(' + this.markerData.image + ')';

		if (typeof(self.args.marker_id) !== 'undefined') {
			div.dataset.marker_id = self.args.marker_id;
		}

		var uluru = {lat: Number(markerData.lat), lng: Number(markerData.lng)};
		var infowindow = new google.maps.InfoWindow({
			content: '<div class="marker_wrap">'
					+	'<a href="' + markerData['url'] + '"><div class="marker_img" style="background-image:url(' + markerData['image'] + ');"></div></a>'
					+ 	'<div class="marker_good_field">'
					+		'<a href="' + markerData['url'] + '"><span>いいね!</span><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><span>' + markerData['good'] + '</span>'
					+ 		'<span>コメント</span><i class="fa fa-commenting-o" aria-hidden="true"></i><span>' + markerData['comment'] + '</span></a>'
					// +		'<div><a href="#"><i class="fa fa-twitter-square marker_twitter fa-2x" aria-hidden="true"></i></a><a href="#"><i class="fa fa-facebook-official marker_facebook fa-2x" aria-hidden="true"></i></a><a href="#"><i class="fa fa-instagram marker_instagram fa-2x" aria-hidden="true"></i></a></div>'
					+		'<a href="' + markerData['url'] + '"><div class="marker_feeling_field"><span class="btn btn-danger article_list_data_label">' + markerData['feeling'] + '</span>'
					+		'<span class="btn btn-success article_list_data_label">' + markerData['age'] + '</span></div>'
					+		'<div>' + markerData['tag'] + '</div></a>'
					+ 	'</div>'
					+'</div>'
					+'<a href="' + markerData['url'] + '"><div class="marker_episode">' + markerData['episode'] + '</div></a>',
			position: uluru
		});

		// google.maps.event.addDomListener(div, "click", function() {
		// 	location.href = markerData.url;
		// });

		google.maps.event.addDomListener(div, "click", function() {
			infowindow.open(map);
		});

		// google.maps.event.addDomListener(map, "click", function() {
		// 	infowindow.close();
		// });

		var panes = this.getPanes();
		panes.overlayImage.appendChild(div);
		//panes.overlayImage.appendChild(span);
	}

	var point = this.getProjection().fromLatLngToDivPixel(this.latlng);

	if (point) {
		div.style.left = (point.x - 20) + 'px';
		div.style.top = (point.y - 10) + 'px';
		//span.style.left = (point.x + 15) + 'px';
		//span.style.top = (point.y + 5) + 'px';
	}
};

CustomMarker.prototype.remove = function() {
	if (this.div) {
		this.div.parentNode.removeChild(this.div);
		this.div = null;
	}
};

CustomMarker.prototype.getPosition = function() {
	return this.latlng;
};
///////////////// カスタムマーカー終わり

init(0, true);

///////////googlemap
function init(type, default_flg) {
	var paramsArray = getUrlParams();
	if (default_flg) {
		var lat  = basic_lat;
		var lng  = basic_lng;
		var zoom = basic_zoom;
	}else {
		var lat = Number(paramsArray['lat']);
		var lng = Number(paramsArray['lng']);
		var zoom = Number(paramsArray['zoom']);
	}

    // キャンパスの要素を取得する
    var canvas = document.getElementById( 'map-canvas' ) ;

    // 中心の位置座標を指定する
    var latlng = new google.maps.LatLng( lat , lng );
    // 地図のオプションを設定する
    var mapOptions = {
        zoom: zoom,
        center: latlng ,		// 中心座標 [latlng]
		scrollwheel: true,
		disableDefaultUI: true,
    };

    // [canvas]に、[mapOptions]の内容の、地図のインスタンス([map])を作成する
    var map = new google.maps.Map( canvas , mapOptions ) ;

	google.maps.event.addListenerOnce(map, 'idle', function() {
		var bounds = map.getBounds();
		history.pushState(null, null, location.pathname + '?lat=' + latlng.lat() + '&lng=' + latlng.lng() + '&zoom=' + zoom + '&sw_lat=' + bounds.getSouthWest().lat() + '&sw_lng=' + bounds.getSouthWest().lng() + '&ne_lat=' + bounds.getNorthEast().lat() + '&ne_lng=' + bounds.getNorthEast().lng());
		dispLatLang(type);
	});
	postLatLangZoom(map, type);

	for (var i = 0; i < markerData.length; i++) {
		markerLatLng = new google.maps.LatLng({lat: Number(markerData[i]['lat']), lng: Number(markerData[i]['lng'])});
		var overlay = new CustomMarker(
			markerLatLng,
			map,
			{},
			markerData[i]
		);
	}

}


/////// function
// マーカーにクリックイベントを追加
function markerEvent(i) {
    marker[i].addListener('click', function() { // マーカーをクリックしたとき
        infoWindow[i].open(map, marker[i]); // 吹き出しの表示
    });
}

function setZoomLimit(map, mapTypeId){
  //マップタイプIDを管理するレジストリを取得
  var mapTypeRegistry = map.mapTypes;

  //レジストリから現在のマップタイプIDのMapTypeを取得する
  var mapType = mapTypeRegistry.get(mapTypeId);
  //ズームレベルを設定する
  if ($(window).width() <= 768) {
	  mapType.maxZoom = 15;  //SATELLITE・HYBRIDは機能しない
	  mapType.minZoom = 4;
  }else {
	  mapType.maxZoom = 15;  //SATELLITE・HYBRIDは機能しない
	  mapType.minZoom = 5;
  }
}

function postLatLangZoom(map, type) {
	google.maps.event.addListener(map, 'dragend', function() {
		changeUrl(map);
		dispLatLang(type);
	});

	google.maps.event.addListener(map, 'zoom_changed', function() {
		changeUrl(map);
		dispLatLang(type);
	});

	google.maps.event.addListenerOnce(map, "projection_changed", function(){
      map.setMapTypeId(google.maps.MapTypeId.HYBRID);  //一瞬だけマップタイプを変更
      setZoomLimit(map, google.maps.MapTypeId.ROADMAP);
      setZoomLimit(map, google.maps.MapTypeId.HYBRID);
      setZoomLimit(map, google.maps.MapTypeId.SATELLITE);
      setZoomLimit(map, google.maps.MapTypeId.TERRAIN);
      map.setMapTypeId(google.maps.MapTypeId.ROADMAP);  //もとに戻す
    });
}
// urlを変更
function changeUrl(map) {
	var mapArray = [];
	var bounds = map.getBounds();
	mapArray['lat'] = map.getCenter().lat();
	mapArray['lng'] = map.getCenter().lng();
	mapArray['zoom'] = map.getZoom();
	mapArray['sw_lat'] = bounds.getSouthWest().lat();
	mapArray['sw_lng'] = bounds.getSouthWest().lng();
	mapArray['ne_lat'] = bounds.getNorthEast().lat();
	mapArray['ne_lng'] = bounds.getNorthEast().lng();
	history.pushState(null, null, location.pathname + '?lat=' + mapArray['lat'] + '&lng=' + mapArray['lng'] + '&zoom=' + mapArray['zoom'] + '&sw_lat=' + mapArray['sw_lat'] + '&sw_lng=' + mapArray['sw_lng'] + '&ne_lat=' + mapArray['ne_lat'] + '&ne_lng=' + mapArray['ne_lng']);
}

function getUrlParams() {
	var url = location.href;
	var paramsArray = [];
	if (url.match('&')) {
		var parameters = url.split("?");
		var params = parameters[1].split('&');
		for (var i = 0; i < params.length; i++) {
			neet = params[i].split('=');
			paramsArray[neet[0]] = neet[1];
		}
	}
	return paramsArray;
}
function dispLatLang(type) {
	$(".top_loading_img").css('display', '');
	current_num = 0;
	res_articles = [];

	// urlパラメータを取得
	var paramsArray = getUrlParams();
	$.ajax({
		type: "POST",
		url: "{{url('/ajax/article_list')}}",
		data: {
			'sw_lat': paramsArray['sw_lat'],
			'sw_lng': paramsArray['sw_lng'],
			'ne_lat': paramsArray['ne_lat'],
			'ne_lng': paramsArray['ne_lng'],
			'type'  : type,
		},
		success: function(res) {
			res_articles = res.articles;
			if (res_articles.length > list_num) {
				var length = list_num;
			}else {
				var length = res_articles.length;
			}
			$(".top_loading_img").css('display', 'none');
			$(".top_article_list").children('ul').children('li').remove();
			$(".top_article_list").children('ul').children('p').remove();
			$(".top_article_list").children('ul').children('input').remove();
			if (res_articles.length != 0) {
				for (var i = 0; i < length; i++) {
					current_num = articleListDesign(res_articles, i, current_num);
				}
				if (res_articles.length > list_num) {
					if (current_num == list_num) {
						$(".top_article_list")
						.children('ul')
						.append('<input type="button" class="btn btn-primary" id="next" value="Next&nbsp;>">')
						.hide().fadeIn('normal');
					}
				}
			}else {
				$(".top_article_list").children('ul').append('<p>該当するものはありません</p>');
			}
		}

	});
}
// 場所を検索
function addressFocus(address, map, type) {
	var search_type = $("#search_type").data('id');
	// キャンパスの要素を取得する
    var canvas = document.getElementById( 'map-canvas' );
	var geocoder = new google.maps.Geocoder();

	geocoder.geocode( { 'address': address}, function(results, status) {
		// ジオコーディングが成功した場合
		if (status == google.maps.GeocoderStatus.OK) {
			if (type == 'keyword') {
				// 地図表示に関するオプション
				var mapOptions = {
					zoom: 15,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					scrollwheel: true,
					disableDefaultUI: true,
				};
			}else {
				// 地図表示に関するオプション
				var mapOptions = {
					zoom: 10,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					scrollwheel: true,
					disableDefaultUI: true,
				};
			}

			// 地図を表示させるインスタンスを生成
			var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

			google.maps.event.addListenerOnce(map, 'center_changed', function() {
				changeUrl(map);
				dispLatLang(search_type);
			});

			postLatLangZoom(map, search_type);

			for (var i = 0; i < markerData.length; i++) {
				markerLatLng = new google.maps.LatLng({lat: Number(markerData[i]['lat']), lng: Number(markerData[i]['lng'])});
				var overlay = new CustomMarker(
					markerLatLng,
					map,
					{},
					markerData[i]
				);
			}

			// google.maps.Map()コンストラクタに定義されているsetCenter()メソッドで
			// 変換した緯度・経度情報を地図の中心に表示
			map.setCenter(results[0].geometry.location);

		// ジオコーディングが成功しなかった場合
		} else {
			if (address == "") {
			}else {
				alert("その場所を検索することができませんでした");
			}
		}
	});
}
function keywordSubmit() {
	var address = $("#keyword").val();
	addressFocus(address, map, 'keyword');
}

$(document).on('click', '#next', function() {
	page_num += 1;
	NextPrev();
});

$(document).on('click', '#prev', function() {
	page_num -= 1;
	current_num = list_num * (page_num - 1);
	NextPrev();
});

// next, prev
function NextPrev() {
	$(".top_article_list").children('ul').children('li').remove();
	$(".top_article_list").children('ul').children('p').remove();
	$(".top_article_list").children('ul').children('input').remove();
	if (res_articles.length - current_num > list_num) {
		var length = current_num + list_num;
	}else {
		var length = current_num + (res_articles.length - current_num);
	}
	for (var i = current_num; i < length; i++) {
		current_num = articleListDesign(res_articles, i, current_num);
	}
	if (current_num < res_articles.length) {
		$(".top_article_list")
		.children('ul')
		.append('<input type="button" class="btn btn-primary" id="next" value="Next&nbsp;>">')
		.hide().fadeIn('normal');
	}
	if (current_num > list_num) {
		$(".top_article_list")
		.children('ul')
		.append('<input type="button" class="btn btn-primary" id="prev" value="<&nbsp;Prev">')
		.hide().fadeIn('normal');
	}
}

// 記事リスト作成
function articleListDesign(res_articles, i, current_num) {
	var url = "{{url('/ajax/favcheck')}}";
	$(".top_article_list")
	.children('ul')
	.append(
		'<li>'
		+	'<div class="article_list_header">'
		+		'<a href="' + res_articles[i][0].url + '">'
		+			'<div class="article_list_header_top">'
		+				'<i class="fa fa-user" aria-hidden="true"></i>'
		+				'<span class="article_list_footer_user_name">' + res_articles[i][0].nickname + '</span>'
		+				'<span class="btn btn-danger article_list_data_label">' + res_articles[i][0].feeling + '</span>'
		+				'<span class="btn btn-success article_list_data_label">' + res_articles[i][0].age + '</span>'
		+				'<span class="article_list_footer_address">' + wordRoundJs(res_articles[i][0].address, 15) + '</span>'
		+			'</div>'
		+			'<div class="article_list_main">'
		+				'<div class="article_list_img" style="background-image: url(' + res_articles[i][0].image +')"></div>'
		+				'<div class="article_list_data">'
		+					'<p class="article_list_data_title">' + res_articles[i][0].episode + '</p>'
		// +					'<div>' + res_articles[i][0].tag + '</div>'
		+				'</div>'
		+			'</div>'
		+		'</a>'
		+	'</div>'
		+	'<div class="article_list_footer">'
		+		'<i class="fa fa-heart" aria-hidden="true" data-id="' + res_articles[i][0].id + '" id="fav_' + res_articles[i][0].id + '"></i>'
		+		'<span class="article_list_footer_good">行きたい！</span>'
		+      	'<span class="article_list_footer_good_count">' + res_articles[i][0].goods + '</span>'
		+		'<span class="article_list_footer_comment">コメント&nbsp;</span>'
		+		'<span class="article_list_footer_comment_count">' + res_articles[i][0].comments + '</span>'
		+	'</div>'
		+'</li>'
	).hide().fadeIn('normal');
	$.ajax({
		type: 'POST',
		url : url,
		data: {
			'post_id': res_articles[i][0].id
		},
		success: function(res) {
			if (res.mes == 's') {
				$("#fav_" + res_articles[i][0].id).addClass('fav_flag');
			}
		}
	});
	return current_num += 1;
}

function wordRoundJs(str, length) {
	var result = '';
	if (str.length > length) {
		result = str.slice(0, length) + '...';
	}else {
		result = str;
	}
	return result;
}

//　お気に入り登録
$(document).on('click', '.fa-heart', function(){
	var post_id = $(this).data('id');
	var url = "{{url('/ajax/favorite/post')}}";
	var clicked = $(this);
	$.ajax({
		type: 'POST',
		url: url,
		data: {
			'post_id': post_id,
		},
		success: function (res) {
			if (typeof(res.error) == 'undefined') {
				if (clicked.hasClass('fav_flag')) {
					clicked.removeClass('fav_flag');
				}else {
					clicked.addClass('fav_flag');
				}
			}
		}
	});
});

function feelingTypeChangeMarker(type, self) {
	type = Number(type);
	$('.active_border').removeClass('active_border');
	self.addClass('active_border');
	$("#search_type").data('id', type);
	var episode_flg = $('#episode_flg').data('id');
	switch (type) {
		case 1:
			markerData = <?php echo json_encode(AppUtil::createMarkerData(Post::where('feeling', AppUtil::TRAVEL)->get()), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
			break;
		case 2:
			markerData = <?php echo json_encode(AppUtil::createMarkerData(Post::where('feeling', AppUtil::DATE)->get()), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
			break;
		case 3:
			markerData = <?php echo json_encode(AppUtil::createMarkerData(Post::where('feeling', AppUtil::EVERYDAY)->get()), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
			break;
		case 4:
			markerData = <?php echo json_encode(AppUtil::createMarkerData(Post::where('feeling', AppUtil::EAT)->get()), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
			break;
		case 5:
			markerData = <?php echo json_encode(AppUtil::createMarkerData(Post::where('feeling', AppUtil::LODGING)->get()), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
			break;
		default:
			markerData = <?php echo json_encode(AppUtil::createMarkerData($posts), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
	}
	if (episode_flg) {
		dispLatLang(type);
	}else {
		init(type, true);
	}
}

// 都道府県クリックでそこにズーム
$(".prefecture_name").on('click', function() {
	var prefecture_name = $(this).data('name');
	addressFocus(prefecture_name, map, 'prefecture');
	$("html,body").animate({scrollTop:$('.top_map_search').offset().top});
});

// 全てを表示をクリック
$('.map_btn_all').click(function () {
	var self = $(this);
	feelingTypeChangeMarker(0, self);
});

// 旅行をクリック
$('.map_btn_travel').click(function () {
	var self = $(this);
	feelingTypeChangeMarker("{{AppUtil::TRAVEL}}", self);
});

// デートをクリック
$('.map_btn_date').click(function () {
	var self = $(this);
	feelingTypeChangeMarker("{{AppUtil::DATE}}", self);
});

// 日常をクリック
$('.map_btn_everyday').click(function () {
	var self = $(this);
	feelingTypeChangeMarker("{{AppUtil::EVERYDAY}}", self);
});

// 食事をクリック
$('.map_btn_eat').click(function () {
	var self = $(this);
	feelingTypeChangeMarker("{{AppUtil::EAT}}", self);
});

// 宿泊をクリック
$('.map_btn_lodging').click(function () {
	var self = $(this);
	feelingTypeChangeMarker("{{AppUtil::LODGING}}", self);
});

// タブレットスマホで思い出マップボタンをクリック
$('.sp_under_map').click(function(){
	$('#episode_flg').data('id', 0);
	var type = $("#search_type").data('id');
	$('.map-embed').show();
	$('.top_map_search').show();
	$('.top_left_contents').hide();
	init(type, false);

});

$('.sp_under_episode').click(function(){
	$('#episode_flg').data('id', 1);
	$('.top_map_search').hide();
	$('.map-embed').hide();
	$('.top_left_contents').show();
});

$('.sp_under_mypage').click(function() {
	var href = window.location.pathname + 'mypage';
	window.location.href = href;
});




////////////// googlemap 終わり

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
});
</script>
@stop
