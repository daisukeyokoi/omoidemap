@extends('layout')
@section('css_partial')
<link rel="stylesheet" href="{{url('/css/lib/fileinput/4.3.1/fileinput.min.css')}}">
<style>
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
    margin-bottom: 40px;
}

.map-embed > div
{
	position: absolute ;
	top: 0 ;
	left: 0 ;

	width: 100% ;
	height: 100% ;

	margin: 0 ;
	padding: 0 ;
}

.map-embed img
{
	max-width: none ;
}
ul {
    list-style: none;
    padding: 0;
}

</style>
@stop
@section('body')
<div class="a_p_box">
    @include('parts.errormessage')
    <ul id="article_error_message">
        <li></li>
    </ul>

    <!-- ///////////左サイド///////////////////// -->
    <div class="a_p_box_left">
        <!-- map -->
        <div class="a_p_description">
            <span class="a_p_description_title">1.思い出の場所を選択してください。(地図をクリックして選択)</span>
            <span class="need_mark btn btn-danger">必須</span>
        </div>
        <div class="a_p_map_input">
            <form action="{{url('/')}}" onsubmit="keywordSubmit(); return false;">
                <input type="text" id="address" placeholder="住所または地名・建物名を入力してください" class="form-control" value="{{AppUtil::postNumberRemove($post->address)}}">
                <input type="button" value="検索" id="map_button" class="btn btn-primary">
            </form>
        </div>
        <div class="map-embed">
        	<div id="post-map-canvas">ここに地図が表示されます</div>
        </div>
        <!-- map end -->

        <div class="a_p_description">
            <span class="a_p_description_title">2.思い出の種類と当時の年齢を選んでください。</span>
            <span class="need_mark btn btn-danger">必須</span>
        </div>

        <!-- photo feeling -->
        <div class="photo_feeling">
            @foreach(AppUtil::photoFeelingList() as $key => $value)
                <input type="radio" name="photo_feeling" id="feeling_select_{{$value}}" value="{{$value}}" @if (old('photo_feeling', $post->feeling) == $value) checked @endif>
                <label for="feeling_select_{{$value}}">{{$key}}</label>
            @endforeach
        </div>
        <!-- photo feeling end -->

        <!-- photo age -->
        <div class="photo_age">
            @foreach(AppUtil::photoAgeList() as $key => $value)
                <input type="radio" name="photo_age" id="age_select_{{$value}}" value="{{$value}}" @if (old('photo_age', $post->age) == $value) checked @endif>
                <label for="age_select_{{$value}}">{{$key}}</label>
            @endforeach
        </div>
        <!-- photo age end -->

        <!-- tag -->
        <p>タグ</p>
        <div class="a_post_tag_field">
            <form action="{{url('/')}}" onsubmit="tagSubmit(); return false;">
                <input type="text" class="form-control" id="tag_text" placeholder="タグ名を入力してください。" maxlength="20">
                <input type="button" class="btn" id="add_tag" value="追加">
            </form>
            <div id="new_tag_field" class="">
                @if ( count($post_tags) > 0 )
                    @foreach($post_tags as $key => $value)
                        <div class="selected_tag">
                            <input type="checkbox" class="selected_tag" name="tags[]" value="{{$value}}" id="{{$value}}" checked>
                            <label>{{$value}}&nbsp;&nbsp;</label>
                            <span class="tag_delete">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </span>
                        </div>
                    @endforeach
                @else
                    <p id="new_tag_placeholder">選択されたタグはここに表示されます。</p>
                @endif
            </div>
        </div>
        <p>プロフィールタグ</p>
        <div class="profile_tag_field">
            @foreach($tags as $tag)
                <div class="profile_tag">{{$tag->name}}&nbsp;&nbsp;<i class="fa fa-angle-right" aria-hidden="true"></i></div>
            @endforeach
        </div>
        <!-- tag end -->
    </div>
    <!-- ///////////左サイド終わり///////////////////// -->

    <!-- ///////////右サイド///////////////////// -->
    <div class="a_p_box_right">
        <div class="a_p_description">
            <span class="a_p_description_title">3.思い出のエピソードを記入してください。(地図をクリックして選択)</span>
            <span class="need_mark btn btn-danger">必須</span>
        </div>
        <textarea name="episode" rows="17" cols="40" id="episode" class="form-control a_post_episode" maxlength="2000">{{$post->episode}}
        </textarea>
        <div class="a_p_description">
            <span class="a_p_description_title">4.思い出の写真</span>
        </div>

        <div class="update-tweet-image" style="background-image: url({{url(AppUtil::showPostImage($post))}});">
        </div>
        <form action="{{url('/mypage/post/updatepost')}}" method="POST" id="addImages" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" value="{{$post->id}}" name="post_id">
            <input type="hidden" value="" name="lat" id="lat">
            <input type="hidden" value="" name="lng" id="lng">
            <input type="hidden" value="" name="address_true" id="address_true">
            <input class="fileinput file-loading" name="image_file" type="file">
        </form>
    </div>
    <!-- ///////////右サイド終わり///////////////////// -->
</div>
<input type="button" class="btn btn-success" id="upload_btn" value="変更を保存する" >

<!-- ロードのモーダル -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
                <p style="margin-left: 5%; font-size: 20px;">Now Loading</p>
                <img src="http://www.benricho.org/loading_images/img-popular/FFF-F90-08.gif" style="width: 100%;">
            </div>
        </div>
    </div>
</div>
<!-- ロードのモーダル終わり -->

<!-- アラートモーダル -->
<div class="modal fade" id="myAlertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        エラーが発生しました。ページをリロードして再度お試しください
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
      </div>
    </div>
  </div>
</div>
<!-- アラートモーダル終わり -->
@stop
@section('js_partial')
<script src="{{url('/js/lib/fileinput/4.3.1/fileinput.min.js')}}"></script>
<script src="{{url('/js/lib/fileinput/4.3.1/fileinput_locale_ja.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsACv6SiwiUKM1YnUg2_nIfrjSnYzFke0" type="text/javascript"></script>
<script type="text/javascript">
    (function($) {
        $(".fileinput").fileinput({
        "language": "ja",
        "showUpload": false,
        "showCaption": false
        });
    }(jQuery));

    // 投稿の緯度・経度
    var lat = parseFloat("{{$post->lat}}");
    var lng = parseFloat("{{$post->lng}}");
    
    //////////////////// dropzone
    if ($(window).width() <= 600) {
        var thumbnailHeight = "200";
        var thumbnailWidth = "200";
    } else {
        var thumbnailHeight = "400";
        var thumbnailWidth = "400";
    }

    // 投稿する
    $("#upload_btn").on('click', function() {
        $(".ds_input").remove();
        createInput('photo_age', $("input[name=photo_age]:checked").val());
        createInput('photo_feeling', $("input[name=photo_feeling]:checked").val());
        createInput('episode', $("#episode").val());
        $(":checkbox[name='tags[]']:checked").each(function (index, checkbox) {
            createInput('tags[]', $(checkbox).val());
        });
        var form = $("#addImages");
        vex.dialog.confirm({
            message: '投稿してもよろしいですか？',
            callback: function(value) {
                if (value) {
                    $("#myModal").modal('show');
                    form.off('submit');
                    form.submit();
                }
            }
        });
    });

////////////// googlemap
    // マーカーの配列
    var markersArray = [];
    init(lat, lng);

/////////////////////////////// function

    // エラーのところにスクロール
    function scrollPosition () {
        $("html,body").animate({
            scrollTop: 0
        }, {
            queue : false
        });
    }

    // validation
    function articleValidation () {
        var error_list = $("#article_error_message");
        var success_flg = true;
        if ($("#img_flg").val() != {{AppUtil::FLG_ON}}) {
            success_flg = validationMessageScroll('img_flg', error_list);
        }
        if ($("#episode").val() == "") {
            success_flg = validationMessageScroll('episode', error_list);
        }else {
            if ($("#episode").val().length < 10) {
                success_flg = validationMessageScroll('episode_min', error_list);
            }
        }
        if ($("#jp_flg").val() != {{AppUtil::FLG_ON}}) {
            success_flg = validationMessageScroll('jp_flg', error_list);
        }
        return success_flg;
    }

    // validation message, scroll
    function validationMessageScroll(name, error_list) {
        scrollPosition();
        success_flg = false;
        switch (name) {
            case 'img_flg':
                error_list.append("<li class='error_message'>写真を選択してください。</p>");
                break;
            case 'episode':
                error_list.append("<li class='error_message'>エピソードを入力してください。</p>");
                break;
            case 'episode_min':
                error_list.append("<li class='error_message'>エピソードは10文字以上で入力してください。</p>");
                break;
            case 'jp_flg':
                error_list.append("<li class='error_message'>日本以外は選択しないでください。</p>");
                break;
        }
        return success_flg;
    }

    // タグの追加
    $("#add_tag").on('click', function() {
        $("#new_tag_placeholder").hide();
        var tag = $("#tag_text").val().replace(/^[\s　]+|[　\s]+$/g, '');
        addTag('text', tag);
    });

    // プロフィールタグをクリックした時
    $(".profile_tag").on('click', function(){
        $("#new_tag_placeholder").hide();
        var tag = $(this).text().replace(/^[\s　]+|[　\s]+$/g, '');
        addTag('profile', tag);
    });

    // タグの作成
    function addTag(type, tag) {
        if (type == "profile") {
            if (tag != "") {
                $("#new_tag_field").append("<div class='selected_tag'><input type='checkbox' class='selected_tag' name='tags[]' value='" + tag + "' id='" + tag + "' checked><label>" + tag + "&nbsp;&nbsp;</label><span class='tag_delete'><i class='fa fa-times' aria-hidden='true'></i></span></div>");
            }
        }else {
            if (tag != "") {
                $("#new_tag_field").append("<div class='selected_tag'><input type='checkbox' class='selected_tag' name='tags[]' value='" + tag + "' id='" + tag + "' checked><label>" + tag + "&nbsp;&nbsp;</label><span class='tag_delete'><i class='fa fa-times' aria-hidden='true'></i></span></div>");
                $("#tag_text").val("");
                $("#tag_text").focus();
            }
        }
    }

    // inputタグの生成
    function createInput(name, value) {
        $input = "<input type='hidden' class='ds_input' name='" + name + "' value='" + value +"'>";
        $("#addImages").append($input);
    }

    // タグの消去
    $(document).on('click', '.tag_delete', function() {
        $(this).closest('div').remove();
    });
    // エピソードのエラーメッセージ
    function episodeErrorMessage (success_flg) {
        var episode = $("#episode");
        if (episode.val() == "") {
            episode.after("<p id='episode_error' class='error_message'>エピソードを入力してください。</p>");
            success_flg = false;
        }else {
            if (episode.val().length < 10) {
                episode.after("<p id='episode_error' class='error_message'>エピソードは10文字以上で入力してください。</p>");
                if ($("#img_flg").val() == 1) {
                    episode.focus();
                }
                success_flg = false;
            }
        }
        return success_flg;
    }

    // 地図検索
    $("#map_button").on('click', function() {
        codeAddress($("#address").val());
    });

    function keywordSubmit() {
        codeAddress($("#address").val());
    }

    function tagSubmit() {
        $("#new_tag_placeholder").hide();
        var tag = $("#tag_text").val().replace(/^[\s　]+|[　\s]+$/g, '');
        addTag('text', tag);
    }

////////////////////////////////////  googlemap api

    // googlemap 表示
    function init(lat, lng) {
        // キャンパスの要素を取得する
        var canvas = document.getElementById( 'post-map-canvas' ) ;

        // 中心の位置座標を指定する
        var latlng = new google.maps.LatLng( lat, lng );

        // 地図のオプションを設定する
        var mapOptions = {
            zoom: 18,
            center: latlng ,		// 中心座標 [latlng]
        };

        // [canvas]に、[mapOptions]の内容の、地図のインスタンス([map])を作成する
        var map = new google.maps.Map( canvas , mapOptions ) ;

        settingMarker(latlng, map);
        codeAddressLatlng(latlng);

        // クリックイベントを追加
        map.addListener('click', function(e) {
            getClickLatLng(e.latLng, map);
        });
    }

    // マーカー設置
    function settingMarker(position, map) {
        var marker = new google.maps.Marker({
            map: map,
            position: position
        });
        markersArray.push(marker);

        // 座標を表示
        document.getElementById('lat').value = position.lat();
        document.getElementById('lng').value = position.lng();

        // 座標の中心をずらす
        // http://syncer.jp/google-maps-javascript-api-matome/map/method/panTo/
        map.panTo(position);
    }

    // クリックした位置にマーカーを設置
    function getClickLatLng(lat_lng, map) {
        clearOverlays();
        settingMarker(lat_lng, map);
        codeAddressLatlng(lat_lng);
    }

    // 設置したマーカーをすべて削除する。
    function clearOverlays() {
        for (var i = 0; i < markersArray.length; i++ ) {
            markersArray[i].setMap(null);
        }
    }

    // 入力した住所にマーカーを立てる
    function codeAddress(address) {

        // google.maps.Geocoder()コンストラクタのインスタンスを生成
        var geocoder = new google.maps.Geocoder();

        // 地図表示に関するオプション
        var mapOptions = {
            zoom: 18,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        // 地図を表示させるインスタンスを生成
        var map = new google.maps.Map(document.getElementById("post-map-canvas"), mapOptions);

        // geocoder.geocode()メソッドを実行
        geocoder.geocode( { 'address': address}, function(results, status) {

            // ジオコーディングが成功した場合
            if (status == google.maps.GeocoderStatus.OK) {
                clearOverlays();
                // google.maps.Map()コンストラクタに定義されているsetCenter()メソッドで
                // 変換した緯度・経度情報を地図の中心に表示
                map.setCenter(results[0].geometry.location);

                // 地図上に目印となるマーカーを設定います。
                // google.maps.Marker()コンストラクタにマーカーを設置するMapオブジェクトと
                // 変換した緯度・経度情報を渡してインスタンスを生成
                // →マーカー詳細
                settingMarker(results[0].geometry.location, map);

                // 住所を取得(日本の場合だけ「日本, 」を削除)
                var address_true = results[0].formatted_address.replace(/^日本, /, '');
                document.getElementById('address_true').value = address_true;
                jpCheck(results);

                // クリックイベントを追加
                map.addListener('click', function(e) {
                    getClickLatLng(e.latLng, map);
                });

            // ジオコーディングが成功しなかった場合
            } else {
                if (address == "") {
                    alert("検索する場所を入力してください");
                }else {
                    alert("その場所を検索することができませんでした");
                }
                console.log('Geocode was not successful for the following reason: ' + status);
            }

        });
    }

    // 入力した住所にマーカーを立てる
    function codeAddressLatlng(latlng) {
        var geocoder = new google.maps.Geocoder();

        var mapOptions = {
            zoom: 18,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById("post-map-canvas"), mapOptions);

        geocoder.geocode( { 'location': latlng}, function(results, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                clearOverlays();

                map.setCenter(results[0].geometry.location);

                settingMarker(results[0].geometry.location, map);

                var address_true = results[0].formatted_address.replace(/^日本, /, '');
                document.getElementById('address_true').value = address_true;
                jpCheck(results);

                map.addListener('click', function(e) {
                    getClickLatLng(e.latLng, map);
                });

            } else {
                alert('エラーが発生しました。ページをリロードして再度お試しください');
                console.log('Geocode was not successful for the following reason: ' + status);
            }
        });
    }

    function jpCheck(results) {
        if (results[0].formatted_address.slice(0,2) != '日本') {
            $("#jp_flg").val({{AppUtil::FLG_OFF}});
        }else {
            $("#jp_flg").val({{AppUtil::FLG_ON}});
        }
    }

    $('#map_button').click();

</script>
@stop
