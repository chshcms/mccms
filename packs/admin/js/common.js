var tlen = 0, wap = navigator.userAgent.match(/iPhone|Android|Linux|iPod/i) != null;
//Layui
layui.use(['layer','form','element','table','laydate','util'],function() {
    layer = layui.layer;
    form = layui.form;
    element = layui.element;
    table = layui.table;
    laydate = layui.laydate;
    util = layui.util;

    Admin.init();
    //浏览器小于768则关闭侧边栏
    $(window).resize(function(){
        var n = $(window).width() < 756 ? 1 : 0;
        Admin.tab_nav(n);
    });
    //左侧菜单
    $('.left-nav #nav').on('click', 'li', function(event) {
        if($('.left-nav').css('width')=='60px'){
            $('.left-nav').animate({width: '220px'}, 100);
            $('.page-content').animate({left: '220px'}, 100);
            $('.layui-footer').animate({left: '220px'}, 100);
            $('.left-nav i').css('font-size','14px');
            $('.left-nav cite,.left-nav .nav_right').show();
        }
        if($(window).width()<768){
            $('.page-content-bg').show();
        }
        $('.left-nav').find('a').removeClass('active');
        $(this).children('a').addClass('active');
        if($(this).children('.sub-menu').length){
            if($(this).hasClass('open')){
                $(this).removeClass('open');
                $(this).find('.nav_right').html('&#xe603;');
                $(this).children('.sub-menu').stop(true,true).slideUp();
                $(this).siblings().children('.sub-menu').slideUp();
            }else{
                $(this).addClass('open');
                $(this).children('a').find('.nav_right').html('&#xe61a;');
                $(this).children('.sub-menu').stop(true,true).slideDown();
                $(this).siblings().children('.sub-menu').stop(true,true).slideUp();
                $(this).siblings().find('.nav_right').html('&#xe603;');
                $(this).siblings().removeClass('open');
            }
        }
        event.stopPropagation(); 
    });
    //监听全局表单提交
    form.on('submit(*)', function(data){
        //console.log(data);
        var index = layer.load();
        $.post(data.form.action, data.field, function(res) {
            layer.close(index);
            if(res.code == 1){
                layer.msg(res.msg,{icon: 1});
                setTimeout(function() {
                    if(res.parent == 1){
                        parent.location.reload();
                    }else{
                        window.location.href = res.url;
                    }
                }, 1000);
            }else{
                layer.msg(res.msg,{icon: 2,shift:6});
            }
        },'json');
        return false;
    });
    //监听switch
    form.on('switch(*)', function(data){
        var _zt = data.elem.checked ? 'yes' : 'no';
        var _link = $(this).data('link');
        var _id = $(this).data('id');
        var index = layer.load();
        $.post(_link, {'zt':_zt,'id':_id}, function(res) {
            layer.close(index);
            if(res.code == 1){
                layer.msg(res.msg,{icon: 1});
            }else{
                var flag = $("input[name='switch']").prop("checked");
                $("input[name='switch']").prop("checked",!flag);
                form.render("checkbox");
                layer.msg(res.msg,{icon: 2,shift:6});
            }
        },'json');
        return false;
    });
	//监听table表单搜索
    form.on('submit(table-sreach)', function (data) {
    	var _id = $(this).data('id');
        if (data.field.times) {
            var searchDate = data.field.times.split(' - ');
            data.field.kstime = searchDate[0];
            data.field.jstime = searchDate[1];
        } else {
            data.field.kstime = '';
            data.field.jstime = '';
        }
        data.field.times = undefined;
        table.reload(_id,{where: data.field,page:{curr: 1}});
    });
    // 时间范围选择
    laydate.render({
        elem: 'input[name="times"]',
        type: 'date',
        range: true,
        trigger: 'click'
    });
    //监听radio
    form.on('radio(none)', function (data) {
        var _id = $(data.elem).attr('name');
        if(_id == 'Url_Mode'){
            var _nid = $(data.elem).attr('nid');
            if(_nid == 1){
                $('#'+_id+'1').show();
                $('#'+_id+'2').hide();
            }else{
                $('#'+_id+'2').show();
                $('#'+_id+'1').hide();
            }
        }else{
            var _zt = $(data.elem).attr('xs');
            if(_zt == 'yes'){
                $('#'+_id).show();
            }else{
                $('#'+_id).hide();
            }
        }
    });
    //隐藏侧边栏TIPS提示
    $('.left-nav #nav').on('mouseenter','.left-nav-li', function(event) {
        if($('.left-nav').css('width') != '220px'){
            layer.tips($(this).attr('lay-tips'), $(this).children('.nav-tps'),{time:600});
        }
    });
    // 隐藏左侧
    $('.container .left_open i').click(function(event) {
        if($('.left-nav').css('width') == '220px'){
            Admin.tab_nav(1);
        }else{
            Admin.tab_nav(2);
        }
    });
    //TAB导航栏右键监听
    $(".layui-tab-title").on('contextmenu', 'li', function(event) {
        var tab_left = $(this).position().left;
        var tab_width = $(this).width();
        var left = $(this).position().top;
        var this_index = $(this).attr('lay-id');
        $('#tab_right').css({'left':tab_left+tab_width/2}).show().attr('lay-id',this_index);
        $('#tab_show').show();
        return false;
    });
    //关闭TAB导航
    $('#tab_right').on('click', 'dd', function(event) {
        var data_type = $(this).attr('data-type');
        var lay_id = $(this).parents('#tab_right').attr('lay-id');
        if(data_type == 'this'){
            $('.layui-tab-title li[lay-id='+lay_id+']').find('.layui-tab-close').click();
        }else if(data_type == 'other'){
            $('.layui-tab-title li').eq(0).find('.layui-tab-close').remove();
            $('.layui-tab-title li[lay-id!='+lay_id+']').find('.layui-tab-close').click();
        }else if(data_type == 'all'){
           $('.layui-tab-title li[lay-id]').find('.layui-tab-close').click();
           tlen = 0;
        }
        $('#tab_right').hide();
        $('#tab_show').hide();
    });
    $('.page-content,#tab_show,.container,.left-nav').click(function(event) {
        $('#tab_right').hide();
        $('#tab_show').hide();
    });
    //判断滚动条
    window.onscroll=function(){
        if($(".breadcrumb-nav").length > 0){
            var x = (document.body.scrollTop||document.documentElement.scrollTop);
            if(x > 100){
                $(".breadcrumb-nav .layui-btn").addClass('breadcrumb-fixed');
            }else{
                $(".breadcrumb-nav .layui-btn").removeClass('breadcrumb-fixed');  
            }
        }
    };
});
;!function (win) {
    "use strict";
    var doc = document,
    Admin = function(){
        this.v = '1.0'; //版本号
    };
    //默认加载
    Admin.prototype.init = function () {
        if($(window).width()<768) this.tab_nav(1);
    };
    Admin.prototype.tab_nav = function (sign) {
        if(sign == 1){ //关闭
            $('.left_open i').html('&#xe66b;');
            $('.left-nav .open').click();
            $('.left-nav i').css('font-size','20px');
            $('.left-nav').animate({width: '60px'}, 100);
            $('.left-nav cite,.left-nav .nav_right').hide();
            $('.page-content').animate({left: '60px'}, 100);
            $('.page-content-bg').hide();
            $('.layui-footer').css('left','60px');
        }else{ //开启
            $('.left_open i').html('&#xe668;');
            $('.left-nav').animate({width: '220px'}, 100);
            $('.page-content').animate({left: '220px'}, 100);
            $('.layui-footer').css('left','220px');
            $('.left-nav i').css('font-size','14px');
            $('.left-nav cite,.left-nav .nav_right').show();
        }
    };
    Admin.prototype.add_tab = function (title,url) {
        var uri = [];
        uri = url.split('?');
        var id = hex_md5(uri[0]);//md5每个url
        //重复点击
        for (var i = 0; i < $('.iframe').length; i++) {
            if($('.iframe').eq(i).attr('tab-id') == id){
                $('#nav .sub-menu a').each(function(){
                    //设置边栏CLASS
                    if($(this).children('cite').html() == title){
                        $(this).addClass('active');
                    }else{
                        $(this).removeClass('active');
                    }
                });
                element.tabChange('iframe', id);
                $('.iframe').eq(i).attr("src",url);
                if($(window).width()<768) $('.container .left_open i').click();
                return;
            }
        };
        if(tlen == 0 && $('.iframe').length > 2){
            tlen = 1;
            layer.tips('点击标签右键可以关闭全部',$('.layui-tab-title'),{tips: 1});
        }
        this.add_lay_tab(title,url,id);
        if($(window).width()<768) $('.container .left_open i').click();
        element.tabChange('iframe', id);
    };
    Admin.prototype.add_lay_tab = function(title,url,id) {
        element.tabAdd('iframe', {
            title: title ,
            content: '<iframe tab-id="'+id+'" frameborder="0" src="'+url+'" scrolling="yes" class="iframe"></iframe>',
            id: id
        })
    };
    Admin.prototype.del = function(_url,_id,_this) {
        var ids = [];
        if (isNaN(_id)) {
            var checkStatus = table.checkStatus(_id);
            checkStatus.data.forEach(function(n,i){
                ids.push(n.id);
            });
            var one = false;
	    }else{
	    	ids.push(_id);
	    	var one = true;
	    }
        if(ids.length ==0){
            layer.msg('请选择要删除的数据',{icon: 2,shift:6});
        }else{
            layer.confirm('确定要删除吗', {
                title:'删除提示',
                btn: ['确定', '取消'], //按钮
                shade:0.001
            }, function(index) {
                $.post(_url, {'id':ids}, function(res) {
                    if(res.code == 1){
                        layer.msg('删除成功...',{icon: 1});
                        if(one){
                        	$(_this).parent().parent().parent().remove();
                        }else{
	                        setTimeout(function() {
	                            location.reload();
	                        }, 1000);    	
                        }
                    }else{
                        layer.msg(res.msg,{icon: 2,shift:6});
                    }
                },'json');
            }, function(index) {
                layer.close(index);
            });
        }
    };
    //弹出层
    Admin.prototype.open = function (title,url,w,h,full) {
        if(wap) w = '';
        if (title == null || title == '') {
            var title = false;
        };
        if (w == null || w == '') {
            var w = ($(window).width()*0.9);
        };
        if (h == null || h == '') {
            var h = ($(window).height() - 50);
        };
        h = h-20;
        var open = layer.open({
            type: 2,
            area: [w+'px', h +'px'],
            fix: false, //不固定
            maxmin: true,
            shadeClose: false,
            maxmin: false,
            shade:0.2,
            title: title,
            content: url
        });
        if(full){
           layer.full(open);
        }
    };
    //导出表格excel
    Admin.prototype.get_excel = function(_id) {
        var insTb = table.render({id:_id});
        var checkStatus = table.checkStatus(_id);
        if (checkStatus.data.length == 0) {
            layer.msg('请选择要导出的数据', {icon: 2,shift:6});
        } else {
            table.exportFile(insTb.config.id, checkStatus.data,'xls');
        }
    };
    //默认模版设置
    Admin.prototype.skin_init = function(_url,_path) {
        if(_path == null || _path ==''){
            layer.msg('请选择模版',{icon: 2,shift:6});
        }else{
            layer.confirm('确定要设置该模版为默认吗', {
                title:'模版提示',
                btn: ['确定', '取消'], //按钮
                shade:0.001
            }, function(index) {
                $.post(_url, {'path':_path}, function(res) {
                    if(res.code == 1){
                        layer.msg(res.msg,{icon: 1});
                        setTimeout(function() {
                            window.location.href = res.url;
                        }, 1000);
                    }else{
                        layer.msg(res.msg,{icon: 2,shift:6});
                    }
                },'json');
            }, function(index) {
                layer.close(index);
            });
        }
    };
    //默认模版设置
    Admin.prototype.skin_del = function(_url,_path) {
        if(_path == null || _path ==''){
            layer.msg('请选择模版',{icon: 2,shift:6});
        }else{
            layer.confirm('确定要删除吗', {
                title:'删除提示',
                btn: ['确定', '取消'], //按钮
                shade:0.001
            }, function(index) {
                $.post(_url, {'path':_path}, function(res) {
                    if(res.code == 1){
                        layer.msg(res.msg,{icon: 1});
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }else{
                        layer.msg(res.msg,{icon: 2,shift:6});
                    }
                },'json');
            }, function(index) {
                layer.close(index);
            });
        }
    };
    //随机数生成
    Admin.prototype.get_rand = function(_id){
        var rand = Math.random().toString(36).substr(2)+Math.random().toString(36).substr(5);
        $('#'+_id).val(rand);
    };
    //main默认执行
    Admin.prototype.get_main = function(){
        win.Admin.get_div();
        $(window).resize(function(){
            win.Admin.get_div();
        });
        //版本检测更新
        $.ajax({
            url: config.apiurl,
            type: 'post',
            data: config,
            dataType: 'jsonp',
            success: function(res){
                if(res.code == 1  && res.data) {
                    if(res.data.ver > config.ver && win.Admin.get_cookie('mccms_ver') !== res.data.ver){
                        var w = wap ? 'auto' : '500px';
                        parent.layer.open({
                            title: '发现新版本',
                            area: [w, "auto"],
                            zIndex:999,
                            shade:0.1,
                            content: '<h5 style="background-color:#f7f7f7; font-size:14px; padding: 10px;">你的版本是:' + config.ver + '，新版本:' + res.data.ver + '</h5><fieldset class="layui-elem-field"><legend>更新说明</legend><div class="layui-field-box">' + res.data.upmsg + '</div></fieldset>',
                            btn: ['在线更新', '手动更新', '忽略此次更新', '不在提示'],
                            btn2: function(index, layero) {
                                window.open(res.data.updateurl,"_blank");
                            },
                            btn3: function(index, layero) {
                                win.Admin.set_cookie("mccms_ver", res.data.ver);
                            },
                            btn4: function(index, layero) {
                                win.Admin.set_cookie("mccms_ver", res.data.ver, 30);
                            },
                            yes: function(index) {
                                layer.close(index);
                                parent.Admin.open(res.data.ver + '在线更新',config.path+config.self+'/update?url='+res.data.zipurl+'&token='+res.data.uptoken,700,400);
                            },
                        });
                    }
                    var item = '',newslist = res.data.news;
                    $.each(newslist, function(i, r) {
                        item+= '<tr><td><a href="' + r.url + '" target="_blank">'+(i+1)+'.'+r.name+'</a></td><td>'+r.time+'</td></tr>';
                    });
                    $('.mccmsgg tbody').html(item);
                    var ad = '',adlist = res.data.ads;
                    $.each(adlist, function(i, r) {
                        if(r.url == ''){
                            ad+= '<div class="layui-col-xs6 layui-col-sm4 layui-col-md6"><p class="ads '+r.code+'">'+r.txt+'</p></div>';
                        }else{
                            ad+= '<div class="layui-col-xs6 layui-col-sm4 layui-col-md6"><a href="' + r.url + '" target="_blank"><p class="ads '+r.code+'">'+r.txt+'</p></a></div>';
                        }
                    });
                    $('.mccmsads .layui-card-body').html(ad);
                }else{
                    layer.msg("获取最新版本失败", {icon: 2,time: 1800});
                }
            }
        });
    };
    //刷新当前页
    Admin.prototype.get_load = function(){
        layer.load();
        window.location.reload();
    };
    //写入缓存
    Admin.prototype.set_cookie = function(name,value,day){
        var Days = (day == null || day == '') ? 30 : day;
        var exp = new Date(); 
        exp.setTime(exp.getTime() + Days*24*60*60*1000); 
        document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString(); 
    };
    //读取缓存
    Admin.prototype.get_cookie = function(name){
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg)){
            return unescape(arr[2]);
        }else{
            return null;
        }
    };
    //删除缓存
    Admin.prototype.del_cookie = function(name){
        var exp = new Date();
        exp.setTime(exp.getTime() - 1);
        var cval = win.Admin.get_cookie(name);
        if(cval != null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
    };
    Admin.prototype.get_div = function(){
        if($(window).width() < 975){
            $("#mccms_right").after($("#mccms_left"));
            $('.mccmsgg').css('min-height','0px');
        }else{
            $("#mccms_left").after($("#mccms_right"));
            $('.mccmsgg').css('min-height','345px');
        }
    };
    Admin.prototype.delcache = function(_link){
        var index = layer.load();
        $.get(_link, function(res) {
            layer.close(index);
            if(res.code == 1){
                layer.msg(res.msg,{icon: 1});
            }else{
                layer.msg(res.msg,{icon: 2,shift:6});
            }
        },'json');
        return false;
    };
    win.Admin = new Admin();
}(window);