$(function() {
    "use strict";

    // one page navigation
    $('a[href*=#]:not([href=#])').click(function() {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top
                }, 1500);
                return false;
            }
        }
    });
    //image slider 
    $('.fadein img:gt(0)').hide();
    setInterval(function() {
            $('.fadein :first-child').fadeOut(2000)
                .next('img').fadeIn()
                .end().appendTo('.fadein');
        },
        6000);
    //lightbox
    $('.lightbox').lightGallery();
    //justify gallery
    $('#mygallery').justifiedGallery({
        rowHeight: 130,
        lastRow: 'justify',
        margins: 10
    });
    //responsive menu
    $('#menu').slicknav({
        label: ''
    });
  
    //create the slider
    $('.cd-testimonials-wrapper').flexslider({
        selector: ".cd-testimonials > li",
        animation: "slide",
        controlNav: false,
        slideshow: false,
        smoothHeight: true,
        start: function() {
            $('.cd-testimonials').children('li').css({
                'opacity': 1,
                'position': 'relative'
            });
        }
    });

    //open the testimonials modal page
    $('.cd-see-all').on('click', function() {
        $('.cd-testimonials-all').addClass('is-visible');
    });

    //close the testimonials modal page
    $('.cd-testimonials-all .close-btn').on('click', function() {
        $('.cd-testimonials-all').removeClass('is-visible');
    });
    $(document).keyup(function(event) {
        //check if user has pressed 'Esc'
        if (event.which == '27') {
            $('.cd-testimonials-all').removeClass('is-visible');
        }
    });


})();
