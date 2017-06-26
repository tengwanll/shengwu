$(function(){


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
                    zdalert('系统提示',errmsg);
                });
            }
        });
    }else{
        ajaxAction("put",'/api/order/manage',info,true,function(data,textStatus){
            $(obj).parent().parent().find('#status').html(status);
        },function(errno,errmsg){
            zdalert('系统提示',errmsg);
        });
    }
}
function orderList(role,object){
    var info={rows:20,page:1};
    if(object != undefined){
        info=$.extend(info,object);
    }
    ajaxAction("get",'/api/order'+ passParam(info),"",false,function(data,textStatus){
        var count=Math.ceil(data.total/20);
        var htmlTab="";
        if(!count){
            $('tbody').html(htmlTab);
            return;
        }
        $.jqPaginator('#pagination', {
            totalPages: count,
            visiblePages: 6,
            currentPage: 1,
            activeClass: 'on',
            prev: '<span class="prev_page">上一页</span>',
            next: '<span class="next_page">下一页</span>',
            page: '<li>{{page}}</li>',
            onPageChange: function (num, type) {
                info.page=num;
                ajaxAction("get",'/api/order'+ passParam(info),"",true,function(data,textStatus){
                    htmlTab = '';
                    $.each(data.list,function(index,value){
                        if(value.status=='-1'){
                            value.status='未通过';
                        }else if(value.status=='0'){
                            value.status='无效订单';
                        }else if(value.status=='1'){
                            value.status='待审核';
                        }else if(value.status=='2'){
                            value.status='订购中';
                        }else if(value.status=='3'){
                            value.status='已到货';
                        }else if(value.status=='4'){
                            value.status='已反馈';
                        }
                        if(role>1){
                            htmlTab+='<tr><td>'+value.number+'</td><td>'+value.createTime+'</td><td>'+value.username+'</td><td>'+value.price+'</td><td id="status">'+value.status+'</td><td><a href="/adm/order/'+value.id+'" class="infoColor">查看详情</a><select name="" id="changeStatus" onchange="changeStatus(this,'+value.id+')"><option value="1">初始</option><option value="2">通过</option><option value="3">到货</option><option value="-1">驳回</option></select></td>';
                        }else{
                            htmlTab+='<tr><td>'+value.number+'</td><td>'+value.createTime+'</td><td>'+value.username+'</td><td>'+value.price+'</td><td>'+value.status+'</td><td><a href="/adm/order/'+value.id+'" class="infoColor">查看详情</a></td>';
                        }
                    });
                    $('tbody').html(htmlTab);
                },function(errno,errmsg){
                    zdalert('系统提示',errmsg);
                });

            }
        });


    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });

}




