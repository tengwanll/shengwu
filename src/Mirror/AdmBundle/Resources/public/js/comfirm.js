(function($) {  
       
    $.alerts = {         
        alert: function(title, message, callback) {  
            if( title == null ) title = '系统提示';
            $.alerts._show(title, message, null, 'alert', function(result) {  
                if( callback ) callback(result);  
            });  
        },  
           
        confirm: function(title, message, callback) {  
            if( title == null ) title = '系统提示';
            $.alerts._show(title, message, null, 'confirm', function(result) {  
                if( callback ) callback(result);  
            });  
        },

        comment: function(title, message, callback) {
            if( title == null ) title = '备注';
            $.alerts._show(title, message, null, 'comment', function(result,msg) {
                if( callback ) callback(result,msg);
            });
        },

        photo: function(title, url, callback) {
            if( title == null ) title = '图片';
            $.alerts._show(title, url, null, 'photo', function(result,msg) {
                if( callback ) callback(result,msg);
            });
        },
        _show: function(title, msg, value, type, callback) {
                var height=$(window).height()-150;
                var _html = "";
   
                    _html += '<div id="mb_box"></div><div id="mb_con"><span id="mb_tit">' + title + '</span>';
                    if(type=="comment"){
                        _html += '<div id="mb_msg"><textarea id="message" cols="60" rows="10" placeholder="'+msg+'"></textarea></div><div id="mb_btnbox">';
                    }else if(type=="photo"){
                        _html += '<div id="mb_msg"><img src="'+msg+'" alt="" style="max-width: 100%;max-height:'+height+'px "></div><div id="mb_btnbox">';
                    }else{
                _html += '<div id="mb_msg">' + msg + '</div><div id="mb_btnbox">';
                    }


                    if (type == "alert"||type =="photo") {
                      _html += '<input id="mb_btn_ok" type="button" value="确定" />';  
                    }  
                    if (type == "confirm"||type =="comment") {
                      _html += '<input id="mb_btn_ok" type="button" value="确定" />';  
                      _html += '<input id="mb_btn_no" type="button" value="取消" />';  
                    }
                    _html += '</div></div>';
                   
                    //必须先将_html添加到body，再设置Css样式  
                    $("body").append(_html); GenerateCss();  
           
            switch( type ) {  
                case 'alert':  
          
                    $("#mb_btn_ok").click( function() {  
                        $.alerts._hide();  
                        callback(true);  
                    });  
                    $("#mb_btn_ok").focus().keypress( function(e) {  
                        if( e.keyCode == 13 || e.keyCode == 27 ) $("#mb_btn_ok").trigger('click');  
                    });  
                break;  
                case 'confirm':  
                     
                    $("#mb_btn_ok").click( function() {  
                        $.alerts._hide();  
                        if( callback ) callback(true);
                    });  
                    $("#mb_btn_no").click( function() {  
                        $.alerts._hide();  
                        if( callback ) callback(false);  
                    });  
                    $("#mb_btn_no").focus();  
                    $("#mb_btn_ok, #mb_btn_no").keypress( function(e) {  
                        if( e.keyCode == 13 ) $("#mb_btn_ok").trigger('click');  
                        if( e.keyCode == 27 ) $("#mb_btn_no").trigger('click');  
                    });  
                break;
                case 'comment':

                    $("#mb_btn_ok").click( function() {
                        var message=$('#message').val();
                        $.alerts._hide();
                        if( callback ) callback(true,message);
                    });
                    $("#mb_btn_no").click( function() {
                        var message=$('#message').val();
                        $.alerts._hide();
                        if( callback ) callback(false,message);
                    });
                    $("#mb_btn_no").focus();
                    $("#mb_btn_ok, #mb_btn_no").keypress( function(e) {
                        if( e.keyCode == 13 ) $("#mb_btn_ok").trigger('click');
                        if( e.keyCode == 27 ) $("#mb_btn_no").trigger('click');
                    });
                    break;
                case 'photo':
                    $("#mb_btn_ok").click( function() {
                        var message=$('#message').val();
                        $.alerts._hide();
                        if( callback ) callback(true,message);
                    });
                    $("#mb_btn_ok, #mb_btn_no").keypress( function(e) {
                        if( e.keyCode == 13 ) $("#mb_btn_ok").trigger('click');
                    });
                    break;
            }  
        },  
        _hide: function() {  
             $("#mb_box,#mb_con").remove();  
        }  
    }  
    // Shortuct functions  
    zdalert = function(title, message, callback) {  
        $.alerts.alert(title, message, callback);  
    }  
       
    zdconfirm = function(title, message, callback) {  
        $.alerts.confirm(title, message, callback);  
    };

    zdcomment = function(title, message, callback) {
        $.alerts.comment(title, message, callback);
    };

    zdphoto = function(title, url, callback) {
        $.alerts.photo(title, url, callback);
    };



    //生成Css
  var GenerateCss = function () {  
   
    $("#mb_box").css({ width: '100%', height: '100%', zIndex: '99999', position: 'fixed',  
      filter: 'Alpha(opacity=60)', backgroundColor: 'black', top: '0', left: '0', opacity: '0.6'  
    });  
   
    $("#mb_con").css({ zIndex: '999999', width: '50%', position: 'fixed',  
      backgroundColor: 'White', borderRadius: '15px'  
    });  
   
    $("#mb_tit").css({ display: 'block', fontSize: '14px', color: '#444', padding: '10px 15px',  
      backgroundColor: '#DDD', borderRadius: '15px 15px 0 0',  
      borderBottom: '3px solid #009BFE', fontWeight: 'bold'  
    });  
   
    $("#mb_msg").css({ padding: '20px', lineHeight: '20px',  
      borderBottom: '1px dashed #DDD', fontSize: '18px',textAlign:'center'
    });  
   
    $("#mb_ico").css({ display: 'block', position: 'absolute', right: '10px', top: '9px',  
      border: '1px solid Gray', width: '18px', height: '18px', textAlign: 'center',  
      lineHeight: '16px', cursor: 'pointer', borderRadius: '12px', fontFamily: '微软雅黑'  
    });  
   
    $("#mb_btnbox").css({ margin: '15px 0 10px 0', textAlign: 'center' });  
    $("#mb_btn_ok,#mb_btn_no").css({ width: '85px', height: '30px', color: 'white', border: 'none' });  
    $("#mb_btn_ok").css({ backgroundColor: '#168bbb' });  
    $("#mb_btn_no").css({ backgroundColor: 'gray', marginLeft: '20px' });  
   
   
    //右上角关闭按钮hover样式  
    $("#mb_ico").hover(function () {  
      $(this).css({ backgroundColor: 'Red', color: 'White' });  
    }, function () {  
      $(this).css({ backgroundColor: '#DDD', color: 'black' });  
    });  
   
    var _widht = document.documentElement.clientWidth; //屏幕宽  
    var _height = document.documentElement.clientHeight; //屏幕高  
   
    var boxWidth = $("#mb_con").width();  
    var boxHeight = $("#mb_con").height();  
   
    //让提示框居中  
    $("#mb_con").css({ top: (_height - boxHeight) / 2 + "px", left: (_widht - boxWidth) / 2 + "px" });  
  }  
   
  
})(jQuery);  