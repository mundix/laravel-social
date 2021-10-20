import $ from 'jquery';
import Quill from 'quill';
import Litepicker from 'litepicker'

(function() {

  $('[data-open]').on('click', function(e) {
    e.preventDefault();
  });

  $('.js-upload-video').on('click', function(e) {
    e.preventDefault()
    $('#video-upload-input').trigger('click')
  })

  $('.js-toggle-password-input').on('click', function(e) {
    e.preventDefault()
    if ($(this).prev().attr('data-visibility') === 'hidden') {
      $(this)
          .addClass('--password-visible')
          .prev()
          .attr('data-visibility', 'visible')
          .attr('type', 'text')
    } else {
      $(this)
          .removeClass('--password-visible')
          .prev()
          .attr('data-visibility', 'hidden')
          .attr('type', 'password')
    }

    return false
  })
})();