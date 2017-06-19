$(function(){

    orderList();
    $('#search').click(function(){
        var info={};
        info.number=$('#number').val();
        info.status=$('#status').val();
        info.username=$('#username').val();
        info.beginTime=$('#beginTime').val();
        info.endTime=$('#endTime').val();
        orderList(info);
        return false;
    });

    $('button[type=reset]').click(function(){
        orderList();
    });
});
function changeStatus(obj,orderId){
    var info={};
    info.orderId=orderId;
    info.status= $(obj).val();
    var status=1;
    if(info.status=='-1'){
        status='未通过';
    }else if(info.status=='0'){
        status='无效订单';
    }else if(info.status=='1'){
        status='待审核';
    }else if(info.status=='2'){
        status='待发货';
    }else if(info.status=='3'){
        status='已发货';
    }else if(info.status=='4'){
        status='已完成';
    }
    if(info.status==-1){
        zdcomment('备注信息','输入备注信息更方便您之后查看',function(r,message){
            if(r){
                info.message=message;
                ajaxAction("put",'/api/order/manage',info,false,function(data,textStatus){

                    $(obj).parent().parent().find('#status').html(status);

                },function(errno,errmsg){
                    alert(errmsg);
                });
            }
        });
    }else{
        ajaxAction("put",'/api/order/manage',info,true,function(data,textStatus){
            $(obj).parent().parent().find('#status').html(status);
        },function(errno,errmsg){
            alert(errmsg);
        });
    }
}





