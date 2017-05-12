$(function(){

    propertyinfo(propertyId());


    $('.propertyDelete').click(function(){
        $('.deletePopup').show();
        $('.popupConfirm').click(function(){
            $('.deletePopup').hide();
            var info={};
            info.propertyId=propertyId();
            ajaxAction("DELETE",'/api/property/back/delete',info,true,function(data,textStatus){
                window.location.href="/adm/info/creditor";
            },function(errno,errmsg){
                alert(errmsg);
            });
        });
        $('.popupCancel').click(function(){
            $('.deletePopup').hide();
        });

    });



});

function propertyinfo(id) {

    ajaxAction("get", "/api/property/back/"+id, "", true, function (data, textStatus) {
        //console.log(data.list[0].code);
        var htmlPic = '<div class="container_image"><a href="javascript:void(0)" tip="0" class="i_btn prev_L"></a><a href="javascript:void(0)" tip="1" class="i_btn next_R"></a><ul class="slide_img"></li>';
        var deal = [];
        $.each(data.dealPattern, function (index1, value) {
            deal.ratio=value.ratio;
            deal.name=value.name;

        });
        $.each(data.evidencePhoto, function (index, el) {
            htmlPic += '<li><a href="javascript:void(0)"><img src="' + el + '" /></a></li>';
        });
        htmlPic += '</ul></div>';
        $('.slide_img').html(htmlPic);
        var newopt = {
            w2: "200",//小图宽度
            h2: "150"//小图高度
        };
        //$(".container_image").i_slide(newopt);
        //$('.slide_img li').eq(0).addClass('on');

        $('#code').text(data.code);
        $('#ID').text('ID:' + data.propertyId);

        if(data.status==-3){
            data.status='被禁止';
        }else if(data.status==-2){
            data.status='被驳回';
        }else if(data.status==-1){
            data.status='被删除';
        }else if (data.status == 0) {
            data.status = "待审核";
        } else if (data.status == 1) {
            data.status = "处置中";
        } else if (data.status == 2) {
            data.status = "已完成";
        }
        $('.status').text(data.status);
        if (data.type == 0) {
            data.type='个人';
        } else if (data.type == 1) {
            data.type='企业';
        } else if (data.type == 2) {
            data.type='机构';
        }
        $('.creMoney').text(data.money);
        $('.creReturn').text(deal.ratio);
        if (data.isJudgment == 0) {
            data.isJudgment='未判决'
        } else if (data.isJudgment == 1) {
            data.isJudgment='已判决';
        }

        if (data.isLawsuit == 0) {
            data.isLawsuit='未诉讼';
        } else if (data.isLawsuit == 1) {
            data.isLawsuit='已诉讼';
        }
        if (data.isUrge == 0) {
            data.isUrge='未催收';
        } else if (data.isUrge == 1) {
            data.isUrge='已催收';
        }

//            $('.timeCon').text('债权时间：'+data.+'个月');
        $('.disposeCon').text(deal.name);

        if (data.isAssure == 0) {
            data.isAssure='没有担保';
        } else if (data.isAssure == 1) {
            data.isAssure='有担保';
        }

        if(data.professionalAttribute.isPledge==0){
            data.professionalAttribute.isPledge='否';
        }else if(data.professionalAttribute.isPledge==1){
            data.professionalAttribute.isPledge='是';
        }

        if(data.professionalAttribute.getPattern==0){
            data.professionalAttribute.getPattern='出让';
        }else if(data.professionalAttribute.getPattern==1){
            data.professionalAttribute.getPattern='划拨';
        }

        if (data.sellPattern == 0) {
            if (data.propertyPattern == 1) {
                var htmlL = '<dt>售让编号：</dt><dd>' + data.code + '</dd><dt>售让类型：</dt><dd>土地</dd><dt>售让金额：</dt><dd>' + data.money + '</dd><ul class="disposal"><dt class="dealPattern">期望处置方式：</dt><li><span class="dealName"></span><p class="dealCon">' + deal.name + '</p></li></ul> <dt>是否抵押：</dt><dd >' + data.professionalAttribute.isPledge + '</dd><dt>土地取得方式：</dt><dd >' + data.professionalAttribute.getPattern + '</dd> <dt>土地所在地：</dt> <dd>' + data.professionalAttribute.landProvince + data.professionalAttribute.landCity + '</dd><dt>土地使用年限：</dt> <dd >' + data.professionalAttribute.landEndTime + '年</dd><dt>土地使用面积：</dt><dd >' + data.professionalAttribute.isUseful + '</dd><dt>是否诉讼：</dt> <dd>' + data.isLawsuit + '</dd><dt>联系人电话：</dt><dd>' + data.contactTelephone + '</dd><dt>联系人姓名：</dt><dd >' + data.contactName + '</dd><dt>联系人地址：</dt><dd >' + data.country + data.province + data.city + '</dd>';

            } else if (data.propertyPattern == 3) {
                htmlL = '<dt>售让编号：</dt><dd>' + data.code + '</dd><dt>售让类型：</dt><dd>股权</dd><dt>股权名称：</dt><dd>' + data.professionalAttribute.stockName + '</dd><dt>股权价值：</dt><dd>' + data.money + '</dd> <ul class="disposal"><dt class="dealPattern">期望处置方式：</dt><li><span class="dealName"></span><p class="dealCon">' + deal.name + '</p></li></ul><dt>是否诉讼：</dt> <dd>' + data.isLawsuit + '</dd><dt>联系人电话：</dt><dd>' + data.contactTelephone + '</dd><dt>联系人姓名：</dt><dd >' + data.contactName + '</dd><dt>联系人地址：</dt><dd >' + data.country + data.province + data.city + '</dd>';
            } else if (data.propertyPattern == 5) {
                htmlL = '<dt>售让编号：</dt><dd>' + data.code + '</dd><dt>售让类型：</dt><dd>专利</dd> <dt>专利名称：</dt><dd>' + data.professionalAttribute.patentName + '</dd><dt>专利编号：</dt><dd>' + data.professionalAttribute.patentNumber + '</dd><dt>专利从属：</dt><dd>' + data.professionalAttribute.patentOnwer + '(人/单位)</dd><ul class="disposal"><dt class="dealPattern">期望处置方式：</dt><li><span class="dealName"></span><p class="dealCon">' + deal.name + '</p></li></ul><dt>是否诉讼：</dt><dd>' + data.isLawsuit + '</dd><dt>是否判决：</dt><dd>' + data.isJudgment + '</dd><dt>联系人电话：</dt><dd>' + data.contactTelephone + '</dd><dt>联系人姓名：</dt><dd >' + data.contactName + '</dd><dt>联系人地址：</dt><dd >' + data.country + data.province + data.city + '</dd>';
            } else if (data.propertyPattern == 0) {

                if (0 == data.professionalAttribute.houseCharacter) {
                    data.professionalAttribute.houseCharacter = '商用'
                } else {
                    data.professionalAttribute.houseCharacter = '住宅'
                }
                if (0 == data.professionalAttribute.houseIsRight) {
                    data.professionalAttribute.houseIsRight = '无'
                } else {
                    data.professionalAttribute.houseIsRight = '有'
                }
                if (0 == data.professionalAttribute.houseIsPledge) {
                    data.professionalAttribute.houseIsPledge = '否'
                } else {
                    data.professionalAttribute.houseIsPledge = '是'
                }
                htmlL = '<dt>售让编号：</dt><dd>' + data.code + '</dd><dt>售让类型：</dt><dd>房产</dd> <dt>房产金额：</dt><dd>' + data.money + '万元</dd><dt>房产性质：</dt><dd>' + data.professionalAttribute.houseCharacter + '</dd><dt>房产所在地：</dt><dd>' + data.professionalAttribute.houseProvince + data.professionalAttribute.houseCity + '</dd><dt>使用面积：</dt><dd>' + data.professionalAttribute.houseArea + '平米</dd><dt>房产使用年限：</dt><dd>' + data.professionalAttribute.houseUseTime + '</dd><dt>房产证：</dt><dd>' + data.professionalAttribute.houseIsRight + '</dd><ul class="disposal"><dt class="dealPattern">期望处置方式：</dt><li><span class="dealName"></span><p class="dealCon">' + deal.name + '</p></li></ul><dt>是否诉讼：</dt><dd>' + data.isLawsuit + '</dd><dt>是否抵押：</dt><dd>' + data.professionalAttribute.houseIsPledge + '</dd><dt>是否判决：</dt><dd>' + data.isJudgment + '</dd> <dt>联系人电话：</dt><dd>' + data.contactTelephone + '</dd><dt>联系人姓名：</dt><dd >' + data.contactName + '</dd><dt>联系人地址：</dt><dd >' + data.country + data.province + data.city + '</dd>';
            } else if (data.propertyPattern == 2) {
                htmlL = '<dt>售让编号：</dt><dd>' + data.code + '</dd><dt>售让类型：</dt><dd>设备</dd><dt>设备名称：</dt><dd>' + data.professionalAttribute.equipmentName + '</dd><dt>设备价值：</dt><dd>' + data.money + '万元</dd><dt>设备所在地：</dt><dd>' + data.professionalAttribute.equipmentProvince + data.professionalAttribute.equipmentCity + '</dd><dt>发票：</dt><dd>' + data.professionalAttribute.equipmentIsTicket + '</dd><ul class="disposal"><dt class="dealPattern">期望处置方式：</dt><li><span class="dealName"></span><p class="dealCon">' + deal.name + '</p></li></ul><dt>是否诉讼：</dt><dd>' + data.isLawsuit + '</dd><dt>是否抵押：</dt><dd>' + data.professionalAttribute.equipmentIsPledge + '</dd><dt>是否判决：</dt><dd>' + data.isJudgment + '</dd> <dt>是否催收：</dt><dd>' + data.isUrge + '</dd><dt>联系人电话：</dt><dd>' + data.contactTelephone + '</dd><dt>联系人姓名：</dt><dd >' + data.contactName + '</dd><dt>联系人地址：</dt> <dd >' + data.country + data.province + data.city + '</dd>';
            } else if (data.propertyPattern == 6) {
                htmlL = '<dt>售让编号：</dt><dd>' + data.code + '</dd><dt>售让类型：</dt><dd>版权</dd><dt>版权名称：</dt><dd>' + data.professionalAttribute.coperightName + '</dd><dt>版权价值：</dt><dd>' + data.money + '万元</dd><dt>版权从属：</dt><dd>' + data.professionalAttribute.copyrightUserName + '</dd><ul class="disposal"><dt class="dealPattern">期望处置方式：</dt><li><span class="dealName"></span><p class="dealCon">' + deal.name + '</p></li></ul><dt>是否诉讼：</dt><dd>' + data.isLawsuit + '</dd><dt>是否抵押：</dt><dd>' + data.ispledge + '</dd><dt>是否判决：</dt><dd>' + data.isJudgment + '</dd><dt>是否催收：</dt> <dd>' + data.isUrge + '</dd><dt>联系人电话：</dt><dd>' + data.contactTelephone + '</dd><dt>联系人姓名：</dt><dd >' + data.contactName + '</dd><dt>联系人地址：</dt><dd >' + data.country + data.province + data.city + '</dd>';

            } else if (data.propertyPattern == 4) {
                htmlL = '<dt>售让编号：</dt><dd>' + data.code + '</dd><dt>售让类型：</dt><dd>车辆</dd><dt>资产名称：</dt><dd>' + data.professionalAttribute.carName + '</dd><dt>资产价值：</dt><dd>' + data.money + '</dd><dt>车辆年限：</dt><dd>' + data.professionalAttribute.carEndTime + '</dd><ul class="disposal"><dt class="dealPattern">期望处置方式：</dt><li><span class="dealName"></span><p class="dealCon">' + deal.name + '</p></li></ul><dt>是否诉讼：</dt><dd>' + data.isLawsuit + '</dd><dt>是否判决：</dt><dd>' + data.isJudgment + '</dd><dt>是否抵押：</dt><dd>' + data.ispledge + '</dd><dt>联系人电话：</dt><dd>' + data.contactTelephone + '</dd><dt>联系人姓名：</dt><dd >' + data.contactName + '</dd><dt>联系人地址：</dt> <dd >' + data.country + data.province + data.city + '</dd>';

            } else if (data.propertyPattern == 7) {

                htmlL = '<dt>售让编号：</dt><dd>' + data.code + '</dd><dt>售让类型：</dt><dd>其他</dd><dt>资产名称：</dt><dd>' + data.professionalAttribute.otherName + '</dd><dt>资产价值：</dt><dd>' + data.money + '万元</dd><dt>资产从属：</dt><dd>' + data.professionalAttribute.otherOwner + '</dd><dt>资产年限：</dt><dd>' + data.professionalAttribute.otherEndTime + '年</dd><ul class="disposal"><dt class="dealPattern">期望处置方式：</dt> <li><span class="dealName"></span><p class="dealCon">' + deal.name + '</p></li></ul><dt>是否诉讼：</dt> <dd>' + data.isLawsuit + '</dd><dt>是否判决：</dt><dd>' + data.isJudgment + '</dd><dt>联系人电话：</dt><dd>' + data.contactTelephone + '</dd> <dt>联系人姓名：</dt><dd >' + data.contactName + '</dd><dt>联系人地址：</dt><dd >' + data.country + data.province + data.city + '</dd>';
            }

        } else if (data.sellPattern == 1) {
            if (data.propertyPattern == 0) {
                htmlL = '<dt>售让编号：</dt><dd>' + data.code + '</dd><dt>售让类型：</dt> <dd>债权资产包</dd><dt>资产包所在区域：</dt><dd>' + data.packageCountry + data.packageProvince + data.packageCity + '</dd><dt>资产包价值：</dt><dd>' + data.money + '万元</dd><dt>债权资产处置方式：</dt> <dd>' + deal.name + '</dd><dt>联系人电话：</dt><dd>' + data.contactTelephone + '</dd> <dt>联系人姓名：</dt><dd >' + data.contactName + '</dd><dt>联系人地址：</dt><dd >' + data.country + data.province + data.city + '</dd>';
            } else if (data.propertyPattern == 1) {
                $('.cre_person').text('固定资产包');
                $('.assetMoney').text(data.money);
                $('.assetArea').text(deal.name);
                $('.assetTerm').text(data.professionalAttribute.packageCountry + data.professionalAttribute.packageProvince + data.professionalAttribute.packageCity);
                $('.moneyTitle').text('固定资产价值');
                $('.termTitle').text('固定资产所在区域');
                $('.areaTitle').text('固定资产处置方式');

                htmlL = '<dt>售让编号：</dt><dd>' + data.code + '</dd><dt>售让类型：</dt> <dd>固定资产包</dd><dt>资产包所在区域：</dt><dd>' + professionalAttribute.packageCountry + data.professionalAttribute.packageProvince + data.professionalAttribute.packageCity + '</dd> <dt>资产包价值：</dt><dd>' + data.money + '万元</dd><dt>债权资产处置方式：</dt><dd>' + deal.name + '</dd> <dt>联系人电话：</dt><dd>' + data.contactTelephone + '</dd><dt>联系人姓名：</dt><dd >' + data.contactName + '</dd><dt>联系人地址：</dt><dd >' + data.country + data.province + data.city + '</dd>';
            } else if (data.propertyPattern == 2) {
                htmlL = '<dt>售让编号：</dt><dd>' + data.code + '</dd><dt>售让类型：</dt> <dd>信用卡资产包</dd><dt>资产包所在区域：</dt><dd>' + data.packageCountry + data.packageProvince + data.packageCity + '</dd><dt>资产包价值：</dt><dd>' + data.money + '万元</dd><dt>债权资产处置方式：</dt> <dd>' + deal.name + '</dd><dt>联系人电话：</dt><dd>' + data.contactTelephone + '</dd><dt>联系人姓名：</dt><dd >' + data.contactName + '</dd><dt>联系人地址：</dt><dd >' + data.country + data.province + data.city + '</dd>';
            }
        }

        $('.zt').html(htmlL);

        $('.description p').text(data.description);

        $('.send').attr('href', "/adm/info/send/" + data.creditId);
    }, function (errno, errmsg) {
        alert(errmsg);
    });
}


