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
                        var status,isMarried;
                        if(value.status==2){
                            status='检查中';
                        }else{
                            status='已完成';
                        }
                        if(value.is_married==1){
                            isMarried='已婚';
                        }else if(value.is_married==2){
                            isMarried='未婚';
                        }else{
                            isMarried='未设置';
                        }
                        htmlTab+='<tr><td>'+value.order_no+'</td><td>'+value.name+'</td><td>'+value.telephone+'</td><td>'+value.price+'</td><td>'+value.number+'</td><td>'+formatter(value.pay_time*1000,'YYYY MM DD')+'</td><td>'+value.user_name+'</td><td>'+value.user_age+'</td><td>'+isMarried+'</td><td>'+status+'</td><td><a href="/adm/goods/'+value.id+'" class="infoColor">查看</a> <label for="file'+value.id+'" style="color: #ff7800;cursor: pointer">导入报表</label><form action="" name="uploadForm"><input type="file" style="display: none" name="file" id="file'+value.id+'" onchange="changeFile(this,'+info.page+')"><input type="hidden" value="'+value.id+'" name="orderId"></form></td>';
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

function goodsInfo(goodsId){
    ajaxAction("get","/api/goods/"+goodsId,"",false,function(data,textStatus){
        var infoHtml='<dl><dt>商品名称：</dt><dd>'+ data.name +'</dd><dt>商品分类：</dt><dd>'+data.sort+'</dd><dt>商品价格：</dt><dd>'+data.price+'</dd><dt>图片：</dt><dd><a href="javascript:void(0)"><img style="max-height: 300px;max-width: 300px" src="'+data.image+'" /></a></dd><dt>订购次数：</dt><dd>'+data.buyNum+'</dd><dt>商品备注：</dt><dd>'+data.description+'</dd><dt>商品货号：</dt><dd>'+data.goodsNumber+'</dd><dt>商品单位：</dt><dd>'+data.unit+'</dd><dt>商品规格：</dt><dd>'+data.standard+'</dd><dt>商品厂家：</dt><dd>'+data.vender+'</dd>';
        if(data.attr){
            $.each(data.attr,function(index,value){
                infoHtml+='<dt>'+index+'：</dt><dd>'+value+'</dd>';
            });
            infoHtml+='</dl>';
        }
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





