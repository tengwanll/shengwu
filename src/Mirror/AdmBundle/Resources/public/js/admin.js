
/*导航切换*/
function adminTabNav(tabName){
    tabName.addClass('on').parent().siblings('li').find('a').removeClass('on');
}

function tabNav(tabName){
    //tabName.addClass('on').siblings('li').removeClass('on');
    tabName.find('.auditName').addClass('on').parent().siblings('li').find('.auditName').removeClass('on');
}

function tabNavTitle(tabName){
    tabName.addClass('on').siblings('li').removeClass('on');
}

/*判断单选框是否被选中改变颜色*/
function isRadioChecked(string){
    $('input:radio[name='+string+']:checked').siblings('label').css({
        'background-color':'#ff8400',
        'color':'#fff'
    });
}
/*检测复选框是否选中及颜色改变*/
function checkboxChangeColor(checkboxColor,checkboxName){
    if($('input[name='+checkboxName+']').is(':checked')){
        checkboxColor.css({
            'background-color':'#ff8400',
            'color':'#fff'
        });
    }else{
        checkboxColor.css({
            'background-color':'#eeeeee',
            'color':'#333333'
        });
    }
}

/*筛选框点击颜色变换*/
function clickCheckbox(clickCheckbox,checkboxColor,checkboxName){
    clickCheckbox.click(function(){
        if($('input[name='+checkboxName+']').is(':checked')){
            checkboxColor.css({
                'background-color':'#eeeeee',
                'color':'#333333'
            });
        }else{
            checkboxColor.css({
                'background-color':'#ff8400',
                'color':'#fff'
            });
        }
    });
}

/*选择单选框值*/
function RadioCheckedVal(string){
    var Val=$('input:radio[name='+string+']:checked').siblings('label').text();
    return Val;
}

/*选择单选框*/
function selectRadioChecked(radio){
    radio.on('click',function(){
        radio.css({'background-color':'#eeeeee',
            'color':'#333333'});
        $(this).css({'background-color':'#ff8400',
            'color':'#fff'});

    });
}

var passwordPatten = new RegExp(/^(?=.*[a-zA-Z])(?=.*[0-7])[a-zA-Z0-9]{6,20}$/);
function checkPassword(value,msg){
    if(!checkNull(value,msg)) return false;
    if(!passwordPatten.test(value)){
        myAlert('密码必须由6-20位字母和数字组成！！');
        return false;
    }
    if(value.length < 6 || value.length > 20){
        myAlert('密码必须由6-20位字母和数字组成！');
        return false;
    }

    return true;
}

function checkPasswordAffirm(value,checkValue,msg,mmsg){
    if(!checkNull(value,msg)) return false;
    if(!checkNull(checkValue,mmsg)) return false;
    if(!passwordPatten.test(value)){
        myAlert('密码必须由6-20位字母和数字组成！！');
        return false;
    }
    if(value.length < 6 || value.length > 20){
        myAlert('密码必须由6-20位字母和数字组成！');
        return false;
    }
    if(value != checkValue){
        myAlert('两次密码输入不同，请重新输入！');
        return false;
    }
    return true;
}

/*手机号校验*/
function checkPhone(value,msg,bool){
    var phoneReg = new RegExp(/^1[34578]\d{9}$/);
    var flag = true;
    if(bool){
        flag = checkNull(value,msg);
    }
    if(flag){
        if(!phoneReg.test(value)){
            myAlert('手机号码不规范，请重新输入!');
            flag = false;
        }
    }
    return flag;
}

/*空校验*/
function checkNull(value,msg,callBack){
    if(!$.trim(value)){
        if(callBack){
            callBack(value,msg);
            return false;
        }
        if(msg){
            myAlert(msg);
            return false;
        }
        return false;
    }
    return true;
}

/*获取手机验证码*/
function acquireCode(clickButton,telephone){
    clickButton.click(function(){
        if(!checkPhone(telephone.val(),'手机号不可为空！',true)){
            return false;
        }
        var phone=telephone.val();
        settime(this);
        ajaxAction("get","/api/sms/code/"+phone,"",true,function(data,textStatus){

        },function(errno,errmsg) {
            alert(errmsg);
        });
    });
}

/*图片上传*/
var file=[];
function pubImgUpload(formTagName,filePosition){
    imgUpload(formTagName,'/api/file/upload','post',function(){},function(data,textStatus){
        filePosition.val(data.result.fileId);
    },function(errno,errmsg){
        alert(errmsg);
    });
}

/*excel导入*/
function fileUpload(formTagName){
    imgUpload(formTagName,'/api/file/import','post',function(){},function(data,textStatus){
        // location.href='/adm/car';
    },function(errno,errmsg){
        alert(errmsg);
    });
}

/*图片预览*/
function banner_upload(target,mark_target) {
    var url='';
    if(window.FileReader){
        var reader = new FileReader();
        reader.onloadend  = function(e) {
            mark_target.find('img').attr('src',e.target.result);
        };
        reader.readAsDataURL(target.files[0]);
    }else{
        if (navigator.userAgent.indexOf("MSIE")>=1) { // IE
            url = target.value;
        }else{
            url = window.URL.createObjectURL(target.files.item(0));
        }
        mark_target.find('img').attr('src',url);
    }
}