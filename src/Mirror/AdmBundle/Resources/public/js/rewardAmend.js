$(function(){

    selectprovince('selectP','selectC');
    $('select[name=selectP]').change(function(){
        selectcityarea('select_addr','selectP','selectC');
    });

    /*凭证上传*/
    $('#uploadFileInput').on('change',function(){
        if(!/image\/\w+/.test(this.files[0].type)){
            alert("文件必须为图片！");
            return false;
        }
        var imgUrl=prepareImg(this);
        pubImgUpload($('form[name=select_addr]')[0],$('.pubHide'));
    });

    $('.amendConfirm').click(function(){
        if($('.rewardName').val()==''){
            alert('被悬赏人姓名不可为空');
            return false;
        }
        if($('select[name="selectP"]').val()=="0"){
            alert('请选择被悬赏搜索地区省份');
        }
        if($('select[name="selectC"]').val()=="0"){
            alert('请选择被悬赏搜索地区城市');
        }
        if($('.creditAmendMoney').val()==''){
            alert('悬赏金额不可为空');
            return false;
        }
        if($('.rewardEndTime').val()==''){
            alert('悬赏时限不可为空');
            return false;
        }
        if($('.contactName').val()==''){
            alert('联系人姓名不可为空');
            return false;
        }
        if($('.contactTelephone').val()==''){
            alert('联系人电话不可为空');
            return false;
        }

        if($('.debtDescription').val()==''){
            alert('悬赏描述不可为空');
        }
        var info={};
        info.rewardId=amendId();
        info.type=$('.creditAmendType').val();
        info.name=$('.rewardName').val();

        info.province=$('select[name="selectP"]').val();
        info.city=$('select[name="selectC"]').val();
        info.money=$('.creditAmendMoney').val();

        info.time=$('.rewardEndTime').val();


        info.contactName=$('.contactName').val();
        info.contactTelephone=$('.contactTelephone').val();
        info.setEvidenceBlurredPhoto=$('.pubHide').val();
        info.description=$('.debtDescription').val();

        ajaxAction("put",'/api/reward/back/update',info,true,function(data,textStatus){
            window.location.href="/adm/info/creditor";
        },function(errno,errmsg){
            alert(errmsg);
        });



    });

    $('.amendDelete').click(function(){
        $('.deletePopup').show();
        $('.popupConfirm').click(function(){
            $('.deletePopup').hide();
            var info={};
            info.rewardId=amendId();
            ajaxAction("DELETE",'/api/reward/back/delete',info,true,function(data,textStatus){
                window.location.href="/adm/info/creditor";
            },function(errno,errmsg){
                alert(errmsg);
            });
        });
        $('.popupCancel').click(function(){
            $('.deletePopup').hide();
        });

    });


    reward_amend(amendId());

});

function reward_amend(number){
    var htmlInfo='';
    var htmlPhoto='';
    ajaxAction("get",'/api/reward/back/'+number,"",true,function(data,textStatus){

        if(data.status==-3){
            data.status='被禁止';
        }else if(data.status==-2){
            data.status='被驳回';
        }else if(data.status==-1){
            data.status='被删除';
        }else if(data.status==0){
            data.status='待审核';
        }else if(data.status==1){
            data.status='悬赏中';
        }else if(data.status==2){
            data.status='合作成功';
        }

        $('.waitSigned').html(data.status);
        $('.creditNumId').html(data.code);
        if(data.type==0){
            $('.creditAmendType').val("0");
        }else if(data.type==1){
            $('.creditAmendType').val("1");
        }

        $('.rewardName').val(data.name);
        $('.creditAmendMoney').val(data.money);
        $('.rewardEndTime').val(data.time);

        $('select[name="selectP"]').val(data.province);
        selectcityarea('select_addr','selectP','selectC');
        $('select[name="selectC"]').val(data.city);


        $('.contactName').val(data.contactName);
        $('.contactTelephone').val(data.contactTelephone);


        var photoHtml='';
        $.each(data.evidencePhoto,function(index,el){
            photoHtml+='<li><img src="'+el+'" alt=""></li>';
        });
        $('.notDispose ul').html(photoHtml);

        $('.debtDescription').val(data.description);


    },function(errno,errmsg){
        alert(errmsg);
    });

}