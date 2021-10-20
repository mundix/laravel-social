import $ from 'jquery';

(function() {
  $('.js-menu-button').on('click', function() {
    $('.js-offcanvas').addClass('is-open');
  })

  $('.js-close-button').on('click', function() {
    $('.js-offcanvas').removeClass('is-open');
  })

  $('.js-profile-image').on('click', function() {
    $('.profile-menu').toggleClass('show');
  })

  $(document).on('click', 'body', function(e) {
    if(!$(e.target).hasClass("js-profile-image")) {
      $('.profile-menu').removeClass('show')
    }
  })
})();