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
                        htmlTab+='<tr><td>'+value.uniqueId+'</td><td><img style="height: 40px;width: 40px;cursor: pointer" src="'+value.codeUrl+'" onclick="zdphoto(\'图片显示\',\''+value.codeUrl+'\')"></td><td>'+report+'</td><td>'+value.status+'</td><td>'+value.createTime+'</td><td><a href="/adm/face/'+value.id+'" class="infoColor">查看</a> <a href="/adm/face/edit/'+value.uniqueId+'/'+info.page+'" class="infoColor">填报</a> <label for="file'+value.id+'" style="color: #ff7800;cursor: pointer">导入报表</label><form action="" name="uploadForm"><input type="file" style="display: none" name="file" id="file'+value.id+'"></form></td>';
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
        var infoHtml='<dl><dt>盒子编号：</dt><dd>'+ data.uniqueId +'</dd><dt>二维码：</dt><dd><a href="javascript:void(0)"><img style="max-height: 100px;max-width: 100px" src="'+data.codeUrl+'" /></a></dd><dt>盒子状态：</dt><dd>'+data.status+'</dd><dt>创建时间：</dt><dd>'+data.createTime+'</dd>';
        if(!$.isEmptyObject(data.boxInfo)){
            infoHtml+='<dt>用户姓名：</dt><dd>'+ data.boxInfo.name +'</dd><dt>年龄：</dt><dd>'+data.boxInfo.age+'</dd><dt>用户性别：</dt><dd>'+data.boxInfo.gender+'</dd><dt>用户email：</dt><dd>'+data.boxInfo.email+'</dd><dt>用户电话：</dt><dd>'+data.boxInfo.telephone+'</dd><dt>用户检查项：</dt><dd>'+data.boxInfo.ability+'</dd>';
        }
        if(!$.isEmptyObject(data.boxGene)){
            $.each(data.boxGene,function(index,value){
                infoHtml+='<dt>'+index+'：</dt><dd>'+value+'</dd>';
            });
        }
        infoHtml+='</dl>';
        $('.infoUserCon').html(infoHtml);
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });
}







