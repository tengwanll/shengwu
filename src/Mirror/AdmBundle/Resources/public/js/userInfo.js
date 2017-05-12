$(function(){

});
function userInfo(userId){

    ajaxAction("get","/api/user/"+userId,"",false,function(data,textStatus){
        var infoHtml='<dl><dt>ID：</dt><dd>'+ data.userId +'</dd><dt>头像：</dt><dd><a href="javascript:void(0)"><img src="'+data.image+'" /></a></dd><dt>手机号：</dt><dd>'+data.mobile+'</dd><dt>用户名：</dt><dd>'+data.username+'</dd><dt>注册时间：</dt><dd>'+data.createTime+'</dd></dl>';

        $('.infoUserCon').html(infoHtml);
    },function(errno,errmsg){
        alert(errmsg);
    });


}
//////////////////////////////////////////////////////////

//用户订单列表
function orderList(userId){
    // 债权列表
    var info={};
    info.rows=20;
    info.page=1;
    info.userId=userId;
    ajaxAction("get",'/api/user/order'+ passParam(info),"",false,function(data,textStatus){
        var count=Math.ceil(data.total/20);
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
                        if(value.status==-1){
                            value.status="未通过";
                        }else if(value.status==0){
                            value.status="待审核";
                        }else if(value.status==1){
                            value.status="代发货";
                        }else if(value.status==3){
                            value.status="已发货";
                        }else if(value.status==4){
                            value.status="已完成";
                        }
                        html='';
                        html+=' <tr><td>'+value.number+'</td><td>'+value.createTime+'</td><td>'+value.price+'</td><td>'+value.status+'</td><td><a href="/adm/order/'+value.id+'" class="infoColor">查看详情</a></td></tr>';


                    });
                    $('tbody').html(html);
                },function(errno,errmsg){
                    alert(errmsg);
                });

            }
        });


    },function(errno,errmsg){
        alert(errmsg);
    });



}