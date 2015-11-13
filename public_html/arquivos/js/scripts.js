$(function()
{
    $('#inner-header > ul.sf-menu > li > a').each(function()
    {
        if ( window.location.href == $(this).attr('href') )
        {
            $(this).addClass('active');
        }
    });

    // Força a exibição da setas na barra lateral
    $('#sidebar > ul > li > div > a.next').show();
    $('#sidebar > ul > li > div > a.prev').show();

    var galleryThumbs = new Swiper('.gallery-thumbs', {
        nextButton: '.gallery-thumbs .swiper-button-next',
        prevButton: '.gallery-thumbs .swiper-button-prev',
        lazyLoading: true,//preloadImages
        centeredSlides: true,
        spaceBetween: 10,
        slidesPerView: 4,
        loop:true,
        loopedSlides: 4, //looped slides should be the same
        slideToClickedSlide: true
    });
    var galleryTop = new Swiper('.gallery-top', {
        lazyLoading: true,//preloadImages
        nextButton: '.gallery-top .swiper-button-next',
        prevButton: '.gallery-top .swiper-button-prev',
        pagination: '.gallery-top .swiper-pagination',
        spaceBetween: 10,
        loop:true,
        loopedSlides: 4, //looped slides should be the same  
    });
    galleryTop.params.control = galleryThumbs;
    galleryThumbs.params.control = galleryTop;
});