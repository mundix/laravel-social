import Swiper, { Navigation, Pagination } from 'swiper';
const $ = require('jquery');

Swiper.use([Navigation, Pagination]);

(function() {
  new Swiper('.swiper-container-co-photos', {
    slidesPerView: 1,
    spaceBetween: 0,
    loop: ($('.swiper-container-co-photos .swiper-slide').length > 1) ? true : false,
    navigation: {
      nextEl: '.swiper-button-next-co-photos',
      prevEl: '.swiper-button-prev-co-photos',
    },
    pagination: {
      el: '.swiper-pagination-co-photos',
      clickable: true,
    },
  });
  
  new Swiper('.swiper-container-co-stories', {
    slidesPerView: 'auto',
    centeredSlides: true,
    initialSlide: 0,
    spaceBetween: 32,
    loop: ($('.swiper-container-co-stories .swiper-slide').length > 4) ? true : false,
    pagination: {
      el: '.swiper-pagination-co-stories',
      clickable: true,
    },
  });

  new Swiper('.swiper-container-co-says', {
    slidesPerView: 'auto',
    centeredSlides: true,
    spaceBetween: 32,
    loop: ($('.swiper-container-co-says .swiper-slide').length > 1) ? true : false,
    navigation: {
      nextEl: '.swiper-button-next-co-says',
      prevEl: '.swiper-button-prev-co-says',
    },
    pagination: {
      el: '.swiper-pagination-co-says',
      clickable: true,
    },
  });

  new Swiper('.swiper-container-co-events', {
    slidesPerView: 'auto',
    centeredSlides: true,
    initialSlide: 0,
    spaceBetween: 32,
    loop: ($('.swiper-container-co-events .swiper-slide').length > 1) ? true : false,
    pagination: {
      el: '.swiper-pagination-co-events',
      clickable: true,
    },
  });
  
  new Swiper('.swiper-container-co-employees', {
    slidesPerView: 'auto',
    centeredSlides: true,
    spaceBetween: 32,
    loop: ($('.swiper-container-co-employees .swiper-slide').length > 5) ? true : false,
    pagination: {
      el: '.swiper-pagination-co-employees',
      clickable: true,
    },
  });
})();