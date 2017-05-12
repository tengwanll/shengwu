$(function(){
    $('input[name="TypeRadio"][value="0"]').attr('checked',true);
    isRadioChecked('TypeRadio');
    selectRadioChecked($('.statisticsTypeRadio li label'));
    $('.selectDateInput').datepicker({
        format: 'yyyy-mm-dd',
        weekStart: 1,
        autoclose: true,
        todayBtn: 'linked',
        language: 'cn'
    });



    $('.selectDateInput').val(getDate());


    $('.datepicker').change(function(){
        statistical_data();
    });

    $('.fixedDate ul li').click(function(){
        tabNavTitle($(this));
        statistical_data();
    });

    $('.statisticsTypeRadio ul li input').change(function() {
        statistical_data();
    });




/*默认显示列表*/
statisticsApi('/api/report/user/custom','register',{time:$('.selectDateInput').val(),cycle:$('.fixedDate ul .on').attr('period')});


});


function statistical_data() {

    if($('input[name="TypeRadio"]:checked').prev().text()=='注册人数'){
        statisticsApi('/api/report/user/custom','register',{time:$('.selectDateInput').val(),cycle:$('.fixedDate ul .on').attr('period')});
    }else if($('input[name="TypeRadio"]:checked').prev().text()=='登录人数'){
        statisticsApi('/api/report/login/custom','login',{time:$('.selectDateInput').val(),cycle:$('.fixedDate ul .on').attr('period')});
    }else if($('input[name="TypeRadio"]:checked').prev().text()=='活跃度'){
        statisticsApi('/api/report/ua/custom','active',{time:$('.selectDateInput').val(),cycle:$('.fixedDate ul .on').attr('period')});
    }else if($('input[name="TypeRadio"]:checked').prev().text()=='省级代理'){
        statisticsApi('/api/report/provincialagents/custom','province',{time:$('.selectDateInput').val(),cycle:$('.fixedDate ul .on').attr('period')});
    }else if($('input[name="TypeRadio"]:checked').prev().text()=='市级代理'){
        statisticsApi('/api/report/cityagents/custom','city',{time:$('.selectDateInput').val(),cycle:$('.fixedDate ul .on').attr('period')});
    }else if($('input[name="TypeRadio"]:checked').prev().text()=='催收公司'){
        statisticsApi('/api/report/company/custom','collection',{time:$('.selectDateInput').val(),cycle:$('.fixedDate ul .on').attr('period')});
    }else if($('input[name="TypeRadio"]:checked').prev().text()=='债权数量'){
        statisticsApi('/api/report/credit/custom','creditor',{time:$('.selectDateInput').val(),cycle:$('.fixedDate ul .on').attr('period')});
    }else if($('input[name="TypeRadio"]:checked').prev().text()=='售让数量'){
        statisticsApi('/api/report/property/custom','property',{time:$('.selectDateInput').val(),cycle:$('.fixedDate ul .on').attr('period')});
    }else if($('input[name="TypeRadio"]:checked').prev().text()=='悬赏数量'){
        statisticsApi('/api/report/reward/custom','reward',{time:$('.selectDateInput').val(),cycle:$('.fixedDate ul .on').attr('period')});
    }
}





function statisticsApi(urlApi,type,objectParameter){
    // console.log(objectParameter);
    var statisticType=type;
    var info={}
    info=$.extend(info,objectParameter);
    ajaxAction("post",urlApi,info,false,function(data,textStatus){
        // console.log(data.num.length);
        var week=[];
        var day='',i;
        if(data.num.length==7){
            for(i=1;i<=7;i++){
                if(i==1) day='周一';
                if(i==2) day='周二';
                if(i==3) day='周三';
                if(i==4) day='周四';
                if(i==5) day='周五';
                if(i==6) day='周六';
                if(i==7) day='周日';
                week.push(day);
            }
        }else if(data.num.length==12){
            for(i=1;i<=12;i++){
                if(i==1) day='一月';
                if(i==2) day='二月';
                if(i==3) day='三月';
                if(i==4) day='四月';
                if(i==5) day='五月';
                if(i==6) day='六月';
                if(i==7) day='七月';
                if(i==8) day='八月';
                if(i==9) day='九月';
                if(i==10) day='十月';
                if(i==11) day='十一月';
                if(i==12) day='十二月';
                week.push(day);
            }

        } else if(data.num.length==30){
            for(i=1;i<=30;i++){
                if(i==1) day='一日';
                if(i==2) day='二日';
                if(i==3) day='三日';
                if(i==4) day='四日';
                if(i==5) day='五日';
                if(i==6) day='六日';
                if(i==7) day='七日';
                if(i==8) day='八日';
                if(i==9) day='九日';
                if(i==10) day='十日';
                if(i==11) day='十一日';
                if(i==12) day='十二日';
                if(i==13) day='十三日';
                if(i==14) day='十四日';
                if(i==15) day='十五日';
                if(i==16) day='十六日';
                if(i==17) day='十七日';
                if(i==18) day='十八日';
                if(i==19) day='十九日';
                if(i==20) day='二十日';
                if(i==21) day='二十一日';
                if(i==22) day='二十二日';
                if(i==23) day='二十三日';
                if(i==24) day='二十四日';
                if(i==25) day='二十五日';
                if(i==26) day='二十六日';
                if(i==27) day='二十七日';
                if(i==28) day='二十八日';
                if(i==29) day='二十九日';
                if(i==30) day='三十日';

                week.push(day);
            }

        }else if(data.num.length==31){
            for(i=1;i<=31;i++){
                if(i==1) day='一日';
                if(i==2) day='二日';
                if(i==3) day='三日';
                if(i==4) day='四日';
                if(i==5) day='五日';
                if(i==6) day='六日';
                if(i==7) day='七日';
                if(i==8) day='八日';
                if(i==9) day='九日';
                if(i==10) day='十日';
                if(i==11) day='十一日';
                if(i==12) day='十二日';
                if(i==13) day='十三日';
                if(i==14) day='十四日';
                if(i==15) day='十五日';
                if(i==16) day='十六日';
                if(i==17) day='十七日';
                if(i==18) day='十八日';
                if(i==19) day='十九日';
                if(i==20) day='二十日';
                if(i==21) day='二十一日';
                if(i==22) day='二十二日';
                if(i==23) day='二十三日';
                if(i==24) day='二十四日';
                if(i==25) day='二十五日';
                if(i==26) day='二十六日';
                if(i==27) day='二十七日';
                if(i==28) day='二十八日';
                if(i==29) day='二十九日';
                if(i==30) day='三十日';
                if(i==31) day='三十一日';

                week.push(day);
            }

        }else if(data.num.length==28){
            for(i=1;i<=28;i++){
                if(i==1) day='一日';
                if(i==2) day='二日';
                if(i==3) day='三日';
                if(i==4) day='四日';
                if(i==5) day='五日';
                if(i==6) day='六日';
                if(i==7) day='七日';
                if(i==8) day='八日';
                if(i==9) day='九日';
                if(i==10) day='十日';
                if(i==11) day='十一日';
                if(i==12) day='十二日';
                if(i==13) day='十三日';
                if(i==14) day='十四日';
                if(i==15) day='十五日';
                if(i==16) day='十六日';
                if(i==17) day='十七日';
                if(i==18) day='十八日';
                if(i==19) day='十九日';
                if(i==20) day='二十日';
                if(i==21) day='二十一日';
                if(i==22) day='二十二日';
                if(i==23) day='二十三日';
                if(i==24) day='二十四日';
                if(i==25) day='二十五日';
                if(i==26) day='二十六日';
                if(i==27) day='二十七日';
                if(i==28) day='二十八日';
                week.push(day);
            }

        }else if(data.num.length==29){
            for(i=1;i<=29;i++){
                if(i==1) day='一日';
                if(i==2) day='二日';
                if(i==3) day='三日';
                if(i==4) day='四日';
                if(i==5) day='五日';
                if(i==6) day='六日';
                if(i==7) day='七日';
                if(i==8) day='八日';
                if(i==9) day='九日';
                if(i==10) day='十日';
                if(i==11) day='十一日';
                if(i==12) day='十二日';
                if(i==13) day='十三日';
                if(i==14) day='十四日';
                if(i==15) day='十五日';
                if(i==16) day='十六日';
                if(i==17) day='十七日';
                if(i==18) day='十八日';
                if(i==19) day='十九日';
                if(i==20) day='二十日';
                if(i==21) day='二十一日';
                if(i==22) day='二十二日';
                if(i==23) day='二十三日';
                if(i==24) day='二十四日';
                if(i==25) day='二十五日';
                if(i==26) day='二十六日';
                if(i==27) day='二十七日';
                if(i==28) day='二十八日';
                if(i==29) day='二十九日';
                week.push(day);
            }


        }
        statistics(week,data.num,statisticType);
    },function(errno,errmsg){
        alert(errmsg);
    });
}

function statistics(arrayX,arrayY,type){
    var statisticType;
    if(type=='register'){
        statisticType='注册人数'
    }else if(type=='login'){
        statisticType='登录人数'
    }else if(type=='active'){
        statisticType='活跃度'
    }else if(type=='province'){
        statisticType='省级代理'
    }else if(type=='city'){
        statisticType='市级代理'
    }else if(type=='collection'){
        statisticType='催收公司'
    }else if(type=='creditor'){
        statisticType='债权数量'
    }else if(type=='property'){
        statisticType='资产数量'
    }else if(type=='reward'){
        statisticType='悬赏数量'
    }
    $('#canvasDiv').highcharts({
        lang: {
            printChart:"打印图表",
            downloadJPEG: "下载JPEG 图片" ,
            downloadPDF: "下载PDF文档"  ,
            downloadPNG: "下载PNG 图片"  ,
            downloadSVG: "下载SVG 矢量图" ,
            exportButtonTitle: "导出图片"
        },
        title: {
            text: null,
            x: -20 //center
        },
        subtitle: {
            text: null,
            x: -20
        },

        xAxis: {
            title: {

                text: null

            },

            categories: arrayX

        },

        yAxis: {

            title: {

                text: null

            },

            plotLines: [{

                value: 0,

                width: 1,

                color: '#808080'

            }]

        },

        tooltip: {

            valueSuffix: ''

        },

        legend: {

            layout: 'vertical',

            align: 'right',

            verticalAlign: 'middle',

            borderWidth: 0,
            enabled:false

        },

        credits:{
            enabled:false // 禁用版权信息
        },
        series: [{

            name: statisticType,

            data: arrayY

        }]

    });

}


function getDate(){
    //debugger;
    var today = new Date();
    var date;
    date = (today.getFullYear()) +"-" + (today.getMonth() + 1 ) + "-" + today.getDate();
    return date;
}