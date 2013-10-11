$(document).ready(function(){
	$("#vertical_menu>li>ul").hide();
	$("#vertical_menu>li>a").click(function(){
	    var _this=$(this);

	    if(_this.next("ul").length>0){
	    if(_this.next().is(":visible")){

	    _this.html(_this.html()).next().hide();
	    }else{

	    _this.html(_this.html()).next().show();
	    }

	    return false;
	    }
	    });

	$("a").focus( function(){
	    $(this).blur();
	    });
	});
$(document).ready(function(){
	// 幫 #menu li 加上 hover 事件
	$('#horizontal_menu>li').hover(function(){
	    // 先找到 li 中的子選單
	    var _this = $(this),
	    _subnav = _this.children('ul');

	    // 變更目前母選項的背景顏色
	    // 同時顯示子選單(如果有的話)
	    //_subnav.css('display', 'block').css('backgroundColor', '#000000');
	    _subnav.css('display', 'block');
	    } , function(){
	    // 變更目前母選項的背景顏色
	    // 同時隱藏子選單(如果有的話)
	    // 也可以把整句拆成上面的寫法
	    $(this).css('backgroundColor', '').children('ul').css('display', 'none');
	    });

	// 取消超連結的虛線框
	$('a').focus(function(){
	    this.blur();
	    });
});
