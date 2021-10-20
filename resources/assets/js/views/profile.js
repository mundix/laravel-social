import Swiper, { Navigation, Pagination } from 'swiper';
import SimpleLightbox from "../simple-lightbox.modules";
const $ = require('jquery');
require('select2');

Swiper.use([Navigation, Pagination]);

(function() {
  if (document.querySelector('.Profile-gallery')) {
    new SimpleLightbox('.Profile-gallery a', { /* options */ });
  }

  // if (document.querySelector('.js-read-more')) {
  // }
  
  new Swiper('.swiper-container-photos', {
    slidesPerView: 2,
    spaceBetween: 16,
    loop: ($('.swiper-container-photos .swiper-slide').length > 1) ? true : false,
    navigation: {
      nextEl: '.swiper-button-next-photos',
      prevEl: '.swiper-button-prev-photos',
    },
    pagination: {
      el: '.swiper-pagination-photos',
      clickable: true,
    },
  });

  new Swiper('.swiper-container-activity', {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: ($('.swiper-container-activity .swiper-slide').length > 1) ? true : false,
    navigation: {
      nextEl: '.swiper-button-next-activity',
      prevEl: '.swiper-button-prev-activity',
    },
    pagination: {
      el: '.swiper-pagination-activity',
      clickable: true,
    },
  });

  new Swiper('.swiper-container-testimonials', {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: ($('.swiper-container-testimonials .swiper-slide').length > 1) ? true : false,
    navigation: {
      nextEl: '.swiper-button-next-testimonials',
      prevEl: '.swiper-button-prev-testimonials',
    },
    pagination: {
      el: '.swiper-pagination-testimonials',
      clickable: true,
    },
  });

  new Swiper('.swiper-container-favorites', {
    navigation: {
      nextEl: '.swiper-button-next-favorites',
      prevEl: '.swiper-button-prev-favorites',
    },
    pagination: {
      el: '.swiper-pagination-favorites',
      clickable: true,
    },
    breakpoints: {
      640: {
        slidesPerView: 3,
        slidesPerColumn: 3,
        spaceBetween: 20,
      },
      800: {
        slidesPerView: 4,
        slidesPerColumn: 2,
        spaceBetween: 20,
      }
    }
  });

  new Swiper('.swiper-container-events', {
    slidesPerView: 'auto',
    spaceBetween: 30,
    centeredSlides: true,
    initialSlide: 1,
    loop: ($('.swiper-container-events .swiper-slide').length > 1) ? true : false,
    pagination: {
      el: '.swiper-pagination-events',
      clickable: true,
    },
  });

  new Swiper('.swiper-container-testimonial', {
    slidesPerView: 'auto',
    spaceBetween: 30,
    centeredSlides: true,
    loop: ($('.swiper-container-testimonial .swiper-slide').length > 1) ? true : false,
    pagination: {
      el: '.swiper-pagination-testimonial',
      clickable: true,
    },
  });

  new Swiper('.swiper-container-featured-events', {
    slidesPerView: 'auto',
    spaceBetween: 30,
    centeredSlides: true,
    loop: ($('.swiper-container-featured-events .swiper-slide').length > 1) ? true : false,
    pagination: {
      el: '.swiper-pagination-featured-events',
      clickable: true,
    },
  });

  $('.js-select').select2();
  
  $(".js-select-tags").select2({
    tags: true,
    placeholder: {
      id: '-1',
      text: 'Add a new tag'
    }
  });
  
  $(".select2-search__field").attr('placeholder', 'Add a new tag');
})();