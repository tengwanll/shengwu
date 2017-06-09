$(function(){
    $('#order').click(function () {
        var info={};
        info.price=$('#totalPrice').html();
        info.carId=[];
        $(':checked').not('#choice').each(function(index,value){
            info.carId.push($(value).attr('carId'));
        });
        console.log(info.carId);
        if(info.carId.length<=0){
            zdalert('系统提示','请选择操作项');
        }

    });
});

function goodsList(){
    var info={rows:20,page:1};
    ajaxAction("get",'/api/car'+ passParam(info),"",true,function(data,textStatus){
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
                ajaxAction("get",'/api/car'+ passParam(info),"",true,function(data,textStatus){
                    htmlTab = '';
                    $.each(data.list,function(index,value){
                        index=index+1;
                        htmlTab+='<tr><td><input type="checkbox" name="carChoice" carId="'+value.id+'"></td><td>'+index+'</td><td>'+value.goodsName+'</td><td id="number">'+value.number+'</td><td id="price">'+value.price+'</td><td><a href="/adm/goods/'+value.goodsId+'" class="infoColor">查看详情</a><a href="javascript:void(0)" class="infoColor" onclick="add(this,'+value.id+')"><span style="font-size: 25px"> + </span></a><a href="javascript:void(0)" class="infoColor" onclick="sub(this,'+value.id+')"><span style="font-size: 35px"> -</span></a></td>';
                        $('tbody').html(htmlTab);
                    });
                    $('#totalPrice').html(data.totalPrice);
                },function(errno,errmsg){
                    alert(errmsg);
                });

            }
        });


    },function(errno,errmsg){
        alert(errmsg);
    });
}
function add(obj,id) {
    var number=$(obj).parent().parent().find('#number').html();
    var price=$(obj).parent().parent().find('#price').html();
    var totalPrice=$('#totalPrice').html();
    number=parseInt(number);
    price=parseFloat(price);
    totalPrice=parseFloat(totalPrice);
    ajaxAction("post",'/api/car/number/'+ id,"",false,function(data,textStatus){
        $(obj).parent().parent().find('#number').html(number+1);
        $(obj).parent().parent().find('#price').html(price+price/number);
        $('#totalPrice').html(totalPrice+price/number);
    },function(errno,errmsg){
        alert(errmsg);
    });
}
function sub(obj,id) {
    var number=$(obj).parent().parent().find('#number').html();
    var price=$(obj).parent().parent().find('#price').html();
    var totalPrice=$('#totalPrice').html();
    number=parseInt(number);
    price=parseFloat(price);
    totalPrice=parseFloat(totalPrice);
    if(number<=1){
        zdconfirm('系统确认框','当前数量为1,是否要删除商品',function(r){
            if(r){
                var info={};
                info.carId=id;
                ajaxAction("delete",'/api/car',info,false,function(data,textStatus){
                    $(obj).parent().parent().remove();
                    $('#totalPrice').html(totalPrice-price);
                },function(errno,errmsg){
                    alert(errmsg);
                });
            }
        });
    }else{
        ajaxAction("delete",'/api/car/number/'+ id,"",false,function(data,textStatus){
            $(obj).parent().parent().find('#number').html(number-1);
            $(obj).parent().parent().find('#price').html(price-price/number);
            $('#totalPrice').html(totalPrice-price/number);
        },function(errno,errmsg){
            alert(errmsg);
        });
    }
}

function choiceAll(obj) {
    if($(obj).attr('checked')=='checked'){
        $('input[name=carChoice]').attr('checked','true');
    }else{
        $('input[name=carChoice]').removeAttr('checked');
    }
}