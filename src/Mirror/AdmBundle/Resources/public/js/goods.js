$(function(){
    $('#search').click(function(){
        var info={};
        info.name=$('#name').val();
        info.sort=$('#sort').val();
        info.smallPrice=$('#smallPrice').val();
        info.bigPrice=$('#bigPrice').val();
        goodsList(info);
        return false;
    });

    $('button[type=reset]').click(function(){
        goodsList();
    });

    ajaxAction("get",'/api/sort',"",false,function(data,textStatus){

    },function(errno,errmsg){
        alert(errmsg);
    });
});

function goodsList(object){
    var info={rows:20,page:1};
    if(object != undefined){
        info=$.extend(info,object);
    }
    ajaxAction("get",'/api/goods'+ passParam(info),"",false,function(data,textStatus){
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
                ajaxAction("get",'/api/goods'+ passParam(info),"",true,function(data,textStatus){
                    htmlTab = '';
                    $.each(data.list,function(index,value){
                        var option;
                        if(value.status==1){
                            option='<option value="1" selected>上架</option><option value="0">下架</option>';
                        }else{
                            option='<option value="1">上架</option><option value="0" selected>下架</option>';
                        }
                        htmlTab+='<tr><td>'+value.id+'</td><td>'+value.name+'</td><td>'+value.sort+'</td><td>'+value.price+'</td><td><img style="height: 40px;width: 40px" src="'+value.image+'"></td><td><a href="/adm/goods/'+value.id+'" class="infoColor">修改</a> <a href="/adm/goods/'+value.id+'" class="infoColor">查看</a> <a href="javascript:void(0)" class="infoColor" onclick="addToCar('+value.id+')">加入购物车</a><select name="" id="changeStatus" onchange="changeStatus(this,'+value.id+')">'+option+'</select></td>';
                        $('tbody').html(htmlTab);
                    });
                },function(errno,errmsg){
                    alert(errmsg);
                });

            }
        });


    },function(errno,errmsg){
        alert(errmsg);
    });
}
function goodsInfo(goodsId){
    ajaxAction("get","/api/goods/"+goodsId,"",false,function(data,textStatus){
        var infoHtml='<dl><dt>商品名称：</dt><dd>'+ data.name +'</dd><dt>商品分类：</dt><dd>'+data.sort+'</dd><dt>商品价格：</dt><dd>'+data.price+'</dd><dt>图片：</dt><dd><a href="javascript:void(0)"><img style="max-height: 300px;max-width: 300px" src="'+data.image+'" /></a></dd><dt>订购次数：</dt><dd>'+data.buyNum+'</dd><dt>商品描述：</dt><dd>'+data.description+'</dd>';
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

function addToCar(id) {
    var info={};
    info.id=id;
    ajaxAction("post","/api/goods/car",info,true,function(data,textStatus){
        zdalert('系统提示','加入购物车成功');
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });
}




