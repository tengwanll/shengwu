
/*****************************************************************/
//用户管理代理商详情
function detail(provinceId) {
    ajaxAction("get","/api/admin/proxy/detail/"+provinceId,"",true,function(data,textStatus){
        var certificatePhoto='';
        $.each(data.certificatePhoto,function (index,value) {
            certificatePhoto+='<li><img src="'+value+'" alt=""></li>';
        })
        var legalPersonIdPhoto='';
        $.each(data.legalPersonIdPhoto,function (index,value) {
            legalPersonIdPhoto+='<li><img src="'+value+'" alt=""></li>';
        })
        var accountOpeningLicensePhoto='';
        $.each(data.accountOpeningLicensePhoto,function (index,value) {
            accountOpeningLicensePhoto+='<li><img src="'+value+'" alt=""></li>';
        })
        var infoHtml='<dl><dt>业务类型：</dt><dd>'+data.companyType+'</dd><dt>企业全称：</dt><dd>'+data.companyName+'</dd><dt>企业所在地：</dt><dd>'+data.province+'-'+data.city+'</dd><dt>证件类型：</dt><dd>'+data.certificateType+'</dd><dt>借款时间社会信用代码：</dt><dd>'+data.socialCreditCode+'</dd><ul class="pengzheng"><dt>营业执照照片：</dt><div class="row"><div class="col-md-10 col-md-offset-2 cardUser"><ul>'+certificatePhoto+'</ul><div class="cls"></div></div></div></ul><dt>法定代表人姓名：</dt><dd>'+data.legalPersonName+'</dd><dt>法定代表人身份证号：</dt><dd>'+data.legalPersonId+'</dd><ul class="pengzheng"><dt>身份证照片：</dt><div class="row"><div class="col-md-10 col-md-offset-2 cardUser"><ul>'+legalPersonIdPhoto+'</ul><div class="cls"></div></div></div></ul><dt>开户许可证核准号：</dt><dd>'+data.accountOpeningLicense+'</dd><ul class="pengzheng"><dt>开户许可证照片：</dt><div class="row"><div class="col-md-10 col-md-offset-2 cardUser"><ul>'+accountOpeningLicensePhoto+'</ul><div class="cls"></div></div></div></ul></dl><div class="cls"></div>';
        $('.infoUserCon').html(infoHtml);
    },function(errno,errmsg){
        alert(errmsg);
    });
}

//催收公司详情
function company_detail(companyId) {
    ajaxAction("get","/api/admin/company/"+companyId,"",true,function(data,textStatus){
        var certificatePhoto='';
        $.each(data.certificatePhoto,function (index,value) {
            certificatePhoto+='<li><img src="'+value+'" alt=""></li>';
        })
        var legalPersonIdPhoto='';
        $.each(data.legalPersonIdPhoto,function (index,value) {
            legalPersonIdPhoto+='<li><img src="'+value+'" alt=""></li>';
        })
        var accountOpeningLicensePhoto='';
        $.each(data.accountOpeningLicensePhoto,function (index,value) {
            accountOpeningLicensePhoto+='<li><img src="'+value+'" alt=""></li>';
        })
        var infoHtml='<dl><dt>业务类型：</dt><dd>'+data.companyType+'</dd><dt>企业全称：</dt><dd>'+data.companyName+'</dd><dt>企业所在地：</dt><dd>'+data.place+'</dd><dt>证件类型：</dt><dd>'+data.certificateType+'</dd><dt>借款时间社会信用代码：</dt><dd>'+data.socialCreditCode+'</dd><ul class="pengzheng"><dt>营业执照照片：</dt><div class="row"><div class="col-md-10 col-md-offset-2 cardUser"><ul>'+certificatePhoto+'</ul><div class="cls"></div></div></div></ul><dt>法定代表人姓名：</dt><dd>'+data.legalPersonName+'</dd><dt>法定代表人身份证号：</dt><dd>'+data.legalPersonId+'</dd><ul class="pengzheng"><dt>身份证照片：</dt><div class="row"><div class="col-md-10 col-md-offset-2 cardUser"><ul>'+legalPersonIdPhoto+'</ul><div class="cls"></div></div></div></ul><dt>开户许可证核准号：</dt><dd>'+data.accountOpeningLicense+'</dd><ul class="pengzheng"><dt>开户许可证照片：</dt><div class="row"><div class="col-md-10 col-md-offset-2 cardUser"><ul>'+accountOpeningLicensePhoto+'</ul><div class="cls"></div></div></div></ul></dl><div class="cls"></div>';
        $('.infoUserCon').html(infoHtml);
    },function(errno,errmsg){
        alert(errmsg);
    });
}

//派发给催收公司的债权列表
function companyCreditList(companyId) {
    var info={};
    info.rows=20;
    info.page=1;
    ajaxAction("get","/api/admin/company/credit/"+companyId+passParam(info),"",true,function(data,textStatus){
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
                ajaxAction("get","/api/admin/company/credit/"+companyId+passParam(info),"",true,function(data,textStatus){
                    var thead=' <tr class="tableCreditInfo"><td>ID</td><td>编号</td><td>类型</td><td>金额（万元）</td><td>所在地</td><td>状态</td><td>操作</td></tr>';
                    var tbody='';
                    $.each(data.list,function (index,value) {
                        if(value.status==-3){
                            value.status='被禁止';
                        }else if(data.status==-2){
                            value.status='被驳回';
                        }else if(data.status==-1){
                            value.status='被删除';
                        }else if (data.status == 0) {
                            value.status = "待审核";
                        } else if (data.status == 1) {
                            value.status = "处置中";
                        } else if (data.status == 2) {
                            value.status = "已完成";
                        }
                        if(value.type==0){
                            value.type="个人债权";
                        }else if(value.type==1){
                            value.type="企业商账";
                        }else if(value.type==2){
                            value.type="机构信贷";
                        }
                        tbody += ' <tr><td>' + value.creditId + '</td><td>'+value.code+'</td><td>' + value.type + '</td><td>'+value.money+'</td><td>'+value.place+'</td><td>'+value.status+'</td><td><a href="/adm/user/credit/info/'+value.creditId+'" class="infoColor">查看详情</a></td></tr>';
                    })
                    $('.userTableHead').html(thead);
                    $('.userTableBody').html(tbody);
                },function(errno,errmsg){
                    alert(errmsg);
                });
            }
        });
    },function(errno,errmsg){
        alert(errmsg);
    });
}

function companyUserList(companyId) {
    var info={};
    info.rows=20;
    info.page=1;
    ajaxAction("get",'/api/admin/company/user/'+companyId+ passParam(info),"",false,function(data,textStatus){
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
                ajaxAction("get",'/api/admin/company/user/'+companyId+ passParam(info),"",false,function(data,textStatus){
                    var thead='<tr class="tableTit"><td>ID</td><td>手机号</td><td>注册时间</td><td>上次登陆时间</td><td>所在地</td><td colspan="3" width="18%">操作</td></tr>';
                    htmlTab = '';
                    $.each(data.list,function(index,value){

                        htmlTab+='<tr><td>'+value.id+'</td><td>'+value.telephone+'</td><td>'+value.createTime+'</td><td>'+value.lastTime+'</td><td>'+value.place+'</td><td><a href="/adm/user/info/'+value.id+'" class="infoColor">查看详情</a></td>';
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

//用户管理代理商中的债权
function userCreditList(adminId) {
    var info={};
    info.rows=20;
    info.page=1;
    ajaxAction("get","/api/admin/user/proxy/"+adminId+passParam(info),"",true,function(data,textStatus){
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
                ajaxAction("get","/api/admin/user/proxy/"+adminId+passParam(info),"",true,function(data,textStatus){
                    var thead=' <tr class="tableCreditInfo"><td>ID</td><td>编号</td><td>类型</td><td>金额（万元）</td><td>所在地</td><td>状态</td><td>操作</td></tr>';
                    var tbody='';
                    $.each(data.list,function (index,value) {
                        if(value.status==-3){
                            value.status='被禁止';
                        }else if(data.status==-2){
                            value.status='被驳回';
                        }else if(data.status==-1){
                            value.status='被删除';
                        }else if (data.status == 0) {
                            value.status = "待审核";
                        } else if (data.status == 1) {
                            value.status = "处置中";
                        } else if (data.status == 2) {
                            value.status = "已完成";
                        }
                        if(value.type==0){
                            value.type="个人债权";
                        }else if(value.type==1){
                            value.type="企业商账";
                        }else if(value.type==2){
                            value.type="机构信贷";
                        }
                        tbody += ' <tr><td>' + value.creditId + '</td><td>'+value.code+'</td><td>' + value.type + '</td><td>'+value.money+'</td><td>'+value.place+'</td><td>'+value.status+'</td><td><a href="/adm/user/credit/info/'+value.creditId+'" class="infoColor">查看详情</a></td></tr>';
                    })
                    $('.userTableHead').html(thead);
                    $('.userTableBody').html(tbody);
                },function(errno,errmsg){
                    alert(errmsg);
                });
            }
        });
    },function(errno,errmsg){
        alert(errmsg);
    });
}