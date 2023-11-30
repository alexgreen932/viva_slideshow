(function($) {
  $(document).ready(function() {
    // alert(42);
    //todo
    //$('.oss-slideshow').isMobile();

  }); //end ready
  $.fn.isMobile = function( options ){
    //todo
    var st = $.extend( {
      'location'         : 'top',
      'background-color' : 'blue'
    }, options);
    if (window.matchMedia("(max-width: 639px)").matches) {
      $(this).addClass('v-is-mobile');
      var h = $(window).height();
      $('.oss-slideshow, .uk-slideshow-items, .os-slide-item').height(h);
    } 
    
  }
})(jQuery); //end jquery