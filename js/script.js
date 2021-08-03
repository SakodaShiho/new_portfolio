// Skillbar見える範囲に来たら動く

$(function () {
  $(window).scroll(function () {
    $('.skillbar-bar:not(.fire)').each(function () {
      var position = $(this).offset().top;
      var scroll = $(window).scrollTop();
      var windowHeight = $(window).height();
      var element = $(this);

      if (scroll > position - windowHeight) {
        $(element).addClass('fire');
        $('.skillbar').skillBars({
          from: 0,
          speed: 4000,
          interval: 100,
          decimals: 0,
        });
      }
    });
  });
});

// scrollでnav出現

var _window = $(window),
  _header = $('.site-header'),
  headerChange = $('.header-change'),
  heroBottom;

_window.on('scroll', function () {
  heroBottom = $('.hero').height();
  if (_window.scrollTop() > heroBottom) {
    headerChange.addClass('show');
  } else {
    headerChange.removeClass('show');
  }
});
_window.trigger('scroll');

// ページ内スクロール　ヘッダーの高さ分ずらす

$(function () {
  var headerHight = 100;
  $('a[href^=#]').click(function () {
    var href = $(this).attr('href');
    var target = $(href == '#' || href == '' ? 'html' : href);
    var position = target.offset().top - headerHight;
    $('html, body').animate({ scrollTop: position }, 0, 'swing');
    return false;
  });
});

// ハンバーガーメニュー

$(
  (function () {
    $('.menu-btn').on('click', function () {
      $('.menu').toggleClass('is-active');
      $('.nav_sp i:nth-child(2)').toggleClass('hidden');
      $('.nav_sp i:nth-child(1)').toggleClass('hidden');
    });
  })()
);

$(
  (function () {
    $('.menu__item').on('click', function () {
      $('.menu').removeClass('is-active');
    });
  })()
);

// モーダル

$(function () {
  $('.js-open').click(function () {
    $('body').addClass('no_scroll');

    var id = $(this).data('id');
    $('#overlay, .modal-window[data-id="modal' + id + '"]').fadeIn();
  });

  $('.js-close , #overlay').click(function () {
    $('body').removeClass('no_scroll');

    $('#overlay, .modal-window').fadeOut();
  });
});
