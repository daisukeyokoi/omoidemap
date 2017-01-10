$.ajaxSetup({headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});

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
        navi = $('.side');       //固定するレイヤー
        main = $('.timeline');  //メインのレイヤー
        this.refresh();
      },
      // 基準になる数値の計算
      refresh : function() {
        navi.css({
          position : 'relative',
          top : 'auto',
          width : $('.side').width()
        });
        // メインコンテンツとナビの上限
        var navi_top = navi.offset().top - parseInt(navi.css('margin-top'));
        var main_top = main.offset().top - parseInt(main.css('margin-top'));
        if(navi_top + navi.outerHeight(true) < main_top + main.outerHeight(true)) {
          fixpx_top = Math.max(navi.outerHeight(true) - $(window).height(), 0);
          if($(window).height() > navi.outerHeight(true)) {
            main_scroll = main_top + main.outerHeight(true) - $(window).height() - (navi.outerHeight(true) - $(window).height());
            fixed_start = navi.offset().top - parseInt(navi.css('margin-top'));
            fixpx_end_top = main_scroll;
          }
          else {
            main_scroll = main_top + main.outerHeight(true) - $(window).height();
            fixed_start = (navi.offset().top + navi.outerHeight(true)) - $(window).height() - parseInt(navi.css('margin-top'));
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
      var width_timeline = $('.timeline').width();
      var width_outside_margin = $(window).width() - $('.main-content').width();
      var width_inside_margin  = $('.main-content').width() - width_timeline - $('.side').width();
      var width = width_timeline + width_outside_margin/2 + width_inside_margin
      if(ws > main_scroll) {
        navi.css({
          position : 'fixed',
          top : (fixpx_end_top - ws) + 'px',
          left : width
        });
      } else if(ws > fixed_start) {
        navi.css({
          position : 'fixed',
          top : -fixpx_top + 'px',
          left : width
        });
      } else {
        navi.css({
          position : 'relative',
          top : 'auto',
          left : '0'
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

// 投稿削除時のモーダル
$('.post_delete_btn').on('click', function(){
  confirm_dialog($(this), '削除してもよろしいですか？');
});

// $('#file').change(function(e){
//   url = "http://" + location.host + "/mypage/ajax/updateprofile";
//   var file = e.target.files[0];
//   var img = new Image();
//   var reader = new FileReader();
//   reader.onload = function(event){
//     img.onload = function(){
//       console.log("a");
//       var data = {data:img.src.split(',')[1]};
//       console.log(data);
//       $.ajax({
//         type: "POST",
//         url: url,
//         data: reader.result,
//         success: function(res){
//           console.log(res.message);
//         }
//       });
//     }
//   }
//   reader.readAsDataURL(file);
// });
