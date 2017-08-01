$(function(){

});
function userInfo(userId){

    ajaxAction("get","/api/user/"+userId,"",false,function(data,textStatus){
        if(data.role==1){
            data.role='普通用户';
        }else{
            data.role='平台管理员';
        }
        var infoHtml='<dl><dt>ID：</dt><dd>'+ data.userId +'</dd><dt>用户角色：</dt><dd>'+ data.role +'</dd><dt>头像：</dt><dd><a href="javascript:void(0)"><img height="80px" width="80px" src="'+data.image+'" /></a></dd><dt>手机号：</dt><dd>'+data.mobile+'</dd><dt>用户名：</dt><dd>'+data.username+'</dd><dt>注册时间：</dt><dd>'+data.createTime+'</dd></dl>';

        $('.infoUserCon').html(infoHtml);
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });


}
//////////////////////////////////////////////////////////

//用户订单列表
function orderList(userId){
    var info={};
    info.rows=10;
    info.page=1;
    info.userId=userId;
    ajaxAction("get",'/api/user/order'+ passParam(info),"",false,function(data,textStatus){
        $('#countNumber').html(data.total);
        var count=Math.ceil(data.total/10);
        html="";
        if(!count){
            $('.tab_content').html(html);
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
                ajaxAction("get",'/api/user/order'+ passParam(info),"",true,function(data,textStatus){
                    var html = '';
                    $.each(data.list,function(index,value){
                        var deal=[];
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
                        html+=' <tr><td>'+value.number+'</td><td>'+value.createTime+'</td><td>'+value.price+'</td><td id="status">'+value.status+'</td><td><a href="/adm/order/'+value.id+'" class="infoColor">查看详情</a> <a href="javascript:void(0)" class="infoColor" onclick="replyOrder(this,'+value.id+')">订单反馈</a></td></tr>';
                    });
                    $('tbody').html(html);
                },function(errno,errmsg){
                    zdalert('系统提示',errmsg);
                });

            }
        });


    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });



}
function replyOrder(obj,orderId) {
    var info={};
    zdcomment('订单反馈','请输入反馈信息',function(r,message){
        if(r){
            info.orderId=orderId;
            info.message=message;
            info.status=4;
            ajaxAction("put",'/api/order/manage',info,false,function(data,textStatus){
                $(obj).parent().parent().find('#status').html('已反馈');
            },function(errno,errmsg){
                zdalert('系统提示',errmsg);
            });
        }
    });
}