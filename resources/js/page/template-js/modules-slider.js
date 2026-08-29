"use strict";

$("#slider1,#slider2").owlCarousel({
  items: 1,
  nav: true,
  navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>']
});

$("#slider-rtl").owlCarousel({
  rtl: true,
  items: 1,
  dots: false,
  nav: true,
  navText: [
    '<i class="fa fa-chevron-right"></i>',
    '<i class="fa fa-chevron-left"></i>'
  ]
});
