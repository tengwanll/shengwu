$(function(){

    selectprovince('selectP','selectC');
    $('select[name=selectP]').change(function(){
        selectcityarea('select_addr','selectP','selectC');
    });

    reportList();
    $('.freedomName').text('手机：');
    $('.telephoneAudit').show();
    $('.rejectList').hide();
    $('.auditType').hide();
    $('.addressCity').hide();
    $('.address').show();
    $('.typeTabTit ul li').click(function(){
        tabNav($(this));
        if($(this).index()==0){
            $('.freedomName').text('手机：');
            $('.telephoneAudit').show();
            $('.rejectList').hide();
            $('.auditType').hide();
            $('.addressCity').hide();
            $('.address').show();
            var info={};
            provincecheckList(info);
            $('.search').unbind('click').bind('click',function (event) {
                info={};
                if($('input[name=name]')){
                    info.telephone=$('input[name=name]').val();
                }
                if($('select[name=selectP] option:selected').val()!='0'){
                    info.province=$('select[name=selectP] option:selected').text();
                }


                provincecheckList(info);
                event.stopPropagation();
            });
        }else if($(this).index()==1){
            $('.freedomName').text('名称：');
            $('.telephoneAudit').show();
            $('.rejectList').hide();
            $('.auditType').hide();
            $('.addressCity').show();
            $('.address').show();
            var info={};
            citycheckList(info);


            $('.search').unbind('click').bind('click',function (event) {
                info={};
                if($('input[name=name]')){
                    info.companyName=$('input[name=name]').val();
                }
                if($('select[name=selectP] option:selected').val()!='0'){
                    info.province=$('select[name=selectP] option:selected').text();
                }
                if($('select[name=selectC] option:selected').val()!='0'){
                    info.city=$('select[name=selectC] option:selected').text();
                }

                citycheckList(info);
                event.stopPropagation();
            });
        }else if($(this).index()==2){
            $('.freedomName').text('名称：');
            $('.telephoneAudit').show();
            $('.rejectList').hide();
            $('.auditType').hide();
            $('.addressCity').show();
            $('.address').show();
            var info={};
            companycheckList(info);
            $('.search').unbind('click').bind('click',function (event) {
                info={};
                if($('input[name=name]')){
                    info.companyName=$('input[name=name]').val();
                }
                if($('select[name=selectP] option:selected').val()!='0'){
                    info.province=$('select[name=selectP] option:selected').text();
                }
                if($('select[name=selectC] option:selected').val()!='0'){
                    info.city=$('select[name=selectC] option:selected').text();
                }

                companycheckList(info);
            });
        }else if($(this).index()==3){
            $('.freedomName').text('名称：');
            $('.telephoneAudit').show();
            $('.rejectList').hide();
            $('.auditType').show();
            $('.addressCity').show();
            $('.address').show();
            creditcheckList();
        }else if($(this).index()==4){
            $('.freedomName').text('编号：');
            $('.telephoneAudit').show();
            $('.rejectList').hide();
            $('.auditType').show();
            $('.addressCity').show();
            $('.address').show();

            var info={};
            creditcheckList(info);
            $('.xinxi').change(function(){
               var select= $('.xinxi>option:selected').val();
               if(select=='资产售让'){
                   propertycheckList(info);
               }else if(select=='信息悬赏'){
                   rewardcheckList(info);
               }else{
                   creditcheckList(info);
               }
            });

            $('.search').unbind('click').bind('click',function (event) {
                info={};
                if($('input[name=name]')){
                    info.code=$('input[name=name]').val();
                }
                if($('select[name=selectP] option:selected').val()!='0'){
                    info.province=$('select[name=selectP] option:selected').text();
                }
                if($('select[name=selectC] option:selected').val()!='0'){
                    info.city=$('select[name=selectC] option:selected').text();
                }
                var select= $('.xinxi>option:selected').val();
                if(select=='资产售让'){
                    propertycheckList(info);
                }else if(select=='信息悬赏'){
                    rewardcheckList(info);
                }else{
                    creditcheckList(info);
                }

            });
        }else if($(this).index()==5){
            $('.freedomName').text('手机：');
            $('.telephoneAudit').show();
            $('.rejectList').hide();
            $('.auditType').hide();
            $('.addressCity').hide();
            $('.address').hide();
            var info={};
            usercheckList(info);
            $('.search').unbind('click').bind('click',function (event) {
                info={};
                if($('input[name=name]')){
                    info.telephone=$('input[name=name]').val();
                }
                usercheckList(info);
            });
        }else if($(this).index()==6){
            $('.freedomName').text('类型：');
            $('.telephoneAudit').hide();
            $('.rejectList').show();
            $('.auditType').hide();
            $('.addressCity').hide();
            $('.address').hide();
            info={};
            rejecChecktList(info);
            $('.search').unbind('click').bind('click',function (event) {
                info={};
                if($('select[name=rejectName] option:selected').val()!=null){
                    info.type=$('select[name=rejectName] option:selected').val();
                }
                rejecChecktList(info);
            });


        }



    });
    var info={};
    provincecheckList(info);


    function usercheckList(info){
        // 用户审核列表

        info.status=0;
        info.rows=20;
        info.page=1;
        ajaxAction("get",'/api/user'+ passParam(info),"",true,function(data,textStatus){
            var count=Math.ceil(data.list.length/20);
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
                    ajaxAction("get",'/api/user'+ passParam(info),"",true,function(data,textStatus){
                        var tabTitle='<td>ID</td><td>手机号</td><td>姓名</td><td>身份证号</td><td colspan="3" width="18%">操作</td>';
                        htmlTab = '';

                        $.each(data.list,function(index,value){

                            htmlTab+='<tr><td class="id">'+value.userId+'</td><td>'+value.telephone+'</td><td>'+value.name+'</td><td>'+value.idCard+'</td><td><a  href="/adm/audit/info/people/'+value.userId+'" class="infoColor">查看详情</a></td><td class="pass"><a class="modificationColor ">通过</a></td><td><a href="javascritp:void(0)" class="deleteColor reject">驳回</a></td></tr>';

                        });


                        $('tr.tableTit').html(tabTitle);
                        $('tbody').html(htmlTab);

                        $('.pass').click(function(){
                            var info={};
                            info.userId=$(this).siblings('.id').text();
                            info.status=1;
                            ajaxAction("put",'/api/user/check',info,true,function(data,textStatus){alert('审核通过成功');},function(errno,errmsg){
                                alert('审核失败');

                            });
                            return false;
                        });

                        $('.reject').click(function(){
                            $('.rejectReasonPopup').show();
                            var aaaid=$(this).parent().siblings('.id').text();
                            $('.ReasonConfirm').attr('value',aaaid);
                            return false;
                        });

                        $('.ReasonCancel').click(function(){
                            $('.rejectReasonPopup').hide();
                            return false;
                        });
                        $('.ReasonConfirm').unbind('click').click(function(){
                            if($('.rejectReasonContent').val()==''){
                                alert('驳回理由不可为空！');
                                return false;
                            }
                            info={};
                            info.status=-2;
                            info.userId=$('.ReasonConfirm').val();
                            console.log(info.userId);
                            info.refuseMessage=$('.rejectReasonContent').val();
                            ajaxAction("put",'/api/user/check',info,true,function(data,textStatus){alert('成功驳回');
                            },function(errno,errmsg){
                                alert('驳回失败');

                            });
                            $('.rejectReasonPopup').hide();
                            $('.rejectPopup').hide();
                            return false;
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

    var info={};
    function creditcheckList(info){
        // 债权审核列表
        info.status=0;
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

                        var tabTitle='<td>ID</td><td>编号</td><td>类型</td><td>金额（万元）</td><td>所在地</td><td colspan="3" width="18%">操作</td>';
                        htmlTab = '';
                        $.each(data.list,function(index,value){
                            if(value.type==0){
                                value.type='个人债权';
                            }else if(value.type==1){
                                value.type='企业商帐';
                            }else if(value.type==2){
                                value.type='机构信贷';
                            }

                            htmlTab+='<tr><td class="id">'+value.creditId+'</td><td>'+value.code+'</td><td>'+value.type+'</td><td>'+value.money+'</td><td>'+value.place+'</td><td><a href="/adm/audit/info/credit/'+value.creditId+'" class="infoColor">查看详情</a></td><td class="pass"><a href="javascript:void(0)" class="modificationColor">通过</a></td><td><a href="javascritp:void(0)" class="deleteColor reject">驳回</a></td></tr>';
                            //console.log(value.code)


                        });
                        $('tr.tableTit').html(tabTitle);
                        $('tbody').html(htmlTab);
                        $('.pass').click(function(){
                            var info={};
                            info.creditId=$(this).siblings('.id').text();
                            var creditId=info.creditId;
                            info.status=1;
                            ajaxAction("put",'/api/credit/check',info,true,function(data,textStatus){alert('审核通过成功');

                                var info={};
                                creditcheckList(info);

                            },function(errno,errmsg){
                                alert('审核失败');

                            });
                            return false;
                        });

                        $('.reject').click(function(){
                            $('.rejectReasonPopup').show();
                            var aaaid=$(this).parent().siblings('.id').text();
                            $('.ReasonConfirm').attr('value',aaaid);
                            return false;
                        });

                        $('.ReasonCancel').click(function(){
                            $('.rejectReasonPopup').hide();
                            return false;
                        });
                        $('.ReasonConfirm').unbind('click').click(function(){
                            if($('.rejectReasonContent').val()==''){
                                alert('驳回理由不可为空！');
                                return false;
                            }
                            info={};
                            info.status=-2;
                            info.creditId= $('.ReasonConfirm').val();
                            info.refuseMessage=$('.rejectReasonContent').val();
                            ajaxAction("put",'/api/credit/check',info,true,function(data,textStatus){alert('成功驳回');

                                var info={};
                                creditcheckList(info);

                            },function(errno,errmsg){
                                alert('驳回失败');

                            });
                            $('.rejectReasonPopup').hide();
                            $('.rejectPopup').hide();
                            return false;
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


    function rewardcheckList(info){
        // 悬赏审核列表
        info.status=0;
        info.rows=20;
        info.page=1;
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

                        var tabTitle='<td>ID</td><td>编号</td><td>类型</td><td>金额（万元）</td><td>所在地</td><td colspan="3" width="18%">操作</td>';
                        htmlTab = '';
                        $.each(data.list,function(index,value){

                            if(value.type==0){
                                value.type='找人';
                            }else if(value.type==1){
                                value.type='找资产';
                            }

                            htmlTab+='<tr><td class="id">'+value.rewardId+'</td><td>'+value.code+'</td><td>'+value.type+'</td><td>'+value.money+'</td><td>'+value.place+'</td><td><a href="/adm/audit/info/reward/'+value.rewardId+'" class="infoColor">查看详情</a></td><td class="pass"><a href="javascript:void(0)" class="modificationColor">通过</a></td><td><a href="javascritp:void(0)" class="deleteColor reject">驳回</a></td></tr>';
                            //console.log(value.code)


                        });

                        $('tr.tableTit').html(tabTitle);
                        $('tbody').html(htmlTab);
                        $('.pass').click(function(){
                            var info={};
                            info.rewardId=$(this).siblings('.id').text();
                            info.status=1;

                            ajaxAction("put",'/api/reward/check',info,true,function(data,textStatus){alert('审核通过成功');

                                var info={};
                                rewardcheckList(info);

                            },function(errno,errmsg){
                                alert('审核失败');

                            });
                            return false;
                        });

                        $('.reject').click(function(){
                            $('.rejectReasonPopup').show();
                            var aaaid=$(this).parent().siblings('.id').text();
                            $('.ReasonConfirm').attr('value',aaaid);
                            return false;
                        });

                        $('.ReasonCancel').click(function(){
                            $('.rejectReasonPopup').hide();
                            return false;
                        });
                        $('.ReasonConfirm').unbind('click').click(function(){
                            if($('.rejectReasonContent').val()==''){
                                alert('驳回理由不可为空！');
                                return false;
                            }
                            info={};
                            info.status=-1;
                            info.rewardId=$('.ReasonConfirm').val();
                            info.refuseMessage=$('.rejectReasonContent').val();
                            ajaxAction("put",'/api/reward/check',info,true,function(data,textStatus){alert('成功驳回');

                                var info={};
                                rewardcheckList(info);

                            },function(errno,errmsg){
                                alert('驳回失败');

                            });
                            $('.rejectReasonPopup').hide();
                            $('.rejectPopup').hide();
                            return false;
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




    function propertycheckList(info){
        // 资产审核列表
        info.status=0;
        info.rows=20;
        info.page=1;
        ajaxAction("get",'/api/property'+ passParam(info),"",true,function(data,textStatus){
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
                    ajaxAction("get",'/api/property'+ passParam(info),"",true,function(data,textStatus){

                        var tabTitle='<td>ID</td><td>编号</td><td>类型</td><td>金额（万元）</td><td>所在地</td><td colspan="3" width="18%">操作</td>';
                        htmlTab = '';
                        $.each(data.list,function(index,value){

                            if(value.sellType==0){
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

                            htmlTab+='<tr><td class="id">'+value.propertyId+'</td><td>'+value.code+'</td><td>'+value.propertyPattern+'</td><td>'+value.money+'</td><td>'+value.place+'</td><td><a href="/adm/audit/info/property/'+value.propertyId+'" class="infoColor">查看详情</a></td><td class="pass"><a href="javascript:void(0)" class="modificationColor">通过</a></td><td><a href="javascritp:void(0)" class="deleteColor reject">驳回</a></td></tr>';
                            //console.log(value.code)


                        });

                        $('tr.tableTit').html(tabTitle);
                        $('tbody').html(htmlTab);
                        $('.pass').click(function(){
                            var info={};
                            info.propertyId=$(this).siblings('.id').text();
                            info.status=1;

                            ajaxAction("put",'/api/property/check',info,true,function(data,textStatus){alert('审核通过成功');

                                var info={};
                                propertycheckList(info);

                            },function(errno,errmsg){
                                alert('审核失败');

                            });
                            return false;
                        });

                        $('.reject').click(function(){
                            $('.rejectReasonPopup').show();
                            var aaaid=$(this).parent().siblings('.id').text();
                            $('.ReasonConfirm').attr('value',aaaid);
                            return false;
                        });

                        $('.ReasonCancel').click(function(){
                            $('.rejectReasonPopup').hide();
                            return false;
                        });
                        $('.ReasonConfirm').unbind('click').click(function(){
                            if($('.rejectReasonContent').val()==''){
                                alert('驳回理由不可为空！');
                                return false;
                            }
                            info={};
                            info.status=-2;
                            info.propertyId= $('.ReasonConfirm').val();
                            info.refuseMessage=$('.rejectReasonContent').val();
                            ajaxAction("put",'/api/property/check',info,true,function(data,textStatus){alert('成功驳回');

                                var info={};
                                propertycheckList(info);

                            },function(errno,errmsg){
                                alert('驳回失败');

                            });
                            $('.rejectReasonPopup').hide();
                            $('.rejectPopup').hide();
                            return false;
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



    function companycheckList(info){
        // 催收公司审核列表
        info.companyType=4;
        info.status=0;
        info.rows=20;
        info.page=1;
        ajaxAction("get",'/api/company'+ passParam(info),"",true,function(data,textStatus){
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
                    ajaxAction("get",'/api/company'+ passParam(info),"",true,function(data,textStatus){

                        var tabTitle='<td>ID</td><td>名称</td><td>手机号</td><td>所在地</td><td colspan="3" width="18%">操作</td>';
                        htmlTab = '';
                        $.each(data.list,function(index,value){

                            htmlTab+='<tr><td class="id">'+value.companyId+'</td><td>'+value.companyName+'</td><td>'+value.companyTelephone+'</td><td>'+value.place+'</td><td><a href="/adm/audit/info/collection/'+value.companyId+'" class="infoColor">查看详情</a></td><td class="pass"><a href="javascript:void(0)" class="modificationColor">通过</a></td><td><a href="javascritp:void(0)" class="deleteColor reject">驳回</a></td></tr>';
                            //console.log(value.code)


                        });

                        $('tr.tableTit').html(tabTitle);
                        $('tbody').html(htmlTab);

                        $('.pass').click(function(){
                            var info={};
                            info.companyId=$(this).siblings('.id').text();
                            info.status=1;

                            ajaxAction("put",'/api/company/check',info,true,function(data,textStatus){alert('审核通过成功');

                                var info={};
                                companycheckList(info);

                            },function(errno,errmsg){
                                alert('审核失败');

                            });
                            return false;
                        });

                        $('.reject').click(function(){
                            $('.rejectReasonPopup').show();
                            var aaaid=$(this).parent().siblings('.id').text();
                            $('.ReasonConfirm').attr('value',aaaid);
                            return false;
                        });


                        $('.ReasonCancel').click(function(){
                            $('.rejectReasonPopup').hide();
                            return false;
                        });
                        $('.ReasonConfirm').unbind('click').click(function(){
                            if($('.rejectReasonContent').val()==''){
                                alert('驳回理由不可为空！');
                                return false;
                            }
                            info={};
                            info.status=-2;
                            info.companyId=$('.ReasonConfirm').val();
                            info.refuseMessage=$('.rejectReasonContent').val();
                            ajaxAction("put",'/api/company/check',info,true,function(data,textStatus){alert('成功驳回');

                                var info={};
                                companycheckList(info);

                            },function(errno,errmsg){
                                alert('驳回失败');

                            });
                            $('.rejectReasonPopup').hide();
                            $('.rejectPopup').hide();
                            return false;
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


    function provincecheckList(info){
        // 省级代理公司审核列表
        info.companyType=2;
        info.status=0;
        info.rows=20;
        info.page=1;
        ajaxAction("get",'/api/company'+ passParam(info),"",true,function(data,textStatus){
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
                    ajaxAction("get",'/api/company'+ passParam(info),"",true,function(data,textStatus){

                        var tabTitle='<td>ID</td><td>名称</td><td>手机号</td><td>所在地</td><td colspan="3" width="18%">操作</td>';
                        htmlTab = '';
                        $.each(data.list,function(index,value){


                            htmlTab+='<tr><td class="id">'+value.companyId+'</td><td>'+value.companyName+'</td><td>'+value.companyTelephone+'</td><td>'+value.place+'</td><td><a href="/adm/audit/info/company/'+value.companyId+'" class="infoColor detail">查看详情</a></td><td class="pass" id="kk"><a href="javascript:void(0)" class="modificationColor ">通过</a></td><td><a href="javascritp:void(0)" class="deleteColor reject">驳回</a></td></tr>';
                            //console.log(value.code)


                        });

                        $('tr.tableTit').html(tabTitle);
                        $('tbody').html(htmlTab);

                        $('.pass').click(function(){
                            var info={};
                            info.companyId=$(this).siblings('.id').text();
                            info.status=1;

                            ajaxAction("put",'/api/company/check',info,true,function(data,textStatus){alert('审核通过成功');
                                var info={};
                                provincecheckList(info);
                            },function(errno,errmsg){
                                alert('审核失败');

                            });
                            return false;
                        });

                        $('.reject').click(function(){
                            $('.rejectReasonPopup').show();
                            var aaaid=$(this).parent().siblings('.id').text();
                            $('.ReasonConfirm').attr('value',aaaid);
                            return false;
                        });


                        $('.ReasonCancel').click(function(){
                            $('.rejectReasonPopup').hide();
                            return false;
                        });
                        $('.ReasonConfirm').unbind('click').bind('click',function(event){
                            if($('.rejectReasonContent').val()==''){
                                alert('驳回理由不可为空！');
                                return false;
                            }

                            info={};
                            info.status=-2;
                            info.companyId=$('.ReasonConfirm').val();
                            info.refuseMessage=$('.rejectReasonContent').val();
                            ajaxAction("put",'/api/company/check',info,true,function(data,textStatus){alert('成功驳回');},function(errno,errmsg){
                                alert('驳回失败');

                            });
                            $('.rejectReasonPopup').hide();
                            $('.rejectPopup').hide();
                            event.stopPropagation();
                            return false;
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



    function citycheckList(info){
        // 市级代理公司审核列表

        info.companyType=3;
        info.rows=20;
        info.page=1;
        info.status=0;
        //console.log(info);
        ajaxAction("get",'/api/company'+ passParam(info),"",true,function(data,textStatus){
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
                    //console.log(info);
                    ajaxAction("get",'/api/company'+ passParam(info),"",true,function(data,textStatus){
                        var tabTitle='<td>ID</td><td>名称</td><td>手机号</td><td>所在地</td><td colspan="3" width="18%">操作</td>';
                        var htmlTab = '';
                        $.each(data.list,function(index,value){
                            htmlTab+='<tr><td class="id">'+value.companyId+'</td><td>'+value.companyName+'</td><td>'+value.companyTelephone+'</td><td>'+value.place+'</td><td><a href="/adm/audit/info/city/'+value.companyId+'" class="infoColor">查看详情</a></td><td class="pass"><a href="javascript:void(0)" class="modificationColor">通过</a></td><td><a href="javascript:void(0)" class="deleteColor reject">驳回</a></td></tr>';
                            //console.log(value.code)


                            ////////////////
                        });

                        $('tr.tableTit').html(tabTitle);
                        $('tbody').html(htmlTab);

                        $('.pass').click(function(){
                            var info={};
                            info.companyId=$(this).siblings('.id').text();
                            info.status=1;

                            ajaxAction("put",'/api/company/check',info,true,function(data,textStatus){alert('审核通过成功');
                                var info={};
                                citycheckList(info);
                            },function(errno,errmsg){
                                alert('审核失败');

                            });
                            return false;
                        });

                        $('.reject').click(function(){
                            $('.rejectReasonPopup').show();
                            var aaaid=$(this).parent().siblings('.id').text();
                            //console.log(aaaid);
                            $('.ReasonConfirm').attr('value',aaaid);
                            return false;
                        });


                        $('.ReasonCancel').click(function(){
                            $('.rejectReasonPopup').hide();
                            return false;
                        });
                        $('.ReasonConfirm').unbind('click').click(function(){
                            if($('.rejectReasonContent').val()==''){
                                alert('驳回理由不可为空！');
                                return false;
                            }
                            info={};
                            info.status=-2;
                            info.companyId=$('.ReasonConfirm').val();
                            info.refuseMessage=$('.rejectReasonContent').val();
                            ajaxAction("put",'/api/company/check',info,true,function(data,textStatus){alert('成功驳回');
                                var info={};
                                citycheckList(info);

                            },function(errno,errmsg){
                                alert('驳回失败');

                            });
                            $('.rejectReasonPopup').hide();
                            $('.rejectPopup').hide();
                            return false;
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


    function rejecChecktList(info){
        // 驳回列表
        info.rows=20;
        info.page=1;
        ajaxAction("get",'/api/reject'+ passParam(info),"",true,function(data,textStatus){
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
                    ajaxAction("get",'/api/reject'+ passParam(info),"",true,function(data,textStatus){

                        var tabTitle='<td>ID</td><td>类型</td><td>驳回时间</td><td>操作</td>';
                        $('tr.tableTit').html(tabTitle);
                        htmlTab = '';

                        $.each(data.list,function(index,value){
                            var title;
                            if(value.rejectType==5){
                                title='代理公司';
                                //var name=value.companyName;

                                htmlTab+='<tr><td>'+value.id+'</td><td>'+title+'</td><td>'+value.createTime+'</td><td><a href="/adm/audit/info/reject/company/'+value.typeId+'" class="infoColor">查看详情</a></td></tr>';
                                //console.log(value.code)
                                $('tbody').html(htmlTab);
                            }else if( value.rejectType==4 ){

                                title='债权';
                                name=value.code;
                                htmlTab+='<tr><td>'+value.id+'</td><td>'+title+'</td><td>'+value.createTime+'</td><td><a href="/adm/audit/info/reject/credit/'+value.typeId+'" class="infoColor">查看详情</a></td></tr>';
                                //console.log(value.code)
                                $('tbody').html(htmlTab);
                            }else if( value.rejectType==8 ){

                                title='普通用户';
                                name=value.telephone;
                                htmlTab+='<tr><td>'+value.id+'</td><td>'+title+'</td><td>'+value.createTime+'</td><td><a href="/adm/audit/info/reject/people/'+value.typeId+'" class="infoColor">查看详情</a></td></tr>';
                                //console.log(value.code)
                                $('tbody').html(htmlTab);

                            }else if(value.rejectType==3){
                                title='悬赏';
                                name=value.code;
                                htmlTab+='<tr><td>'+value.id+'</td><td>'+title+'</td><td>'+value.createTime+'</td><td><a href="/adm/audit/info/reject/reward/'+value.typeId+'" class="infoColor">查看详情</a></td></tr>';
                                //console.log(value.code)
                                $('tbody').html(htmlTab);
                            }else if(value.rejectType==2){
                                title='资产';
                                name=value.code;
                                htmlTab+='<tr><td>'+value.id+'</td><td>'+title+'</td><td>'+value.createTime+'</td><td><a href="/adm/audit/info/reject/property/'+value.typeId+'" class="infoColor">查看详情</a></td></tr>';
                                //console.log(value.code)
                                $('tbody').html(htmlTab);

                            }

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

    function reportList() {
        // 悬赏审核列表

        ajaxAction("get", '/api/report', "", true, function (data, textStatus) {
            $('.province').html(data.provinceAgentNum);
            $('.city').html(data.cityAgentNum);
            $('.company').html(data.debtCollectorsNum);
            $('.infomation').html(data.infoNum);
            $('.user').html(data.memberNum);
        }, function (errno, errmsg) {
            alert(errmsg);
        })
    }
});
