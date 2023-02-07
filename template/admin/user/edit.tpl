<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>会员修改</title>
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
<style type="text/css">
.layui-form-item .layui-input-inline{
    width: auto;
    margin-left: 5px;
}
.type-input{
    height:70px;
    overflow-y: auto;
}
.type-input::-webkit-scrollbar {
    /*滚动条整体样式*/
    width : 10px;  /*高宽分别对应横竖滚动条的尺寸*/
    height: 1px;
}
.type-input::-webkit-scrollbar-thumb {
    /*滚动条里面小方块*/
    border-radius: 10px;
    box-shadow   : inset 0 0 5px rgba(0, 0, 0, 0.2);
    background   : #666;
}
.type-input::-webkit-scrollbar-track {
    /*滚动条里面轨道*/
    box-shadow   : inset 0 0 5px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    background   : #ededed;
}
.layui-form-radio{
    margin: 0; 
    padding-right: 0;
}
.layui-form-pane .layui-form-checkbox {
    margin: 4px 0 4px 1px;
}
.layui-form-checkbox[lay-skin=primary] span {
    padding-right: 4px;
}
.layui-form-checkbox[lay-skin=primary] i {
    left: 6px;
}
.layui-form-item .layui-col-xs12{
    margin-top: 10px;
}
@media screen and (max-width: 990px){
    .layui-form-item .layui-col-xs12:first-child{
        margin-top: 0px;
    }
    .layui-form-item{
        margin-bottom: 10px; 
    }
}
</style>
</head>
<body class="bsbg">
<div class="layui-fluid">
    <form class="layui-form layui-form-pane" action="<?=links('user','save')?>">
        <div class="layui-tab layui-tab-brief">
            <ul class="layui-tab-title">
                <li class="layui-this">基本信息</li>
                <li>认证信息</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <div class="layui-form-item">
                        <div class="layui-col-xs12 layui-col-md4">
                            <label class="layui-form-label">登陆账号</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" autocomplete="off" required lay-verify="required" class="layui-input" value="<?=$name?>" placeholder="请输入登陆账号">
                            </div>
                        </div>
                        <div class="layui-col-xs12 layui-col-md4">
                            <label class="layui-form-label">登陆密码</label>
                            <div class="layui-input-block">
                                <input type="password" name="pass" autocomplete="off" class="layui-input" value="" placeholder="请输入登陆密码<?php if($id > 0) echo '，不修改请留空';?>">
                            </div>
                        </div>
                        <div class="layui-col-xs12 layui-col-md4">
                            <label class="layui-form-label">用户性别</label>
                            <div class="layui-input-block">
                                <select name="sex">
                                    <option value="男">男</option>
                                    <option value="女"<?php if($sex=='女') echo 'selected';?>>女</option>
                                    <option value="保密"<?php if($sex=='保密') echo 'selected';?>>保密</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-col-xs12 layui-col-md6">
                            <label class="layui-form-label">昵称/笔名</label>
                            <div class="layui-input-block">
                                <input type="text" name="nichen" autocomplete="off" required lay-verify="required" class="layui-input" value="<?=$nichen?>" placeholder="请输入用户昵称">
                            </div>
                        </div>
                        <div class="layui-col-xs12 layui-col-md6">
                            <label class="layui-form-label">用户头像</label>
                            <div class="layui-input-block">
                                <input type="text" id="pic" name="pic" class="layui-input" placeholder="请上传用户头像或者输入图片URL" value="<?=$pic?>">
                                <div class="layui-btn layui-btn-normal uppic" style="position: absolute;top: 0px;right: 0;">头像上传</div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-col-xs12 layui-col-md4">
                            <label class="layui-form-label">联系手机</label>
                            <div class="layui-input-block">
                                <input type="number" name="tel" required lay-verify="required" class="layui-input" value="<?=$tel?>" placeholder="请输入联系手机">
                            </div>
                        </div>
                        <div class="layui-col-xs12 layui-col-md4">
                            <label class="layui-form-label">联系邮箱</label>
                            <div class="layui-input-block">
                                <input type="text" name="email" class="layui-input" value="<?=$email?>" placeholder="请输入联系邮箱">
                            </div>
                        </div>
                        <div class="layui-col-xs12 layui-col-md4">
                            <label class="layui-form-label">城市地区</label>
                            <div class="layui-input-block">
                                <input type="text" name="city" class="layui-input" value="<?=$city?>" placeholder="请填写城市地区">
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-col-xs12 layui-col-md3">
                            <label class="layui-form-label">联系QQ</label>
                            <div class="layui-input-block">
                                <input type="number" name="qq" class="layui-input" value="<?=$qq?>" placeholder="请输入联系QQ">
                            </div>
                        </div>
                        <div class="layui-col-xs12 layui-col-md3">
                            <label class="layui-form-label">是否锁定</label>
                            <div class="layui-input-block">
                                <select name="sid">
                                    <option value="0">未锁</option>
                                    <option value="1"<?php if($sid == 1) echo 'selected';?>>已锁</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-col-xs12 layui-col-md3">
                            <label class="layui-form-label">是否Vip</label>
                            <div class="layui-input-block">
                                <select name="vip" lay-filter="vip">
                                    <option value="0">否</option>
                                    <option value="1"<?php if($vip == 1) echo 'selected';?>>是</option>
                                </select>
                            </div>
                        </div>
                        <div id="viptime" class="layui-col-xs12 layui-col-md3"<?php if($vip == 0) echo ' style="display: none;
                        "';?>>
                            <label class="layui-form-label">Vip到期时间</label>
                            <div class="layui-input-block">
                                <input id="time" type="text" name="viptime" class="layui-input" value="<?=$viptime > 0 ? date('Y-m-d H:i:s',$viptime):'';?>" placeholder="请选择Vip到期时间">
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-col-xs12 layui-col-md3">
                            <label class="layui-form-label">是否签约</label>
                            <div class="layui-input-block">
                                <select name="signing">
                                    <option value="0">未签</option>
                                    <option value="1"<?php if($signing == 1) echo 'selected';?>>已签</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-col-xs12 layui-col-md3">
                            <label class="layui-form-label">剩余金额</label>
                            <div class="layui-input-block">
                                <input type="number" name="rmb" class="layui-input" value="<?=$rmb?>" placeholder="剩余金额">
                            </div>
                        </div>
                        <div class="layui-col-xs12 layui-col-md3">
                            <label class="layui-form-label"><?=Pay_Cion_Name?>数量</label>
                            <div class="layui-input-block">
                                <input type="number" name="cion" class="layui-input" value="<?=$cion?>" placeholder="剩余<?=Pay_Cion_Name?>">
                            </div>
                        </div>
                        <div class="layui-col-xs12 layui-col-md3">
                            <label class="layui-form-label">月票数量</label>
                            <div class="layui-input-block">
                                <input type="number" name="ticket" class="layui-input" value="<?=$ticket?>" placeholder="月票数量">
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">用户简介</label>
                        <div class="layui-input-block">
                            <textarea name="text" placeholder="用户简介" class="layui-textarea"><?=$text?></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item" style="text-align: center;">
                        <input type="hidden" name="id" value="<?=$id?>">
                        <button class="layui-btn" lay-filter="*" lay-submit>保存</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
                <div class="layui-tab-item">
                    <div class="layui-form-item">
                        <div class="layui-col-xs12 layui-col-md3">
                            <label class="layui-form-label">认证状态</label>
                            <div class="layui-input-block">
                                <select name="cid" lay-filter="rz">
                                    <option value="0">未认证</option>
                                    <option value="1"<?php if($cid == 1) echo 'selected';?>>待认证</option>
                                    <option value="2"<?php if($cid == 2) echo 'selected';?>>认证失败</option>
                                    <option value="3"<?php if($cid == 3) echo 'selected';?>>个人认证</option>
                                    <option value="4"<?php if($cid == 4) echo 'selected';?>>企业认证</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-col-xs12 layui-col-md4">
                            <label class="layui-form-label"><?=$rz_type == 1 ? '真实姓名':'企业名称';?></label>
                            <div class="layui-input-block">
                                <input type="text" name="realname" class="layui-input" value="<?=$realname?>" placeholder="请填写<?=$rz_type == 1 ? '真实姓名':'企业名称';?>">
                            </div>
                        </div>
                        <div class="layui-col-xs12 layui-col-md5">
                            <label class="layui-form-label">证件号码</label>
                            <div class="layui-input-block">
                                <input type="text" name="idcard" class="layui-input" value="<?=$idcard?>" placeholder="证件号码">
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item" id="rz"<?php if($cid != 2) echo ' style="display: none;"';?>>
                        <div class="layui-col-xs12 layui-col-md12">
                            <label class="layui-form-label">失败原因</label>
                            <div class="layui-input-block">
                                <input type="text" name="rz_msg" class="layui-input" value="<?=$rz_msg?>" placeholder="请填写失败原因">
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-col-xs12 layui-col-md3">
                            <label class="layui-form-label">银行名称</label>
                            <div class="layui-input-block">
                                <select name="bank" lay-verify="" lay-search>
                                    <?php if(!empty($bank)) echo '<option value="'.$bank.'">'.$bank.'</option>';?>
                                    <option value='中国招商银行'>中国招商银行</option>
                                    <option value='中国邮政储蓄银行'>中国邮政储蓄银行</option>
                                    <option value='中国工商银行'>中国工商银行</option>
                                    <option value='中国农业银行'>中国农业银行</option>
                                    <option value='中国银行'>中国银行</option>
                                    <option value='中国建设银行'>中国建设银行</option>
                                    <option value='交通银行'>交通银行</option>
                                    <option value='中信银行'>中信银行</option>
                                    <option value='华夏银行'>华夏银行</option>
                                    <option value='中国光大银行'>中国光大银行</option>
                                    <option value='中国民生银行'>中国民生银行</option>
                                    <option value='兴业银行'>兴业银行</option>
                                    <option value='南昌银行'>南昌银行</option>
                                    <option value='广州银行'>广州银行</option>
                                    <option value='桂林银行'>桂林银行</option>
                                    <option value='重庆银行'>重庆银行</option>
                                    <option value='渤海银行'>渤海银行</option>
                                    <option value='上海农商银行'>上海农商银行</option>
                                    <option value='广发银行'>广发银行</option>
                                    <option value='平安银行'>平安银行</option>
                                    <option value='上海浦东发展银行'>上海浦东发展银行</option>
                                    <option value='北京银行'>北京银行</option>
                                    <option value='包商银行'>包商银行</option>
                                    <option value='上海银行'>上海银行</option>
                                    <option value='江苏银行股份有限公司'>江苏银行股份有限公司</option>
                                    <option value='宁波银行'>宁波银行</option>
                                    <option value='龙江银行股份有限公司'>龙江银行股份有限公司</option>
                                    <option value='汉口银行'>汉口银行</option>
                                    <option value='东莞银行'>东莞银行</option>
                                    <option value='重庆农村商业银行'>重庆农村商业银行</option>
                                    <option value='泉州银行'>泉州银行</option>
                                    <option value='赣州银行'>赣州银行</option>
                                    <option value='上饶银行'>上饶银行</option>
                                    <option value='齐鲁银行'>齐鲁银行</option>
                                    <option value='青岛银行'>青岛银行</option>
                                    <option value='齐商银行'>齐商银行</option>
                                    <option value='东营市商业银行'>东营市商业银行</option>
                                    <option value='烟台银行'>烟台银行</option>
                                    <option value='潍坊银行'>潍坊银行</option>
                                    <option value='济宁银行'>济宁银行</option>
                                    <option value='泰安市商业银行'>泰安市商业银行</option>
                                    <option value='莱商银行'>莱商银行</option>
                                    <option value='威海市商业银行'>威海市商业银行</option>
                                    <option value='德州银行'>德州银行</option>
                                    <option value='临商银行'>临商银行</option>
                                    <option value='日照银行'>日照银行</option>
                                    <option value='郑州银行'>郑州银行</option>
                                    <option value='开封市商业银行'>开封市商业银行</option>
                                    <option value='洛阳银行'>洛阳银行</option>
                                    <option value='漯河市商业银行'>漯河市商业银行</option>
                                    <option value='商丘市商业银行'>商丘市商业银行</option>
                                    <option value='南阳银行股份有限公司'>南阳银行股份有限公司</option>
                                    <option value='长沙银行'>长沙银行</option>
                                    <option value='珠海华润银行清算中心'>珠海华润银行清算中心</option>
                                    <option value='广东华兴银行'>广东华兴银行</option>
                                    <option value='广东南粤银行股份有限公司'>广东南粤银行股份有限公司</option>
                                    <option value='广西北部湾银行'>广西北部湾银行</option>
                                    <option value='柳州银行'>柳州银行</option>
                                    <option value='自贡市商业银行清算中心'>自贡市商业银行清算中心</option>
                                    <option value='攀枝花市商业银行'>攀枝花市商业银行</option>
                                    <option value='德阳银行'>德阳银行</option>
                                    <option value='绵阳市商业银行'>绵阳市商业银行</option>
                                    <option value='贵阳银行'>贵阳银行</option>
                                    <option value='富滇银行'>富滇银行</option>
                                    <option value='西安银行'>西安银行</option>
                                    <option value='长安银行'>长安银行</option>
                                    <option value='兰州银行股份有限公司'>兰州银行股份有限公司</option>
                                    <option value='青海银行'>青海银行</option>
                                    <option value='宁夏银行'>宁夏银行</option>
                                    <option value='乌鲁木齐市商业银行'>乌鲁木齐市商业银行</option>
                                    <option value='昆仑银行'>昆仑银行</option>
                                    <option value='太仓农商行'>太仓农商行</option>
                                    <option value='昆山农村商业银行'>昆山农村商业银行</option>
                                    <option value='吴江农村商业银行'>吴江农村商业银行</option>
                                    <option value='常熟农村商业银行'>常熟农村商业银行</option>
                                    <option value='张家港农村商业银行'>张家港农村商业银行</option>
                                    <option value='广州农村商业银行'>广州农村商业银行</option>
                                    <option value='顺德农村商业银行'>顺德农村商业银行</option>
                                    <option value='恒丰银行'>恒丰银行</option>
                                    <option value='浙商银行'>浙商银行</option>
                                    <option value='天津农商银行'>天津农商银行</option>
                                    <option value='徽商银行'>徽商银行</option>
                                    <option value='北京顺义银座村镇银行'>北京顺义银座村镇银行</option>
                                    <option value='浙江景宁银座村镇银行'>浙江景宁银座村镇银行</option>
                                    <option value='浙江三门银座村镇银行'>浙江三门银座村镇银行</option>
                                    <option value='江西赣州银座村镇银行'>江西赣州银座村镇银行</option>
                                    <option value='深圳福田银座村镇银行'>深圳福田银座村镇银行</option>
                                    <option value='重庆渝北银座村镇银行'>重庆渝北银座村镇银行</option>
                                    <option value='重庆黔江银座村镇银行'>重庆黔江银座村镇银行</option>
                                    <option value='北京农村商业银行'>北京农村商业银行</option>
                                    <option value='吉林农村信用社'>吉林农村信用社</option>
                                    <option value='江苏省农村信用社联合社'>江苏省农村信用社联合社</option>
                                    <option value='浙江省农村信用社'>浙江省农村信用社</option>
                                    <option value='鄞州银行'>鄞州银行</option>
                                    <option value='安徽省农村信用社联合社'>安徽省农村信用社联合社</option>
                                    <option value='福建省农村信用社'>福建省农村信用社</option>
                                    <option value='山东省农联社'>山东省农联社</option>
                                    <option value='湖北农信'>湖北农信</option>
                                    <option value='深圳农商行'>深圳农商行</option>
                                    <option value='东莞农村商业银行'>东莞农村商业银行</option>
                                    <option value='广西农村信用社（合作银行）'>广西农村信用社（合作银行）</option>
                                    <option value='海南省农村信用社'>海南省农村信用社</option>
                                    <option value='云南省农村信用社'>云南省农村信用社</option>
                                    <option value='黄河农村商业银行'>黄河农村商业银行</option>
                                    <option value='外换银行（中国）有限公司'>外换银行（中国）有限公司</option>
                                    <option value='友利银行'>友利银行</option>
                                    <option value='新韩银行中国'>新韩银行中国</option>
                                    <option value='企业银行'>企业银行</option>
                                    <option value='韩亚银行'>韩亚银行</option>
                                    <option value='天津银行'>天津银行</option>
                                    <option value='河北银行股份有限公司'>河北银行股份有限公司</option>
                                    <option value='邯郸市商业银行'>邯郸市商业银行</option>
                                    <option value='邢台银行'>邢台银行</option>
                                    <option value='张家口市商业银行'>张家口市商业银行</option>
                                    <option value='承德银行'>承德银行</option>
                                    <option value='沧州银行'>沧州银行</option>
                                    <option value='廊坊银行'>廊坊银行</option>
                                    <option value='晋商银行'>晋商银行</option>
                                    <option value='晋城市商业银行'>晋城市商业银行</option>
                                    <option value='内蒙古银行'>内蒙古银行</option>
                                    <option value='鄂尔多斯银行'>鄂尔多斯银行</option>
                                    <option value='大连银行'>大连银行</option>
                                    <option value='鞍山市商业银行'>鞍山市商业银行</option>
                                    <option value='锦州银行'>锦州银行</option>
                                    <option value='葫芦岛银行'>葫芦岛银行</option>
                                    <option value='营口银行'>营口银行</option>
                                    <option value='阜新银行结算中心'>阜新银行结算中心</option>
                                    <option value='吉林银行'>吉林银行</option>
                                    <option value='哈尔滨银行结算中心'>哈尔滨银行结算中心</option>
                                    <option value='龙江银行'>龙江银行</option>
                                    <option value='南京银行'>南京银行</option>
                                    <option value='苏州银行'>苏州银行</option>
                                    <option value='江苏长江商行'>江苏长江商行</option>
                                    <option value='杭州银行'>杭州银行</option>
                                    <option value='温州银行'>温州银行</option>
                                    <option value='嘉兴银行清算中心'>嘉兴银行清算中心</option>
                                    <option value='湖州银行'>湖州银行</option>
                                    <option value='绍兴银行'>绍兴银行</option>
                                    <option value='浙江稠州商业银行'>浙江稠州商业银行</option>
                                    <option value='台州银行'>台州银行</option>
                                    <option value='浙江泰隆商业银行'>浙江泰隆商业银行</option>
                                    <option value='浙江民泰商业银行'>浙江民泰商业银行</option>
                                    <option value='福建海峡银行'>福建海峡银行</option>
                                    <option value='厦门银行'>厦门银行</option>
                                    <option value='抚顺银行股份有限公司'>抚顺银行股份有限公司</option>
                                    <option value='禾城农商银行'>禾城农商银行</option>
                                    <option value='广东省农村信用社联合社'>广东省农村信用社联合社</option>
                                    <option value='陕西省农村信用社联合社'>陕西省农村信用社联合社</option>
                                    <option value='莆田农村商业银行'>莆田农村商业银行</option>
                                    <option value='黄山徽州农村商业银行'>黄山徽州农村商业银行</option>
                                    <option value='曲靖市麒麟区农村信用合作联社'>曲靖市麒麟区农村信用合作联社</option>
                                    <option value='许昌银行'>许昌银行</option>
                                    <option value='亳州药都农村商业银行'>亳州药都农村商业银行</option>
                                    <option value='新疆维吾尔自治区农村信用社联合'>新疆维吾尔自治区农村信用社联合</option>
                                    <option value='河南省农村信用社联合社'>河南省农村信用社联合社</option>
                                    <option value='浙江绍兴恒信农村合作银行'>浙江绍兴恒信农村合作银行</option>
                                    <option value='宁波慈溪农村合作银行'>宁波慈溪农村合作银行</option>
                                    <option value='盛京银行'>盛京银行</option>
                                    <option value='河北省农村信用社联合社'>河北省农村信用社联合社</option>
                                    <option value='湖南省农村信用社联合社'>湖南省农村信用社联合社</option>
                                    <option value='辽宁省农村信用社联合社'>辽宁省农村信用社联合社</option>
                                    <option value='吉林农信联合社'>吉林农信联合社</option>
                                    <option value='成都农村商业银行股份有限公司'>成都农村商业银行股份有限公司</option>
                                    <option value='成都商业银行'>成都商业银行</option>
                                    <option value='江苏农信社'>江苏农信社</option>
                                    <option value='武汉农村商业银行'>武汉农村商业银行</option>
                                    <option value='浙江省农村信用社联合社'>浙江省农村信用社联合社</option>
                                    <option value='山东农村信用联合社'>山东农村信用联合社</option>
                                    <option value='深圳农村商业银行'>深圳农村商业银行</option>
                                    <option value='成都银行'>成都银行</option>
                                    <option value='湖北省农村信用社联合社'>湖北省农村信用社联合社</option>
                                    <option value='山东省农村信用社'>山东省农村信用社</option>
                                    <option value='佛山农商银行'>佛山农商银行</option>
                                    <option value='江西省农村信用社'>江西省农村信用社</option>
                                    <option value='贵州省农村信用社'>贵州省农村信用社</option>
                                    <option value='厦门农商银行'>厦门农商银行</option>
                                    <option value='漳州农商银行'>漳州农商银行</option>
                                    <option value='连云港东方农村商业银行'>连云港东方农村商业银行</option>
                                    <option value='江苏江南农村商业银行'>江苏江南农村商业银行</option>
                                    <option value='西安市临潼区农村信用合作联社城区信用社'>西安市临潼区农村信用合作联社城区信用社</option>
                                    <option value='福建省农村信用社联合社'>福建省农村信用社联合社</option>
                                    <option value='景县农村信用合作联社王瞳信用社'>景县农村信用合作联社王瞳信用社</option>
                                    <option value='凉山州商业银行航天路支行'>凉山州商业银行航天路支行</option>
                                    <option value='花旗银行（中国）有限公司'>花旗银行（中国）有限公司</option>
                                    <option value='广西平果农村合作银行'>广西平果农村合作银行</option>
                                    <option value='重庆三峡银行'>重庆三峡银行</option>
                                    <option value='济南市润丰农村合作银行'>济南市润丰农村合作银行</option>
                                    <option value='嫩江县农村信用联社股份有限公司'>嫩江县农村信用联社股份有限公司</option>
                                    <option value='桐乡市农村信用合作联社'>桐乡市农村信用合作联社</option>
                                    <option value='浙江永康农村合作银行'>浙江永康农村合作银行</option>
                                    <option value='三亚农村商业银行股份有限公司'>三亚农村商业银行股份有限公司</option>
                                    <option value='山东广饶农村商业银行'>山东广饶农村商业银行</option>
                                    <option value='浙江温州鹿城农村商业银行'>浙江温州鹿城农村商业银行</option>
                                    <option value='浙江绍兴瑞丰农村商业银行'>浙江绍兴瑞丰农村商业银行</option>
                                    <option value='东亚银行'>东亚银行</option>
                                    <option value='甘肃银行'>甘肃银行</option>
                                    <option value='北京农业银行'>北京农业银行</option>
                                    <option value='佛山市高明区农村信用合作联社'>佛山市高明区农村信用合作联社</option>
                                    <option value='金华银行'>金华银行</option>
                                    <option value='浙江杭州余杭农村商业银行'>浙江杭州余杭农村商业银行</option>
                                    <option value='潍坊农商银行'>潍坊农商银行</option>
                                    <option value='沈阳农村商业银行'>沈阳农村商业银行</option>
                                    <option value='永安市农村信用合作联社洪田信用社'>永安市农村信用合作联社洪田信用社</option>
                                    <option value='浙江温州瓯海农村商业银行'>浙江温州瓯海农村商业银行</option>
                                    <option value='杭州联合农村商业银行'>杭州联合农村商业银行</option>
                                    <option value='长春发展农村商业银行'>长春发展农村商业银行</option>
                                    <option value='美国银行有限公司上海分行'>美国银行有限公司上海分行</option>
                                    <option value='南充市商业银行'>南充市商业银行</option>
                                    <option value='汇丰银行(中国)有限公司上海分行'>汇丰银行(中国)有限公司上海分行</option>
                                    <option value='海口市农村信用合作联社'>海口市农村信用合作联社</option>
                                    <option value='湖北银行'>湖北银行</option>
                                    <option value='中原银行'>中原银行</option>
                                    <option value='肇庆端州农村商业银行'>肇庆端州农村商业银行</option>
                                    <option value='山东临沂兰山农村合作银行'>山东临沂兰山农村合作银行</option>
                                    <option value='四川仪陇惠民村镇银行'>四川仪陇惠民村镇银行</option>
                                    <option value='甘肃嘉峪关农村合作银行'>甘肃嘉峪关农村合作银行</option>
                                    <option value='湖南星沙农村商业银行'>湖南星沙农村商业银行</option>
                                    <option value='浙江萧山农村商业银行'>浙江萧山农村商业银行</option>
                                    <option value='海盐县农村信用合作联社'>海盐县农村信用合作联社</option>
                                    <option value='长沙芙蓉农村合作银行'>长沙芙蓉农村合作银行</option>
                                    <option value='大洼县农村信用合作联社'>大洼县农村信用合作联社</option>
                                    <option value='汇丰银行(中国)有限公司'>汇丰银行(中国)有限公司</option>
                                    <option value='渭南市临渭区农村信用合作联社'>渭南市临渭区农村信用合作联社</option>
                                    <option value='宁晋县农村信用合作联社'>宁晋县农村信用合作联社</option>
                                    <option value='玉溪市商业银行'>玉溪市商业银行</option>
                                    <option value='昆明官渡农村合作银行'>昆明官渡农村合作银行</option>
                                    <option value='宁波余姚农村合作银行'>宁波余姚农村合作银行</option>
                                    <option value='秦皇岛银行'>秦皇岛银行</option>
                                    <option value='延川县农村信用合作联社'>延川县农村信用合作联社</option>
                                    <option value='宁波慈溪农村商业银行'>宁波慈溪农村商业银行</option>
                                    <option value='晋城银行'>晋城银行</option>
                                    <option value='三井住友银行（中国）有限公司'>三井住友银行（中国）有限公司</option>
                                    <option value='江苏如东农村商业银行'>江苏如东农村商业银行</option>
                                    <option value='浙江永康农村商业银行'>浙江永康农村商业银行</option>
                                    <option value='江苏泰兴农村商业银行'>江苏泰兴农村商业银行</option>
                                    <option value='江苏紫金农村商业银行'>江苏紫金农村商业银行</option>
                                    <option value='长治市郊区农村信用合作联社'>长治市郊区农村信用合作联社</option>
                                    <option value='遂平县农村信用合作联社'>遂平县农村信用合作联社</option>
                                    <option value='珠海农村商业银行'>珠海农村商业银行</option>
                                    <option value='盐城农商银行'>盐城农商银行</option>
                                    <option value='黑龙江省农村信用社联合社'>黑龙江省农村信用社联合社</option>
                                    <option value='泸州市商业银行'>泸州市商业银行</option>
                                    <option value='华融湘江银行'>华融湘江银行</option>
                                    <option value='宜宾市商业银行'>宜宾市商业银行</option>
                                    <option value='九江银行'>九江银行</option>
                                    <option value='玉溪红塔区兴和村镇银行'>玉溪红塔区兴和村镇银行</option>
                                    <option value='乐山市商业银行股份有限公司'>乐山市商业银行股份有限公司</option>
                                    <option value='四会市农村信用合作联社'>四会市农村信用合作联社</option>
                                    <option value='凤城农村商业银行'>凤城农村商业银行</option>
                                    <option value='仙游县农村信用合作联社'>仙游县农村信用合作联社</option>
                                    <option value='惠安县农村信用合作联社'>惠安县农村信用合作联社</option>
                                    <option value='德意志银行（中国）有限公司'>德意志银行（中国）有限公司</option>
                                    <option value='龙里县农村信用合作联社'>龙里县农村信用合作联社</option>
                                    <option value='广西壮族自治区农村信用社联合社'>广西壮族自治区农村信用社联合社</option>
                                    <option value='江苏如皋农村商业银行股份有限公司'>江苏如皋农村商业银行股份有限公司</option>
                                    <option value='进贤县农村信用合作联社'>进贤县农村信用合作联社</option>
                                    <option value='湖北天门农村商业银行股份有限公司'>湖北天门农村商业银行股份有限公司</option>
                                    <option value='漳浦县农村信用合作联社绥西信用社'>漳浦县农村信用合作联社绥西信用社</option>
                                    <option value='普洱市思茅区农村信用合作联社'>普洱市思茅区农村信用合作联社</option>
                                    <option value='章丘市明水农村信用合作社'>章丘市明水农村信用合作社</option>
                                    <option value='长治银行'>长治银行</option>
                                    <option value='泾阳县农村信用合作联社'>泾阳县农村信用合作联社</option>
                                    <option value='广东南海农村商业银行'>广东南海农村商业银行</option>
                                    <option value='渣打银行（中国）有限公司'>渣打银行（中国）有限公司</option>
                                    <option value='铁岭银行股份有限公司'>铁岭银行股份有限公司</option>
                                    <option value='浙江义乌农村商业银行股份有限公司'>浙江义乌农村商业银行股份有限公司</option>
                                    <option value='富邦华一银行有限公司'>富邦华一银行有限公司</option>
                                    <option value='江西省横峰县农村信用合作联社'>江西省横峰县农村信用合作联社</option>
                                    <option value='内江兴隆村镇银行股份有限公司'>内江兴隆村镇银行股份有限公司</option>
                                    <option value='江苏兴化农村商业银行股份有限公司'>江苏兴化农村商业银行股份有限公司</option>
                                    <option value='九江农村商业银行'>九江农村商业银行</option>
                                    <option value='本溪市商业银行'>本溪市商业银行</option>
                                    <option value='江苏丰县民丰村镇银行'>江苏丰县民丰村镇银行</option>
                                    <option value='江苏靖江农村商业银行'>江苏靖江农村商业银行</option>
                                    <option value='朝阳银行'>朝阳银行</option>
                                    <option value='河南登封农村商业银行'>河南登封农村商业银行</option>
                                    <option value='保定银行'>保定银行</option>
                                    <option value='江门新会农村商业银行'>江门新会农村商业银行</option>
                                    <option value='广东清远农村商业银行'>广东清远农村商业银行</option>
                                    <option value='浙江乐清农村商业银行股份有限公司'>浙江乐清农村商业银行股份有限公司</option>
                                    <option value='甘肃省兰州市西固区农村信用合作联社'>甘肃省兰州市西固区农村信用合作联社</option>
                                    <option value='山西长治黎都农村商业银行'>山西长治黎都农村商业银行</option>
                                    <option value='泰顺县农村信用合作联社'>泰顺县农村信用合作联社</option>
                                    <option value='聊城农村商业银行'>聊城农村商业银行</option>
                                    <option value='云浮市云城区农村信用合作联社云城信用社'>云浮市云城区农村信用合作联社云城信用社</option>
                                    <option value='曲靖市商业银行'>曲靖市商业银行</option>
                                    <option value='江苏金湖民泰村镇银行股份有限公司'>江苏金湖民泰村镇银行股份有限公司</option>
                                    <option value='江苏沭阳农村商业银行'>江苏沭阳农村商业银行</option>
                                    <option value='惠州农商银行'>惠州农商银行</option>
                                    <option value='浙江玉环农村合作银行'>浙江玉环农村合作银行</option>
                                    <option value='安徽蒙城农村商业银行股份有限公司'>安徽蒙城农村商业银行股份有限公司</option>
                                    <option value='江苏东台农村商业银行'>江苏东台农村商业银行</option>
                                    <option value='中国信托'>中国信托</option>
                                    <option value='延边农村商业银行'>延边农村商业银行</option>
                                    <option value='大同银行'>大同银行</option>
                                    <option value='寿宁县农村信用合作联社'>寿宁县农村信用合作联社</option>
                                    <option value='临沧市临翔区农村信用合作联社'>临沧市临翔区农村信用合作联社</option>
                                    <option value='江苏仪征农村商业银行股份有限公司'>江苏仪征农村商业银行股份有限公司</option>
                                    <option value='淮南淮河农村商业银行股份有限公司'>淮南淮河农村商业银行股份有限公司</option>
                                    <option value='云南昭通昭阳农村合作银行'>云南昭通昭阳农村合作银行</option>
                                    <option value='昆明市盘龙区农村信用合作联社'>昆明市盘龙区农村信用合作联社</option>
                                    <option value='浙江新昌农村商业银行'>浙江新昌农村商业银行</option>
                                    <option value='长治潞州农村商业银行'>长治潞州农村商业银行</option>
                                    <option value='中山农村商业银行'>中山农村商业银行</option>
                                    <option value='湖南望城农村商业银行'>湖南望城农村商业银行</option>
                                    <option value='江门融和农村商业银行'>江门融和农村商业银行</option>
                                    <option value='库尔勒市农村信用合作联社'>库尔勒市农村信用合作联社</option>
                                    <option value='广元市贵商村镇银行'>广元市贵商村镇银行</option>
                                    <option value='新疆昌吉农村商业银行'>新疆昌吉农村商业银行</option>
                                    <option value='宁波通商银行股份有限公司'>宁波通商银行股份有限公司</option>
                                    <option value='宁波鄞州农村合作银行'>宁波鄞州农村合作银行</option>
                                    <option value='浙江上虞农村商业银行'>浙江上虞农村商业银行</option>
                                    <option value='瑞典商业银行公共有限公司上海分行'>瑞典商业银行公共有限公司上海分行</option>
                                    <option value='浙江德清农村商业银行股份有限公司'>浙江德清农村商业银行股份有限公司</option>
                                    <option value='浙江海宁农村商业银行股份有限公司'>浙江海宁农村商业银行股份有限公司</option>
                                    <option value='盘锦市商业银行'>盘锦市商业银行</option>
                                    <option value='安徽宿州农村商业银行'>安徽宿州农村商业银行</option>
                                    <option value='陕西宝鸡渭滨农村商业银行'>陕西宝鸡渭滨农村商业银行</option>
                                    <option value='山东张店农村商业银行'>山东张店农村商业银行</option>
                                    <option value='广东阳东农村商业银行'>广东阳东农村商业银行</option>
                                    <option value='贵州银行'>贵州银行</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-col-xs12 layui-col-md4">
                            <label class="layui-form-label">银行账号</label>
                            <div class="layui-input-block">
                                <input type="text" name="card" class="layui-input" value="<?=$card?>" placeholder="银行账号">
                            </div>
                        </div>
                        <div class="layui-col-xs12 layui-col-md5">
                            <label class="layui-form-label">开户行地址</label>
                            <div class="layui-input-block">
                                <input type="text" name="bankcity" class="layui-input" value="<?=$bankcity?>" placeholder="银行账号开户行地址">
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item" style="text-align: center;">
                        <button class="layui-btn" lay-filter="*" lay-submit>保存</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
layui.use(['form','upload','laydate'], function(){
    var form = layui.form,
        upload = layui.upload,
        laydate = layui.laydate;
    //VIP
    form.on('select(vip)', function(r){
        if(r.value == 1){
            $('#viptime').show();
        }else{
            $('#viptime').hide();
        }
    });
    //认证
    form.on('select(rz)', function(r){
        if(r.value == 2){
            $('#rz').show();
        }else{
            $('#rz').hide();
        }
    });
    laydate.render({
        elem: '#time',
        type: 'datetime'
    });
    upload.render({
        elem: '.uppic',
        url: '<?=links('ajax','upload')?>?dir=<?=sys_auth('user')?>&sy=no',
        accept: 'file',
        acceptMime: 'image/*',
        exts: '<?=Annex_Ext?>',
        done: function(res){
            if(res.code == 0){
                layer.msg(res.msg,{icon: 1});
                $('#pic').val(res.url);
            }else{
                layer.msg(res.msg,{icon: 2});
            }
        }
    });
})
</script>
</body>
</html>