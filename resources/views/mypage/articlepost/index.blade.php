@extends('layout')
@section('css_partial')
<style>
.dz-progress {
  display: none;
}
.dropzone {
  border: 2px dotted rgba(0,0,0,0.3);
}

.dropzone .dz-preview .dz-image {
  width: 150px;
  height: 150px;
}
.dz-image img {
  width: 150px;
}
.dropzone .dz-preview .dz-remove {
  font-size: 30px;
  position: absolute;
  top: -10px;
  right: -10px;
  z-index: 999;
  background-image: url({{url('/remove.png')}});
  width: 32px;
  height: 32px;
}

.dropzone .dz-preview {
  margin: 16px 7%;
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

	width: 100% ;
	height: 100% ;

	margin: 0 ;
	padding: 0 ;
}

.map-embed img
{
	max-width: none ;
}
</style>
@stop
@section('body')
<div class="a_p_box">
    @include('parts.errormessage')
    <div class="a_p_box_title">
        記事を投稿しよう！
    </div>
    <p>(必須)</p>
    <form class="dropzone" id="addImages" action="{{url('/mypage/a_post')}}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="img_flg" id="img_flg" value="">
        <input type="hidden" name="lat" id="lat">
        <input type="hidden" name="lng" id="lng">
        <input type="hidden" name="address" id="address_true">
        <div class="dz-default dz-message">
            <p class="photo_zone_text">写真を追加してください(最大で3枚まで)</p>
            <span class="photo_zone_text">枠内に写真をドロップorクリックして選択！</span>
        </div>
    </form>

    <p id="photo_error" class="none error_message">写真を選択してください</p>
    <div class="photo_age">
        <p>
            この写真を撮ったときのあなたの年代を入力してください。
        </p>
        <select id="photo_age" class="form-control">
            <option value="1">子供時代</option>
            <option value="2">青春時代</option>
            <option value="3">20代</option>
            <option value="4">30代</option>
        </select>
    </div>
    <div class="photo_feeling" class="form-control">
        <p>
            この写真を撮ったときのあなたの気持ちを入力してください。
        </p>
        <select id="photo_feeling" class="form-control">
            <option value="1">嬉しい</option>
            <option value="2">楽しい</option>
            <option value="3">悲しい</option>
            <option value="4">懐かしい</option>
        </select>
    </div>
    <p>
        この写真にまつわるエピソードを入力してください。
    </p>
    <p>(必須)</p>
    <textarea name="episode" rows="8" cols="40" id="episode" class="form-control" maxlength="200" placeholder="エピソードを入力してください。"></textarea>
    <p>
        タグを入力または選択してください。
    </p>
    <input type="text" class="form-control" id="tag_text" placeholder="タグ名を入力してください。" maxlength="20">
    <input type="button" class="btn btn-primary" id="add_tag" value="追加">
    <div id="new_tag_field" class=""></div>
    <div>
        人気のタグ
    </div>
    @foreach($tags as $tag)
        <label>
            <input type="checkbox" name="tags[]" value="{{$tag->name}}">{{$tag->name}}
        </label>
    @endforeach
    <input type="text" id="address" placeholder="住所または地名・建物名を入力してください" class="form-control" value="東京スカイツリー">
    <input type="button" value="検索" id="map_button" class="btn btn-primary">
    <div class="map-embed">
    	<div id="map-canvas">ここに地図が表示されます</div>
    </div>
    <input type="button" class="btn btn-success" id="upload_btn" value="投稿する" >
</div>
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsACv6SiwiUKM1YnUg2_nIfrjSnYzFke0" type="text/javascript"></script>
<script type="text/javascript">
//////////////////// dropzone
    // dropzoneの設定
    var dropzone = new Dropzone ("#addImages", {
        maxFilesize: 2,
        uploadMultiple: true,
        parallelUploads:100,
        addRemoveLinks: true,
        acceptedFiles:'image/*',
        dictInvalidFileType: "画像ファイルを選択してください。",
        maxFilesize:1,
        dictFileTooBig: "ファイルが大きすぎます",
        maxFiles:3,
        dictMaxFilesExceeded: "一度にアップロード出来るのは3ファイルまでです。",
        dictRemoveFile: "",
        autoProcessQueue: false,
        success: function(file, response) {
            if (response.message == 'success') {
                window.location.href = "{{url('/mypage/a_post')}}";
            }else {
                $("#myModal").modal('hide');
                $("#myAlertModal").modal('show');
            }
        }
    });

    // ファイルを追加した時の処理
    dropzone.on('addedfile', function(file) {
        if (file.type.match('image/*')) {
            $("#img_flg").val(1);
        }
    });

    // 投稿する
    $("#upload_btn").on('click', function() {
        $(".ds_input").remove();
        $("#episode_error").remove();
        var flg = articleValidation();
        createInput('photo_age', $("#photo_age").val());
        createInput('photo_feeling', $("#photo_feeling").val());
        createInput('episode', $("#episode").val());
        $(":checkbox[name='tags[]']:checked").each(function (index, checkbox) {
            createInput('tags[]', $(checkbox).val());
        });
        if (flg) {
            var form = $(this).closest('form');
            vex.dialog.confirm({
                message: '投稿してもよろしいですか？',
                callback: function(value) {
                    if (value) {
                        dropzone.processQueue();
                        $("#myModal").modal('show');
                        form.off('submit');
                        form.submit();
                    }
                }
            });
        }else {
            $(this).submit(function() {
                return false;
            });
        }
    });

////////////// googlemap
    // マーカーの配列
    var markersArray = [];
    init();

/////////////////////////////// function

    // エラーのところにスクロール
    function scrollPosition (position_id) {
        var position = $(position_id).offset().top;
        $("html,body").animate({
            scrollTop: position
        }, {
            queue : false
        });
    }

    // validation
    function articleValidation () {
        var success_flg = true;
        if ($("#img_flg").val() != 1) {
            scrollPosition(".a_p_box_title");
            $("#photo_error").removeClass('none');
            episodeErrorMessage(success_flg);
            success_flg = false;
        }else {
            if ($("#episode").val() == "") {
                success_flg = episodeErrorMessage(success_flg);
                $("#episode").focus();
            }else {
                success_flg = episodeErrorMessage(success_flg);
            }
            $("#photo_error").addClass('none');
        }
        return success_flg;
    }

    // タグの追加
    $("#add_tag").on('click', function() {
        var tag = $("#tag_text");
        if (tag.val() != "") {
            $("#new_tag_field").after("<label><input type='checkbox' name='tags[]' value='" + tag.val() + "' checked>" + tag.val() + "</label>");
            tag.val("");
            tag.focus();
        }
    });

    // inputタグの生成
    function createInput(name, value) {
        $input = "<input type='hidden' class='ds_input' name='" + name + "' value='" + value +"'>";
        $("#addImages").append($input);
    }

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


////////////////////////////////////  googlemap api

    // googlemap 表示
    function init() {
        // キャンパスの要素を取得する
        var canvas = document.getElementById( 'map-canvas' ) ;

        // 中心の位置座標を指定する
        var latlng = new google.maps.LatLng( 35.710432 , 139.80937199999994 );

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
        var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

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

        var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

        geocoder.geocode( { 'location': latlng}, function(results, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                clearOverlays();

                map.setCenter(results[0].geometry.location);

                settingMarker(results[0].geometry.location, map);

                var address_true = results[0].formatted_address.replace(/^日本, /, '');
                document.getElementById('address_true').value = address_true;

                map.addListener('click', function(e) {
                    getClickLatLng(e.latLng, map);
                });

            } else {
                alert('エラーが発生しました。ページをリロードして再度お試しください');
                console.log('Geocode was not successful for the following reason: ' + status);
            }
        });
    }

</script>
@stop
