$(function(){
    $('#file').change(function(){
        imgUpload($('form[name=uploadForm]')[0],'/api/file/BImport','post',function(){},function(data,textStatus){
            location.href='/adm/biology';
        },function(errno,errmsg){
            zdalert('系统提示',errmsg);
        });
    });
});

//生物列表
function biologyList(){
    var info={rows:10,page:1};
    ajaxAction("get",'/api/biology'+ passParam(info),"",true,function(data,textStatus){
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
                ajaxAction("get",'/api/biology'+ passParam(info),"",true,function(data,textStatus){
                    htmlTab = '';
                    $.each(data.list,function(index,value){
                        htmlTab+='<tr><td>'+value.id+'</td><td>'+value.englishName+'</td><td>'+value.name+'</td><td>'+value.sort+'</td><td>'+value.kind+'</td><td >'+value.checkGene+'</td><td>'+value.otherGene+'</td><td>'+value.literature+'</td><td>'+value.desease+'</td><td><a href="/adm/biology/'+value.id+'" class="infoColor">查看详情</a> <a href="/adm/biology/'+value.id+'/edit" class="infoColor">查看详情</a> <a href="javascript:void(0)" class="infoColor" onclick="deleteCarGoods('+value.id+')">删除</a></td>';
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
function deleteCarGoods(id) {
    var info={};
    info.id=id;
    ajaxAction("delete",'/api/biology',info,true,function(data,textStatus){
        location.href='/adm/biology';
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });
}
