$(function () {

    $('.index-nav-list ul li,.slide-down').on('mouseover',function () {
        $('.slide-down').stop().slideDown();
    });
    $('.index-nav-list ul li,.slide-down').on('mouseleave',function () {
        $('.slide-down').stop().slideUp();
    });


    ajaxAction("get",'/api/main/company','',false,function(data,textStatus){
        console.log(data.list[0]);
        $('.swiperBox1 .swiper-wrapper').html(bannerSwiperHtml(data.list[0]));
        $('.swiperBox2 .swiper-wrapper').html(bannerSwiperHtml(data.list[1]));
        $('.swiperBox3 .swiper-wrapper').html(bannerSwiperHtml(data.list[2]));
        $('.swiperBox4 .swiper-wrapper').html(bannerSwiperHtml(data.list[3]));
        $('.swiperBox5 .swiper-wrapper').html(bannerSwiperHtml(data.list[4]));

        $('.swiperBox1 .leftButton span').text(1+'/'+data.list[0].banner.length);
        $('.swiperBox1 .rightButton span').text(1+'/'+data.list[0].banner.length);
        $('.swiperBox2 .leftButton span').text(1+'/'+data.list[1].banner.length);
        $('.swiperBox2 .rightButton span').text(1+'/'+data.list[1].banner.length);
        $('.swiperBox3 .leftButton span').text(1+'/'+data.list[2].banner.length);
        $('.swiperBox3 .rightButton span').text(1+'/'+data.list[2].banner.length);
        $('.swiperBox4 .leftButton span').text(1+'/'+data.list[3].banner.length);
        $('.swiperBox4 .rightButton span').text(1+'/'+data.list[3].banner.length);
        $('.swiperBox5 .leftButton span').text(1+'/'+data.list[4].banner.length);
        $('.swiperBox5 .rightButton span').text(1+'/'+data.list[4].banner.length);
        swiperClass('.swiperBox1');
        swiperClass('.swiperBox2');
        swiperClass('.swiperBox3');
        swiperClass('.swiperBox4');
        swiperClass('.swiperBox5');
    },function(errno,errmsg){
        console.log(errmsg);
    });





    var swiperBox1Count=0;
    var swiperBox2Count=0;
    var swiperBox3Count=0;
    var swiperBox4Count=0;
    var swiperBox5Count=0;

    $(window).scroll(function () {
        if(swiperBox1Count<=0){
            isShow($('.swiperBox1'));
            $('.swiperBox1 .swiper-wrapper .swiperLi .swiperLiImg').addClass('animate');
        }
        if(swiperBox2Count<=0){
            isShow($('.swiperBox2'));
            $('.swiperBox2 .swiper-wrapper .swiperLi .swiperLiImg').addClass('animate');
        }
        if(swiperBox3Count<=0){
            isShow($('.swiperBox3'));
            $('.swiperBox3 .swiper-wrapper .swiperLi .swiperLiImg').addClass('animate');
        }
        if(swiperBox4Count<=0){
            isShow($('.swiperBox4'));
            $('.swiperBox4 .swiper-wrapper .swiperLi .swiperLiImg').addClass('animate');
        }
        if(swiperBox5Count<=0){
            isShow($('.swiperBox5'));
            $('.swiperBox5 .swiper-wrapper .swiperLi .swiperLiImg').addClass('animate');
        }

    });



    function isShow(item) {
        var a = item.offset().top;
        console.log(a);
        console.log($(window).scrollTop());
        console.log($(window).height());
        if (a >= $(window).scrollTop() && a < ($(window).scrollTop() + $(window).height())) {
                swiperBox1Count++;
                swiperBox2Count++;
                swiperBox3Count++;
                swiperBox4Count++;
                swiperBox5Count++;

        }
    }


    function swiperClass(item) {
        var mySwiper = new Swiper (item, {
            direction: 'horizontal',
            loop: false,
            // 如果需要前进后退按钮
            navigation: {
                nextEl: item+' .rightButton',
                prevEl: item+' .leftButton'
            },
            on:{
                slideChangeTransitionEnd: function(){
                    console.log(this.activeIndex);//切换结束时，告诉我现在是第几个slide
                    console.log(item);
                    console.log(this.slides.length);
                    $(item+' .leftButton span').text((this.activeIndex+1)+"/"+this.slides.length);
                    $(item+' .rightButton span').text((this.activeIndex+1)+"/"+this.slides.length);
                },
            }
        })
    }


    function bannerSwiperHtml(data) {
        var swiperBox1Html1 = '<div class="swiperLi swiper-slide">';
        $.each(data.banner,function (idx,el) {
            if(idx===0){
                // swiperBox1Html1=swiperBox1Html1+'<div class="swiperLiImg" style="background-image: url("'+el+'")"></div><div class="swiperCon"><ul><li class="weather"><span><b>'+data.temperature+'</b><br><small>'+data.nameAs+'  '+ data.date +'</small></span><span class="weatherIco"><img src="'+data.logo+'" alt=""></span></li><li>'+data.phone+'</li> <li>'+data.detail+'</li></ul></div><h3>'+data.name+'</h3></div>';
                swiperBox1Html1=swiperBox1Html1+'<div class="swiperLiImg" style="background-image: url('+el+')"></div><div class="swiperCon"><ul><li class="weather"><span><b>'+data.temperature+'</b><br><small>'+data.nameAs+'  '+ data.date +'</small></span><span class="weatherIco"><img src="'+data.logo+'" alt=""></span></li><li>'+data.phone+'</li> <li>'+data.detail+'</li></ul></div><h3>'+data.name+'</h3></div>';
            }else{
                // swiperBox1Html1=swiperBox1Html1+'<div class="swiperLi swiper-slide"><div class="swiperLiImg" style="background-image: url("'+el+'")"></div></div>';
                swiperBox1Html1=swiperBox1Html1+'<div class="swiperLi swiper-slide"><div class="swiperLiImg" style="background-image: url('+el+')"></div></div>';
            }
            console.log(el);
        });
        return swiperBox1Html1;
    }

});