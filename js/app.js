const swiper = new Swiper(".js-testimonal-slider", {
  grabCursor: true,
  spaceBetween: 30,
  pagination: {
    el: ".js-testimonals-pagination",
    clickable: true,
  },
  autoplay: {
    delay: 10000,
    disableOnInteraction: false,
  },
  loop: true, 
  breakpoints: {
    767: {
      slidesPerView: 1,
    },
  },
});
