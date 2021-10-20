import $ from 'jquery';

(function() {
  $(document).on('click', '.js-more, .js-filter', function(e) {
    e.stopPropagation();
    if ($(this).find('.Filters').hasClass('show') || $(this).find('.More-tooltip').hasClass('show')) {
      $('.js-more, .js-filter').removeClass('selected')
      $('.More-tooltip').removeClass('show')
      $('.Filters').removeClass('show')
    } else {
      $('.js-more, .js-filter').removeClass('selected')
      $(this).addClass('selected')
      $('.More-tooltip').removeClass('show')
      $('.Filters').removeClass('show')
      $(this).find('.Filters').addClass('show')
      $(this).find('.More-tooltip').addClass('show')
    }
  })

  $('.Filters, .More-tooltip').on('click', 'a', function(e) {
    e.stopPropagation();
  });

  $(document).on('click', 'body', function(e) {
    if( $(e.target).closest("span, input").length == 0 ) {
      $('.js-more, .js-filter').removeClass('selected')
      $('.More-tooltip').removeClass('show')
      $('.Filters').removeClass('show')
    }
  })
})();