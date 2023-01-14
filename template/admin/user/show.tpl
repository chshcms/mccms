<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>会员详细信息</title>
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
.layui-row b{
    padding: 0 10px;
    width: 100px;
    text-align: right;
    display: inline-block;
}
</style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-row layui-col-space25" style="margin-top: 10px;">
            <blockquote class="layui-elem-quote" style="margin: 10px 12px;background-color: #fff;padding: 5px 15px;">用户基本信息</blockquote>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>会员ID：</b><?=$id?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>登陆账号：</b><?=$name?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>笔名昵称：</b><?=$nichen?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>联系电话：</b><?=$tel?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>联系QQ：</b><?=$qq?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>联系邮箱：</b><?=$email?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>城市地区：</b><?=$city?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>剩余金额：</b><?=$rmb?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b><?=Pay_Cion_Name?>数量：</b><?=$cion?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>月票数量：</b><?=$ticket?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>当前状态：</b><?=$sid==0?'<span class="layui-btn layui-btn-xs">正常</span>':'<span class="layui-btn layui-btn-xs layui-btn-danger">锁定</span>'?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>用户认证：</b>
                <?php
                    if($cid == 1){
                        echo '<span class="layui-btn layui-btn-xs layui-btn-danger">待审核</span>';
                    }elseif($cid == 2){
                        echo '<span class="layui-btn layui-btn-xs layui-btn-danger">审核失败</span>';
                    }elseif($cid == 3){
                        echo '<span class="layui-btn layui-btn-xs">个人认证</span>';
                    }elseif($cid == 4){
                        echo '<span class="layui-btn layui-btn-xs layui-btn-normal">企业认证</span>';
                    }else{
                        echo '<span class="layui-btn layui-btn-xs layui-btn-disabled">未认证</span>';
                    }
                ?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>加入时间：</b><?=date('Y-m-d H:i:s',$addtime)?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>Vip权限：</b><?=$vip>0?'<span class="layui-btn layui-btn-xs layui-btn-danger">Vip会员</span>':'<span class="layui-btn layui-btn-xs layui-btn-normal">普通会员</span>';?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>Vip时间：</b><?=$vip>0?date('Y-m-d H:i:s',$viptime):'--------';?>
            </div>
            <div class="layui-col-xs12 layui-col-md12">
                <b>个人介绍：</b><?=$text?>
            </div>
        </div>
        <div class="layui-row layui-col-space25" style="margin-bottom: 20px;">
            <blockquote class="layui-elem-quote" style="margin: 10px 12px;background-color: #fff;padding: 5px 15px;">用户财务信息</blockquote>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b><?=$cid==2?'企业名称':'真实姓名';?>：</b><?=$realname?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b><?=$cid==2?'营业执照':'证件号码';?>：</b><?=$idcard?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>结算银行：</b><?=$bank?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md6">
                <b>银行账号：</b><?=$card?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md6">
                <b>开户行地址：</b><?=$bankcity?>
            </div>
        </div>
    </div>
</div>
</body>
</html>