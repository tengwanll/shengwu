$(function(){
    /*信息推送*/
    $('.pushCommit').click(function(){
        var info={};
        info.type=$('.pushMessageType').val();
        info.pattern=$('.pushMessagePattern').val();
        if($('.pushTitle').val()==''){
            alert('标题不可为空！');
        }else{
            info.title=$('.pushTitle').val();
        }

        if($('.pushContent').val()==''){
            alert('内容不可为空！');
        }else{
            info.content=$('.pushContent').val();
        }

        ajaxAction("post",'/api/message/push/user',info,true,function(data,textStatus){
            window.location.href='/adm/message/index';

        },function(errno,errmsg){
            alert(errmsg);
        });

    });
});