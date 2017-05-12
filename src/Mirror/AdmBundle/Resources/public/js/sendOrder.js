$(function(){
    // 债权列表
    companyList(send_id());
    $('.affirm').click(function () {
        send(send_id());
    });



});

function send(id){
    var params={};
    params.companyId=$('input[name=companyId]:checked').val();
    params.creditId=id;
    ajaxAction("post", '/api/credit/application/distribution',params, true, function (data, textStatus) {
        window.location.href="/adm/info/creditor";
    },function(errno,errmsg){
        alert(errmsg);
    });

}

function companyList(id) {
    var info = {};
    info.rows = 20;
    info.page = 1;
    ajaxAction("get", '/api/credit/application/companylist/'+ id + passParam(info), "", true, function (data, textStatus) {
        //console.log(data.list.length);
        var count = Math.ceil(data.list.length / 20);
        var htmlTab = "";
        if (!count) {
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
                info.page = num;
                ajaxAction("get", '/api/credit/application/companylist/'+ id + passParam(info), "", true, function (data, textStatus) {
                    htmlTab = '';
                    $.each(data.list, function (index, value) {
                        htmlTab += '<tr><td><input type="radio" name="companyId" value=' +value.companyId+'></td><td>'+value.companyId+'</td><td>'+value.companyName+'</td><td>'+value.createTime+'</td><td>'+value.province+' - '+value.city+'</td><td><a href="/adm/user/company/'+value.companyId+'" class="infoColor">查看详情</a></td></tr>';
                        //console.log(value.code)
                        $('.company').html(htmlTab);

                    });

                }, function (errno, errmsg) {
                    alert(errmsg);
                });

            }
        });


    }, function (errno, errmsg) {
        alert(errmsg);
    });
}