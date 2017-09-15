$(function(){
    $('#file').change(function(){
        imgUpload($('form[name=uploadForm]')[0],'/api/file/BImport','post',function(){},function(data,textStatus){
            location.href='/adm/biology';
        },function(errno,errmsg){
            zdalert('系统提示',errmsg);
        });
    });
    $('#search').click(function(){
        biologyList();
    });
    $('.for_search').keydown(function (event) {
        if (event.keyCode == 13) {
            biologyList();
        }
    });
});

//生物列表
function biologyList(){
    var info={rows:10,page:1};
    info.name=$('#name').val();
    info.englishName=$('#englishName').val();
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
                        var literature='';
                        $.each(value.literature,function (ind,val) {
                            if(val){
                                if(ind){
                                    literature+=' , <a href="'+val.url+'">'+val.name+'</a> ';
                                }else{
                                    literature+='<a href="'+val.url+'">'+val.name+'</a> ';
                                }
                            }
                        });
                        htmlTab+='<tr><td>'+value.id+'</td><td>'+value.englishName+'</td><td>'+value.name+'</td><td>'+value.sort+'</td><td>'+value.kind+'</td><td >'+value.checkGene+'</td><td>'+value.otherGene+'</td><td>'+literature+'</td><td>'+value.disease+'</td><td><a href="/adm/biology/'+value.id+'" class="infoColor">查看</a> <a href="/adm/biology/'+value.id+'/edit" class="infoColor">修改</a> <a href="javascript:void(0)" class="infoColor" onclick="deleteCarGoods('+value.id+')">删除</a></td>';
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

function biologyInfo(biologyId){
    ajaxAction("get","/api/biology/"+biologyId,"",false,function(data,textStatus){
        var infoHtml='<dl><dt>中文名称：</dt><dd>'+ data.name +'</dd><dt>拉丁名称：</dt><dd>'+data.englishName+'</dd><dt>微生物类别：</dt><dd>'+data.sort+'</dd><dt>菌种种类：</dt><dd>'+data.kind+'</dd><dt>检测基金：</dt><dd>'+data.checkGene+'</dd><dt>毒力基因：</dt><dd>'+data.otherGene+'</dd><dt>文献：</dt><dd>'+data.literature+'</dd><dt>引发病症：</dt><dd>'+data.disease+'</dd><dt>关键字：</dt><dd>'+data.keyword+'</dd><dt>是否常见：</dt><dd>'+data.isUsual+'</dd><dt>备注：</dt><dd>'+data.comment+'</dd></dl>';
        $('.infoUserCon').html(infoHtml);
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });
}
