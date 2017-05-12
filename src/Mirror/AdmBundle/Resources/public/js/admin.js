
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

/*资产列表*/
function propertyList(object){
    var info={rows:20,page:1};
    if(object != undefined){
        info=$.extend(info,object);
    }
    ajaxAction("get",'/api/property/back/list'+ passParam(info),"",true,function(data,textStatus){
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
                ajaxAction("get",'/api/property/back/list'+ passParam(info),"",true,function(data,textStatus){
                    var htmlTab = '';
                    $.each(data.list,function(index,value){
                        var deal=[];
                        $.each(value.dealPattern,function(index1,value){
                            if(value.ratio){
                                deal.push(value.name+value.ratio+"万");
                            }else{
                                deal.push(value.name+value.ratio);
                            }
                        });
                        if(value.status==0){
                            value.status="已发布";
                        }else if(value.status==1){
                            value.status="待竞标";
                        }else if(value.status==2){
                            value.status="洽谈中";
                        }else if(value.status==3){
                            value.status="已售让";
                        }else if(value.status==-1){
                            value.status="被删除";
                        }else if(value.status==-2){
                            value.status="被驳回";
                        }else if(value.status==-3){
                            value.status="被禁止";
                        }
                        if(0==value.sellType){
                            if(value.propertyPattern==0){
                                value.propertyPattern='房产';
                            }else if(value.propertyPattern==1){
                                value.propertyPattern='土地';
                            }else if(value.propertyPattern==2){
                                value.propertyPattern='设备';
                            }else if(value.propertyPattern==3){
                                value.propertyPattern='股权';
                            }else if(value.propertyPattern==4){
                                value.propertyPattern='车辆';
                            }else if(value.propertyPattern==5){
                                value.propertyPattern='专利/技术';
                            }else if(value.propertyPattern==6){
                                value.propertyPattern='版权';
                            }else if(value.propertyPattern==7){
                                value.propertyPattern='其他';
                            }

                        }else if(value.sellType==1){
                            if(value.propertyPattern==0){
                                value.propertyPattern='债权资产包';
                            }else if(value.propertyPattern==1){
                                value.propertyPattern='固定资产包';
                            }else if(value.propertyPattern==2){
                                value.propertyPattern='信用卡资产包';
                            }
                        }


                        htmlTab+='<tr><td>'+value.propertyId+'</td><td>'+value.code+'</td><td>'+value.propertyPattern+'</td><td>'+value.money+'</td><td>'+value.place+'</td><td>'+value.status+'</td><td><a href="/adm/info/propertyinfo/'+value.propertyId+'" class="infoColor">查看详情</a></td><td><a href="/adm/info/property/amend/'+value.propertyId+'" class="modificationColor">修改</a></td><td><a href="javascript:void(0)" class="deleteColor propertyDelete">删除</a></td></tr>';
                        $('tbody').html(htmlTab);
                        $('.propertyDelete').click(function(){
                            $('.deletePopup').show();
                            $('.popupConfirm').click(function(){
                                $('.deletePopup').hide();
                                var info={};
                                info.rewardId=value.rewardId;
                                ajaxAction("DELETE",'/api/property/back/delete',info,true,function(data,textStatus){
                                    rewardList();
                                },function(errno,errmsg){
                                    alert(errmsg);
                                });
                            });
                            $('.popupCancel').click(function(){
                                $('.deletePopup').hide();
                            });

                        });


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

/*悬赏列表*/

function rewardList(object){
    // 悬赏列表
    var info={rows:20,page:1};
    if(object != undefined){
        info=$.extend(info,object);
    }
    ajaxAction("get",'/api/reward/back/list'+ passParam(info),"",true,function(data,textStatus){
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
                ajaxAction("get",'/api/reward/back/list'+ passParam(info),"",true,function(data,textStatus){
                    htmlTab = '';
                    $.each(data.list,function(index,value){

                        if(value.status==0){
                            value.status="已发布";
                        }else if(value.status==1){
                            value.status="悬赏中";
                        }else if(value.status==2){
                            value.status="合作成功";
                        }else if(value.status==-1){
                            value.status="被删除";
                        }else if(value.status==-2){
                            value.status="被驳回";
                        }else if(value.status==-3){
                            value.status="被禁止";
                        }
                        if(value.type==0){
                            value.type="找人";
                        }else{
                            value.type="找财产";
                        }


                        htmlTab+='<tr><td>'+value.rewardId+'</td><td>'+value.code+'</td><td>'+value.type+'</td><td>'+value.money+'</td><td>'+value.place+'</td><td>'+value.status+'</td><td><a href="/adm/info/rewardinfo/'+value.rewardId+'" class="infoColor">查看详情</a></td><td><a href="/adm/info/reward/amend/'+value.rewardId+'" class="modificationColor">修改</a></td><td><a href="javascript:void(0)" class="deleteColor rewardDelete">删除</a></td></tr>';
                        //console.log(value.code)
                        $('tbody').html(htmlTab);
                        $('.rewardDelete').click(function(){
                            $('.deletePopup').show();
                            $('.popupConfirm').click(function(){
                                $('.deletePopup').hide();
                                var info={};
                                info.rewardId=value.rewardId;
                                ajaxAction("DELETE",'/api/reward/back/delete',info,true,function(data,textStatus){
                                    rewardList();
                                },function(errno,errmsg){
                                    alert(errmsg);
                                });
                            });
                            $('.popupCancel').click(function(){
                                $('.deletePopup').hide();
                            });

                        });

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
/*资产列表*/
function creditList(object){
    // 债权列表
    var info={rows:20,page:1};
    if(object != undefined){
        info=$.extend(info,object);
    }
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


                        htmlTab+='<tr><td>'+value.creditId+'</td><td>'+value.code+'</td><td>'+value.type+'</td><td>'+value.money+'</td><td>'+value.place+'</td><td>'+value.status+'</td><td><a href="/adm/info/creditinfo/'+value.creditId+'" class="infoColor">查看详情</a></td><td><a href="/adm/info/amend/'+value.creditId+'" class="modificationColor">修改</a></td><td><a href="javascript:void(0)" class="deleteColor creditDelete">删除</a></td></tr>';
                        $('tbody').html(htmlTab);
                        $('.creditDelete').click(function(){
                            $('.deletePopup').show();
                            $('.popupConfirm').click(function(){
                                $('.deletePopup').hide();
                                var info={};
                                info.creditId=value.creditId;
                                ajaxAction("DELETE",'/api/credit/back/delete',info,true,function(data,textStatus){
                                    creditList();
                                },function(errno,errmsg){
                                    alert(errmsg);
                                });
                            });
                            $('.popupCancel').click(function(){
                                $('.deletePopup').hide();
                            });

                        });

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



/*省级代理*/
function provinceList(object){
    // 用户列表
    var info={rows:20,page:1};
    if(object != undefined){
        info=$.extend(info,object);
    }
    //console.log(info);
    ajaxAction("get",'/api/admin/proxy/province/list'+ passParam(info),"",false,function(data,textStatus){
        var count=Math.ceil(data.total/20);
        var thead='';
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
                ajaxAction("get",'/api/admin/proxy/province/list'+ passParam(info),"",false,function(data,textStatus){
                    thead='<tr class="tableTit"><td>ID</td><td>名称</td><td>手机号</td><td>注册时间</td><td>上次登陆时间</td><td>所在地</td><td colspan="3" width="18%">操作</td></tr>';
                    htmlTab = '';
                    $.each(data.list,function(index,value){
                        htmlTab+='<tr><td class="_id" id="_id">'+value.id+'</td><td>'+value.company+'</td><td>'+value.telephone+'</td><td>'+value.createTime+'</td><td>'+value.lastTime+'</td><td>'+value.place+'</td><td><a href="/adm/user/index/'+value.id+'" class="infoColor">查看详情</a></td><td><a href="javascript:void(0)" class="stop deleteColor ">禁用</a></td> <td><a href="javascript:void(0)" class="resetPassword infoColor">重置密码</a></td>';
                    });
                    $('thead').html(thead);
                    $('tbody').html(htmlTab);

                },function(errno,errmsg){
                    alert(errmsg);
                });
            }
        });
    },function(errno,errmsg){
        alert(errmsg);
    });

}

/*市级代理*/
function cityList(){
    // 用户列表
    var info={};
    info.rows=20;
    info.page=1;
    ajaxAction("get",'/api/admin/proxy/city/list'+ passParam(info),"",false,function(data,textStatus){
        var count=Math.ceil(data.total/20);
        var thead='';
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
                ajaxAction("get",'/api/admin/proxy/city/list'+ passParam(info),"",false,function(data,textStatus){
                    thead='<tr class="tableTit"><td>ID</td><td>名称</td><td>手机号</td><td>注册时间</td><td>上次登陆时间</td><td>所在地</td><td colspan="3" width="18%">操作</td></tr>';
                    htmlTab = '';
                    $.each(data.list,function(index,value){

                        htmlTab+='<tr><td class="_id">'+value.id+'</td><td>'+value.company+'</td><td>'+value.telephone+'</td><td>'+value.createTime+'</td><td>'+value.lastTime+'</td><td>'+value.place+'</td><td><a href="/adm/user/city/'+value.id+'" class="infoColor">查看详情</a></td><td><a href="javascript:void(0)" class="stop deleteColor">禁用</a></td> <td><a href="javascript:void(0)" class="resetPassword infoColor">重置密码</a></td>';
                        //console.log(value.code)


                    });

                    $('thead').html(thead);
                    $('tbody').html(htmlTab);

                },function(errno,errmsg){
                    alert(errmsg);
                });
            }
        });
    },function(errno,errmsg){
        alert(errmsg);
    });

}

/*催收公司*/
function collectionList(){
    // 用户列表
    var info={};
    info.rows=20;
    info.page=1;
    ajaxAction("get",'/api/admin/company/list'+ passParam(info),"",false,function(data,textStatus){
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
                ajaxAction("get",'/api/admin/company/list'+ passParam(info),"",false,function(data,textStatus){
                    var thead='<tr class="tableTit"><td>ID</td><td>名称</td><td>手机号</td><td>注册时间</td><td>所在地</td><td colspan="3" width="18%">操作</td></tr>';
                    htmlTab = '';
                    $.each(data.list,function(index,value){

                        htmlTab+='<tr><td class="_id">'+value.id+'</td><td>'+value.companyName+'</td><td>'+value.managerTelephone+'</td><td>'+value.createTime+'</td><td>'+value.place+'</td><td><a href="/adm/user/company/'+value.id+'" class="infoColor">查看详情</a></td><td><a href="javascript:void(0)" class="stop deleteColor">禁用</a></td> <td><a href="javascript:void(0)" class="resetPassword infoColor">重置密码</a></td>';
                        //console.log(value.code)

                    });

                    $('thead').html(thead);
                    $('tbody').html(htmlTab);

                },function(errno,errmsg){
                    alert(errmsg);
                });

            }
        });


    },function(errno,errmsg){
        alert(errmsg);
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
        file.push(data.result.fileId);
        files=file.join(',');
        filePosition.val(files);
    },function(errno,errmsg){
        alert(errmsg);
    });
}

/*运营banner图片上传*/
function banner_upload(target,mark_target) {
    var url='';
    if(window.FileReader){
        var reader = new FileReader();
        reader.onloadend  = function(e) {
            var html="<img src='"+e.target.result+"' alt=''><input type='file' id='bannerUpload'>";
            mark_target.html(html);
        };
        reader.readAsDataURL(target.files[0]);
    }else{
        if (navigator.userAgent.indexOf("MSIE")>=1) { // IE
            url = target.value;
        }else{
            url = window.URL.createObjectURL(target.files.item(0));
        }
        var html="<img src='"+url+"' alt=''><input type='file' id='bannerUpload'>";
        mark_target.html(html);
    }
}