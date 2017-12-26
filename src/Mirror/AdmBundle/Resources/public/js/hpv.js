$(function(){



});

function goodsList(nowPage,object){
    var info={rows:10,page:1};
    if(object&&object != undefined){
        info=$.extend(info,object);
    }
    ajaxAction("get",'/api/hpv'+ passParam(info),"",false,function(data,textStatus){
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
            currentPage: nowPage,
            activeClass: 'on',
            prev: '<span class="prev_page">上一页</span>',
            next: '<span class="next_page">下一页</span>',
            page: '<li>{{page}}</li>',
            onPageChange: function (num, type) {
                    info.page=num;
                ajaxAction("get",'/api/hpv'+ passParam(info),"",true,function(data,textStatus){
                    htmlTab = '';
                    $.each(data.list,function(index,value){
                        var status;
                        if(value.status==2){
                            status='检查中';
                        }else{
                            status='已完成';
                        }
                        htmlTab+='<tr><td>'+value.order_no+'</td><td>'+value.name+'</td><td>'+value.telephone+'</td><td>'+value.price+'</td><td>'+value.number+'</td><td>'+formatter(value.pay_time*1000,'YYYY MM DD')+'</td><td>'+value.user_name+'</td><td>'+value.user_age+'</td><td>'+data.is_married+'</td><td>'+status+'</td><td><a href="/adm/hpv/'+value.id+'" class="infoColor">查看</a> <label for="file'+value.id+'" style="color: #ff7800;cursor: pointer">导入报表</label><form action="" name="uploadForm"><input type="file" style="display: none" name="file" id="file'+value.id+'" onchange="changeFile(this,'+info.page+')"><input type="hidden" value="'+value.id+'" name="orderId"></form></td>';
                        $('tbody').html(htmlTab);
                    });
                },function(errno,errmsg){
                    zdalert('系统提示',errmsg);
                });

            }
        });


    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });
}

function changeFile(obj,page){
    imgUpload($(obj).parent()[0],'/api/file/HImport','post',function(){},function(data,textStatus){
        location.href='/adm/hpv?page='+page;
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });
}

function hpvInfo(orderId){
    ajaxAction("get","/api/hpv/"+orderId,"",true,function(data,textStatus){
        var status;
        if(value.status==2){
            status='检查中';
        }else{
            status='已完成';
        }
        var infoHtml='<dl><dt>商品名称：</dt><dd>'+ data.name +'</dd><dt>用户账号：</dt><dd>'+data.telephone+'</dd><dt>价格：</dt><dd>'+data.price+'</dd><dt>数量：</dt><dd>'+data.number+'</dd><dt>支付时间：</dt><dd>'+formatter(value.pay_time*1000,'YYYY MM DD')+'</dd><dt>用户名称：</dt><dd>'+data.user_name+'</dd><dt>用户年龄：</dt><dd>'+data.user_age+'</dd><dt>婚姻：</dt><dd>'+is_married+'</dd><dt>订单状态：</dt><dd>'+status+'</dd><dt>订单地址：</dt><dd>'+data.address+'</dd><dt>报表：</dt><dd><a href="'+data.report+'">下载</a></dd></dl>';
        $('.infoUserCon').html(infoHtml);
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });
}

function addToCar(obj,id) {
    var status=$(obj).parent().find('#changeStatus').val();
    if(status==0){
        zdalert('系统提示','该商品已下架,无法加入购物车');
        return;
    }
    var info={};
    info.id=id;
    ajaxAction("post","/api/goods/car",info,true,function(data,textStatus){
        zdalert('系统提示','加入购物车成功');
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });
}

function changeStatus(obj,id) {
    var info={};
    info.status=$(obj).val();
    info.goodsId=id;
    ajaxAction("put","/api/goods/manage",info,true,function(data,textStatus){

    },function(errno,errmsg){
        $(obj).val(0);
        zdalert('系统提示',errmsg);
    });
}





