$(function(){
    $('#order').click(function () {
        var info={};
        info.price=$('#totalPrice').html();
        info.carId=[];
        $(':checked').not('#choice').each(function(index,value){
            info.carId.push($(value).attr('carId'));
        });
        if(info.carId.length<=0){
            zdalert('系统提示','请选择操作项');
        }else{
            zdcomment('备注信息','输入备注信息更方便您之后查看',function(r,message){
                if(r){
                    info.message=message;
                    ajaxAction("post",'/api/order',info,false,function(data,textStatus){
                        $.each(data.userId,function (index,val) {
                            var msg={};
                            msg.event='order';
                            msg.userId=val;
                            ws.send($.toJSON(msg));
                        });
                        zdalert('系统提示','下单成功');
                        location.href='/adm/order';
                    },function(errno,errmsg){
                        zdalert('系统提示',errmsg);
                    });
                }
            });
        }
    });

    $('#file').change(function(){
        fileUpload($('form[name=uploadForm]')[0]);
    });
});

//购物车列表
function goodsList(){
    var info={rows:10,page:1};
    ajaxAction("get",'/api/car'+ passParam(info),"",true,function(data,textStatus){
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
                ajaxAction("get",'/api/car'+ passParam(info),"",true,function(data,textStatus){
                    htmlTab = '';
                    $.each(data.list,function(index,value){
                        index=index+1;
                        htmlTab+='<tr><td><input type="checkbox" onclick="changePrice(this)" name="carChoice" carId="'+value.id+'"></td><td>'+index+'</td><td>'+value.goodsName+'</td><td>'+value.goodsNumber+'</td><td id="number" ondblclick="changeNumber(this,'+value.number+')">'+value.number+'</td><td id="price" class="price">'+value.price+'</td><td>'+value.standard+'</td><td>'+value.vender+'</td><td><a href="/adm/goods/'+value.goodsId+'" class="infoColor">查看详情</a><a href="javascript:void(0)" class="infoColor" onclick="add(this,'+value.id+')"><span style="font-size: 25px"> + </span></a><a href="javascript:void(0)" class="infoColor" onclick="sub(this,'+value.id+')"><span style="font-size: 35px"> -</span></a> <a href="javascript:void(0)" class="infoColor" onclick="deleteCarGoods(this,'+value.id+')">删除</a></td>';
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

function changeNumber(obj) {
    var number=$(obj).html();
    var html='<input type="text" onblur="setNumber(this,'+number+')">';
    $(obj).html(html);
    $(obj).find('input').focus().val(number);
}

function setNumber(obj,oldNumber) {
    var number=$(obj).val();
    var carId=$(obj).parent().parent().find('input[name=carChoice]').attr('carId');
    var price=$(obj).parent().parent().find('#price').html();
    var totalPrice=$('#totalPrice').html();
    number=parseInt(number);
    price=parseFloat(price);
    totalPrice=parseFloat(totalPrice);
    oldNumber=parseInt(oldNumber);
    var info={};
    info.number=number;
    info.carId=carId;
    ajaxAction("put",'/api/car/number',info,true,function(data,textStatus){
        if($(obj).parent().parent().find('input[name=carChoice]').attr('checked')=='checked'){
            $('#totalPrice').html(totalPrice-price+price/oldNumber*number);
        }
        $(obj).parent().parent().find('#price').html(price/oldNumber*number);
        $(obj).parent().html(number);
    },function(errno,errmsg){
        zdalert('系统提示',errmsg,function(){
            $(obj).focus();
        });
    });

}

function changePrice(obj) {
    var price=$(obj).parent().parent().find('#price').html();
    var totalPrice=$('#totalPrice').html();
    totalPrice=parseFloat(totalPrice);
    price=parseFloat(price);
    if($(obj).attr('checked')=='checked'){
        $('#totalPrice').html(totalPrice+price);
    }else{
        $('#totalPrice').html(totalPrice-price);
    }
}

function deleteCarGoods(obj,id) {
    var price=$(obj).parent().parent().find('#price').html();
    var totalPrice=$('#totalPrice').html();
    price=parseFloat(price);
    totalPrice=parseFloat(totalPrice);
    var info={};
    info.carId=id;
    ajaxAction("delete",'/api/car',info,true,function(data,textStatus){
        if($(obj).parent().parent().find('input[name=carChoice]').attr('checked')=='checked'){
            $('#totalPrice').html(totalPrice-price);
        }
        $(obj).parent().parent().remove();
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });
}

//商品添加数量
function add(obj,id) {
    var number=$(obj).parent().parent().find('#number').html();
    var price=$(obj).parent().parent().find('#price').html();
    var totalPrice=$('#totalPrice').html();
    number=parseInt(number);
    price=parseFloat(price);
    totalPrice=parseFloat(totalPrice);
    ajaxAction("post",'/api/car/number/'+ id,"",false,function(data,textStatus){
        if($(obj).parent().parent().find('input[name=carChoice]').attr('checked')=='checked'){
            $('#totalPrice').html(totalPrice+price/number);
        }
        $(obj).parent().parent().find('#number').html(number+1);
        $(obj).parent().parent().find('#price').html(price+price/number);
    },function(errno,errmsg){
        zdalert('系统提示',errmsg);
    });
}

//商品减少数量
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
                    if($(obj).parent().parent().find('input[name=carChoice]').attr('checked')=='checked'){
                        $('#totalPrice').html(totalPrice-price);
                    }
                    $(obj).parent().parent().remove();
                },function(errno,errmsg){
                    zdalert('系统提示',errmsg);
                });
            }
        });
    }else{
        ajaxAction("delete",'/api/car/number/'+ id,"",false,function(data,textStatus){
            $(obj).parent().parent().find('#number').html(number-1);
            $(obj).parent().parent().find('#price').html(price-price/number);
            if($(obj).parent().parent().find('input[name=carChoice]').attr('checked')=='checked'){
                $('#totalPrice').html(totalPrice-price/number);
            }
        },function(errno,errmsg){
            zdalert('系统提示',errmsg);
        });
    }
}

//全选
function choiceAll(obj) {
    var totalPrice=0;
    if($(obj).attr('checked')=='checked'){
        $('.price').each(function(index,val){
            totalPrice=totalPrice+parseFloat($(val).html());
        });
        $('input[name=carChoice]').attr('checked','true');
    }else{
        $('input[name=carChoice]').removeAttr('checked');
    }
    $('#totalPrice').html(totalPrice);
}
