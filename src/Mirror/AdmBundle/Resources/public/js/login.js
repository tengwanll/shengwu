$(function(){

    $('.changeIp').hide();

    $('.loginSubmit').click(function(){
        if(!checkPhone($('.user-name').val(),'手机号不可为空！',true)){
            return false;
        }
        if(!checkPassword($('.user-pwd').val(),'登录密码不可为空！')){
            return false;
        }

        var info={};
        info.telephone=$('.user-name').val();
        info.password=$('.user-pwd').val();
        if($('.codeLoginInput').val()!=''){
            info.code=$('.codeLoginInput').val();
        }

        ajaxAction("post","/api/user/login",info,true,function(data,textStatus){
            window.location.href="/adm/user";
        },function(errno,errmsg) {
            alert(errmsg);
            if(errno==2012){
                $('.changeIp').show();
            }
        });

    });

    acquireCode($('.codeLogin'),$('.user-name'));


});