$(function(){

    $('.propertyInfo').hide();
    $('.rewardInfo').hide();

    $('.typeTabTit ul li').click(function(){
        tabNav($(this));
        if($(this).index()==0) {
            $('.propertyInfo').hide();
            $('.rewardInfo').hide();
            $('.creditInfo').show();
            creditList();
        }else if($(this).index()==2){
            $('.propertyInfo').hide();
            $('.rewardInfo').show();
            $('.creditInfo').hide();
            rewardList();
        }else if($(this).index()==1){
            $('.propertyInfo').show();
            $('.rewardInfo').hide();
            $('.creditInfo').hide();
            propertyList();
        }

    });

    $('#search').click(function(){
        var info={};
        if($('.typeTabTit ul li div.on').text()=='债权信息'){
            if($('#number').val()) {
                info.code = $('#number').val();
            }
            if($('#creditType').val()) {
                info.type = $('#creditType').val();
            }
            if($('#creditStatus').val()) {
                info.status = $('#creditStatus').val();
            }
            if($('#selectP').val()!=0) {
                info.province = $('#selectP').val();
            }
            if($('#selectC').val()!=0) {
                info.city = $('#selectC').val();
            }
            creditList(info);
        }else if($('.typeTabTit ul li div.on').text()=='资产售让'){
            if($('#number').val()) {
                info.code = $('#number').val();
            }
            if($('#saleType').val()!=0) {
                if($('#saleType').val()=='固定资产') {
                    info.sellType = 0
                }
                if($('#saleType').val()=='资产包') {
                    info.sellType = 1
                }
            }
            if($('#propertyType').val()!=0) {

                if($('#saleType').val()=='固定资产') {

                    if ($('#propertyType').val() == '房产') {
                        info.propertyPattern = 0
                    }
                    if ($('#propertyType').val() == '土地') {
                        info.propertyPattern = 1
                    }
                    if ($('#propertyType').val() == '设备') {
                        info.propertyPattern = 2
                    }
                    if ($('#propertyType').val() == '车辆') {
                        info.propertyPattern = 3
                    }
                    if ($('#propertyType').val() == '专利/技术') {
                        info.propertyPattern = 4
                    }
                    if ($('#propertyType').val() == '版权') {
                        info.propertyPattern = 5
                    }
                    if ($('#propertyType').val() == '其他') {
                        info.propertyPattern = 6
                    }
                }

                    if($('#saleType').val()=='资产包') {

                        if ($('#propertyType').val() == '债权资产包') {
                            info.propertyPattern = 0
                        }
                        if ($('#propertyType').val() == '固定资产包') {
                            info.propertyPattern = 1
                        }
                        if ($('#propertyType').val() == '信用卡资产包') {
                            info.propertyPattern = 2
                        }

                }
            }
            if($('#creditStatus').val()) {
                info.status = $('#creditStatus').val();
            }
            if($('#selectP').val()!=0) {
                info.province = $('#selectP').val();
            }
            if($('#selectC').val()!=0) {
                info.city = $('#selectC').val();
            }
            propertyList(info);
        }else if($('.typeTabTit ul li div.on').text()=='信息悬赏'){
            if($('#number').val()) {
                info.code = $('#number').val();
            }
            if($('#creditType').val()) {
                info.type = $('#creditType').val();
            }
            if($('#creditStatus').val()) {
                info.status = $('#creditStatus').val();
            }
            if($('#selectP').val()!=0) {
                info.province = $('#selectP').val();
            }
            if($('#selectC').val()!=0) {
                info.city = $('#selectC').val();
            }
            rewardList(info);
        }
    });

    $('#reset').click(function() {
        if($('.typeTabTit ul li div.on').text()=='债权信息'){
            creditList();
        }else if($('.typeTabTit ul li div.on').text()=='资产售让'){
            propertyList();
        }else if($('.typeTabTit ul li div.on').text()=='信息悬赏'){
            rewardList();
        }
    });


    selectprovince('selectP','selectC');
    $('select[name=selectP]').change(function(){
        selectcityarea('select_addr','selectP','selectC');
    });

    selectSaleType('saleType','propertyType');
    $('select[name=saleType]').change(function(){
        selectPropertyType('select_addr','saleType','propertyType');
    });

    creditList();



});






/////////////////////////////////////////////////////


function creditchose(){

    var info={};
    //console.log($('.click'));
    if($('#number').val()){
        info.code=$('#number option:selected').val();
    }
    if($('#status option:selected').val()!=" "){
        info.status=$('#status option:selected').val();
    }
    if($('#type option:selected').val()!=" "){
        info.type=$('#type option:selected').val();
    }
    if($('#selectP option:selected').val()!=0){
        info.province=$('#selectP option:selected').val();
    }
    if($('#selectC option:selected').val()!=0){
        info.city=$('#selectC option:selected').val();
    }

    // 债权列表
    info.rows=20;
    info.page=1;
    ajaxAction("get",'/api/credit/back/list'+ passParam(info),"",true,function(data,textStatus){
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
                ajaxAction("get",'/api/credit/back/list'+ passParam(info),"",true,function(data,textStatus){
                    htmlTab = '';
                    $.each(data.list,function(index,value){
                        var deal=[];
                        $.each(value.dealPattern,function(index1,value){
                            if(value.ratio){
                                deal.push(value.name+value.ratio+"%");
                            }else{
                                deal.push(value.name+value.ratio);
                            }

                        });
                        if(value.status==0){
                            value.status="待审核";
                        }else if(value.status==1){
                            value.status="待签约";
                        }else if(value.status==2){
                            value.status="处置中";
                        }else if(value.status==3){
                            value.status="已完成";
                        }else if(value.status==-1){
                            value.status="已删除";
                        }
                        if(value.type==0){
                            value.type="个人债权";
                        }else if(value.type==1){
                            value.type="企业商账";
                        }else if(value.type==2){
                            value.type="机构信贷";
                        }

                        alert(value.status);
                        htmlTab+='<tr><td>'+value.creditId+'</td><td>'+value.code+'</td><td>'+value.type+'</td><td>'+value.money+'</td><td>'+value.place+'</td><td>'+value.status+'</td><td><a href="javascript:void (0)" class="infoColor">查看详情</a></td><td><a href="javascript:void(0)" class="modificationColor">修改</a></td><td><a href="javascritp:void(0)" class="deleteColor">删除</a></td></tr>';
                        //console.log(value.code)
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


