jQuery(document).ready(function($) {
  $('.slider').each(function(i,e) {
    $(e).slick({
      slidesToShow: 1,
      arrows: true,
      autoplay: true,
      dots: false
    });
  });

});