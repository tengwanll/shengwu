$(function(){

    rewardInfo(rewardId());

    $('.rewardDelete').click(function(){
        $('.deletePopup').show();
        $('.popupConfirm').click(function(){
            $('.deletePopup').hide();
            var info={};
            info.rewardId=amendId();
            ajaxAction("DELETE",'/api/reward/back/delete',info,true,function(data,textStatus){
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

function rewardInfo(number){
    var htmlInfo='';
    var htmlPhoto='';
    ajaxAction("get",'/api/reward/back/'+number,"",true,function(data,textStatus){

        if(data.status==-3){
            data.status='被禁止';
        }else if(data.status==-2){
            data.status='被驳回';
        }else if(data.status==-1){
            data.status='被删除';
        }else if(data.status==0){
            data.status='审核中';
        }else if(data.status==1){
            data.status='悬赏中';
        }else if(data.status==2){
            data.status='合作成功';
        }

        if(data.type==0){
            data.type='找人';
        }else if(data.type==1){
            data.type='找资产';
        }


        htmlInfo+='<div class="infoUserTitle"><span class="creditCard">债权ID：'+data.rewardId+'</span><span class="waitSigned">'+data.status+'</span></div><div class="infoUserCon" ><dl><dt>债权编号：</dt><dd>'+data.code+'</dd><dt>悬赏类型：</dt><dd>'+data.type+'</dd><dt>悬赏金额：</dt><dd>'+data.money+'万元</dd><dt>悬赏地址：</dt><dd>'+data.province+data.city+'</dd><dt>悬赏时间：</dt><dd>'+data.time+'</dd><dt>悬赏名称(姓名)：</dt><dd>'+data.name+'</dd><dt>联系人姓名：</dt><dd>'+data.contactName+'</dd><dt>联系人手机号：</dt><dd>'+data.contactTelephone+'</dd><ul class="pengzheng"><dt>凭证：</dt><div class="row"><div class="col-md-10 col-md-offset-2 cardUser"><ul><!--<li></li><li></li><li></li>--></ul><div class="cls"></div></div></div></ul><ul class="creditDescription"><dt>描述：</dt><dd><p>'+data.description+'</p></dd></ul></dl><div class="cls"></div></div>';

        $('.infoUser').html(htmlInfo);

        $.each(data.evidencePhoto,function(index,el){
            htmlPhoto+='<li><img src="'+el+'" alt=""></li>';
        });

        $('.cardUser').html(htmlPhoto);

    },function(errno,errmsg){
        alert(errmsg);
    });

}