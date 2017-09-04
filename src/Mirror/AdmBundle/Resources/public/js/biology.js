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
