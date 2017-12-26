$(function () {

    $('.index-nav-list ul li,.slide-down').on('mouseover',function () {
        $('.slide-down').stop().slideDown();
    });
    $('.index-nav-list ul li,.slide-down').on('mouseleave',function () {
        $('.slide-down').stop().slideUp();
    });


});