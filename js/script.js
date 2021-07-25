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
