/*dataTables汉化文件地址*/
var dataTable_zh = '/bundles/mirroroam/json/language.json';

/*  confirm提示
	留作自定义提示
*/
function myConfirm(msg){
	if(!confirm(msg)) return false;
	else return true;
}

/*  alert提示
	留作自定义提示
*/
function myAlert(msg){
	alert(msg);
}

/*时间格式化默认格式*/
var formatteSet = 'YYYY MM DD H M S';
/*时间格式化，value单位为毫秒*/
function formatter(value,set){
	var date = new Date(value);
	var y = date.getFullYear();
	var m = checkTimeItem(date.getMonth()+1);
	var d = checkTimeItem(date.getDate());
	var h = checkTimeItem(date.getHours());
	var min = checkTimeItem(date.getMinutes());
	var s = checkTimeItem(date.getSeconds());
	var time = '';
	var setInfo = formatteSet;
	if(set) setInfo = set;
	set = set.split(' ');
	$.each(set,function(index,content){
		switch(content){
			case 'YYYY':
				time+=y;
			break;

			case 'MM':
				if(!time) time+=m;
				else time+='-'+m;
			break;

			case 'DD':
				if(!time) time+=d;
				else time+='-'+d;
			break;

			case 'H':
				if(!time) time+=h;
				else time+=' '+h;
			break;

			case 'M':
				if(!time) time+=min;
				else time+=':'+min;
			break;

			case 'S':
				if(!time) time+=s;
				else time+=':'+s;
			break;
		}
	});
	return time;
}

function checkWeiXinId(value,bool){
	if(!bool) bool = false;
	if(value == ''){
		location.href = wxPath(location.pathname,bool);
	}
}

function wxPath(url,bool){
	var getType = 'snsapi_userinfo';
	if(!bool) getType = 'snsapi_base';
	var origin = location.origin;
	var head = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8c06263e253e080d&redirect_uri=';
	var footer = '&response_type=code&scope='+getType+'&state=STATE#wechat_redirect';
	url = head + encodeURIComponent(origin+url) + footer;
	return url;
}

/*格式化月日时分秒，不足两位数补零*/
function checkTimeItem(value){
	if(value < 10)
		return '0'+value;
	else
		return value;
}

/*单个按钮点击关闭/开启，target为按钮dom对象，bool为true/false*/
function changeBind(target,bool,callback){
	$(target).attr('disabled',bool);
	if(callback) callback(target);
}
/*多个按钮的解禁用*/
function changeBinds(target,bool){
	for(i = 0; i < target.length; i++) {
		var btn = $(target[i]);
		$(btn).attr('disabled',bool);
	}
}

/*  url格式化
	用于ajax请求地址
*/
function apiPath(url){
	var apiUrl = url;
	//apiUrl = '/app_dev.php' + url;
	apiUrl = url;
	return apiUrl;
}

/*  url格式化
	用于跳转链接地址
*/
function urlPath(url){
	var jumpUrl = url;
	//jumpUrl = '/app_dev.php' + url;
	jumpUrl = url;
	return jumpUrl;
}

/*获取cookie*/
function getCookie(time){
	if(document.cookie.length > 0){
		start=document.cookie.indexOf(time + "=");
		if(start!=-1){ 
			start=start + time.length+1 ;
			end=document.cookie.indexOf(";",start);
			if (end==-1) end=document.cookie.length;
			return unescape(document.cookie.substring(start,end));
		} 
	}
	return "";
}

/*设置cookie*/
function setCookie(time,value,expiretime){
	var date = new Date();
	date.setDate(date.getDate() + expiretime);
	document.cookie=time+ "=" +escape(value)+";expires="+date.toGMTString()+";path=/";
}
/*  批量绑定数据
	target为要遍历的区域的dom对象
	输入项外属性paramet-data设置为true表示需要获取该输入项内容
	输入项外属性data-key的值为拼接json时的key值
	遍历完成后返回json
*/
function autoBindJson(target){
	var array = $(target).find('[paramet-data = "true"]');
	var json = {};
	$.each(array,function(index,content){
		var key = $(content).attr('data-key');
		var value = $(content).val();
		json[key] = value;
	});
	return json;
}

/*  批量回填数据
	target为要回填内容的区域的dom对象
	输入项外属性data-value的值为返回json数据内对应的key值
	matchTag用于判断回填位置标签类型
	matchItem根据index（key）值对content（value）进行拼接，如拼接单位等
*/
function setBindJson(target,data){
	$.each(data,function(index,content){
		var item = $(target).find('[data-value = "'+index+'"]');
        if(!item[0]) return;
        if(matchTag(item)){
        	item.val(matchItem(index,content));
        }else{
        	item.text(matchItem(index,content));
        }
    });
}

/*回填数据处理（不同页面可重写）*/
function matchItem(target,value){
	return value;
}

/*判断标签类型*/
function matchTag(target){
	var tag = target[0].localName;
	var flag = true;
	switch(tag){
		case 'a':
		case 'li':
		case 'span':
		case 'p':
		case 'div':
			flag = false;
		break;
	}
	return flag;
}


/*  ajax请求封装方法
	GET请求json传''

*/
function ajaxAction(method,url,json,async,success_func,error_func)
{
	var ajaxInfo = {
		type: method,
		url: url,
		async: async,
		dataType: "json",
		timeout: 600000,
		cache: true,
		success: function (resp, textStatus) {
			var errno = resp?resp.errno:0;
			var errmsg = resp?resp.errmsg:'出错';
			if (errno==0){
				success_func(resp, textStatus);
			}else{
				error_func(errno, errmsg);
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			var errno = 10000;
			var errmsg = '未知错误';
			switch(XMLHttpRequest.status)
			{
				case 400:
					errno = 10400;
					errmsg = '接口参数错误';
					break;
				case 401:
					errno = 10401;
					errmsg = '未授权的接口调用';
					break;
				case 403:
					errno = 10403;
					errmsg = '对此接口无访问权限';
					break;
				case 404:
					errno = 10404;
					errmsg = '接口路径不存在';
					break;
				case 406:
					errno = 10406;
					errmsg = '请求无法被接受';
					break;
				case 0:
				case 408:
					errno = 10408;
					errmsg = '请求超时';
					break;
				case 409:
					errno = 10409;
					errmsg = '请求资源冲突，请重试';
					break;
				case 415:
					errno = 10415;
					errmsg = '不支持此用户代理';
					break;
				case 500:
					errno = 10500;
					errmsg = '服务器内部错误';
					break;
				default:
					errno = 10000;
					errmsg = '未知错误 '+XMLHttpRequest.status;
			}
			if(errno!=10409){
				error_func(errno, errmsg);
			}
		}
	};
	if(json) ajaxInfo.data = $.toJSON(json);
	$.ajax(ajaxInfo);
}

/*文档上传*/
function onUploadImgChange(fileInput,paramet) {
	var unitType = 0;
	var maxSize = 0;
	var checkFileErrmsg = '上传文件格式错误';
	if(paramet.unitType) unitType = paramet.unitType; //大小限制单位，0-B，1-KB，2-MB，3-GB
	if(paramet.maxSize) maxSize = paramet.maxSize; //大小限制上限，数值需与单位对应
	if(paramet.fileErrmsg) checkFileErrmsg = paramet.fileErrmsg; //格式错误提示
	var filePath = fileInput.value;
	if(!filePath)return;
	filePath.substring(filePath.lastIndexOf(".")).toLowerCase();
	var fileExt = filePath.substring(filePath.lastIndexOf(".")).toLowerCase();
	if(!checkNull(filePath)){
		return false;
	}
	if (!paramet.checkFileExt(fileExt))
	{
		alert(checkFileErrmsg);
		return false;
	}
	if(!paramet.fileType) preImg(fileInput,paramet.picTarget,paramet.checkUpPicture);

	if(fileInput.files && fileInput.files[0]) {
		var sizeInfo = checkSize(fileInput.files[0].size,unitType);
		var showSize = sizeInfo.size.toFixed(1) + sizeInfo.unit;
		if(maxSize > 0){
			if(sizeInfo.compared > maxSize){
				if(paramet.overSize){
					paramet.overSize(paramet,sizeInfo);
				}else{
					alert('文件过大，文件大小上限为'+maxSize+sizeInfo.unit);
				}
				return false;
			}
		}
		if(paramet.success) paramet.success(sizeInfo);
	} else {
		fileInput.select();
		var url = document.selection.createRange().text;
		try {
			var fso = new ActiveXObject("Scripting.FileSystemObject");
		} catch (e) {
			alert('如果你用的是ie 请将安全级别调低！');
			return false;
		}
		if(paramet.success) paramet.success(sizeInfo);
	}
}
function preImg(target,source,callback) {
	var url = getFileUrl(target);
	var imgPre = source;
	if(window.navigator.userAgent.indexOf("MSIE") >= 1 && !(navigator.userAgent.indexOf("MSIE 10.0") > 0) ) {
		var picpreview=document.getElementById("preview");
		picpreview.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = '';
		picpreview.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = url;
	}else{
		imgPre.src = '';
		imgPre.src = url;
	}
	showSize(imgPre,callback);
}

function getFileUrl(target) {
	var url='';
	if(window.FileReader){
		var reader = new FileReader();
		reader.onloadend  = function(e) {
			var html="<li><img src='"+e.target.result+"' alt=''></li>";
			$('.uploadFileListCon ul').append(html);
		};
		reader.readAsDataURL(target.files[0]);
	}else{
		if (navigator.userAgent.indexOf("MSIE")>=1) { // IE
			url = target.value;
		}else{
			url = window.URL.createObjectURL(target.files.item(0));
		}
		var html="<li><img src='"+url+"' alt=''></li>";
		$('.uploadFileListCon ul').append(html);
	}
}
function showSize(target,callback){
	var width = target.naturalWidth;
	var height = target.naturalHeight;
	if(width==0 || height==0){
		setTimeout(function(){
			showSize(target,callback);
		},500);
	}else{
		var args = {
			'width':width,
			'height':height
		};
		if(callback) callback(args);
	}
}
function checkSize(value,type){
	var time = 0;
	var size = value;
	var compared = value;
	while(type != time){
		compared = compared/1024;
		time++;
	}
	time = 0;
	while(size >= 1024){
		size = size/1024;
		time++;
	}
	var args = {
		'size':size,
		'compared':compared,
		'unit':matchUnit(time)
	}
	return args;
}
function matchUnit(type){
	var result = '';
	switch(type){
		case 0:
			result = 'B';
			break;

		case 1:
			result = 'KB';
			break;

		case 2:
			result = 'MB';
			break;

		case 3:
			result = 'GB';
			break;

		case 4:
			result = 'TB';
			break;
	}
	return result;
}

//存储数据
function checkLocalStroge(){
	return (window.localStorage) ? true : false;
}

function setLocalStroge(key,value){
	if(!value) window.localStorage.removeItem(key);
	else window.localStorage.setItem(key,value);
}

function getLocalStroge(key){
	return window.localStorage.getItem(key);
}

//对表格操作完成后刷新当前页
//加载当前页
function reloadTable(table) {
	$(table).DataTable().ajax.reload(null,false);
}

//var obj=[{'name':'aaa','value':'1'},{'name':'bbb','value':1}];

//传参
function passParam(obj){

	var param='?';
	var i=0;
	$.each(obj,function(index,val){
		if(i>0){
			param+='&'+index+'='+val;
		}else{
			param+=index+'='+val;
		}
		i++;
	});
	return param;
}

var countdown=60;
function settime(obj) {
	if (countdown == 0) {
		obj.removeAttribute("disabled");
		obj.value="获取验证码";
		$(obj).css("background","#ff8400");
		countdown = 60;
		return;
	} else {
		obj.setAttribute("disabled", true);
		obj.value="重新发送(" + countdown + ")";
		$(obj).css("background","#c7c7c7");
		countdown--;
	}
	setTimeout(function() {
			settime(obj);
		}
		,1000)
}

//Tab当前选中样式
var zqCurrent=function(obj){
	obj.click(function(){
		var _this=$(this);
		var index=_this.index();
		_this.addClass('on').siblings('li').removeClass('on');
	});

};





/*图片上传,访问后台*/
function imgUpload(formTag,url,method,beforeFn,success_func,error_func){
	var formData = new FormData(formTag);
	$.ajax({
		url: url,  //server script to process data
		type: method,
		//Ajax事件
		beforeSend: function(){},
		success: function (resp, textStatus) {
			var errno = resp.errno;
			var errmsg = resp.errmsg;
			if (errno==0){
				success_func(resp, textStatus);
			}else{
				if(resp.rows&&resp.col){
					errmsg+=',第'+resp.rows+'行'+resp.col+'列';
				}
				error_func(errno, errmsg);
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			var errno = 10000;
			var errmsg = '未知错误';

			switch(XMLHttpRequest.status)
			{
				case 400:
					errno = 10400;
					errmsg = '接口参数错误';
					break;
				case 401:
					errno = 10401;
					errmsg = '未授权的接口调用';
					break;
				case 403:
					errno = 10403;
					errmsg = '对此接口无访问权限';
					break;
				case 404:
					errno = 10404;
					errmsg = '接口路径不存在';
					break;
				case 406:
					errno = 10406;
					errmsg = '请求无法被接受';
					break;
				case 0:
				case 408:
					errno = 10408;
					errmsg = '请求超时';
					break;
				case 409:
					errno = 10409;
					errmsg = '请求资源冲突，请重试';
					break;
				case 415:
					errno = 10415;
					errmsg = '不支持此用户代理';
					break;
				case 500:
					errno = 10500;
					errmsg = '服务器内部错误';
					break;
				default:
					errno = 10000;
					errmsg = '未知错误 '+XMLHttpRequest.status;
			}
			error_func(errno, errmsg);
		},
		// Form数据
		data: formData,
		//Options to tell JQuery not to process data or worry about content-type
		cache: false,
		contentType: false,
		processData: false
	})
}


/*图片上传显示本地图片地址*//*target为获取地址标签,spirce为地址存放位置*/
/*function prepareImg(target,source) {
	var url = getFileUrl(target);
	source.css({'background-image':'url('+url+')','border':'1px solid #c6c6c6'});
}*/

function prepareImg(target) {
	var url = getFileUrl(target);
	return url;
}
//在指定标签中预览上传图片
function fillImgByTagName(target,mark_target) {
	var url='';
	if(window.FileReader){
		var reader = new FileReader();
		reader.onloadend  = function(e) {
			var html="<li><img src='"+e.target.result+"' alt=''></li>";
			mark_target.append(html);
		};
		reader.readAsDataURL(target.files[0]);
	}else{
		if (navigator.userAgent.indexOf("MSIE")>=1) { // IE
			url = target.value;
		}else{
			url = window.URL.createObjectURL(target.files.item(0));
		}
		var html="<li><img src='"+url+"' alt=''></li>";
		mark_target.append(html);
	}
}






