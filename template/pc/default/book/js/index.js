var regLog = function(){
	var time = 60;
	var tindex = null;
	var errhtml = '<div class="j-verify-err"><i class="iconfont icon-ic-safe-off"></i><span>[msg]</span></div>';
    var loghtml = '<div class="dialog-login"><!--close--><div class="j-dialog-login-header dialog-login__header hide"style="display: block;"><i class="j-dialog-login-close dialog-login__header--close"></i></div><div class="dialog-login_content clearfix"><!--登录/注册--><div class="tab-content"><!--登录--><div class="j-tab-item tab-item active"data-contentid="login"><div class="dialog-login_form"><div class="form-title"><h3>用户登录</h3><span>没账号(&gt;^ω^&lt;)？</span><a class="j-dialog-login-mode"data-contentid="register">快速注册&gt;</a></div><div class="form-item"><div class="input-block"><input id="logname"type="text"name="name"autocomplete="off"placeholder="手机号码／用户名"></div></div><div class="form-item"><div class="input-block"><input id="logpass"type="password"name="pass"placeholder="密码"autocomplete="off"></div></div><div class="form-item piccode hide"><div class="input-block" style="position: relative;"><input id="pcode2" type="text" name="pcode" placeholder="输入验证码" maxlength="11" autocomplete="off"><img title="点击刷新验证码" style="position: absolute; right: 1px; top: 1px; width: 99px; height: 92%; cursor: pointer; display: inline-block;" class="code_pic2" src=""></div></div><div class="form-item"><div class="j-login-submit submit-btn disabled-select">登录</div></div><div class="form-item clearfix"><div class="j-remember-link remember-link disabled-select"><i class="iconfont icon-ic_read_choose_on1 checkbox"></i>记住我</div><div class="forgot-link disabled-select"><a href="'+Mcpath.web+'index.php/user/login/pass"target="_blank">忘记密码？</a></div></div></div></div><!--注册--><div class="j-tab-item tab-item" data-contentid="register"><div class="dialog-login_form"><div class="form-title"><h3>手机号注册</h3><a class="j-dialog-login-mode" data-contentid="login">已有账号&gt;&gt;</a></div><div class="form-item"><div class="input-block" style="position: relative;"><input id="regtel" type="text" name="tel" placeholder="输入常用手机号码" maxlength="11" autocomplete="off">';
    	if(Mcpath.istel == 0) loghtml += '<img title="点击刷新验证码" style="position: absolute;right: 1px;top: 1px;width: 99px;height: 97%;cursor: pointer;" class="code_pic hide" src="">';
    	loghtml += '</div></div><div class="form-item pic-code"><div class="input-block--inline"><input maxlength="4" id="regpcode" type="text" name="pcode" placeholder="输入图形验证码" autocomplete="off">';
    	if(Mcpath.istel == 0){
    		loghtml += '<div class="j-sms-btn sms-btn pcode-send">短信验证</div>';
    	}else{
    		loghtml += '<img title="点击刷新验证码" style="position: absolute;right: 0;top: 0;width: 99px;height: 36px;cursor: pointer;display: inline-block;border: 1px solid #dddddd;" class="code_pic hide" src="">';
    	}
    	loghtml += '</div></div><div class="form-item tel-code hide"><div class="input-block--inline"><input id="regtcode" type="text" name="tcode" placeholder="输入手机验证码" autocomplete="off"><div class="j-sms-btn sms-btn tcode-send" data-status="false">再次获取</div></div></div><div class="form-item"><div class="input-block"><input id="regpass" type="password" name="pass" placeholder="密码（6-16位字符）" autocomplete="off"></div></div><div class="form-item"><div class="j-register-submit submit-btn disabled-select"><i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop" style="display: none"></i>注册账号</div></div><div class="tip-protocol">注册即视为阅读及同意<a class="j-dialog-login-protocol" data-protocolid="user">《用户服务协议》</a>和<a class="j-dialog-login-protocol" data-protocolid="privacy">《隐私协议》</a></div></div></div></div><!--第三方登录--><div class="j-dialog-other-login other-login"><h3 class="j-dialog-other-title title"><div class="title-line"></div><div class="title-text">其他登录方式</div></h3><div class="other-link"><a href="'+Mcpath.web+'index.php/user/open/qq" rel="nofollow" class="other-login-qq"><i class="iconfont icon-ic_buytoast_qq"></i></a><a href="'+Mcpath.web+'index.php/user/open/weixin" rel="nofollow" class="other-login-wx"><i class="iconfont icon-ic_login_wx"></i></a><a href="'+Mcpath.web+'index.php/user/open/weibo" rel="nofollow" class="other-login-sina"><i class="iconfont icon-ic_login_xl"></i></a></div></div></div></div>';
    layer.open({
        type: 1,
        closeBtn: 0,
        title: false,
        content: loghtml,
        shade: 0.6,
        offset: 'auto',
        area: ['405px', '400px'],
        success: function(layero, layerIdx) {
            mccms.index = layerIdx;
                if(mccms.get_cookie('pint') == 1){
                    $('.title').hide();
                    $('.piccode').show();
                    $('.code_pic2').click();
                }
        }
    });
    //关闭窗口
    $('.j-dialog-login-close').click(function(){
        mccms.layer.close(mccms.index);
    });
    //切换
    $('.j-dialog-login-mode').click(function(){
    	var type = $(this).attr('data-contentid');
    	if(type == 'login'){
    		$(".j-tab-item[data-contentid='login']").show().addClass('active');
    		$(".j-tab-item[data-contentid='register']").hide().removeClass('active');
    	}else{
    		//显示图形验证码
    		$('.code_pic').attr('src','//'+Mcpath.url+Mcpath.web+'index.php/api/code').show();
    		$(".j-tab-item[data-contentid='login']").hide().removeClass('active');
    		$(".j-tab-item[data-contentid='register']").show().addClass('active');
    	}
    });
    //勾选记住我
    $('.j-remember-link').click(function(){
    	if($(this).children('.iconfont').hasClass('icon-ic_read_choose_off1')){
    		$(this).children('.iconfont').removeClass('icon-ic_read_choose_off1').addClass('icon-ic_read_choose_on1');
    	}else{
    		$(this).children('.iconfont').removeClass('icon-ic_read_choose_on1').addClass('icon-ic_read_choose_off1');
    	}
    });
    //点击输入框删掉错误提示
    $('.dialog-login_form input').click(function(){
    	$('.j-verify-err').remove();
    });
    //提交登陆
    $('.j-login-submit').click(function(){
    	var name = $('#logname').val();
    	var pass = $('#logpass').val();
        var pcode = $('#pcode2').val();
    	var is = $('.j-remember-link').children('.iconfont').hasClass('icon-ic_read_choose_on1') ? 1 : 0;
    	if(name == ''){
    		var err = errhtml.replace('[msg]','主人，请输入账号~');
    		$('#logname').parent().parent().append(err);
            $('#logname').focus();
    		return false;
    	}
        if(!(/^[\S]{6,16}$/.test(pass))){
    		var err = errhtml.replace('[msg]','密码必须6到16位，且不能出现空格');
    		$('#logpass').parent().parent().append(err);
            $('#logpass').focus();
    		return false;
    	}
        if(mccms.get_cookie('pint') == 1 && pcode == ''){
            var err = errhtml.replace('[msg]','主人，请输入验证码~');
            $('#pcode2').parent().parent().append(err);
            $('#pcode2').focus();
            return false;
        }
		var index = mccms.layer.load();
		$.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/user/login?callback=?', {name:name,pass:pass,islog:is,pcode:pcode}, function(res) {
			mccms.layer.close(index);
            if(res.code == 1){
                mccms.del_cookie('pint');
            	mccms.user = res.user;
			    $('.j-user-avatar').attr('src',mccms.user.pic);
			    $('.j-user-gold').html(mccms.user.cion);
			    $('.nickname').html(mccms.user.nichen);
            	mccms.layer.close(mccms.index);
            	mccms.msg(res.msg,1);
                window.location.reload();
            }else{
        		mccms.msg(res.msg);
                if(res.pcode == 1){
                    mccms.set_cookie('pint',1);
                    $('.title').hide();
                    $('.piccode').show();
                    $('.code_pic2').click();
                }
        	}
        });
    });
	//发送短信验证码
	$('.j-sms-btn').click(function(){
    	var tel = $('#regtel').val();
    	if(tel == ''){
    		var err = errhtml.replace('[msg]','主人，请输入正确的手机号~');
    		$('#regtel').parent().parent().append(err);
            $('#regtel').focus();
    		return false;
    	}
	});
	//刷新验证码
	$('.code_pic,.code_pic2').click(function(){
		$(this).attr('src','//'+Mcpath.url+Mcpath.web+'index.php/api/code?t='+Math.random());
	});
	//发送验证码
	$('.pcode-send').click(function(){
		var tel = $('#regtel').val();
		var pcode = $('#regpcode').val();
		if(!(/^1[3456789]\d{9}$/.test(tel))){
    		var err = errhtml.replace('[msg]','主人，请输入正确的手机号码~');
    		$('#regtel').parent().parent().append(err);
            $('#regtel').focus();
    		return false;
		}
		if(pcode == ''){
    		var err = errhtml.replace('[msg]','请输入上面的图形验证码~');
    		$('#regpcode').parent().parent().append(err);
            $('#regpcode').focus();
    		return false;
		}
		//发送
		$.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/code/tel_send/reg?callback=?', {tel:tel,code:pcode}, function(res) {
            if(res.code == 1){
				$('.pic-code,.code_pic').hide();
				$('.tel-code').show();
				tindex = setInterval(function(){
					time--;
					if(time == 0){
						time = 60;
						window.clearInterval(tindex);
						$('.tcode-send').removeClass('disabled').attr('data-status','false').html('重新发送');
					}else{
						$('.tcode-send').addClass('disabled').attr('data-status','true').html(time+'S后重发');
					}
				},1000);
            }else{
            	mccms.msg(res.msg);
            }
        });
	});
	//再次发送验证码
	$('.tcode-send').click(function(){
		var init = $(this).attr('data-status');
		if(init == 'false'){
			$('#regpcode').val('');
			$('.pic-code').show();
			$('.tel-code').hide();
			$('.code_pic').attr('src','//'+Mcpath.url+Mcpath.web+'index.php/api/code').show();
		}
	});
	//注册提交
	$('.j-register-submit').click(function(){
		var tel = $('#regtel').val();
    	var code = $('#regtcode').val();
    	var pcode = $('#regpcode').val();
		var pass = $('#regpass').val();
    	if(!(/^1[3456789]\d{9}$/.test(tel))){
    		var err = errhtml.replace('[msg]','主人，请输入正确手机号码~');
    		$('#regtel').parent().parent().append(err);
            $('#regtel').focus();
    		return false;
    	}
    	if(Mcpath.istel == 0 && code == ''){
    		if($(".code_pic").css("display") == 'none'){
	    		var err = errhtml.replace('[msg]','主人，请输入手机验证码~');
	    		$('#regtcode').parent().parent().append(err);
                $('#regtcode').focus();
    		}else{
	    		var err = errhtml.replace('[msg]','主人，请获取短信验证码~');
	    		$('#regpcode').parent().parent().append(err);
                $('#regpcode').focus();
    		}
    		return false;
    	}
    	if(Mcpath.istel == 1 && pcode == ''){
            var err = errhtml.replace('[msg]','主人，请输入右边的图形验证码~');
            $('#regpcode').parent().parent().append(err);
            $('#regpcode').focus();
            return false;
        }
        if(!(/^[\S]{6,16}$/.test(pass))){
            var err = errhtml.replace('[msg]','密码必须6到16位，且不能出现空格');
    		$('#regpass').parent().parent().append(err);
            $('#regpass').focus();
    		return false;
    	}
		var index = mccms.layer.load();
		$.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/user/reg?callback=?', {tel:tel,pass:pass,code:code,pcode:pcode}, function(res) {
			mccms.layer.close(index);
            if(res.code == 1){
            	mccms.user = res.user;
			    $('.j-user-avatar').attr('src',mccms.user.pic);
			    $('.j-user-gold').html(mccms.user.cion);
			    $('.nickname').html(mccms.user.nichen);
            	mccms.layer.close(mccms.index);
            	mccms.msg(res.msg,1);
            }else{
        		mccms.msg(res.msg);
        		$('.code_pic').click();
        	}
        });
    });
    //监听回车提交登陆
	$(document).keyup(function(e){
		if(e.keyCode ==13 && $('.dialog-login').length > 0){
			if($('.tab-content .active').attr('data-contentid') == 'login'){
				$('.j-login-submit').click();
			}else{
				$('.j-register-submit').click();
			}
		}
	});
}
//热搜渲染
var rendHotSearch = function() {
	if(!$('.search-hot').hasClass('rended')) {
        mccms.tpl('.hot-search-tpl','.search-hot','api/book/hot');
	}
	$('body').on("mouseenter",".hot-item",function(){
    	$(this).addClass('active').siblings().removeClass('active')
    });
    $('.search-hot').addClass('rended');
};
//观看记录
var rendRead = function() {
	//已登陆
	if(mccms.user.log == 0) {
    	$('.login-notice').removeClass('hide');
  	}else{
        $('.login-notice').addClass('hide');
    }
    if($('.header-his-tpl').length > 0){
    	$.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/rend/history/book?callback=?',{t:Math.random()}, function(res) {
            if(res.code == 1){
    			if (res.data.length > 0) {
          			$('.his-empty').addClass('hide');
                    $('.check-all').removeClass('hide');
          			mccms.laytpl($('.header-his-tpl').html()).render(res.data, function(str) {
            			$('.header-his-dialog .item-container').html(str)
          			});
        		}
            }
        });
    }
    $('.header-his-dialog').show();
};
//收藏记录
var rendFav = function() {
	//已登陆
	if(mccms.user.log == 1) {
		$('.nologin').addClass('hide');
        if($('.header-coll-tpl').length > 0){
            $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/rend/fav/book?callback=?',{t:Math.random()}, function(res) {
                if(res.code == 1){
                    if (res.data.length > 0) {
                        mccms.laytpl($('.header-coll-tpl').html()).render(res.data, function(str) {
                            $('.header-coll-dialog .item-container').html(str);
                        });
                        $('.collect-empty').addClass('hide');
                        $('.header-coll-dialog .check-all').removeClass('hide');
                    }else{
                        $('.collect-empty').removeClass('hide');
                        $('.header-coll-dialog .check-all').addClass('hide');
                    }
                }
            });
        }
    }else{
        $('.collect-empty').addClass('hide');
        $('.header-coll-dialog .check-all').addClass('hide');
    }
    $('.header-coll-dialog').show();
};
// 详情页
var get_info = function(_bid) {
    get_comment(_bid);
    isBuyRead(_bid);
    //收藏
    mccms.isfav({did:_bid,type:'book'},function(res){
        if(res.code == 1){
            $('.j-info-collect').addClass('active').html('已收藏');
        }
    });
    $('body').on('click', '.btn--collect', function() {
        if(mccms.user.log == 0){
            regLog();
        }else{
            mccms.fav({did:_bid,type:'book'},function(res){
                if(res.code == 1){
                    if(res.cid == 0){
                        $('.j-info-collect').removeClass('active').html('收藏');
                    }else{
                        $('.j-info-collect').addClass('active').html('已收藏');
                    }
                }else{
                    mccms.msg(res.msg);
                }
            });
        }
    });
    if($('.j-catalog-list li').length > 60){
        $('.j-catalog-more').removeClass('hide');
    }
    //点击更多
    $('.j-catalog-more').click(function(){
        if($(this).attr('data-more') == 'on'){
            $('.j-catalog-list').addClass('height-auto');
            $(this).attr('data-more','off').removeClass('on').addClass('off');
            $(this).children('span').html('收起');
        }else{
            $('.j-catalog-list').removeClass('height-auto');
            $(this).attr('data-more','on').removeClass('off').addClass('on');
            $(this).children('span').html('点击展开更多章节');
        }
    });
	$('.j-catalog-sort').addClass('reversed');
	var list = $('.j-catalog-list');
	list.append(list.find('.item').get().reverse());
	$('.j-catalog-sort').click(function() {
		if ($(this).hasClass('reversed')) {
			$(this).removeClass('reversed');
            $(this).children('i').removeClass('icon-ic_detail_mldx').addClass('icon-ic_detail_mlsx');
            $(this).children('span').html('正序');
		} else {
			$(this).addClass('reversed');
            $(this).children('i').addClass('icon-ic_detail_mldx').removeClass('icon-ic_detail_mlsx');
            $(this).children('span').html('倒序');
		}
		var list = $('.j-catalog-list');
		list.append(list.find('.item').get().reverse());
	})
	// 先隐藏，后显示，防止html显示会闪一下
	$('.j-catalog-sort').show();
	$('.j-catalog-list').show();
    //介绍展开收起
    if($('.j-info-desc span').html().length > 120) $('.j-info-desc-toggle').show();
    $('.j-info-desc-toggle').click(function(){
        if($(this).hasClass('ic_detail_txtsq')) {
            $(this).removeClass('ic_detail_txtsq').addClass('ic_detail_txtzk').html('展开');
            $('.j-info-desc').css('max-height','40px');
        }else{
            $(this).addClass('ic_detail_txtsq').removeClass('ic_detail_txtzk').html('收起');
            $('.j-info-desc').css('max-height','none');
        }
    });
    //人气榜hove事件
    $('.j-hot-item').hover(function(){
        $('.j-hot-item').removeClass('active');
        $(this).addClass('active');
    });
    //打赏礼物、月票
    $('.j-info-funds').click(function() {
        var type = $(this).data('type');
        if(mccms.user.log == 0){
            regLog();
        }else{
            if(type == 'reward'){
                showGift(_bid);
            }else{
                showTicket(_bid);
            }
        }
    });
}
//未读消息
var isMessage = function(){
	mccms.message(function(res){
        if(res.code == 1 && res.count > 0){
            var html = '<em style="position: absolute;width: 20px;height: 18px;line-height: 18px;text-align: center;font-size: 12px;font-style: normal;color: #FFF;top: -6px;right: -8px;background: #f00;border-radius: 9px;">'+res.count+'</em>';
        	$('.j-user-msg ').append(html);
        }
    });
}
//月票打赏
var showTicket = function(_bid) {
    var ticketTpl = $('.ticket-box').html();
    $('.j-user-ticket').html(mccms.user.ticket);
    var gindex = layer.open({
        type: 1,
        closeBtn: 0,
        title: false,
        content: ticketTpl,
        area: ['440px', '301px'],
        success: function(layero, layerIdx) {
            mccms.index = layerIdx;
            gindex = layerIdx;
        }
    });
    //月票切换
    $('.j-dialog-ticket-recharge').attr('data-num',$('.j-dialog-ticket-item').eq(0).attr('data-num'));
    if(parseInt($('.j-dialog-ticket-recharge').attr('data-num')) > parseInt(mccms.user.ticket)){
        $('.j-dialog-ticket-recharge').html('月票不足，去购买&gt;');
    }
    $('.j-dialog-ticket-item').click(function() {
        $('.j-dialog-ticket-recharge').attr('data-num',$(this).attr('data-num'));
        $(this).addClass('active').siblings().removeClass('active');
        if(parseInt($(this).attr('data-num')) > parseInt(mccms.user.ticket)){
            $('.j-dialog-ticket-recharge').html('月票不足，去购买&gt;');
        }else{
            $('.j-dialog-ticket-recharge').html('立即赠送 (＾Ｕ＾)');
        }
    });
    $('.j-dialog-ticket-recharge').click(function(){
        if(mccms.user.log == 0){
            regLog();
        }else if(parseInt($(this).attr('data-num')) > parseInt(mccms.user.ticket)){
            Pay_Show('yp');
        }else{
            var num = $(this).attr('data-num');
            mccms.ticket_send({bid:_bid,ticket:num});
        }
    });
    //关闭弹框
    $('.j-dialog-close').click(function() {
        mccms.layer.close(gindex);
    });
}
//打赏礼物弹窗
var showGift = function(_bid) {
    $('.ucion').html(mccms.user.cion);
    var giftTpl = $('.gift-box').html();
    var gindex = layer.open({
        type: 1,
        closeBtn: 0,
        title: false,
        content: giftTpl,
        area: ['470px', '477px'],
        success: function(layero, layerIdx) {
            mccms.index = layerIdx;
            gindex = layerIdx;
        }
    });
    //礼物切换
    $('.j-dialog-gift-reward').attr('data-id',$('.j-dialog-gift-item').eq(0).attr('data-id'));
    $('.j-dialog-gift-reward').attr('data-cion',$('.j-dialog-gift-item').eq(0).attr('data-cion'));
    if(parseInt($('.j-dialog-gift-reward').attr('data-cion')) > parseInt(mccms.user.cion)){
        $('.j-dialog-gift-reward').html('余额不足┭┮﹏┭┮去充值');
    }
    $('.j-dialog-gift-item').click(function() {
        $('.j-dialog-gift-reward').attr('data-id',$(this).attr('data-id'));
        $('.j-dialog-gift-reward').attr('data-cion',$(this).attr('data-cion'));
        $(this).addClass('active').siblings().removeClass('active');
        if(parseInt($(this).attr('data-cion')) > parseInt(mccms.user.cion)){
            $('.j-dialog-gift-reward').html('余额不足┭┮﹏┭┮去充值');
        }else{
            $('.j-dialog-gift-reward').html('立即打赏 (＾Ｕ＾)');
        }
    });
    //打赏礼物
    $('.j-dialog-gift-reward').click(function() {
        if(mccms.user.log == 0){
            regLog();
        }else if(parseInt($(this).attr('data-cion')) > parseInt(mccms.user.cion)){
            Pay_Show('jb');
        }else{
            var gid = $(this).attr('data-id');
            mccms.sendgift({bid:_bid,gid:gid});
        }
    });
    //关闭弹框
    $('.j-dialog-close').click(function() {
        mccms.layer.close(gindex);
    });
}
//评论
var get_comment = function(_bid){
	//显示列表
	mccms.comment({bid:_bid,page:1});
	//监听评论点击
    $('.comment-kuang').click(function(){
    	if(mccms.user.log == 0){
			regLog();
		}else{
	    	if($(this).hasClass('has-placeholder')) {
	    		$(this).removeClass('has-placeholder').html('');
	    	}
	    }
    });
    //点击表情
    $('body').on("click",".j-comment-emoji",function(){
    	if(mccms.user.log == 0){
			regLog();
		}else{
    		$(this).children('.j-comment-face-box').show();
    	}
    });
    $('.j-face-item').click(function(event){
    	$('.comment-kuang').click();
    	var em = $(this).attr('data-id');
    	var html = $('.comment-kuang').html();
    	$('.comment-kuang').html(html+em);
    	$('.j-comment-face-box').hide();
    	//event.stopPropagation();
    });
    $('body').on("click",".face-reply",function(event){
    	var id = $(this).parent().attr('data-cid');
    	var em = $(this).attr('data-id');
    	var html = $('.comment-text-'+id).html();
    	$('.comment-text-'+id).html(html+em);
    	event.stopPropagation();
    });
    //回复框
    $('body').on("click",".j-reply-btn",function(){
    	if(mccms.user.log == 0){
			regLog();
		}else{
	    	var id = $(this).attr('data-id');
	    	$('.reply-kuang-'+id).toggle();
	    }
    });
    //提交评论
    $('.j-comment-submit').click(function(){
    	if(mccms.user.log == 0){
			regLog();
		}else{
	    	var bid = $(this).attr('data-id');
	    	var text = $('.comment-kuang').html().replace(/<.*?>/g,"");
	    	if($('.comment-kuang').hasClass('has-placeholder')) {
	    		mccms.msg('请输入评论内容');
	    	}else{
	    		mccms.comment_send({bid:bid,text:text},function(res){
                    if(res.code == 1){
                        mccms.comment({bid:bid,page:1});
                    }else{
                        mccms.msg(res.msg);
                    }
                });
	    	}
	    }
    });
    //提交回复
    $('body').on("click",".j-comment-reply-btn",function(){
    	if(mccms.user.log == 0){
			regLog();
		}else{
	    	var bid = $(this).attr('data-bid');
	    	var cid = $(this).attr('data-cid');
	    	var fid = $(this).attr('data-fid');
	    	var text = $('.comment-text-'+cid).html().replace(/<.*?>/g,"");
	    	if(text == '') {
	    		mccms.msg('请输入回复内容');
	    	}else{
                mccms.comment_send({bid:bid,text:text,cid:cid,fid:fid},function(res){
                    if(res.code == 1){
                        mccms.comment({bid:bid,page:1});
                    }else{
                        mccms.msg(res.msg);
                    }
                });
	    	}
	    }
    });
    //监听赞评论
    $('body').on("click",".j-like-btn,.j-reply-like-btn",function(){
    	if(mccms.user.log == 0){
			regLog();
		}else{
	    	var id = $(this).attr('data-id');
	    	var fid = $(this).attr('data-fid');
	    	var _this = $(this);
            $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/comment/zan?callback=?',{id:id,fid:fid}, function(res) {
				if(res.code == 2){
					regLog();
	            } else if(res.code == 1){
	            	if(res.zt == 0){
	            		$(_this).removeClass('active');
	            	}else{
	            		$(_this).addClass('active');
	            	}
	            	$(_this).children('span').html(res.zan);
	            }else{
	            	mccms.msg(res.msg);
	            }
	        });
		}
    });
}
//充值
var Pay_Show = function(type){
    var payhtml = '<div id="dialog-pay"><div class="j-dialog-pay dialog-pay"lay-filter="dialog-pay"><div class="j-pay-header dialog-pay_header"><div class="j-user-name dialog-pay_header--username">{{d.unichen}}</div><div class="dialog-pay_header--foundinfo"><span class="j-user-gold">{{d.ucion}}</span>{{d.cion_name}}<em>|</em><span class="j-user-ticket">{{d.ticket}}</span>月票</div></div><div class="j-pay-close dialog-pay_header--close"><i class="iconfont icon-ic_buy_toast_close"></i></div><!--tab--><div class="dialog-pay_body layui-tab layui-tab-brief layui-tab-mkz"><!--tab-title--><ul class="j-tab-title dialog-pay_body--tab-title layui-tab-title"><li data-type="jb"class="layui-this">充值{{d.cion_name}}</li><li data-type="yp">购买月票</li><li data-type="vip">购买VIP</li></ul><div class="dialog-pay_body--tab-body"><!--全部列表--><div class="j-tab-content"><div class="j-paytype-item"><div class="j-paytype-jb item_row hide"><h3 class="item--title"><i class="item--title-icon"></i>购买项目<span class="item--pay-tip">（充值比例：1元={{d.rmb_cion}}{{d.cion_name}}，充值数量：必须是10的整数倍）</span></h3><ul class="item--content clearfix">{{#layui.each(d.pay.cion,function(index,item){}}<li class="j-item-btn item--btn cion--btn{{ index == 0 ? \' active\' : \'\' }}"data-cion="{{item.cion}}"data-rmb="{{item.rmb}}"><p>{{item.cion}}{{d.cion_name}}</p><p class="item--price">￥{{item.rmb}}</p><i class="j-item-icon item--icon iconfont icon-ic_buylist_choose"{{index>0?\' style="display: none;"\':\'\'}}></i></li>{{#})}}<li class="j-item-btn item--btn item--btn-input cion--btn"data-cion="0"data-rmb="0"><input class="j-item-input item--input cion-input" type="text" value="" placeholder="其他金额" autocomplete="off" oninput="value=value.replace(\/[^\\d]\/g,\'\')"><i class="j-item-icon item--icon iconfont icon-ic_buylist_choose"style="display: none;"></i></li></ul></div><!--月票--><div class="j-paytype-yp item_row hide"><h3 class="item--title"><i class="item--title-icon"></i>购买项目<span class="item--pay-tip">（购买比例：1元={{d.rmb_cion}}{{d.cion_name}}）</span></h3><ul class="item--content clearfix">{{#layui.each(d.pay.ticket,function(index,item){}}<li class="j-item-btn item--btn yp-btn{{ index == 0 ? \' active\' : \'\' }}"data-num="{{item.num}}"data-rmb="{{item.rmb}}"data-cion="{{item.cion}}"><p>{{item.num}}张月票</p><p class="item--price">￥{{item.rmb}}</p><i class="j-item-icon item--icon iconfont icon-ic_buylist_choose"{{index>0?\' style="display: none;"\':\'\'}}></i></li>{{#})}}<li class="j-item-btn item--btn item--btn-input yp-btn"data-num="0"data-rmb="0"data-cion="0"><input class="j-item-input item--input yp-input" type="text" value="" placeholder="其他数量" autocomplete="off" oninput="value=value.replace(\/[^\\d]\/g,\'\')"><i class="j-item-icon item--icon iconfont icon-ic_buylist_choose"style="display: none;"></i></li></ul></div><!--VIP--><div class="j-paytype-vip item_row hide"><h3 class="item--title"><i class="item--title-icon"></i>购买项目<span class="item--pay-tip">（购买比例：1元={{d.rmb_cion}}{{d.cion_name}}）</span></h3><ul class="item--content clearfix"id="vip_product_list">{{#layui.each(d.pay.vip,function(index,item){}}<li class="j-item-btn item--btn vip-btn{{ index == 0 ? \' active\' : \'\' }}"data-day="{{item.day}}"data-rmb="{{item.rmb}}"><p>{{item.name}}</p><p class="item--price">￥{{item.rmb}}</p><p class="item--recome">{{item.txt}}</p><i class="j-item-icon item--icon iconfont icon-ic_buylist_choose"{{index>0?\' style="display: none;"\':\'\'}}></i></li>{{#})}}</ul></div></div><!--支付方式--><div class="item_row"><h3 class="item--title"><i class="item--title-icon"></i>支付方式</h3><ul class="item--content clearfix paytype-box"><li class="j-paytype-btn item--paytype-btn paytype-cionpay hide"data-pay-type="cion"><i class="iconfont item--pay-icon icon-ic_toast_yb"></i>{{d.cion_name}}支付<i class="j-item-icon item--icon iconfont icon-ic_buylist_choose"style="display: none;"></i></li><li class="j-paytype-btn item--paytype-btn paytype-wxpay{{ d.pay.is_wxpay == 1 ? \' hide\' : \'\' }}"data-pay-type="wxpay"><i class="item--pay-icon iconfont icon-ic_buytoast_wx"></i>微信支付<i class="j-item-icon item--icon iconfont icon-ic_buylist_choose"style="display: none;"></i></li><li class="j-paytype-btn item--paytype-btn paytype-alipay{{ d.pay.is_alipay == 1 ? \' hide\' : \'\' }}"data-pay-type="alipay"><i class="item--pay-icon iconfont icon-ic_buytoast_zfb"></i>支付宝支付<i class="j-item-icon item--icon iconfont icon-ic_buylist_choose"style="display: none;"></i></li><li class="j-paytype-btn item--paytype-btn paytype-qqpay{{ d.pay.is_qqpay == 1 ? \' hide\' : \'\' }}"data-pay-type="qqpay"><i class="item--pay-icon iconfont icon-ic_buytoast_qq"></i>QQ钱包支付<i class="j-item-icon item--icon iconfont icon-ic_buylist_choose"style="display: none;"></i></li></ul></div><!--应付金额--><div class="item_row"><span class="item--inline-title">应付金额：</span><span class="item--found"><strong class="j-pay-num item--num">10</strong><em class="pay_ext">元</em></span><!--提醒信息--><span class="j-pay-warning item--warning hide"style="display: none;">{{d.cion_name}}不足，请修改支付方式或<strong class="j-go-gold item--link">充值{{d.cion_name}}</strong></span><!--qrcode--><iframe src=""id="j-alipay-qrcode"class="qrcode-alipay"width="120"height="120"frameborder="0"scrolling="no"></iframe></div><!--按钮--><div class="item_row"><!--disabled--><div class="j-pay-btn item_pay-btn layui-btn layui-btn-danger">确认支付</div></div></div></div></div></div></div>';
    var rmb_cion = 1,cion_name = '金币',pindex = null;
    var post = {type:'cion',rmb:0,day:0,num:0,pay:''};
    var index = mccms.layer.load();
    $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/pay?callback=?',{t:Math.random()}, function(res) {
        mccms.layer.close(index);
        if(res.code == 1){
            rmb_cion = res.data.rmb_cion;
            cion_name = res.data.cion_name;
            mccms.laytpl(payhtml).render(res.data, function(html){
                mccms.layer.open({
                    type: 1,
                    closeBtn: 0,
                    title: false,
                    content: html,
                    shade: 0.6,
                    offset: 'auto',
                    area: ['700px', '480px'],
                    success: function(layero, layerIdx) {
                        mccms.index = layerIdx;
                        $('.j-paytype-item .item_row').hide();
                        $('.j-paytype-'+type).show();
                        $(".j-tab-title li").removeClass('layui-this');
                        $(".j-tab-title li[data-type='"+type+"']").addClass('layui-this');
                        if(type == 'jb'){
                            post.type = 'cion';
                            post.rmb = $('.cion--btn').eq(0).attr('data-rmb');
                        }else if(type=='yp'){
                            post.type = 'ticket';
                            post.pay = 'cion';
                            post.rmb = $('.yp-btn').eq(0).attr('data-rmb');
                            post.num = $('.yp-btn').eq(0).attr('data-num');
                        }else{
                            post.type = 'vip';
                            post.pay = 'cion';
                            post.day = $('.vip-btn').eq(0).attr('data-day');
                            post.rmb = $('.vip-btn').eq(0).attr('data-rmb');
                        }
                        if(type == 'jb'){
                            $('.paytype-box li').each(function(){
                                $('.paytype-cionpay').addClass('hide');
                                if(!$(this).hasClass('hide') && $(this).attr('data-pay-type') != 'cion'){
                                    post.pay = $(this).attr('data-pay-type');
                                    $(this).addClass('active');
                                    $(this).children('.j-item-icon').show();
                                    return false;
                                }
                            });
                        }else{
                            $('.paytype-box li').each(function(){
                                $('.paytype-cionpay').removeClass('hide');
                                if(!$(this).hasClass('hide')){
                                    $(this).addClass('active');
                                    $(this).children('.j-item-icon').show();
                                    return false;
                                }
                            });
                        }
                        get_rmb();
                    }
                });
            });
        }else{
            mccms.msg(res.msg);
        }
    });
    //关闭窗口
    $('body').on("click",".j-pay-close",function(){
        window.clearInterval(pindex);
        mccms.layer.close(mccms.index);
    });
    //导航切换按钮
    $('body').on("click",".dialog-pay_body--tab-title li",function(){
        var type = $(this).attr('data-type');
        get_tabs(type);
    });
    //选择金币
    $('body').on("click",".cion--btn",function(){
        post.rmb = $(this).attr('data-rmb');
        post.cion = $(this).attr('data-cion');
        if(post.rmb == 0){
            $('.j-pay-btn').addClass('disabled');
        }else{
            $('.j-pay-btn').removeClass('disabled');
        }
        $('.cion--btn').removeClass('active');
        $('.cion--btn').children('.j-item-icon').hide();
        $(this).addClass('active');
        $(this).children('.j-item-icon').show();
        get_rmb();
    });
    //监控输入金币
    $('body').on("input propertychange",".cion-input",function(){
        post.rmb = $(".cion-input").val();
        if(post.rmb == ''){
            post.rmb = 0;
            $('.j-pay-btn').addClass('disabled');
        }else{
            $('.j-pay-btn').removeClass('disabled');
        }
        get_rmb();
    });
    //选择月票
    $('body').on("click",".yp-btn",function(){
        post.rmb = $(this).attr('data-rmb');
        post.num = $(this).attr('data-num');
        if(post.rmb == 0){
            $('.j-pay-btn').addClass('disabled');
        }else{
            $('.j-pay-btn').removeClass('disabled');
        }
        $('.yp-btn').removeClass('active');
        $('.yp-btn').children('.j-item-icon').hide();
        $(this).addClass('active');
        $(this).children('.j-item-icon').show();
        get_rmb();
    });
    //监控输入月票
    $('body').on("input propertychange",".yp-input",function(){
        post.num = $(".yp-input").val();
        if(post.num == ''){
            post.num = 0;
            $('.j-pay-btn').addClass('disabled');
        }else{
            $('.j-pay-btn').removeClass('disabled');
        }
        post.rmb = post.num;
        get_rmb();
    });
    //选择VIP
    $('body').on("click",".vip-btn",function(){
        post.rmb = $(this).attr('data-rmb');
        post.day = $(this).attr('data-day');
        if(post.rmb == 0){
            $('.j-pay-btn').addClass('disabled');
        }else{
            $('.j-pay-btn').removeClass('disabled');
        }
        $('.vip-btn').removeClass('active');
        $('.vip-btn').children('.j-item-icon').hide();
        $(this).addClass('active');
        $(this).children('.j-item-icon').show();
        get_rmb();
    });
    //支付方式
    $('body').on("click",".j-paytype-btn",function(){
        post.pay = $(this).attr('data-pay-type');
        $('.j-paytype-btn').removeClass('active');
        $('.j-paytype-btn').children('.j-item-icon').hide();
        $(this).addClass('active');
        $(this).children('.j-item-icon').show();
        get_rmb();
    });
    //充值金币
    $('body').on("click",".j-go-gold",function(){
        post.type = 'cion';
        post.pay = '';
        get_tabs('jb');
    });
    //提交请求
    $('body').on("click",".j-pay-btn",function(){
        if(!$(this).hasClass('disabled')){
            $(this).addClass('disabled');
            var index = mccms.layer.load();
            $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/pay/save?callback=?', post, function(res) {
                mccms.layer.close(index);
                if(res.code == 1){
                    if(res.pay == 1){
                        $('#j-alipay-qrcode').attr('src',res.payurl);
                        pindex = setInterval(function(){
                            get_payinit(res.did);
                        },3000);
                    }else{
                        mccms.msg(res.msg,1);
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    }
                }else{
                    $('.j-pay-btn').removeClass('disabled');
                    mccms.msg(res.msg);
                }
            });
        }
    });
    //判断订单是否完成
    function get_payinit(did){
        $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/pay/init?callback=?', {id:did}, function(res) {
            if(res.code == 1){
                window.clearInterval(pindex);
                mccms.msg(res.msg,1);
                setTimeout(function() {
                    window.location.reload();
                }, 3000);
            }
        });
    }
    //计算金额
    function get_rmb(){
        window.clearInterval(pindex);
        $('.j-alipay-qrcode').attr('src','');
        if(post.pay == 'cion'){
            $('.j-pay-num').html(post.rmb*rmb_cion);
            $('.pay_ext').html(cion_name);
            if(mccms.user.cion < post.rmb*rmb_cion){
                $('.j-pay-warning').show();
                $('.j-pay-btn').addClass('disabled');
            }else{
                $('.j-pay-warning').hide();
                $('.j-pay-btn').removeClass('disabled');
            }
        }else{
            $('.j-pay-num').html(post.rmb);
            $('.pay_ext').html('元');
            $('.j-pay-warning').hide();
            $('.j-pay-btn').removeClass('disabled');
        }
    }
    //切换导航
    function get_tabs(type){
        $('.j-paytype-item .item_row').hide();
        $('.j-paytype-'+type).show();
        //支付方式
        $('.j-paytype-btn').removeClass('active');
        $('.j-paytype-btn .j-item-icon').hide();
        $(".j-tab-title li").removeClass('layui-this');
        $(".j-tab-title li[data-type='"+type+"']").addClass('layui-this');
        if(type == 'jb'){
            post.type = 'cion';
            post.rmb = $('.cion-btn').eq(0).attr('data-rmb');
            get_rmb();
            $('.paytype-box li').each(function(){
                $('.paytype-cionpay').hide();
                if(!$(this).hasClass('hide') && $(this).attr('data-pay-type') != 'cion'){
                    $(this).addClass('active');
                    $(this).children('.j-item-icon').show();
                    post.pay = $(this).attr('data-pay-type');
                    return false;
                }
            });
        }else{
            post.pay = 'cion';
            $('.paytype-cionpay').addClass('active').show();
            $('.paytype-cionpay .j-item-icon').show();
            if(type == 'yp'){
                post.type = 'ticket';
                post.rmb = $('.yp-btn').eq(0).attr('data-rmb');
                post.num = $('.yp-btn').eq(0).attr('data-num');
            }else{
                post.type = 'vip';
                post.rmb = $('.vip-btn').eq(0).attr('data-rmb');
                post.day = $('.vip-btn').eq(0).attr('data-day');
            }
            get_rmb();
        }
    }
}
//章节阅读记录
var isBuyRead = function(bid){
    $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/book/buyread?callback=?', {bid:bid}, function(res) {
        if(res.code == 1){
            var read = res.read,buy = res.buy,pay = res.pay;
            for (var i = 0; i < read.length; i++) {
                $('.book-chapter-'+read[i].cid).children('.icon-ic_readlist_look1').removeClass('hide');
            }
            for (var i = 0; i < buy.length; i++) {
                $('.book-chapter-'+buy[i].cid).children('.icon-ic_detail_ml_yff').removeClass('hide');
            }
            for (var i = 0; i < pay.length; i++) {
                $('.book-chapter-'+pay[i].id).children('.icon-ic_detail_ml_vip').removeClass('hide');
            }
        }
    });
}
var swiper_index = function(){
    var _swiper3dIndex = '';
    $('.swiper-wrapper').waterwheelCarousel({
        flankingItems: 2,
        separation: 55,
        sizeMultiplier: .7,
        autoPlay: 3000,
        movedToCenter: function(item){
            _swiper3dIndex = $(item.context).data('id');
            $('.recom-info').addClass('hide');
            $('#swiper_'+_swiper3dIndex).removeClass('hide');
        }
    });
}
var get_banner = function(){
    var bindex = 0,left = 0,row_w = $('.banner-slide__item').width()+12;
    var width = $('.banner-slide__item').length * row_w;
    $('.banner-slider').css('width',width+'px');
    setInterval(function(){
        left = left+row_w;
        if((left+row_w*5) > width) left = 0;
        $(".banner-slider").animate({marginLeft:'-'+left+'px'},1000);
    },5000);
    //上一页
    $('.slide-prev').click(function(){
        left = left-row_w;
        if(left < 0) left = 0;
        $(".banner-slider").animate({marginLeft:'-'+left+'px'},1000);
    });
    //下一页
    $('.slide-next').click(function(){
        left = left+row_w;
        if((left+row_w*5) > width) left = 0;
        $(".banner-slider").animate({marginLeft:'-'+left+'px'},1000);
    });
}
// 阅读页
var get_read = function() {
    //主题
    read_theme();
    //打赏
    $('.j-reward-button').click(function(){
        showGift(bid);
    });
    //月票
    $('.j-ticket-button').click(function(){
        showTicket(bid);
    });
    //收藏
    mccms.isfav({did:bid,type:'book'},function(res){
        if(res.code == 1){
            $('.j-collect-button .yes').removeClass('hide');
            $('.j-collect-button .no').addClass('hide');
        }
    });
    $('body').on('click', '.j-collect-button div', function() {
        if(mccms.user.log == 0){
            regLog();
        }else{
            mccms.fav({did:bid,type:'book'},function(res){
                if(res.code == 1){
                    mccms.msg(res.msg,-1);
                    if(res.cid == 0){
                        $('.j-collect-button .yes').addClass('hide');
                        $('.j-collect-button .no').removeClass('hide');
                    }else{
                        $('.j-collect-button .yes').removeClass('hide');
                        $('.j-collect-button .no').addClass('hide');
                    }
                }else{
                    mccms.msg(res.msg);
                }
            });
        }
    });
    //章节列表
    mccms.tpl('.rd-catalog-tpl','.chapter-list','api/book/chapter',{bid:bid,cid:cid});
    $('.dialog-control').click(function(){
        var type = $(this).data('type');
        $('.'+type+'-dialog').removeClass('hide');
    });
    $('.dialog-close').click(function(){
        $('.control-dialog').addClass('hide');
        return false;
    });
    //上一章
    $('.prev-btn').click(function(){
        if(slink == ''){
            mccms.msg('已经是第一章了',-1);
        }else{
            window.location.href = slink;
        }
    });
    //下一章
    $('.next-btn').click(function(){
        if(xlink == ''){
            mccms.msg('没有下一章了',-1);
        }else{
            window.location.href = xlink;
        }
    });
    //判断收费
    if(vip > 0 || cion > 0){
        $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/book/isbuy?callback=?', {bid:bid,cid:cid}, function(res) {
            if(res.code == 2){
                regLog();
            }else if(res.code == 3){
                if(res.type == 'vip'){
                    $('.pay-title').html('该章节是Vip章节，需购买VIP后方可阅读');
                    $('.price-tag,.pay-vip-tip').removeClass('hide');
                    $('.pay-cion,.pay-cion-tip').addClass('hide');
                }else{
                    $('.pay-title').html('该章节是收费章节，需购买后方可阅读');
                    $('.price-number').html(cion);
                    $('.price-tag,.pay-vip-tip').addClass('hide');
                    $('.pay-cion,.pay-cion-tip').removeClass('hide');
                    if(mccms.user.cion < cion){
                        $('.pay-cion-tip').html('余额不足，去充值&gt;');
                    }
                }
                $('.pay-flow-dialog').removeClass('hide');
            }else if(res.code == 1){
                if(res.cion) mccms.msg('已消耗：'+res.cion,-1);
                $('.content').html(res.text);
                $('.pay-flow-dialog').addClass('hide');
            }else{
                mccms.msg(res.msg,-1);
            }
        });
        //购买章节
        $('.pay-cion-tip').click(function(){
            if(mccms.user.cion < cion){
                Pay_Show('jb');
            }else{
                var auto = $('.auto-check').prop("checked") ? 1 : 0;
                $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/book/buy/'+bid+'/'+cid+'/'+auto+'?callback=?',function(res) {
                    if(res.code == 2){
                        regLog();
                    }else if(res.code == 3){
                        Pay_Show('jb');
                    }else if(res.code == 1){
                        if(res.cion) mccms.msg('已消耗：'+res.cion,-1);
                        $('.content').html(res.text);
                        $('.pay-flow-dialog').addClass('hide');
                    }else{
                        mccms.msg(res.msg,-1);
                    }
                });
            }
        });
    }
    //离开前记录阅读记录
    window.onbeforeunload = function () {
        mccms.read({did:bid,cid:cid,type:'book'});
    }
}
//主体设置
var read_theme = function(){
    //阅读模式
    var theme = mccms.get_cookie('theme');
    var font = mccms.get_cookie('font');
    var size = mccms.get_cookie('size');
    if(!theme) theme = 0;
    if(!font) font = 1;
    if(!size) size = 16;
    get_setting();
    //白天黑夜
    $('.night-control').click(function(){
        theme = theme == 7 ? 0 : 7;
        mccms.set_cookie('theme',theme);
        get_setting();
    });
    //背景
    $('.background-setting .item').click(function(){
        theme = $(this).data('id');
        mccms.set_cookie('theme',theme);
        get_setting();
    });
    //字体
    $('.font-setting .item').click(function(){
        font = $(this).data('id');
        mccms.set_cookie('font',font);
        get_setting();
    });
    //大小
    $('.size-setting .reduce').click(function(){
        size--;
        if(size < 11) size = 11;
        mccms.set_cookie('size',size);
        get_setting();
    });
    $('.size-setting .add').click(function(){
        size++;
        if(size > 40) size = 40;
        mccms.set_cookie('size',size);
        get_setting();
    });
    function get_setting(){
        $('.background-setting .item,.font-setting .item').removeClass('active');
        $('body').attr('class','theme-'+theme);
        $('.background-setting .bg'+(parseInt(theme)+1)).addClass('active');
        $('.size-setting .default').text(size);
        $('.font-setting .font'+font).addClass('active');
        if(theme == 7){
            $('.night-control .night').removeClass('hide');
            $('.night-control .day').addClass('hide');
        }else{
            $('.night-control .night').addClass('hide');
            $('.night-control .day').removeClass('hide');
        }
        if(font == 1){
            $('.content').css('font-family','Microsoft Yahei');
        }else if(font == 2){
            $('.content').css('font-family','SimSun');
        }else if(font == 3){
            $('.content').css('font-family','KaiTi');
        }
        $('.content').css('font-size',size+'px');
    }
}
//默认数据
$(function(){
    //头部浮动
    if($('.story-read').length == 0){
        $(window).scroll(function(){
            if($(window).scrollTop() >= 380){
                $('.story-header').addClass('fixed');
            }else{
                $('.story-header').removeClass('fixed');
            }
        });
    }
    //翻牌
    if($('.swiper-wrapper').length > 0){
        swiper_index();
    }
    //banner
    if($('.banner-slider').length > 0){
        get_banner();
    }
    //首页排行切换
    $('.story-rank__nav .nav-item').click(function(){
        var id = $(this).data('id');
        $('.story-rank__nav .nav-item').removeClass('active');
        $(this).addClass('active');
        $('.story-rank__list').hide();
        $('.story-rank__wrap .list-'+id).show();
        $('img.lazy').lazyload({
            container: $(this)
        });
    });
	//关闭open窗口
	$('body').on("click",".dialog__close",function(){
		mccms.layer.close(mccms.index);
	});
	//投月票
	$('.btn--ticket').click(function() {
		if(mccms.user.log == 0){
			regLog();
		}else{
			var mticket = $(this).attr('data-ticket');
			var mname = $(this).attr('data-name');
			var mrank = $(this).attr('data-rank');
			var bid = $(this).attr('data-id');
			showTicket(bid,mname,mticket,mrank);
		}
	});
	//搜索
	$('.J_search_btn').click(function() {
		var k = $('.J_search_input').val();
		if(k == ''){
			mccms.msg('请输入要搜索的关键字',3,'.J_search_input');
		}else{
            window.location.href = $(this).data('link')+'?key=' + k;
		}
	});
	$('.J_user_avatar').hover(function() {
        if(mccms.user.vip == '0') mccms.user.viptime = '会员已到期';
        $('.j-user-avatar').attr('src',mccms.user.pic);
        $('.j-user-gold').html(mccms.user.cion);
        $('.j-user-ticket').html(mccms.user.ticket);
        $('.j-user-name').html(mccms.user.nichen);
        $('.j-user-vip-time').html(mccms.user.viptime);
		if(mccms.user.log == 1){
            $('.login-btn').addClass('hide');
            $('.j-logout').removeClass('hide');
		}else{
            $('.login-btn').removeClass('hide');
            $('.j-logout').addClass('hide');
        }
		$('.header-user-dialog').show();
	},function(){
		$('.header-user-dialog').hide();
	});
	//退出登陆
	$('body').on("click",".logout",function(){
		if(mccms.user.log == 1) mccms.logout();
		mccms.msg('退出成功',1);
		setTimeout(function() {
			if(mccms.user.vip == '0') mccms.user.viptime = '会员已到期';
            $('.j-user-avatar').attr('src',mccms.user.pic);
            $('.j-user-gold').html(mccms.user.cion);
            $('.j-user-ticket').html(mccms.user.ticket);
            $('.j-user-name').html(mccms.user.nichen);
            $('.j-user-vip-time').html(mccms.user.viptime);
		}, 500);
	});
    //收藏
	$('.J_user_collect').hover(function() {
		rendFav();
	}, function() {
		$('.header-coll-dialog').hide()
	})
	//APP二维码
	$('.J_user_download').hover(function() {
		$('.header-down-dialog').show();
	},function(){
		$('.header-down-dialog').hide();
	});
    //历史
	$('.J_user_history').hover(function() {
		rendRead();
	},function(){
		$('.header-his-dialog').hide()
	});
	//搜索框点击事件
	$('.J_search_input').on('focus', function() {
		rendHotSearch();
		$('.story-header__input-dialog').show();
	})
	//关闭热搜框
	$('body').click(function(e) {
		var target = e.target;
	  	if($('.story-header__search').find(target).length == 0) {
	    	$('.story-header__input-dialog').fadeOut();
	  	}
	  	//评论框
	  	if($('.de-comment__textarea-wrap').find(target).length == 0 && $('.comment-kuang').html() == '') {
	    	$('.comment-kuang').html('看点槽点，不吐不快！别憋着，马上大声说出来吧～').addClass('has-placeholder');
	    }
	    //表情框
	    if($('.de-comment__item--btn-group').find(target).length == 0) {
	    	$('.j-comment-face-box').hide();
	    }
        //章节
        if($('.dialog-control').find(target).length == 0) {
            $('.control-dialog').addClass('hide');
        }
	});
	//返回顶部
	mccms.gotop('.j-story-toolbar-backtop,.j-top-button');
    //图片懒加载
    $("img.lazy").lazyload();
    //用户头像
    setTimeout(function() {
	    $('.j-user-avatar').attr('src',mccms.user.pic);
	    $('.j-user-gold').html(mccms.user.cion);
        $('.j-user-ticket').html(mccms.user.ticket);
	    $('.j-user-name').html(mccms.user.nichen);
        $('.j-user-vip-time').html(mccms.user.viptime);
	}, 1000);
    //充值虚拟币
    $('body').on("click",".cion-btn",function(){
        if(mccms.user.log == 0){
            regLog();
        }else{
            Pay_Show('jb');
        }
    });
    //充值月票
    $('body').on("click",".ticket-btn",function(){
        if(mccms.user.log == 0){
            regLog();
        }else{
            Pay_Show('yp');
        }
    });
    //充值VIP
    $('body').on("click",".j-buy-vip,.j-agin-buy-vip,.pay-vip-tip",function(){
        if(mccms.user.log == 0){
            regLog();
        }else{
            Pay_Show('vip');
        }
    });
    //人气榜切换
    $('.j-rank-item-type').click(function(){
        $('.j-rank-item-type').removeClass('active');
        $(this).addClass('active');
        var type = $(this).data('type');
        $('.j-rank-item-con').hide();
        $('.hot-'+type).show();
    });
    $('div[data-href]').click(function(e){
        var target = e.target;
        if(mccms.user.log == 0) {
            regLog();
            return false;
        }else{
            window.location.href = $(this).data('href');
        }
    });
  	isMessage();
});