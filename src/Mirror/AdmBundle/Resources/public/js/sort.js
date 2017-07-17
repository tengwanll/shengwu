$(function(){

});

function sortList(){
    ajaxAction("get",'/api/sort',"",false,function(data,textStatus){
        var htmlTab=jointSorts(data.list);
        $('tbody').html(htmlTab);
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });
}

function jointSorts(list,parentId){
    var html='';
    $.each(list,function(index,value){
        var hidden='';
        if(value.level>1){
            hidden=';display: none';
        }
        var colorTo=255-value.level*10;
        html+='<tr style="background-color: rgb('+colorTo+','+colorTo+','+colorTo+');cursor: pointer'+hidden+'" class="sortTr'+parentId+'" onclick="showChild('+value.id+')"><td>'+value.id+'</td><td>'+value.name+'</td><td><img src="'+value.image+'" width="60px" height="60px"></td><td>'+value.child.length+'</td><td>'+value.level+'</td><td><a href="/adm/sort/'+value.id+'" class="infoColor">查看 </a><a href="/adm/sort/'+value.id+'/add/'+value.name+'" class="infoColor"> 添加 </a><a href="/adm/sort/'+value.id+'/edit" class="infoColor"> 修改 </a><a href="javascript:void(0)" class="infoColor" onclick="delSort('+value.id+')"> 删除</a></td>';
        if(value.child.length>0){
            html+=jointSorts(value.child,value.id);
        }
    });
    return html;
}



function showChild(id) {
    $('.sortTr'+id).toggle();
}

function delSort(id) {
    var info={};
    info.sortId=id;
    ajaxAction("delete",'/api/sort',info,false,function(data,textStatus){
        zdalert('系统提示','删除成功');
        sortList()
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
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
        zdalert('系统提示',errmsg);
    });
}




