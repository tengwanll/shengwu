$(function(){

    orderList();
    $('#search').click(function(){
        var info={};
        info.number=$('#number').val();
        info.status=$('#status').val();
        info.username=$('#username').val();
        info.beginTime=$('#beginTime').val();
        info.endTime=$('#endTime').val();
        orderList(info);
        return false;
    });

    $('button[type=reset]').click(function(){
        orderList();
    });
});

function orderList(object){
    var info={rows:20,page:1};
    if(object != undefined){
        info=$.extend(info,object);
    }
    ajaxAction("get",'/api/order'+ passParam(info),"",false,function(data,textStatus){
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
                ajaxAction("get",'/api/order'+ passParam(info),"",true,function(data,textStatus){
                    htmlTab = '';
                    $.each(data.list,function(index,value){
                        if(value.status=='-1'){
                            value.status='未通过';
                        }else if(value.status=='0'){
                            value.status='待审核';
                        }else if(value.status=='1'){
                            value.status='代发货';
                        }else if(value.status=='2'){
                            value.status='已发货';
                        }else if(value.status=='3'){
                            value.status='已完成';
                        }
                        htmlTab+='<tr><td>'+value.number+'</td><td>'+value.createTime+'</td><td>'+value.username+'</td><td>'+value.price+'</td><td>'+value.status+'</td><td><a href="/adm/order/'+value.id+'" class="infoColor">查看详情</a></td>';
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




