<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>管理员列表</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="<?=Web_Base_Path?>admin/css/style.css">
	<script src="<?=Web_Base_Path?>jquery/jquery.min.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="<?=Web_Base_Path?>jquery/jquery-1.9.1.min.js"></script>
    <script src="<?=Web_Base_Path?>admin/js/html5.min.js"></script>
    <script src="<?=Web_Base_Path?>admin/js/respond.min.js"></script>
    <![endif]-->
	<script src="<?=Web_Base_Path?>layui/layui.js"></script>
	<script src="<?=Web_Base_Path?>admin/js/common.js"></script>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-xs12 layui-col-md8" id="mccms_left">
		    <div class="layui-row layui-col-space15">
		        <div class="layui-col-xs6 layui-col-sm6 layui-col-md2 lay-hits">
		            <div class="layui-card">
		                <div class="layui-card-header">
		                    APP用户<span class="layui-badge layui-badge-pink pull-right">合计</span>
		                </div>
		                <div class="layui-card-body">
		                    <p class="lay-big-font"><?=format_wan($app1)?> <span style="font-size:24px;line-height: 1;">位</span></p>
		                    <p style="font-size:12px;">今日活跃<span class="pull-right"><?=format_wan($app2)?> 人</span></p>
		                </div>
		            </div>
		        </div>
		        <div class="layui-col-xs6 layui-col-sm6 layui-col-md2 lay-hits">
		            <div class="layui-card">
		                <div class="layui-card-header">
		                    漫画浏览<span class="layui-badge layui-badge-green pull-right">今日</span>
		                </div>
		                <div class="layui-card-body">
		                    <p class="lay-big-font"><?=format_wan($rhits)?></p>
		                    <p style="font-size:12px;">总浏览量<span class="pull-right"><?=format_wan($hits)?></span></p>
		                </div>
		            </div>
		        </div>
		        <div class="layui-col-xs6 layui-col-sm6 layui-col-md2 lay-hits">
		            <div class="layui-card">
		                <div class="layui-card-header">
		                    小说浏览<span class="layui-badge layui-badge-red pull-right">今日</span>
		                </div>
		                <div class="layui-card-body">
		                    <p class="lay-big-font"><?=format_wan($brhits)?></p>
		                    <p style="font-size:12px;">总浏览量<span class="pull-right"><?=format_wan($bhits)?></span></p>
		                </div>
		            </div>
		        </div>
		        <div class="layui-col-xs6 layui-col-sm6 layui-col-md2 lay-hits">
		            <div class="layui-card">
		                <div class="layui-card-header">
		                    充值额<span class="layui-badge layui-badge-blue pull-right">今日</span>
		                </div>
		                <div class="layui-card-body">
		                    <p class="lay-big-font"><span style="font-size:25px;line-height: 1;">¥</span><?=format_wan($rmb)?></p>
		                    <p style="font-size:12px;">成功金额<span class="pull-right"><?=format_wan($rmb2)?></span></p>
		                </div>
		            </div>
		        </div>
		        <div class="layui-col-xs6 layui-col-sm6 layui-col-md2 lay-hits">
		            <div class="layui-card">
		                <div class="layui-card-header">
		                    订单量<span class="layui-badge layui-badge-red pull-right">今日</span>
		                </div>
		                <div class="layui-card-body">
		                    <p class="lay-big-font"><?=format_wan($dd)?></p>
		                    <p style="font-size:12px;">转化率<span class="pull-right"><?=$bi?>%</span></p>
		                </div>
		            </div>
		        </div>
		        <div class="layui-col-xs6 layui-col-sm6 layui-col-md2 lay-hits">
		            <div class="layui-card">
		                <div class="layui-card-header">
		                    注册用户<span class="layui-badge layui-badge-pink pull-right">今日</span>
		                </div>
		                <div class="layui-card-body">
		                    <p class="lay-big-font"><?=format_wan($u1)?> <span style="font-size:24px;line-height: 1;">位</span></p>
		                    <p style="font-size:12px;">总注册<span class="pull-right"><?=format_wan($u2)?> 人</span></p>
		                </div>
		            </div>
		        </div>
		    </div>
		    <div class="layui-col-md12" style="margin-top: 15px;">
		        <div class="layui-card">
		            <div class="layui-card-header">系统信息</div>
		            <div class="layui-card-body ">
		                <table class="layui-table">
		                	<colgroup>
							    <col width="100">
							    <col>
							</colgroup>
		                    <tbody>
		                        <tr>
		                            <th>系统名称</th>
		                            <td>Mccms comic system</td>
		                        </tr>
		                        <tr>
		                            <th>运行域名</th>
		                            <td><?=$_SERVER["HTTP_HOST"]?><span id="cscms_sq"></span></td>
		                        </tr>
		                        <tr>
		                            <th>服务器IP</th>
		                            <td><?=GetHostByName($_SERVER['SERVER_NAME'])?></td>
		                        </tr>
		                        <tr>
		                            <th>操作系统</th>
		                            <td><?php $os = explode(" ", php_uname()); echo $os[0];?></td>
		                        </tr>
		                        <tr>
		                            <th>运行环境</th>
		                            <td><?php if('/'==DIRECTORY_SEPARATOR){echo $os[2];}else{echo $os[1];} ?> /   <?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
		                        </tr>
		                        <tr>
		                            <th>PHP版本</th>
		                            <td><?=PHP_VERSION?></td>
		                        </tr>
		                        <tr>
		                            <th>Mysql版本</th>
		                            <td><?=$this->db->version()?></td>
		                        </tr>
		                        <tr>
		                            <th>系统时间</th>
		                            <td><?=date('Y-m-d H:i:s')?></td>
		                        </tr>
		                    </tbody>
		                </table>
		            </div>
		        </div>
		    </div>
		    <div class="layui-row">
		        <div class="layui-card">
		            <div class="layui-card-header">开发团队</div>
		            <div class="layui-card-body ">
		                <table class="layui-table">
		                	<colgroup>
							    <col width="100">
							    <col>
							 </colgroup>
		                    <tbody>
		                        <tr>
		                            <th>版权所有</th>
		                            <td><a href="http://www.mccms.cn/" target="_blank">桂林崇胜网络科技有限公司</a></td>
		                        </tr>
		                        <tr>
		                            <th>开发者</th>
		                            <td>烟雨江南(2811358863@qq.com)</td></tr>
		                    </tbody>
		                </table>
		            </div>
		        </div>
    		</div>
    	</div>
    	<div class="layui-col-xs12 layui-col-md4" id="mccms_right">
		    <div class="layui-row" style="background: #fff;">
		        <div class="layui-card mccmsads">
		            <div class="layui-card-header">广告赞助</div>
		            <div class="layui-card-body layui-row">
			        	<div class="layui-col-xs6 layui-col-sm4 layui-col-md6">
							<p class="ads layui-bg-red">赞助位招租<br>QQ:157503886</p>
			        	</div>
			        	<div class="layui-col-xs6 layui-col-sm4 layui-col-md6">
							<p class="ads layui-bg-blue">赞助位招租<br>QQ:157503886</p>
			        	</div>
			        	<div class="layui-col-xs6 layui-col-sm4 layui-col-md6">
							<p class="ads layui-bg-orange">赞助位招租<br>QQ:157503886</p>
			        	</div>
			        	<div class="layui-col-xs6 layui-col-sm4 layui-col-md6">
							<p class="ads layui-bg-green">赞助位招租<br>QQ:157503886</p>
			        	</div>
			        	<div class="layui-col-xs6 layui-col-sm4 layui-col-md6">
							<p class="ads layui-bg-black">赞助位招租<br>QQ:157503886</p>
			        	</div>
			        	<div class="layui-col-xs6 layui-col-sm4 layui-col-md6">
							<p class="ads layui-bg-red">赞助位招租<br>QQ:157503886</p>
			        	</div>
			        </div>
			    </div>
		    </div>
		    <div class="layui-row">
		        <div class="layui-card">
		            <div class="layui-card-header">官方公告</div>
		            <div class="layui-card-body mccmsgg">
			        	<table class="layui-table" lay-skin="nob" lay-even lay-size="sm">
							<colgroup>
								<col>
								<col width="70">
							</colgroup>
							<tbody>
								<tr>
									<td><a href="http://www.mccms.cn/" target="_blank">1.漫城CMS开源版2020.03.01正式发布</a></td>
									<td>1个月前</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
	        </div>
	    </div>
    </div>
</div>
<script type="text/javascript">
var config = <?=$config;?>;
$(function(){
	Admin.get_main();
});
</script>
</body>
</html>