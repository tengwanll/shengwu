$(function(){


    ////////////////////////////////////////////
});
function userList(role){
    // 用户列表
    var info={};
    info.rows=10;
    info.page=1;
    info.username=$('#username').val();
    info.mobile=$('#mobile').val();
    ajaxAction("get",'/api/user'+ passParam(info),"",false,function(data,textStatus){
        $('#countNumber').html(data.total);
        var count=Math.ceil(data.total/10);
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
                ajaxAction("get",'/api/user'+ passParam(info),"",false,function(data,textStatus){
                    var thead='<tr class="tableTit"><td>ID</td><td>用户名</td><td>手机号</td><td>上次登陆时间</td><td>注册时间</td><td colspan="3" width="18%">操作</td></tr>';
                    htmlTab = '';
                    $.each(data.list,function(index,value){
                        if(role==3){
                            htmlTab+='<tr><td class="id">'+value.id+'</td><td>'+value.username+'</td><td>'+value.mobile+'</td><td>'+value.lastTime+'</td><td>'+value.createTime+'</td><td><a href="/adm/user/'+value.id+'" class="infoColor">查看详情</a></td><td><a href="javascript:void(0)" class="deleteColor stop">禁用</a></td> <td><a href="javascript:void(0)" class="resetpassword infoColor">重置密码</a></td></tr>';
                        }else{
                            htmlTab+='<tr><td class="id">'+value.id+'</td><td>'+value.username+'</td><td>'+value.mobile+'</td><td>'+value.lastTime+'</td><td>'+value.createTime+'</td><td><a href="/adm/user/'+value.id+'" class="infoColor">查看详情</a></td></tr>';
                        }
                    });

                    $('thead').html(thead);
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






