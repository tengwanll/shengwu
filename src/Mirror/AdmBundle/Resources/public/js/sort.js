$(function(){

});

function sortList(sortId){
    if(!sortId){
        sortId=1;
    }
    ajaxAction("get",'/api/sort/list/'+sortId,"",false,function(data,textStatus){
        var li='';
        $.each(data.parentArr,function (index,value) {
            if(index){
                li+='<li style="float: left;cursor: pointer;padding: 5px 2px;" onclick="sortList('+value.id+')"> >  '+value.name+'</li>'
            }else{
                li+='<li style="float: left;cursor: pointer;padding: 5px 2px" onclick="sortList('+value.id+')">'+value.name+'</li>'
            }
        });
        $('.navigation').html(li);
        var html='';
        $.each(data.list,function (index,value) {
            var child='';
            if(value.count>0){
                child='onclick="sortList('+value.id+')';
            }
            html+='<tr style="cursor: pointer" '+child+'"><td>'+value.id+'</td><td>'+value.name+'</td><td><img src="'+value.image+'" width="60px" height="60px"></td><td>'+value.count+'</td><td>'+value.level+'</td><td><a href="/adm/sort/'+value.id+'" class="infoColor">查看 </a><a href="/adm/sort/'+value.id+'/add/'+sortId+'" class="infoColor"> 添加 </a><a href="/adm/sort/'+value.id+'/edit/'+sortId+'" class="infoColor"> 修改 </a><a href="javascript:void(0)" class="infoColor" onclick="delSort('+value.id+')"> 删除</a></td>';
        });
        $('tbody').html(html);
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });
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




