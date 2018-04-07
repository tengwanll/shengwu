$(function () {

    $('.index-nav-list ul li,.slide-down').on('mouseover',function () {
        $('.slide-down').stop().slideDown();
    });
    $('.index-nav-list ul li,.slide-down').on('mouseleave',function () {
        $('.slide-down').stop().slideUp();
    });

    $('.tabContent .product').show();
    $('.tabContent .question').hide();
    $('.tabContent .case').hide();
    $('.tabContent .result').hide();

    $('.tabNav ul li').on('click',function () {
        $(this).addClass('current').siblings('li').removeClass('current');
        switch ($(this).index()){
            case 0:
                $('.tabContent .product').show();
                $('.tabContent .question').hide();
                $('.tabContent .case').hide();
                $('.tabContent .result').hide();
                return;
            case 1:
                $('.tabContent .product').hide();
                $('.tabContent .question').show();
                $('.tabContent .case').hide();
                $('.tabContent .result').hide();
                return;
            case 2:
                $('.tabContent .product').hide();
                $('.tabContent .question').hide();
                $('.tabContent .case').show();
                $('.tabContent .result').hide();
                return;
            case 3:
                $('.tabContent .product').hide();
                $('.tabContent .question').hide();
                $('.tabContent .case').hide();
                $('.tabContent .result').show();
                return;
            default:
                return;
        }
    })



});