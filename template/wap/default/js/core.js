var comic = {
    lazyLoad : function(e){
    	e=e||{};var t=e.time||500,n=function(e){var t=["webkit","Moz","ms","o"],n=document.documentElement.style,a=[],i=function(e){return e.replace(/-(\w)/g,function(e,t){return t.toUpperCase()})};for(var r in t)a.push(i(t[r]+"-"+e));a.push(i(e));for(var r in a)if(a[r]in n)return!0;return!1}("background-size"),a=setInterval(function(){var t=!![].map&&0==document.createElement("canvas").toDataURL("image/webp").indexOf("data:image/webp");$("img[data-src]:visible").each(function(){var i=$(this),r=$(window).scrollTop(),o=$(window).height(),c=i.offset().top,s=parseInt(i.height(),10),l=e.loading||"",u=e.space||"data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7",d=e.error||Mcpath.tpl+"img/loadding-error.png",m=i.data("dislazyload"),f=t&&!m?i.data("src"):i.data("src");if(l&&i.css("background","url("+l+") center center / 32px 32px no-repeat"),c<r+o&&c+s>r){if(n){i.css({opacity:0,background:"url("+f+") no-repeat center center",backgroundSize:"cover"}).removeAttr("data-src").animate({opacity:1},300);var g=$(new Image);g.attr("src",f),g.error(function(){if(new RegExp(d).test(i.attr("style")))return!1;i.attr("src",u).css({opacity:0,background:"#eee url("+d+") no-repeat center center"}).animate({opacity:1},300)})}else i.attr("src",f).removeAttr("data-src");i.error(function(){if(new RegExp(d).test(i.attr("style")))return!1;i.attr("src",u).css({opacity:0,background:"#fff url("+d+") no-repeat center center"}).animate({opacity:1},300)})}$("img").length==$("img[data-src]:visible").length&&clearInterval(a)})})
    },
	init : function() {
		comic.lazyLoad();
	    var mcswiper = new Swiper(".banner", {
	        autoplay: 5e3,
	        pagination: ".swiper-pagination"
	    });
        $(window).scroll(function(e) {
            $(this).scrollTop() > 300 ? $(".float-nav").fadeIn() : $(".float-nav").fadeOut()
        });
		var showFlag = 1,djItem = $(".dj .comic-item");
        $(".change").click(function() {
            var e = djItem.length;
            if (e / 6 === showFlag) showFlag = 0;
            else if (e < 6) return ! 1;
            djItem.each(function(e, i) {
                e >= 6 * showFlag && e < 6 * (showFlag + 1) ? $(i).show() : $(i).hide()
            }),
            showFlag += 1;
        });
        var catswiper = new Swiper("#catagoryCon", {
            watchSlidesVisibility: !0,
            onSlideChangeStart: function(e) {
                switch (e.activeIndex) {
                case 0:
                    $(".catalog-nav .underline").animate({left:"10%"},300),
                    $(".catalog-nav .nav-item").eq(0).addClass("active").siblings().removeClass("active");
                    break;
                case 1:
                    $(".catalog-nav .underline").animate({left:"35%"},300),
                    $(".catalog-nav .nav-item").eq(1).addClass("active").siblings().removeClass("active");
                    break;
                case 2:
                    $(".catalog-nav .underline").animate({left:"60%"},300),
                    $(".catalog-nav .nav-item").eq(2).addClass("active").siblings().removeClass("active");
                    break;
                case 3:
                    $(".catalog-nav .underline").animate({left:"85%"},300),
                    $(".catalog-nav .nav-item").eq(3).addClass("active").siblings().removeClass("active");
                }
            }
        });
        $('.nav-item').click(function(){
            catswiper.slideTo($(this).index());
        });
	    //刷新验证码
	    $('.code_pic').click(function(){
	    	$(this).attr('src','//'+Mcpath.url+Mcpath.web+'index.php/api/code?t='+Math.random());
	    });
		//退出登陆
		$('body').on("click",".logout",function(){
			if(mccms.user.log == 1) mccms.logout();
			mccms.msg('退出成功',1);
			setTimeout(function() {
				window.location.href = '//'+Mcpath.url+Mcpath.web+'index.php/user/login';
			}, 500);
		});
	},
	search : function(){
        if($('.getmore').length > 0){
        	var page = 1,end = 0,isloading = false;
			$(window).bind("scroll", function () {
			    if ($(document).scrollTop() + $(window).height() 
			          > $(document).height() - 10 && !isloading) {
			        isloading = true;
			        get_data();
			    }
			});
        }
		$("#searchInput").focus(function() {
            $(".search-history").hide();
        }).blur(function() {
            $.trim($(this).val()) || ($(".hot-search").show(), $(".search-history").show(), $(".search-active").hide());
        }),
		$(".search-btn").click(function() {
            var key = $("#searchInput").val();
            if(key == ''){
            	mccms.msg('请输入要搜索的关键字');
            }else{
            	history_save(key);
            	location.href = '//'+Mcpath.url+Mcpath.web+"index.php/search?key=" + key;
            }
        });
        $(".clear-all").click(function() {
            mccms.layer.open({
                content: "亲，确定要删除历史吗？",
                btn: ["确定", "取消"],
                yes: function(t) {
                    mccms.layer.close(t),
                    mccms.del_cookie('search_history');
                }
            })
        });
        //删除历史
        $('body').on('click', '.del-btn', function() {
        	history_del($(this));
        });
        $("#searchInput").bind('input propertychange', function() {
	        var key = $("#searchInput").val();
	        if(key != ''){
		        $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/data/comic?callback=?', {key:key}, function(res) {
		        	if(res.code == 1){
		        		var t = res.data;
		        		var n = new RegExp(key, "ig"),
				        a = t.length,
				        c = "";
				        if (a > 0) {
				            $(".hot-search").hide();
				            for (var o = 0; o < a; o++) c += '<div class="search-active-item" data-keyword="' + key + '" data-cid="' + t[o].id + '"><p class="comic-name">' + t[o].name.replace(n,
				            function(t) {
				                return '<span style="color:red">' + t + "</span>"
				            }) + '</p><p class="comic-author">' + t[o].author.replace(n,
				            function(t) {
				                return '<span style="color:red">' + t + "</span>"
				            }) + "</p></div>";
				            0 != $(".search-result").length && $(".search-result").hide(),
				            $(".search-active").html(c).show();
				        }
		        	}
		        });
		    }else{
		    	$(".hot-search").show();
		    	$(".search-active").html('');
		    }
	    });
        function history_list(){
    		var i = mccms.get_cookie('search_history') ? JSON.parse(mccms.get_cookie('search_history')) : []
    		var n = "";
    		for (a = 0; a < i.length; a++){
    			n = '<p class="search-history-item clearfix"><a href="'+Mcpath.web+'index.php/search?key=' + i[a] + '"><span class="search-his-txt">' + i[a] + '</span></a><span class="del-btn"><i class="ift-searchlist_clearo"></i></span></p>';
    		}
    		$(".search-history-list").append(n);
        }
        function history_save(t){
	        var e = mccms.get_cookie('search_history'),
	        i = [],
	        n = $.trim(t);
	        if (e) {
	            i = JSON.parse(e);
	            for (var a = i.length,
	            c = 0; c < a; c++) if (i[c] === n) {
	                i.splice(c, 1);
	                break
	            }
	        }
	        "" != n && i.push(n),
	        i.length > 10 && i.splice(0, 1),
	        mccms.set_cookie('search_history',JSON.stringify(i));
        }
        function history_del(t){
    		var i = mccms.get_cookie('search_history') ? JSON.parse(mccms.get_cookie('search_history')) : [];
    		var n = t.prev().find(".search-his-txt").text();
    		for (a = 0; a < i.length; a++){
    			if (i[a] === n) {
            		i.splice(a, 1);
            		break;
            	}
            }
            t.parents(".search-history-item").remove(),
            mccms.set_cookie('search_history',JSON.stringify(i));
        }
        function get_data(){
	        var key = $("#searchInput").val();
        	if(end == 0){
	        	page++;
	        	$.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/data/comic?callback=?', {key:key,page:page}, function(res) {
		        	if(res.code == 1){
		        		var d = res.data;
		        		if(d.length == 0){
		        			end = 1;
		        			$('.getmore').html('没有更多了~!');
		        		}else{
		        			var str = '';
		        			for (var i = 0; i < d.length; i++){
			        			var type = '';
			        			for (var k = 0; k < d[i].tags.length; k++) type+='<span>'+d[i].tags[k]+'</span>&nbsp;';
			        			str += '<div class="comic-list-item clearfix"><a class="cover" href="'+d[i].url+'"><img src="'+d[i].pic+'" alt="'+d[i].name+'"></a><div class="comic-item-info"><p class="comic-name"><a href="'+d[i].url+'">'+d[i].name+'</a></p><p class="comic-author">'+d[i].author+'</p><p class="comic-tags">'+type+'</p><p class="comic-update-at">'+d[i].chapter_name+'</p></div><a class="fast-read-btn" href="'+d[i].url+'"><i class="ift-searchlist_read1"></i><span>速看</span></a></div>';
			        		}
			        		$(".search-result").append(str);
		        		}
		        		isloading = false;
		        	}
		        });
	        }
        }
        if($(".search-history-list").length > 0) history_list();
	},
	update : function(){
		$('.update-nav-item').click(function(){
			var id = $(this).data('id');
			for(var i=1;i<8;i++){
				if(id == i){
					$('#w_'+i).addClass('active');
					$('#week_'+i).show();
				}else{
					$('#w_'+i).removeClass('active');
					$('#week_'+i).hide();
				}
			}
		});
	},
	category : function(type){
		var page = 1,end = 0,isloading = false;
		$(window).bind("scroll", function () {
		    if ($(document).scrollTop() + $(window).height() 
		          > $(document).height() - 10 && !isloading) {
		        isloading = true;
		        get_data(type);
		    }
		});
        function get_data(type){
        	if(end == 0){
	        	page++;
	        	$.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/data/comic?callback=?', {type:type,page:page}, function(res) {
		        	if(res.code == 1){
		        		var d = res.data;
		        		if(d.length == 0){
		        			end = 1;
		        			$('.getmore').html('没有更多了~!');
		        		}else{
		        			var str = '';
		        			for (var i = 0; i < d.length; i++){
			        			str += '<li class="comic-item"><a href="'+d[i].url+'"><img class="cover" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="'+d[i].pic+'"></a><div class="comic-info-box"><p class="comic-name">'+d[i].name+'</p><p class="comic-tip">'+d[i].chapter_name+'</p></div></li>';
			        		}
			        		$(".catagory-list").append(str);
		        		}
		        		isloading = false;
		        	}
		        });
	        }
        }
	},
	readbuy : function(mid){
	    $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/comic/buyread?callback=?', {mid:mid}, function(res) {
	        if(res.code == 1){
	            var read = res.read,buy = res.buy;
	            for (var i = 0; i < read.length; i++) {
	                $('.id-'+read[i].cid).children('.new-chapter').remove();
	                $('.id-'+read[i].cid).append('<i class="i-dt-read"></i>');
	            }
	            for (var i = 0; i < buy.length; i++) {
	                $('.id-'+buy[i].cid).children('.i-dt-lock').remove();
	                $('.id-'+buy[i].cid).append('<i class="i-dt-unlock"></i>');
	            }
	        }
	    });
	},
	share : function(type,url,title,pic){
		switch (type) {
            case 'qq':
				var url = 'https://connect.qq.com/widget/shareqq/index.html?url='+encodeURIComponent(url)+'&title='+encodeURIComponent(title)+'&desc=&summary=&site=baidu&pics='+encodeURIComponent(pic);
				break;
			case 'qzone':
				var url = 'https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+encodeURIComponent(url)+'&title='+encodeURIComponent(title)+'&desc=&summary=&site=&pics='+encodeURIComponent(pic);
				break;
			case 'tsina':
				var url = 'http://service.weibo.com/share/share.php?url='+encodeURIComponent(url)+'&title='+encodeURIComponent(title)+'&appkey=1343713053&pic='+encodeURIComponent(pic)+'&searchPic=true';
				break;
		}
		window.open(url);
	},
	show : function(mid){
		comic.readbuy(mid);
		//评论
		var page = 1,isloading = false;
		mccms.comment({mid:mid,page:page});
		$(window).bind("scroll", function () {
		    if ($(document).scrollTop() + $(window).height() 
		          > $(document).height() - 10 && !isloading) {
		    	isloading = true;
		    	if($('.getmore').data('end') == '0'){
			    	page++;
			        mccms.comment({mid:mid,page:page});
			        setTimeout(function() {
			        	isloading = false;
			        },2000);
		    	}
		    }
		});
		//评论点赞
		$('body').on('click', '.zan-btn', function() {
        	if(mccms.user.log == 0){
				window.location.href = 	'//'+Mcpath.url+Mcpath.web+'index.php/user/login';
			}else{
				var _this = $(this);
		    	var id = _this.data('cid');
		    	$.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/comment/zan?callback=?', {id:id}, function(res) {
		            if(res.code == 1){
		            	mccms.msg(res.msg,1);
		            	_this.children('.num').html(res.zan);
	            		if(res.zt == 0){
	            			_this.removeClass('zaned');
	            			_this.children('i').attr('class','ift-coment_like_off');
	            		}else{
	            			_this.addClass('zaned');
	            			_this.children('i').attr('class','ift-coment_like_on');
	            		}
		            }else{
		            	mccms.msg(res.msg);
		            }
		        });
			}
        });
        //评论框
        $('body').on('click', '.comment-input-box', function() {
        	if(mccms.user.log == 0){
				window.location.href = 	'//'+Mcpath.url+Mcpath.web+'index.php/user/login';
			}else{
        		$('.comment-layout').show();
        	}
        });
        //关闭评论框
        $('body').on('click', '.ift-nav_close', function() {
        	$('.comment-layout').hide();
        });
        //表情
        $('body').on('click', '.J_face_btn', function() {
        	$('.face-wrapper').show();
        });
        $('body').on('click', '.face-list .item', function() {
        	var html = $('#commentArea').val();
        	var em = $(this).data('id');
    		$('#commentArea').val(html+em);
    		$('.comment-sumit').css('background','#f60');
        });
        $("#commentArea").bind('input propertychange', function() {
        	var str = $(this).val();
        	if(str != ''){
        		$('.comment-sumit').css('background','#f60');
        	}else{
        		$('.comment-sumit').css('background','#ccc');
        	}
        });
        $('body').on('click', '.comment-content', function() {
        	var user = $(this).data('user'),id = $(this).data('id');
        	$('.comment-btn').html('回复：'+user);
        	$('.comment-sumit').attr('data-cid',id);
        });
        //提交评论
        $('.comment-sumit').click(function(){
	    	var cid = $(this).attr('data-cid');
	    	var text = $('#commentArea').val().replace(/<.*?>/g,"");
	    	if(text == '') {
	    		mccms.msg('请填写内容');
	    	}else{
	    		mccms.comment_send({mid:mid,text:text,cid:cid,fid:0},function(res){
	    			if(res.code == 1){
	    				mccms.msg(res.msg,1);
	    				$('.comment-layout').hide();
	    				$('#commentArea').val('');
	    				mccms.comment({mid:mid,page:1});
	    			}else{
	    				mccms.msg(res.msg);
	    			}
	    		});
	    	}
        });
		//分享
		$('.p-dt-share').click(function(){
			mccms.layer.open({
	            content: $('.bdshare').html(),
	            btn: ["取消"],
	            className: "share-ctrl",
	            skin: "footer",
	            yes: function(e) {
	                mccms.layer.close(e);
	            }
	        });
		});
		$('body').on('click', '.share-btn', function() {
        	comic.share($(this).data('cmd'),window.location.href,shareTxt,sharePic);
        });
        //金币
	    setTimeout(function() {
		    $('.gift-confirm .txt').html(mccms.user.cion);
		    $('.ticket-box .num').html(mccms.user.ticket);
	        //收藏
	        if(mccms.user.log == 1){
		        $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/rend/isfav?callback=?', {mid:mid}, function(res) {
			        if(res.code == 1){
			            $('.J_collect').addClass('collected').children('span').html('已收藏');
			            $('.J_collect').children('i').removeClass('i-dt-coll').addClass('i-dt-collon');
			        }
			    });
			}
		}, 200);
		//收藏
        $('.J_collect').click(function(){
        	if(mccms.user.log == 0){
        		window.location.href = 	'//'+Mcpath.url+Mcpath.web+'index.php/user/login';
        	}else{
        		$.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/rend/favadd?callback=?', {did:mid}, function(res) {
			        if(res.code == 1){
			            if(res.cid == 1){
			            	$('.J_collect').addClass('collected').children('span').html('已收藏');
			            	$('.J_collect').children('i').removeClass('i-dt-coll').addClass('i-dt-collon');
			            }else{
			            	$('.J_collect').removeClass('collected').children('span').html('收藏');
			            	$('.J_collect').children('i').addClass('i-dt-coll').removeClass('i-dt-collon');
			            }
			            mccms.msg(res.msg,1);
			        }else{
			        	mccms.msg(res.msg);
			        }
			    });
        	}
        });
        //导航切换
        $('.detail-nav li').click(function(){
        	var index = $(this).index();
        	$('.detail-nav li').removeClass('active');
        	$('.detail-nav li').eq(index).addClass('active');
        	switch (index) {
            	case 0:
            		$('.detail-intro-box').show();
            		$('.catalog-box').hide();
            		$('.comment-box').hide();
            		$('.underline').animate({left:"10%"},300);
            	break;
            	case 1:
            		$('.detail-intro-box').hide();
            		$('.catalog-box').show();
            		$('.comment-box').hide();
            		$('.underline').animate({left:"42.8%"},300);
            		if($('.slide-up .ift-detail_sort_down').length > 0){
            			$('.slide-up').children('i').removeClass('ift-detail_sort_down').addClass('ift-detail_sort_up');
            		}else{
            			$('.slide-up').children('i').removeClass('ift-detail_sort_up').addClass('ift-detail_sort_down');
            		}
					var list = $('.catalog-list ul');
					list.append(list.find('.chapter-item').get().reverse());
					$('.catalog-list ul li').each(function(index){
						if(index > 11){
							$(this).addClass('hide');
						}else{
							$(this).removeClass('hide');
						}
					});
            	break;
            	case 2:
            		$('.detail-intro-box').hide();
            		$('.catalog-box').hide();
            		$('.comment-box').show();
            		$('.underline').animate({left:"76.5%"},300);
            	break;
            }
        });
        //展开
        $('.show-more').click(function(){
        	if($('.show-more').data('id') == '2'){
				$('.catalog-list ul li').each(function(index){
					if(index > 11){
						$(this).addClass('hide');
					}else{
						$(this).removeClass('hide');
					}
				});
        		$('.show-more').children('span').html('大人，更多话在这里');
        		$('.show-more').children('i').addClass('ift-readlist_more').removeClass('ift-readlist_stop');
        		$('.show-more').data('id','1');
        	}else{
        		$('.catalog-list ul li').removeClass('hide');
        		$('.show-more').children('span').html('收起');
        		$('.show-more').children('i').removeClass('ift-readlist_more').addClass('ift-readlist_stop');
        		$('.show-more').data('id','2');
        	}
        });
        //月票
        $('.J_ticket').click(function(){
        	if(mccms.user.log == 0){
        		window.location.href = 	'//'+Mcpath.url+Mcpath.web+'index.php/user/login';
        	}else{
	        	mccms.layer.open({
	        		title: ['为喜欢的漫画投上一票','height: auto;line-height: 1rem;'],
				    content: $('.ticket-box').html(),
				    btn: ['确定', '取消'],
				    yes: function(index){
				    	var ticket = $('.reward-item.select').data('num');
				    	mccms.ticket_send({mid:mid,ticket:ticket});
				    	mccms.user.ticket = mccms.user.ticket-parseInt(ticket);
				    	$('.ticket-box .num').html(mccms.user.ticket);
				    	mccms.layer.close(index);
				    }
				});
				$('body').on('click', '.reward-item', function() {
					$('.reward-item').removeClass('select');
					$(this).addClass('select');
				});
        	}
        });
        //打赏
        $('.J_gift').click(function(){
        	if(mccms.user.log == 0){
        		window.location.href = 	'//'+Mcpath.url+Mcpath.web+'index.php/user/login';
        	}else{
        		mccms.layer.open({
				    type: 1,
				    content: $('.gift-box').html(),
				    anim: 'up',
				    style: 'position:fixed; bottom:0; left:0; width: 100%; min-height: 200px;border:none;'
				});
				$('body').on('click', '.gift-itm', function() {
					$('.gift-itm').removeClass('z-select');
					$(this).addClass('z-select');
					$('.gift-confirm .btn').data('id',$(this).data('id'));
				});
				$('body').on('click', '.gift-confirm .btn', function() {
					var gid = $(this).data('id');
					if(gid == null){
						mccms.msg('请选择礼物',1);
					}else{
						mccms.sendgift({mid:mid,gid:gid},function(res){
				            if(res.code == 1){
				            	$('.gift-confirm .txt').html(res.ticket);
				            	mccms.msg(res.msg,1);
				            }else{
				        		mccms.msg(res.msg);
				        	}
						});
					}
				});
        	}
        });
	},
	read : function(mid,cid,vip,cion){
		var pnow = 0,pid = mccms.get_cookie('pid');
	    var slink = $('.prev-chapter').attr('_href');
	    var xlink = $('.next-chapter').attr('_href');
	    //判断VIP
	    if(vip > 0 || cion > 0){
	        if(mccms.user.log == 0){
	            layer.open({
	            	content: '浏览该章节您需要先登陆',
	            	btn: '去登陆',
	            	yes: function(index){
				    	window.location.href = Mcpath.web+'index.php/user/login';
			    	}
				});
	        }else{
	            if(cion > 0 || vip > 0){
	               get_buy(cion);
	            } else {
	               pic_show();
	            }
	        }
	    }else{
	        pic_show();
	    }
        //金币
	    setTimeout(function() {
		    $('.cion-num').html(mccms.user.cion);
		    $('.ticket-num').html(mccms.user.ticket);
	    	//判断是否收藏
	        if(mccms.user.log == 1){
		        $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/rend/isfav?callback=?', {mid:mid}, function(res) {
			        if(res.code == 1){
			            $('.collect').addClass('collected').children('i').attr('class','ift-detail_coll_hl');
			        }
			    });
			}
		}, 200);
	    //默认阅读模式
	    if(mccms.get_cookie('pmode') == '2'){
	        $('.comic-list').attr('data-type','2');
	        $('.switch').children('i').attr('class','ift-read_tab_roll');
	        $('.switch').children('span').html('卷轴');
	    }else{
		    //导航显示隐藏
		    $('body').on('click', '.comic-list', function() {
		    	if($('.top-tool-bar').css('top') == '0px'){
			    	$('.top-tool-bar').animate({top:"-100px"},500);
			    	$('.bottom-tool-bar').animate({bottom:"-100px"},500);
		    	}else{
			    	$('.top-tool-bar').animate({top:"0px"},500);
			    	$('.bottom-tool-bar').animate({bottom:"0px"},500);
		    	}
		    });
	    }
	    //上一话
	    $('.prev-btn').click(function(){
	        get_prev();
	    });
	    //下一话
	    $('.next-btn').click(function(){
	        get_next();
	    });
	    //阅读模式
	    $('.switch').click(function() {
	        var type = $('.comic-list').attr('data-type');
	        if(type == 1){
	            mccms.set_cookie('pmode','2');
		        $('.comic-list').attr('data-type','2');
		        $('.switch').children('i').attr('class','ift-read_tab_roll');
		        $('.switch').children('span').html('卷轴');
	        }else{
	            mccms.set_cookie('pmode','1');
		        $('.comic-list').attr('data-type','1');
		        $('.switch').children('i').attr('class','ift-read_tab_flip');
		        $('.switch').children('span').html('翻页');
	        }
	        mccms.set_cookie('pid',pid);
	        window.location.reload();
	    });
	    mccms.read({did:mid,cid:cid,pid:pid});
	    //离开前获取离开时间和已读图片数量
	    window.onbeforeunload = function () {
	        mccms.read({did:mid,cid:cid,pid:pid});
	    }
	    //页面滚动
	    $(window).scroll(function () {
	        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
	        //下拉模式重置图片页数
	        if(mccms.get_cookie('pmode') != '2'){
	            $('.comic-page').each(function(index){
	                var a = $(this).offset().top;
	                if(a >= $(window).scrollTop() && a < ($(window).scrollTop() + $(window).height())) {
	                    pnow = $(this).attr('data-index');
	                    pid = $(this).attr('data-pid');
	                    $('.comic-i').html(pnow);
	                    mccms.set_cookie('pid',pid);
	                }
	            });
	        }
	    });
	    //收藏
	    $('.collect').click(function(){
	        if(mccms.user.log == 0){
	            window.location.href = Mcpath.web+'index.php/user/login';
	        }else{
	        	var _this = $(this);
				$.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/rend/favadd?callback=?', {did:mid}, function(res) {
		            if(res.code == 1){
		            	mccms.msg(res.msg,1);
		            	if(res.cid == 1){
		            		$('.collect').addClass('collected').children('i').attr('class','ift-detail_coll_hl');
		            	}else{
		            		$('.collect').removeClass('collected').children('i').attr('class','ift-detail_coll_off');
		            	}
		            }else{
		            	mccms.msg(res.msg);
		            }
		        });
	        }
	    });
		//分享
		$('.share').click(function(){
			mccms.layer.open({
	            content: $('.bdshare').html(),
	            btn: ["取消"],
	            className: "share-ctrl",
	            skin: "footer",
	            yes: function(e) {
	                mccms.layer.close(e);
	            }
	        });
		});
		$('body').on('click', '.share-btn', function() {
        	comic.share($(this).data('cmd'),window.location.href,shareTxt,sharePic);
        });
        //月票
        $('.J_ticket').click(function(){
        	if(mccms.user.log == 0){
        		window.location.href = 	'//'+Mcpath.url+Mcpath.web+'index.php/user/login';
        	}else{
	        	mccms.layer.open({
	        		title: ['为喜欢的漫画投上一票','height: auto;line-height: 1rem;'],
				    content: $('.ticket-box').html(),
				    btn: ['确定', '取消'],
				    yes: function(index){
				    	var ticket = $('.reward-item.select').data('num');
				    	mccms.ticket_send({mid:mid,ticket:ticket});
				    	mccms.user.ticket = mccms.user.ticket-parseInt(ticket);
				    	$('.ticket-box .num').html(mccms.user.ticket);
				    	mccms.layer.close(index);
				    }
				});
				$('body').on('click', '.reward-item', function() {
					$('.reward-item').removeClass('select');
					$(this).addClass('select');
				});
        	}
        });
        //打赏
        $('.J_gift').click(function(){
        	if(mccms.user.log == 0){
        		window.location.href = 	'//'+Mcpath.url+Mcpath.web+'index.php/user/login';
        	}else{
        		mccms.layer.open({
				    type: 1,
				    content: $('.gift-box').html(),
				    anim: 'up',
				    style: 'position:fixed; bottom:0; left:0; width: 100%; min-height: 200px;border:none;'
				});
				$('body').on('click', '.gift-itm', function() {
					$('.gift-itm').removeClass('z-select');
					$(this).addClass('z-select');
					$('.gift-confirm .btn').data('id',$(this).data('id'));
				});
				$('body').on('click', '.gift-confirm .btn', function() {
					var gid = $(this).data('id');
					if(gid == null){
						mccms.msg('请选择礼物',1);
					}else{
						mccms.sendgift({mid:mid,gid:gid},function(res){
				            if(res.code == 1){
				            	$('.gift-confirm .txt').html(res.ticket);
				            	mccms.msg(res.msg,1);
				            }else{
				        		mccms.msg(res.msg);
				        	}
						});
					}
				});
        	}
        });
        $('.read-ctrl').click(function(){
        	mccms.set_cookie('read-ctrl','1');
        	$(this).hide();
        });
	    //显示图片方式
	    function pic_show(){
	        if(!pid || $('#pic_'+pid).length == 0) pid = $('.comic-page').eq(0).attr('data-pid');
	        sharePic = $('.comic-page').eq(0).children('img').attr('src');
	        pnow = $('#pic_'+pid).attr('data-index')-1;
	        if(mccms.get_cookie('pmode') == '2'){
	            page_pic_show();
	        }else{
	            Look_pic_show();
	        }
	    }
	    //翻页模式
	    function page_pic_show(){
	    	if(!mccms.get_cookie('read-ctrl')) $('.read-ctrl').show();
	        $('.comic-page').each(function(){
	            $(this).hide();
	        });
	        $('#pic_'+pid).show();
	        $('.comic-i').html($('#pic_'+pid).attr('data-index'));
	        //鼠标点击左右边
	        $('.comic-page').click(function(e){
	            var x = e.pageX-$(this).offset().left;
	            var px = $(this).width();
	            get_next_show(0);
	            if(x < 80){ //上一张
	                $('.comic-page').eq(pnow).hide();
	                pnow--;
	                if(pnow < 0){
	                    pnow++;
	                    get_prev();
	                }else{
	                    $('.comic-page').eq(pnow).show();
	                    $('.comic-i').html((pnow-1));
	                    pid = $('.comic-page').eq(pnow).attr('data-pid');
	                    mccms.set_cookie('pid',pid);
	                }
	            } else if((px - x) < 80){ //下一张
	                $('.comic-page').eq(pnow).hide();
	                pnow++;
	                var num = $('.comic-page').length;
	                if(pnow >= num){
	                    pnow--;
	                    get_next();
	                }else{
	                    pid = $('.comic-page').eq(pnow).attr('data-pid');
	                    $('.comic-page').eq(pnow).show();
	                    $('.comic-i').html((pnow+1));
	                    mccms.set_cookie('pid',pid);
	                }
	            }else{
			    	if($('.top-tool-bar').css('top') == '0px'){
				    	$('.top-tool-bar').animate({top:"-100px"},500);
				    	$('.bottom-tool-bar').animate({bottom:"-100px"},500);
			    	}else{
				    	$('.top-tool-bar').animate({top:"0px"},500);
				    	$('.bottom-tool-bar').animate({bottom:"0px"},500);
			    	}
	            }
	        });
	    }
	    //下拉模式
	    function Look_pic_show(){
	        $('.chapter-end').show();
	        if($('#pic_'+pid).length > 0){
	            setTimeout(function() {
	                $("html,body").animate({
	                    scrollTop: $('#pic_'+pid).offset().top+"px"
	                },500);
	            },1000);
	        }
	    }
	    //上一话
	    function get_prev(){
	        mccms.del_cookie('pid');
	        if(slink == ''){
	            $('#pic_'+pid).show();
	            mccms.msg('已经是第一话了',1);
	        }else{
	            window.location.href = slink;
	        }
	    }
	    //下一话
	    function get_next(){
	        mccms.del_cookie('pid');
	        if(xlink == ''){
	            $('.read-end').show();
	            if(mccms.get_cookie('pmode') == '1') get_next_show(1);
	        }else{
	            window.location.href = xlink;
	        }
	    }
	    //没有下一话
	    function get_next_show(t){
	        if(t == 1){
	            $('.chapter-end').show();
	            var h = $(document).height()-$(window).height();
	            $(document).scrollTop(h);
	        }else{
	            $('.chapter-end').hide();
	        }
	    }
	    //收费章节
	    function get_buy(cion){
	        $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/comic/isbuy?callback=?', {id:cid}, function(res) {
	            if(res.code == 2){
	                mccms.layer.open({
		            	content: '浏览该章节您需要先登陆',
		            	btn: '去登陆',
		            	no: function(index){
					    	window.location.href = Mcpath.web+'index.php/user/login';
				    	}
					});
	            } else if(res.code == 1){
	                var parr = res.pic;
	                for (var i = 0; i < parr.length; i++) {
	                    $('.comic-list').append('<li id="pic_'+parr[i]['id']+'" class="comic-page" data-pid="'+parr[i]['id']+'" data-index="'+i+'"><img src="'+parr[i]['img']+'" alt="'+i+'.jpg"></li>');
	                }
	                pic_show();
	            } else if(res.code == 3){
	                if(res.type == 'vip'){
	                    mccms.layer.open({
						    title: 'Vip章节',
						    content: $('.vip-box').html(),
						    btn: '关闭'
						});
	                }else{
	                    //判断是否自动购买
	                    if(mccms.get_cookie('pay') == mid){
	                        buy_pay();
	                    }else{
		                    mccms.layer.open({
							    title: '购买章节',
							    content: $('.buy-box').html(),
							    btn: '关闭'
							});
	                    	if(parseInt(mccms.user.cion) < parseInt(cion)){
                                $('.buy-btn--charge').show();
                                $('.buy-btn').hide();
                            }else{
                                $('.buy-btn--charge').hide();
                                $('.buy-btn').show();
                            }
	                        //购买章节
	                        $('body').on("click",".buy-btn",function(){
	                            buy_pay();
	                        });
	                    }
	                }
	            }else{
	                mccms.msg(res.msg);
	            }
	        });
	    }
	    //购买章节
	    function buy_pay(){
	        var auto = ($('.auto-check').is(':checked') || mccms.get_cookie('pay') == mid) ? 1 : 0;
	        $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/comic/buy?callback=?', {id:cid,auto:auto}, function(res) {
	            if(res.code == 2){
	                mccms.layer.open({
		            	content: '登陆超时',
		            	btn: '去登陆',
		            	no: function(index){
					    	window.location.href = Mcpath.web+'index.php/user/login';
				    	}
					});
	            } else if(res.code == 3){
	                mccms.layer.open({
		            	content: '金币不足，请先充值',
		            	btn: '去充值',
		            	no: function(index){
					    	window.location.href = Mcpath.web+'index.php/user/pay/index/cion';
				    	}
					});
	            } else if(res.code == 1){
	                //判断自动购买下一章
	                if(auto == 1) mccms.set_cookie('pay',mid);
	                window.location.reload();
	            }else{
	                mccms.msg(res.msg);
	            }
	        });
	    }
	}
};
var user = {
    login : function(){
    	$('#loginForm').on('submit', function(e) {
      		e.preventDefault(); // 阻止默认提交
      		var name = $('.username').val();
      		var pass = $('.userpass').val();
      		var code = $('.pcode').val();
		    if (!name || !pass) {
		        mccms.msg('请输入账户名或密码',1);
		        return;
		    }
	        var index = mccms.layer.open({type: 2});
	        $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/user/login?callback=?',{name:name,pass:pass,islog:1,pcode:code},function(res){
	            mccms.layer.close(index);
	            if(res.code == 1){
	                mccms.msg(res.msg,1);
	                mccms.del_cookie('pint');
	                setTimeout(function() {
	                    window.location.href = res.url;
	                }, 1000);
	            }else{
	                mccms.msg(res.msg,1);
	                if(res.pcode == 1){
	                    mccms.set_cookie('pint',1);
	                    $('.piccode').show();
	                    $('.code_pic').click();
	                }
	            }
	        });
	    });
	},
    regpass : function(type){
    	var time = 60,tindex = null;
	    //发送短信验证码
	    $('.pcode-send').click(function(){
	        var tel = $('#regtel').val();
	        if(!(/^1[3456789]\d{9}$/.test(tel))){
	            mccms.msg('请输入正确的手机号~',1);
	            $('#regtel').focus();
	            return false;
	        }
	    });
		//发送验证码
	    $('.pcode-send').click(function(){
	        var tel = $('#regtel').val();
	        var pcode = $('#regpcode').val();
	        if(!(/^1[3456789]\d{9}$/.test(tel))){
	            mccms.msg('请输入正确的手机号码~',1);
	            $('#regtel').focus();
	            return false;
	        }
	        if(pcode == ''){
	            mccms.msg('请输入上面的图形验证码~',1);
	            $('#regpcode').focus();
	            return false;
	        }
	        //发送
	        $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/code/tel_send/'+type+'?callback=?', {tel:tel,code:pcode}, function(res) {
	            if(res.code == 1){
	                $('.pic-code,.code_pic').hide();
	                $('.tel-code').show();
	                tindex = setInterval(function(){
	                    time--;
	                    if(time == 0){
	                        time = 60;
	                        window.clearInterval(tindex);
	                        $('.tcode-send').removeClass('active').attr('data-status','false').html('重新发送');
	                    }else{
	                        $('.tcode-send').addClass('active').attr('data-status','true').html(time+'S后重发');
	                    }
	                },1000);
	            }else{
	                mccms.msg(res.msg,1);
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
	    $('.register-btn').click(function(){
	        var tel = $('#regtel').val();
	        var code = $('#regtcode').val();
	        var pcode = $('#regpcode').val();
	        var pass = $('#regpass').val();
	        if(!(/^1[3456789]\d{9}$/.test(tel))){
	            mccms.msg('请输入正确手机号码~',1);
	            $('#regtel').focus();
	            return false;
	        }
	        if(Mcpath.istel == 0 && code == ''){
	            if($(".code_pic").css("display") == 'none'){
	                mccms.msg('请输入手机验证码~',1);
	                $('#regtcode').focus();
	            }else{
	                mccms.msg('请获取短信验证码~',1);
	                $('#regpcode').focus();
	            }
	            return false;
	        }
	        if(Mcpath.istel == 1 && pcode == ''){
	        	mccms.msg('请输入验证码~',1);
	            $('#regpcode').focus();
	            return false;
	        }
	        if(!(/^[\S]{6,16}$/.test(pass))){
	            mccms.msg('密码必须6到16位，且不能出现空格~',1);
	            $('#regpass').focus();
	            return false;
	        }
	        var index = mccms.layer.open({type: 2});
	        $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/user/reg?callback=?', {tel:tel,pass:pass,code:code,pcode:pcode}, function(res) {
	            mccms.layer.close(index);
	            if(res.code == 1){
	                setTimeout(function() {
	                    window.location.href = res.url;
	                }, 1000);
	                mccms.msg(res.msg,1);
	            }else{
	                mccms.msg(res.msg,1);
	                $('.code_pic').click();
	            }
	        });
	        return false;
	    });
	    //找回密码
	    $('.finish-btn').click(function(){
	        var tel = $('#regtel').val();
	        var code = $('#regtcode').val();
	        var pass = $('#regpass').val();
	        if(!(/^1[3456789]\d{9}$/.test(tel))){
	            mccms.msg('请输入正确手机号码~',1);
	            $('#regtel').focus();
	            return false;
	        }
	        if(code == ''){
	            if($(".code_pic").css("display") == 'none'){
	                mccms.msg('请输入手机验证码~',1);
	                $('#regtcode').focus();
	            }else{
	                mccms.msg('请获取短信验证码~',1);
	                $('#regpcode').focus();
	            }
	            return false;
	        }
	        if(!(/^[\S]{6,16}$/.test(pass))){
	            mccms.msg('密码必须6到16位，且不能出现空格~',1);
	            $('#regpass').focus();
	            return false;
	        }
	        var index = mccms.layer.open({type: 2});
	        $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/user/pass?callback=?', {tel:tel,pass:pass,code:code}, function(res) {
	            mccms.layer.close(index);
	            if(res.code == 1){
	                mccms.msg(res.msg,1);
	                setTimeout(function() {
	                    window.location.href = res.url;
	                }, 1000);
	            }else{
	                mccms.msg(res.msg,1);
	            }
	        });
	        return false;
	    });
	    //协议
	    $('.protocol').click(function(){
	    	mccms.layer.open({
			    content: $('.help-box').html(),
			    className: 'help-box',
			    btn: '关闭'
			});
	    });
    },
    info : function(province,city,area){
    	new PCAS("province","city","area",province,city,area);
    	//性别选择
    	$('.sex span').click(function(){
    		$('.sex span').removeClass('on');
    		$(this).addClass('on');
    		$('.user-sex').val($(this).data('sex'));
    	});
    	//提交
    	$('.info-btn').click(function(){
	        var name = $('.user-name').val(),nichen = $('.user-nichen').val(),qq = $('.user-qq').val(),email = $('.user-email').val(),sex = $('.user-sex').val(),
	        province = $('.user-province').val(),city = $('.user-city').val(),area = $('.user-area').val(),text = $('.user-text').val();
	        if(nichen == ''){
                mccms.msg('请输入昵称或者笔名~',1);
                $('.user-nichen').focus();
	            return false;
	        }
	        if(sex == ''){
                mccms.msg('请选择性别~',1);
	            return false;
	        }
	        var index = mccms.layer.open({type: 2});
	        $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/user/info/save?callback=?', {name:name,nichen:nichen,qq:qq,email:email,sex:sex,province:province,city:city,area:area,text:text}, function(res) {
	            mccms.layer.close(index);
	            if(res.code == 1){
	                mccms.msg(res.msg,1);
	                setTimeout(function() {
	                    window.location.reload();
	                }, 500);
	            }else{
	                mccms.msg(res.msg,1);
	            }
	        });
	        return false;
	    });
    },
    infopass : function(){
    	//提交
    	$('.info-btn').click(function(){
	        var pass = $('.user-pass').val(),pass1 = $('.user-pass1').val(),pass2 = $('.user-pass2').val();
	        if(!(/^[\S]{6,16}$/.test(pass))){
	            mccms.msg('原密码必须6到16位，且不能出现空格~',1);
	            $('.user-pass').focus();
	            return false;
	        }
	        if(!(/^[\S]{6,16}$/.test(pass1))){
	            mccms.msg('密码必须6到16位，且不能出现空格~',1);
	            $('.user-pass1').focus();
	            return false;
	        }
	        if(pass1 != pass2){
	            mccms.msg('两次密码不一致~',1);
	            $('.user-pass2').focus();
	            return false;
	        }
	        var index = mccms.layer.open({type: 2});
	        $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/user/info/pass_save?callback=?', {pass:pass,pass1:pass1,pass2:pass2}, function(res) {
	            mccms.layer.close(index);
	            if(res.code == 1){
	                mccms.msg(res.msg,1);
	                setTimeout(function() {
	                    window.location.reload();
	                }, 500);
	            }else{
	                mccms.msg(res.msg,1);
	            }
	        });
	        return false;
	    });
    },
    comic_buy : function(type){
	    $(".auto-pay").click(function(){
	        var _this = $(this);
	        var did = _this.data('id');
	        var auto = _this.data('status');
	        $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/user/buy/auto_init/'+type+'?callback=?', {did:did,auto:auto}, function(res) {
	            if (res.code == 1) {
	                mccms.msg(res.msg,1);
	                if(auto == '1'){
	                    _this.html('取消自动购买').data('status','0');
	                }else{
	                    _this.html('自动购买').data('status','1');
	                }
	            }else{
	                mccms.msg(res.msg,1);
	            }
	        });
	    });
	},
	paycion : function(){
		$('.pay-item').click(function(){
		    $('.pay-item').removeClass('select');
		    $(this).addClass('select');
		});
		$('.J_buy_gold').click(function(){
		    var rmb = $('.pay-item.select').data('rmb');
		    if(rmb == null){
		    	mccms.msg('请选择要充值的数量',1);
		    	return false;
		    }
		    var pay = $(this).data('pay');
		    $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/pay/save?callback=?', {type:'cion',rmb:rmb,pay:pay}, function(res) {
		    	if (res.code == 1) {
		        	window.location.href = res.payurl;
		    	}else{
		        	mccms.msg(res.msg,1);
		    	}
		    });
		});
	},
	payticket : function(cionname){
		$('.pay-item').click(function(){
		    $('.pay-item').removeClass('select');
		    $(this).addClass('select');
		});
		$('.J_buy_ticket').click(function(){
		    var num = $('.pay-item.select').data('num');
		    var cion = $('.pay-item.select').data('cion');
		    if(num == null){
		    	mccms.msg('请选择要购买的数量',1);
		    	return false;
		    }
		    var pay = $(this).data('pay');
		    if(pay == 'cion'){
		    	mccms.layer.open({
				    content: '需要'+cion+'个'+cionname+'，确定购买吗?',
				    btn: ['确定', '取消'],
				    yes: function(index){
				    	paysave(num,pay);
				    	mccms.layer.close(index);
				    }
				});
		    }else{
			  	paysave(num,pay);
			}
		});
		function paysave(num,pay){
		    $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/pay/save?callback=?', {type:'ticket',num:num,pay:pay}, function(res) {
		    	if (res.code == 1) {
		    		if(res.pay == 0){
		    			mccms.msg(res.msg,1);
		    			setTimeout(function() {
		                    window.location.reload();
		                },1500);
		    		}else{
		        		window.location.href = res.payurl;
		    		}
		    	}else{
		        	mccms.msg(res.msg,1);
		    	}
		    });
		}
	},
	payvip : function(cionname){
		$('.pay-item').click(function() {
		    $('.pay-item').removeClass('select');
		    $(this).addClass('select');
		});
		$('.J_buy_vip').click(function(){
		    var day = $('.pay-item.select').data('day');
		    var cion = $('.pay-item.select').data('cion');
		    if(day == null){
		    	mccms.msg('请选择要购买的时间',1);
		    	return false;
		    }
		    var pay = $(this).data('pay');
		    if(pay == 'cion'){
		    	mccms.layer.open({
				    content: '需要'+cion+'个'+cionname+'，确定购买吗?',
				    btn: ['确定', '取消'],
				    yes: function(index){
				    	paysave(day,pay);
				    	mccms.layer.close(index);
				    }
				});
		    }else{
			  	paysave(day,pay);
			}
		});
		function paysave(day,pay){
		    $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/api/pay/save?callback=?', {type:'vip',day:day,pay:pay}, function(res) {
		    	if (res.code == 1) {
		    		if(res.pay == 0){
		    			mccms.msg(res.msg,1);
		    			setTimeout(function() {
		                    window.location.reload();
		                },1500);
		    		}else{
		        		window.location.href = res.payurl;
		    		}
		    	}else{
		        	mccms.msg(res.msg,1);
		    	}
		    });
		}
	},
	read : function(){
		$('.clean-up').click(function(){
			if($(this).data('id') == '1'){
				$(this).data('id','2').children('span').html('取消');
				$('.read-history-list').animate({left:"17%"},300);
				$('.clean-confirm').show();
			}else{
				$(this).data('id','1').children('span').html('整理');
				$('.read-history-list').animate({left:"0%"},300);
				$('.clean-confirm').hide();
			}
		});
		//单选
		$('.delete-hide-block').click(function(){
			if($(this).parent().hasClass('select')){
				$(this).parent().removeClass('select');
			}else{
				$(this).parent().addClass('select');
			}
		});
		//全选
		$('.select-all').click(function(){
			var ok = $(this).hasClass('select');
			if(ok){
				$(this).removeClass('select');
			}else{
				$(this).addClass('select');
			}
			$('.comic-list-item').each(function(){
				if(ok){
					$(this).removeClass('select');
				}else{
					$(this).addClass('select');
				}
			});
		});
		//删除
		$('.delete').click(function(){
			var ids = [];
			$('.comic-list-item').each(function(){
				if($(this).hasClass('select')){
					ids.push($(this).data('id'));
				}
			});
			if(ids.length == 0){
				mccms.msg('请选择要删除的数据',1);
			} else {
		        mccms.layer.open({
		            content: '亲，确定要删除吗？',
		            btn: ['确定', '取消'],
		            yes: function(index) {
		                $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/user/read/del?callback=?', {ids:ids}, function(res) {
		                    if (res.code == 1) {
				    			mccms.msg(res.msg,1);
				    			setTimeout(function() {
				                    window.location.reload();
				                },1500);
		                    }else{
		                        mccms.msg(res.msg,1);
		                    }
		                });
		            }
		        });
			}
		});
	},
	fav : function(){
		$('.clean-up').click(function(){
			if($(this).data('id') == '1'){
				$(this).data('id','2').children('span').html('取消');
				$('.clean-confirm,.select-wrap').show();
			}else{
				$(this).data('id','1').children('span').html('整理');
				$('.clean-confirm,.select-wrap').hide();
			}
		});
		//单选
		$('.select-wrap').click(function(){
			if($(this).parent().hasClass('select')){
				$(this).parent().removeClass('select');
			}else{
				$(this).parent().addClass('select');
			}
		});
		//全选
		$('.select-all').click(function(){
			var ok = $(this).hasClass('select');
			if(ok){
				$(this).removeClass('select');
			}else{
				$(this).addClass('select');
			}
			$('.comic-item').each(function(){
				if(ok){
					$(this).removeClass('select');
				}else{
					$(this).addClass('select');
				}
			});
		});
		//删除
		$('.delete').click(function(){
			var ids = [];
			$('.comic-item').each(function(){
				if($(this).hasClass('select')){
					ids.push($(this).data('id'));
				}
			});
			if(ids.length == 0){
				mccms.msg('请选择要删除的数据',1);
			} else {
		        mccms.layer.open({
		            content: '亲，确定要删除吗？',
		            btn: ['确定', '取消'],
		            yes: function(index) {
		                $.getJSON('//'+Mcpath.url+Mcpath.web+'index.php/user/fav/del?callback=?', {ids:ids}, function(res) {
		                    if (res.code == 1) {
				    			mccms.msg(res.msg,1);
				    			setTimeout(function() {
				                    window.location.reload();
				                },1500);
		                    }else{
		                        mccms.msg(res.msg,1);
		                    }
		                });
		            }
		        });
			}
		});
	}
};
$(function(){
	comic.init();
});