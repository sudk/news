function displayLoadingLayer() {
    var F = 0;
    var E = 0;
	var layerDiv = document.getElementById("loadMask");
    var A = jQuery(layerDiv);
	//A.css("left",((jQuery(document).width())/2-(parseInt(A.width())/2))+"px");
	//return;
    F = (jQuery(document).width()  - A.width())/ 2;
    E = jQuery(document).scrollTop() + 10;
	//E = document.body.scrollTop + document.body.clientHeight/2 - A.height/2;
	
    layerDiv.style.position = "absolute";
    layerDiv.style.left = F + "px";
    layerDiv.style.top = E + "px";
	A.show();

}
function displayLoadingLayer(msg) {
    var F = 0;
    var E = 0;
    var layerDiv = document.getElementById("loadMask");
    var A = jQuery(layerDiv);
    if(msg){A.html(msg)}
    //A.css("left",((jQuery(document).width())/2-(parseInt(A.width())/2))+"px");
    //return;
    F = (jQuery(document).width()  - A.width())/ 2;
    E = jQuery(document).scrollTop() + 10;
    //E = document.body.scrollTop + document.body.clientHeight/2 - A.height/2;

    layerDiv.style.position = "absolute";
    layerDiv.style.left = F + "px";
    layerDiv.style.top = E + "px";
    A.show();

}

function hideLoadingLayer() {
	jQuery("#loadMask").hide();
}

var validatorMap = {
    "required": ["本项必填", function (b, a) {
        return a != null && a != ""
    }],
    "number": ["请录入数字", function (b, a) {
        return a == null || a == "" || !isNaN(a) && !/^\s+$/.test(a)
    }],
    "digits": ["请录入整数", function (b, a) {
        return a == null || a == "" || !/[^\d]/.test(a)
    }],
    "unsignedint": ["请录入正整数", function (b, a) {
        return a == null || a == "" || (!/[^\d]/.test(a) && a > 0)
    }],
    "unsigned": ["请输入大于等于0的数值", function (b, a) {
        return a == null || a == "" || (!isNaN(a) && !/^\s+$/.test(a) && a >= 0)
    }],
    "positive": ["请输入大于0的数值", function (b, a) {
        return a == null || a == "" || (!isNaN(a) && !/^\s+$/.test(a) && a > 0)
    }],
	"max": ["请录入数值", function (b, a) {
        return a == null || a == "" || !isNaN(a) && !/^\s+$/.test(a)
    }],
    "alpha": ["请录入英文字母", function (b, a) {
        return a == null || a == "" || /^[a-zA-Z]+$/.test(a)
    }],
    "alphaint": ["请录入英文字母或者数字", function (b, a) {
        return a == null || a == "" || !/\W/.test(a) || /^[a-zA-Z0-9]+$/.test(a)
    }],
    "alphanum": ["请录入英文字母、中文及数字", function (b, a) {
        return a == null || a == "" || !/\W/.test(a) || /^[\u4e00-\u9fa5a-zA-Z0-9]+$/.test(a)
    }],
    "date": ["请录入日期格式yyyy-mm-dd", function (b, a) {
        return a == null || a == "" || /^(19|20)[0-9]{2}-([1-9]|0[1-9]|1[012])-([1-9]|0[1-9]|[12][0-9]|3[01])+$/.test(a)
    }],
    "email": ["请录入正确的Email地址", function (b, a) {
        return a == null || a == "" || /\S+@\S+/.test(a)
    }],
    "text": ["", function (b, a) {
        return true
    }],
    "select": ["", function (b, a) {
        return true
    }],
    "radio": ["", function (b, a) {
        return true
    }],
    "checkbox": ["", function (b, a) {
        return true
    }],
    "url": ["请录入正确的网址", function (b, a) {
        return a == null || a == "" || /^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*)(:(\d+))?\/?/i.test(a)
    }],
    "mobile":["请录入移动手机号", function (b, a) {
        return a == null || a == "" ||  /^(((134|135|136|137|138|139|147|150|151|152|157|158|159|182|187|188))+\d{8})$/.test(a)
    }],
    "unicom":["请录入联通手机号", function (b, a) {
        return a == null || a == "" ||  /^(((130|131|132|155|156|185|186))+\d{8})$/.test(a)
    }],
    "telecom":["请录入电信手机号", function (b, a) {
        return a == null || a == "" ||  /^(((133|153|180|189))+\d{8})$/.test(a);
    }],
    "chinese": ["请录入中文", function (b, a) {
        return a == null || a == "" ||  /^[\u4e00-\u9fa5]+$/.test(a)
    }],
    
	"num_15": ["请输入15位编号", function (b, a) {
    return a == null || a == "" || /^[a-zA-Z0-9]{15}$/.test(a)
	}],

    "idcard": ["请输入正确的身份证号", function (b, a) {
    return a == null || a == "" || /^(((\d{14})|(\d{17}))((\d{1})|x))$/i.test(a)
    }]

    
};


var validForm = function (fid) {
	var error = false;
	jQuery("#"+fid+" ._x_ipt").each(function() {
		var e = jQuery('#'+this["id"]);
		//alert(e.attr("validator"));
		e.removeClass('input_error iptxt');
		e.attr("title","");
		//e.showTip = function(){};
		var m = [];
		var v = e.attr("validator");
		if(!v) return;
		if (v.indexOf("&")>0) {
            v = v.split("&");
			m = m.concat(v);
        }
		else {
			m.push(v);
		}
		for(var i=0;i<m.length;i++) {
			value = m[i];
			if(typeof validatorMap[value] == 'undefined') continue;
            if (!validatorMap[value][1]('', e.val())) {
                e.addClass('input_error iptxt');

                if (e.attr("tip_title")) {
                    e.showTip({flagInfo:e.attr("tip_title")});
                } else {
                    e.showTip({flagInfo:validatorMap[value][0]});
                }
                e.focus();
                error = true;
                return false;
            }
        };
	});
	return error;
};


/**@author yangtl
 *
 * 横向li选项过多时，换行的自动对齐功能
 * selector:li中的选项选择器
 * className:需要换行时对选项附加的class
 * maxlen：行宽
 * space:两个选项的间隔
 */
var optionAlign = function(selector,className,maxlen,space){
    var len = 0;
    $(selector).each(function(){
        var width = $(this).width()+space;
        len += width;
        //alert($(this).html()+","+$(this).width()+","+width+','+len);
        if(len>=maxlen){
            $(this).addClass(className);
            len = 0;
        }

    });
}

var chcity=function(m){
    var city=$("#"+m+"_city").val();
    //alert(city);
    $("#"+m+"_area").attr("disabled",true);
    url="./?r=posm/posinfo/getarea";
    //alert(url);
    $.ajax({
        url:url,
        data:{code:city},
        success:function(data,type){
            if(data){
                $("#"+m+"_area").attr("disabled",false);
                $("#"+m+"_area").html(data);
            }else{
                alert('获取地区失败请刷新页面重试！');
            }
        }
    })
}

$(function(){
    jQuery.fn.extend({
        showTip:function(settings)
        {
            jQuery(this).each(function(){
                //初始化配置信息
                var options = jQuery.extend({
                    flagCss:"tip",
                    flagWidth:jQuery(this).outerWidth(),
                    flagInfo:jQuery(this).attr("title"),
                    isAnimate:false
                },
                settings);
                if(!options.flagInfo)
                {
                    return;
                }
                jQuery(this).removeAttr("title");
                jQuery(this).focus(function(){
					if(!jQuery(this).hasClass("input_error")) return;
					jQuery("#ui-slider-tooltip").remove();
                    //设置提示信息最小宽度为163
                    options.flagWidth = (parseInt(options.flagWidth) < 100) ? 163 : parseInt(options.flagWidth);
                    var oTip = jQuery("<div class='ui-slider-tooltip  ui-corner-all' id='ui-slider-tooltip'></div>");
                    var oPointer = jQuery("<div class='ui-tooltip-pointer-down'><div class='ui-tooltip-pointer-down-inner'></div></div>");
                    var oTipInfo = jQuery("<div>" + options.flagInfo + "</div>").attr("class",options.flagCss).css("width",options.flagWidth + "px");
                    //合并提示信息
                    var oToolTip = jQuery(oTip).append(oTipInfo).append(oPointer);
                    //添加淡入效果
                    if(options.isAnimate)
                    {
                        jQuery(oToolTip).fadeIn("slow");
                    }
                    jQuery(this).after(oToolTip);

                    //计算提示信息的top、left和width
                    var position = jQuery(this).position();
                    var oTipTop = eval(position.top - jQuery(oTip).outerHeight() - 8);
                    var oTipLeft = position.left;
                    jQuery(oToolTip).css("top" , oTipTop + "px").css("left" , oTipLeft + "px");

                    jQuery(this).blur(function(){
                        jQuery(oToolTip).remove();
                    });
                });
            });
            return this;
        }
    })
});
/*操作提示栏隐藏*/
function hideMsg() {
    $("#msg").hide("slow");
}
// $(document).ready(function () {  //防止冒泡事件
//     $("a").bind("click", function (event) {
//         event.stopPropagation();
//     });
// });