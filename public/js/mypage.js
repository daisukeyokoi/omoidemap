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
      navi = $('.sidebar');
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
