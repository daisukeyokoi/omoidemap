/* ========================================
  サイドバー固定
======================================== */
if (window.location.href.split("/").pop() != "updateprofile") {
  var fixedSidebar = (function() {
    var navi,
        main,
        main_scroll,
        fixed_start,
        fixpx_top,
        fixpx_end_top;
    return {
      run : function() {
        // 固定するナビゲーションレイヤー
        navi = $('.side');

        // メインのレイヤー
        main = $('.timeline');
        this.refresh();
      },
      // 基準になる数値の計算
      refresh : function() {
        navi.css({
          position : 'relative',
          top : 'auto',
          width : $('.side').width()
        });
        // メインコンテンツとナビの上部
        var navi_top = navi.offset().top - parseInt(navi.css('margin-top'));
        var main_top = main.offset().top - parseInt(main.css('margin-top'));
        if(navi_top + navi.outerHeight(true) < main_top + main.outerHeight(true)) {
          // 開始時のTOP基準値
          fixpx_top = Math.max(navi.outerHeight(true) - $(window).height(), 0);
          // ウィンドウに固定レイヤーが収まる
          if($(window).height() > navi.outerHeight(true)) {
            // スクロール上限
            main_scroll = main_top + main.outerHeight(true) - $(window).height() - (navi.outerHeight(true) - $(window).height());
            // 開始位置：ナビのTOP
            fixed_start = navi.offset().top - parseInt(navi.css('margin-top'));
            // 終了時のTOP基準値
            fixpx_end_top = main_scroll;
          }
          // ウィンドウに固定レイヤーが収まらない
          else {
            // スクロール上限
            main_scroll = main_top + main.outerHeight(true) - $(window).height();
            // 開始位置：ナビのBOTTOMがウィンドウに表示されたら
            fixed_start = (navi.offset().top + navi.outerHeight(true)) - $(window).height() - parseInt(navi.css('margin-top'));
            // 終了時のTOP基準値
            fixpx_end_top = main_scroll - (navi.outerHeight(true) - $(window).height());
          }
          $(window).off('scroll', _onScroll).on('scroll', _onScroll);
        } else {
          $(window).off('scroll', _onScroll);
        }
        $(window).trigger('scroll');
      }
    };

    function _onScroll() {
      var ws = $(window).scrollTop();
      if(ws > main_scroll) {
        // 固定する上限
        navi.css({
          position : 'fixed',
          top : (fixpx_end_top - ws) + 'px',
        });
      } else if(ws > fixed_start) {
        // 固定中間
        navi.css({
          position : 'fixed',
          top : -fixpx_top + 'px',
        });
      } else {
        //　固定開始まで
        navi.css({
          position : 'relative',
          top : 'auto',
        });
      }
    }
  })();

  $(window).on('load', function() {
    fixedSidebar.run();
  }).on('resize', function() {
    fixedSidebar.refresh();
  });
};

// マイページのプロフィール画像のマウス処理 
$('#image').mouseover(function() {
  $('#setting-image').show();
});
$('#image').mouseout(function() {
  $('#setting-image').hide();
});

// プロフィール編集ページのリストの背景色変更
$('.setting li').mouseover(function(){
  if(!$(this).hasClass("selected")){
    $(this).addClass("mouseovered");
  }
});
$('.setting li').mouseout(function() {
  $(this).removeClass("mouseovered");
});

/* ========================================
  プロフィールの生年月日フォームに値入れる
======================================== */

$(function(){
    var birthday = $('#birthday').val();
    if(birthday == '' || !birthday){ 
        birthday = '1980-01-01'; // 誕生日が入っていない場合はデフォルト値をセット
    }
    // 基本的にシステムでセットされるけど値チェック
    if(birthday.length != 10 || !birthday.match(/^\d{4}-\d{2}-\d{2}/)){
        alert('生年月日が不正です。');
    }else{
        var date = new Date();
        var nowyear = date.getFullYear();
        var startyear = 1900;
        var endyear = nowyear;
        setDate(startyear, endyear, birthday); // 日付のセット
    }

    /***************************
     * trigger
     ***************************/
    $('#year','#month').change(function(){
        setDay();
    });
    $('#submitBtn').click(function(e){
        e.preventDefault();
        var year = $('#year').val();
        var month = $('#month').val();
        var day = $('#day').val();
        var birthday = year + '-' + month + '-' + day;
        if(birthday.length != 10 || !birthday.match(/^\d{4}-\d{2}-\d{2}/)){
            alert('生年月日が不正です。');
            return;
        }
        $('#birthday').val(birthday);
        $('#birthdayForm').submit();
    });
});

function setDay(){
    var year    = $('#year').val();
    var month   = $('#month').val();
    var day     = $('#day').val();
    var lastday = monthday(year, month);
    console.log("aa");
    var option = '';
    for (var i = 1; i <= lastday; i++) {
        if (i == day){
            option += '<option value="' + zeroPadding(i,2) + '" selected="selected">' + zeroPadding(i,2) + '</option>\n';
        }else{
            option += '<option value="' + zeroPadding(i,2) + '">' + zeroPadding(i,2) + '</option>\n';
        }
    }
    $('#day').html(option);
}

function monthday(year,month){
    var lastday = new Array('', 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    if ((year % 4 == 0 && year % 100 != 0) || year % 400 == 0){
        lastday[2] = 29;
    }
    return lastday[parseInt(month)];
}

function zeroPadding(num,length){
    return ('0000000000' + num).slice(-length);
}

function setDate(startyear, endyear, date){
    var dateArray = date.split('-');
    var selectedYear = dateArray[0];
    var selectedMonth = dateArray[1];
    var selectedDay = dateArray[2];
    var date = new Date();
    var nowyear = date.getFullYear();
    var yearOptions = '';
    var monthOptions = '';
    var dayOptions = '';
    if(!startyear || startyear == ""){
        startyear = nowyear - 30;
    }
    if(!endyear || endyear == ""){
        endyear = nowyear;
    }
    if(startyear > endyear){
        return;
    }
    for (var i = startyear; i <= endyear; i++){
        if(i == parseInt(selectedYear)){ // 0でのパディングを数値に変換
            yearOptions += '<option value="' + i + '" selected="selected">' + i + '</option>';
        }else{
            yearOptions += '<option value="' + i +'">' + i + '</option>';
        }
    }
    for (var j=1; j <= 12; j++){
        if(j == parseInt(selectedMonth)){ // 0でのパディングを数値に変換
            monthOptions += '<option value="' + zeroPadding(j,2) + '" selected="selected">' + zeroPadding(j,2) + '</option>';
        }else{
            monthOptions += '<option value="' + zeroPadding(j,2) +'">' + zeroPadding(j,2) + '</option>';
        }
    }
    var lastday = monthday(selectedYear, selectedMonth);
    for (var k = 1; k <= lastday; k++){
        if(k == parseInt(selectedDay)){ // 0でのパディングを数値に変換
            dayOptions += '<option value="' + zeroPadding(k,2) + '" selected="selected">' + zeroPadding(k,2) + '</option>';
        }else{
            dayOptions += '<option value="' + zeroPadding(k,2) +'">' + zeroPadding(k,2) + '</option>';
        }
    }
    $('#year').html(yearOptions);
    $('#month').html(monthOptions);
    $('#day').html(dayOptions);
}
