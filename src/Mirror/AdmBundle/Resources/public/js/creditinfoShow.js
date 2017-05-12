$(function(){

    creditinfo();


    function creditinfo(){
        var collectStatus=0;

        ajaxAction("get","/api/credit/back/"+creditId(),"",true,function(data,textStatus){
            //console.log(data.list[0].code);
            var htmlPic='<div class="container_image"><a href="javascript:void(0)" tip="0" class="i_btn prev_L"></a><a href="javascript:void(0)" tip="1" class="i_btn next_R"></a><ul class="slide_img"></li>';
            var deal='';
            $.each(data.dealPattern,function(index1,value){
                if(value.name=='诉讼'){
                    deal+='<li><span class="dealName">诉讼</span><p class="dealCon">报酬比例 '+value.ratio+'%（律师劳务费用，不考虑是否诉讼成功）</p></li>';
                }
                if(value.name=='催收'){
                    deal+='<li><span class="dealName">催收</span><p class="dealCon">报酬比例 '+value.ratio+'%（催收方抽成比例）</p></li>';
                }

                if(value.name=='转让'){
                    deal+='<li><span class="dealName">转让</span><p class="dealCon">报酬比例 '+value.ratio+'%（债权转让折扣）</p></li>';
                }

            });
            //console.log(deal);
            $('.disposal').append(deal);
            $.each(data.evidencePhoto,function(index,el){
                htmlPic+='<li><a href="javascript:void(0)"><img src="'+el+'" /></a></li>';
            });
            htmlPic+='</ul></div>';
            $('.slide_img').html(htmlPic);
            var newopt={
                w2:"200",//小图宽度
                h2:"150"//小图高度
            };
            //$(".container_image").i_slide(newopt);
            //$('.slide_img li').eq(0).addClass('on');

            $('#code').text(data.code);
            $('#ID').text('债权ID:'+data.creditId);
            if(data.status==0){
                data.status="待审核";
            }else if(data.status==1){
                data.status="处置中";
            }else if(data.status==2){
                data.status="已完成";
            }
            $('.status').text(data.status);
            if(data.type==0){
                $('#type').text('个人');
            }else if(data.type==1){
                $('#type').text('企业');
            }else if(data.type==2){
                $('#type').text('机构');
            }
            $('.creMoney').text(data.money);
            $('.creReturn').text(deal.ratio);
            if(data.isJudgment==0){
                $('.judgmentCon').text('未判决')
            }else if(data.isJudgment==1){
                $('.judgmentCon').text('已判决');
            }

            if(data.isLawsuit==0){
                $('.lawsuitCon').text('未诉讼');
            }else if(data.isLawsuit==1){
                $('.lawsuitCon').text('已诉讼');
            }
            if(data.isUrge==0){
                $('.urgeCon').text('未催收');
            }else if(data.isUrge==1){
                $('.urgeCon').text('已催收');
            }

//            $('.timeCon').text('债权时间：'+data.+'个月');
            $('.disposeCon').text(deal.name);

            if(data.isAssure==0){
                $('.assureCon').text('没有担保');
            }else if(data.isAssure==1){
                $('.assureCon').text('有担保');
            }

            $('#money').text(data.money);
            $('.borrowDate').text(data.borrowTime);
            $('.refundDate').text(data.refundTime);
            $('.debtorName').text(data.debtorName);
            $('.debtorTelephone').text(data.debtorTelephone);
            $('.debtorPlace').text(data.debtorProvince+data.debtorCountry);
            $('.debtorCity').text(data.debtorCity);
            $('.addrCon').text('债权地址：'+data.province+data.city);
            //$('.dealCon').append(deal);
            $('.creditorName').text(data.contactName);
            $('#place').text(data.province+data.city);
            $('.creditorTelephone').text(data.contactTelephone);
            if(data.isUrge==0){
                $('.urgeCon').text('否');
            }else if(data.isUrge==1){
                $('.urgeCon').text('是');
            }

            $('.description p').text(data.description);
            if(data.collectId){
                $('.collect').addClass('on');
                collectStatus=data.collectId;
            }else{
                $('.collect').removeClass('on');
                collectStatus=0;
            }
            //$('.send').attr('href',"/adm/info/send/"+data.creditId);
            $('.send').click(function(){
                window.location.href='/adm/info/send/'+data.creditId;
            });
        },function(errno,errmsg){
            alert(errmsg);
        });





        $('.collect').click(function(){
            if(collectStatus){
                var info={};
                info.collectId=collectStatus;
                ajaxAction("delete", "/api/collect",info,true,function(data,textStatus){
                    $('.collect').removeClass('on');
                    collectStatus=0;
                },function(errno,errmsg){
                    alert(errmsg);
                });
            }else{
                var info={};
                info.type=1;
                info.typeId="{{ creditId }}";
                ajaxAction("post", "/api/collect",info,true,function(data,textStatus){
                    $('.collect').addClass('on');
                    collectStatus=1;
                },function(errno,errmsg){
                    alert(errmsg);
                });
            }

        });

    }

    $('button[name="delete"]').click(function(){
        $('.deletePopup').show();
        $('.popupConfirm').click(function(){
            $('.deletePopup').hide();
            var info={};
            info.creditId=creditId();
            ajaxAction("DELETE",'/api/credit',info,true,function(data,textStatus){
                window.location.href="/adm/info/creditor";
            },function(errno,errmsg){
                alert(errmsg);
            });
        });
        $('.popupCancel').click(function(){
            $('.deletePopup').hide();
        });

    });

});