const swiper = new Swiper('.js-testimonal-slider', {
    grabCursor: true,
    spaceBetween: 30,
    pagination: {
      el: '.js-testimonals-pagination',
      clickable: true
    },
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    loop: true, // Adiciona um loop infinito
    breakpoints: {
      767: {
        slidesPerView: 2
      }
    }
});
