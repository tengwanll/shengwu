$(function(){



});

function goodsList(nowPage,object){
    var info={rows:10,page:1};
    if(object&&object != undefined){
        info=$.extend(info,object);
    }
    ajaxAction("get",'/api/box'+ passParam(info),"",false,function(data,textStatus){
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
                ajaxAction("get",'/api/box'+ passParam(info),"",true,function(data,textStatus){
                    htmlTab = '';
                    $.each(data.list,function(index,value){
                        var report=value.report;
                        if(report!=''){
                            report='<a href="'+value.report+'" class="infoColor">下载</a>';
                        }
                        htmlTab+='<tr><td>'+value.uniqueId+'</td><td><img style="height: 40px;width: 40px;cursor: pointer" src="'+value.codeUrl+'" onclick="zdphoto(\'图片显示\',\''+value.codeUrl+'\')"></td><td>'+report+'</td><td>'+value.status+'</td><td>'+value.createTime+'</td><td><a href="/adm/face/'+value.id+'" class="infoColor">查看</a> </td>';
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

function add(info) {
    zdalert('系统提示','正在添加,请稍等');
    ajaxAction("post","/api/box",info,true,function(data,textStatus){
        zdalert('系统提示','添加成功',function(r){
            if(r){
                location.href='/adm/face';
            }
        });
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });
}

function boxInfo(id) {
    ajaxAction("get","/api/box/"+id,'',true,function(data,textStatus){
        
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });
}







