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
                        htmlTab+='<tr><td>'+value.id+'</td><td>'+value.name+'</td><td>'+value.sort+'</td><td>'+value.price+'</td><td><img src="'+value.image+'"></td><td><a href="/adm/goods/'+value.id+'" class="infoColor">查看详情</a></td>';
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
        var infoHtml='<dl><dt>商品名称：</dt><dd>'+ data.name +'</dd><dt>商品分类：</dt><dd>'+data.sort+'</dd><dt>商品价格：</dt><dd>'+data.price+'</dd><dt>图片：</dt><dd><a href="javascript:void(0)"><img src="'+data.image+'" /></a></dd><dt>订购次数：</dt><dd>'+data.buyNum+'</dd><dt>商品描述：</dt><dd>'+data.description+'</dd>';
        if(data.attr){
            $.each(data.attr,function(index,value){
                infoHtml+='<dt>'+index+'：</dt><dd>'+value+'</dd>';
            });
            infoHtml+='</dl>';
        }
        $('.infoUserCon').html(infoHtml);
    },function(errno,errmsg){
        alert(errmsg);
    });
}




