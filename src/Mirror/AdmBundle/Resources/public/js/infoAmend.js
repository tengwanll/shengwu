$(function(){

    checkboxChangeColor($('.creditLawsuit'),'creditLawsuit');
    checkboxChangeColor($('.creditCollection'),'creditCollection');
    checkboxChangeColor($('.creditAssignment'),'creditAssignment');


    clickCheckbox($('.creditLawsuit'),$('.creditLawsuit'),'creditLawsuit');
    clickCheckbox($('.creditCollection'),$('.creditCollection'),'creditCollection');
    clickCheckbox($('.creditAssignment'),$('.creditAssignment'),'creditAssignment');


    amendInfo(amendId());

    selectprovince('selectP','selectC');
    $('select[name=selectP]').change(function(){
        selectcityarea('select_addr','selectP','selectC');
    });

    selectprovince('debtorP','debtorC');
    $('select[name=debtorP]').change(function(){
        selectcityarea('select_addr','debtorP','debtorC');
    });

    $('.borrowDate').datepicker({
        format: 'yyyy-mm-dd',
        weekStart: 1,
        autoclose: true,
        todayBtn: 'linked',
        language: 'cn'
    });

    $('.refundDate').datepicker({
        format: 'yyyy-mm-dd',
        weekStart: 1,
        autoclose: true,
        todayBtn: 'linked',
        language: 'cn'
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
        if($('.creditAmendMoney').val()==''){
            alert('债权金额不可为空');
            return false;
        }
        if($('select[name="selectP"]').val()=="0"){
            alert('请选择债权所在地省份');
        }
        if($('select[name="selectC"]').val()=="0"){
            alert('请选择债权所在地城市');
        }
        if($('.borrowDate').val()==''){
            alert('借款时间不可为空');
            return false;
        }
        if($('.refundDate').val()==''){
            alert('还款时间不可为空');
            return false;
        }
        if($('.debtorName').val()==''){
            alert('债务人姓名不可为空');
            return false;
        }
        if($('.debtorTelephone').val()==''){
            alert('债务人电话不可为空');
            return false;
        }
        if($('.debtorTelephone').val()==''){
            alert('债务人电话不可为空');
            return false;
        }
        if($('select[name="debtorP"]').val()=="0"){
            alert('请选择债务人所在地省份');
        }
        if($('select[name="debtorC"]').val()=="0"){
            alert('请选择债务人所在地城市');
        }
        if($('.creditorName').val()==''){
            alert('债权人姓名不可为空');
        }
        if($('.creditorTel').val()==''){
            alert('债权人电话不可为空');
        }
        if($('.debtDescription').val()==''){
            alert('债权描述不可为空');
        }
        var info={};
        info.creditId=amendId();
        info.type=$('.creditAmendType').val();
        info.money=$('.creditAmendMoney').val();
        info.deal=[];
        var deal={};
        if($('#creditLawsuit').is(':checked')){
            deal.name=$('#creditLawsuit').prev('label').text();
            deal.ratio=$('.lawsuitSelect').val();
            info.deal.push(deal);
        }
        if($('#creditCollection').is(':checked')){
            deal.name=$('#creditCollection').prev('label').text();
            deal.ratio=$('.collectionSelect').val();
            info.deal.push(deal);
        }
        if($('#creditAssignment').is(':checked')){
            deal.name=$('#creditAssignment').prev('label').text();
            deal.ratio=$('.assignmentSelect').val();
            info.deal.push(deal);
        }

        info.province=$('select[name="selectP"]').val();
        info.city=$('select[name="selectC"]').val();

        info.borrowTime=$('.borrowDate').val();
        info.refundTime=$('.refundDate').val();

        info.debtorName=$('.debtorName').val();
        info.debtorTelephone=$('.debtorTelephone').val();

        info.debtorProvince=$('select[name="debtorP"]').val();
        info.debtorCity=$('select[name="debtorC"]').val();

        info.isContact=$('input[name="isContact"]').val();

        info.isAssure=$('input[name="isAssure"]').val();

        info.isLawsuit=$('input[name="isLawsuit"]').val();

        info.isJudgment=$('input[name="isJudgment"]').val();

        info.isUrge=$('input[name="isCollection"]').val();

        info.contactName=$('.creditorName').val();
        info.contactTelephone=$('.creditorTel').val();
        info.setEvidenceBlurredPhoto=$('.pubHide').val();
        info.description=$('.debtDescription').val();

        ajaxAction("put",'/api/credit/back/update',info,true,function(data,textStatus){
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
            info.creditId=amendId();
            ajaxAction("DELETE",'/api/credit',info,true,function(data,textStatus){
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

function amendInfo(number){
    var htmlInfo='';
    var htmlPhoto='';
    ajaxAction("get",'/api/credit/back/'+number,"",true,function(data,textStatus){

        if(data.status==-3){
            data.status='被禁止';
        }else if(data.status==-2){
            data.status='被驳回';
        }else if(data.status==-1){
            data.status='被删除';
        }else if(data.status==0){
            data.status='待审核';
        }else if(data.status==1){
            data.status='待签约';
        }else if(data.status==2){
            data.status='处置中';
        }else if(data.status==3){
            data.status='已完成';
        }

        $('.waitSigned').html(data.status);
        $('.creditNumId').html(data.code);
        if(data.type==0){
            $('.creditAmendType').val("0");
        }else if(data.type==1){
            $('.creditAmendType').val("1");
        }else if(data.type==2){
            $('.creditAmendType').val("2");
        }

        $('.creditAmendMoney').val(data.money);
        var info={};

        $.each(data.dealPattern,function(index,el){
            if(el.name=='诉讼'){
                info.creditLawsuit=el.name;
                info.lawsuitSelect=el.ratio;
            }

            if(el.name=='催收'){
                info.creditCollection=el.name;
                info.collectionSelect=el.ratio;
            }

            if(el.name=='转让'){
                info.creditAssignment=el.name;
                info.assignmentSelect=el.ratio;
                $('#creditAssignment').attr('checked',true);
                $('.assignmentSelect').val(el.ratio);
            }

        });

        if(info.creditLawsuit){
            $('#creditLawsuit').attr('checked',true);
            $('.lawsuitSelect').val(info.lawsuitSelect);
        }else{
            $('#creditLawsuit').attr('checked',false);
        }

        if(info.creditCollection){
            $('#creditCollection').attr('checked',true);
            $('.collectionSelect').val(info.collectionSelect);
        }else{
            $('#creditCollection').attr('checked',false);
        }

        if(info.creditAssignment){
            $('#creditAssignment').attr('checked',true);
            $('.assignmentSelect').val(info.assignmentSelect);
        }else{
            $('#creditAssignment').attr('checked',false);
        }

        $('select[name="selectP"]').val(data.province);
        selectcityarea('select_addr','selectP','selectC');
        $('select[name="selectC"]').val(data.city);

        $('.borrowDate').val(data.borrowTime);
        $('.refundDate').val(data.refundTime);

        $('.debtorName').val(data.debtorName);
        $('.debtorTelephone').val(data.debtorTelephone);

        $('select[name="debtorP"]').val(data.debtorProvince);
        selectcityarea('select_addr','debtorP','debtorC');
        $('select[name="debtorC"]').val(data.debtorCity);

        if(data.isAssure==0){
            $('input[name="isAssure"][value="0"]').attr('checked',true);
        }else if(data.isAssure==1){
            $('input[name="isAssure"][value="1"]').attr('checked',true);
        }

        if(data.isLawsuit==0){
            $('input[name="isLawsuit"][value="0"]').attr('checked',true);
        }else if(data.isLawsuit==1){
            $('input[name="isLawsuit"][value="1"]').attr('checked',true);
        }

        if(data.isJudgment==0){
            $('input[name="isJudgment"][value="0"]').attr('checked',true);
        }else if(data.isJudgment==1){
            $('input[name="isJudgment"][value="1"]').attr('checked',true);
        }

        if(data.isUrge==0){
            $('input[name="isCollection"][value="0"]').attr('checked',true);
        }else if(data.isUrge==1){
            $('input[name="isCollection"][value="1"]').attr('checked',true);
        }

        if(data.isContact==0){
            $('input[name="isContact"][value="0"]').attr('checked',true);
        }else if(data.isContact==1){
            $('input[name="isContact"][value="1"]').attr('checked',true);
        }

        $('.creditorName').val(data.contactName);
        $('.creditorTel').val(data.contactTelephone);

        var photoHtml='';
        $.each(data.evidencePhoto,function(index,el){
            photoHtml+='<li><img src="'+el+'" alt=""></li>';
        });
        $('.notDispose ul').html(photoHtml);

        $('.debtDescription').val(data.description);

        checkboxChangeColor($('.creditLawsuit'),'creditLawsuit');
        checkboxChangeColor($('.creditCollection'),'creditCollection');
        checkboxChangeColor($('.creditAssignment'),'creditAssignment');

    },function(errno,errmsg){
        alert(errmsg);
    });

}