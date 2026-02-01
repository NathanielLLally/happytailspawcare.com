document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.sltk_swiper').forEach(slider => {
    const speed = parseInt(slider.dataset.speed) || 1000;
    const slidesPerView = parseInt(slider.dataset.slidesPerView) || 3;
    const noStop = slider.dataset.noStop || 'no';

    const swiper = new Swiper(slider, {
      direction: 'horizontal',
      loop: true,
      slidesPerView: slidesPerView,
      speed: speed,
      centeredSlides: true,
      autoplay: { delay: 0, disableOnInteraction: true },
      scrollbar: { el: '.swiper-scrollbar', loop: false, slidesPerView: 'auto', disableOnInteraction: true }
    });

    slider.addEventListener('mouseenter', () => {
      if (noStop === 'no') swiper.autoplay.start();
      else swiper.autoplay.stop();
    });
    slider.addEventListener('mouseleave', () => { swiper.autoplay.start(); });
  });
});

