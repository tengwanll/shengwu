$(function(){

});
function orderInfo(orderId){

    ajaxAction("get","/api/order/"+orderId,"",false,function(data,textStatus){
        if(data.status=='-1'){
            data.status='未通过';
        }else if(data.status=='0'){
            data.status='待审核';
        }else if(data.status=='1'){
            data.status='代发货';
        }else if(data.status=='2'){
            data.status='已发货';
        }else if(data.status=='3'){
            data.status='已完成';
        }
        var infoHtml='<dl><dt>ID：</dt><dd>'+ data.id +'</dd><dt>订单状态：</dt><dd>'+data.status+'</dd><dt>订单编号：</dt><dd>'+data.number+'</dd><dt>下单时间：</dt><dd>'+data.createTime+'</dd><dt>下单人：</dt><dd>'+data.username+'</dd><dt>价格：</dt><dd>'+data.price+'</dd></dl><dt>订单备注：</dt><dd>'+data.message+'</dd></dl>';

        $('.infoUserCon').html(infoHtml);
    },function(errno,errmsg){
        alert(errmsg);
    });


}
//////////////////////////////////////////////////////////

//用户订单列表
function orderGoodsList(orderId){
    // 债权列表
    var info={};
    info.rows=20;
    info.page=1;
    ajaxAction("get",'/api/order/'+orderId+'/goods'+ passParam(info),"",false,function(data,textStatus){
        var count=Math.ceil(data.total/20);
        html="";
        if(!count){
            $('.tab_content').html(html);
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
                ajaxAction("get",'/api/order/'+orderId+'/goods'+ passParam(info),"",true,function(data,textStatus){
                    var html = '';
                    $.each(data.list,function(index,value){
                        html+=' <tr><td>'+value.goodsName+'</td><td>'+value.number+'</td><td>'+value.price+'</td><td><a href="/adm/order/'+value.id+'" class="infoColor">查看详情</a></td></tr>';
                    });
                    $('tbody').html(html);
                },function(errno,errmsg){
                    alert(errmsg);
                });

            }
        });


    },function(errno,errmsg){
        alert(errmsg);
    });



}