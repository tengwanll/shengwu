$(function () {

    $('.index-nav-list ul li,.slide-down').on('mouseover',function () {
        $('.slide-down').stop().slideDown();
    });
    $('.index-nav-list ul li,.slide-down').on('mouseleave',function () {
        $('.slide-down').stop().slideUp();
    });
    $(window).on('scroll',function () {
        if(($('.firstMain').offset().top)-$(window).scrollTop()<=80){
            $('.topNavLogo').css({
                position:'fixed',
                background:'#fff',

            });
            $('.index-nav-list ul li a').css({
                color:'#353535'
            });
        }else{
            $('.topNavLogo').css({
                position:'static',
                background:'none'
            });
            $('.index-nav-list ul li a').css({
                color:'#fff'
            });
        };
        $('.index-nav-list ul li .active').css({
            color:'#fff'
        });
        // console.log($(window).scrollTop());
    })

});