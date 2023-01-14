<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>提现详细信息</title>
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
            <blockquote class="layui-elem-quote" style="margin: 10px 12px;background-color: #fff;padding: 5px 15px;">提现信息</blockquote>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>提现ID：</b><?=$id?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>提现单号：</b><?=$dd?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>提现金额：</b><font color=red><?=$rmb?></font>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>会员ID：</b><?=$uid?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>提交IP：</b><?=$ip?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>提现状态：</b>
                <?php
                if($pid == 2){
                    echo '<span class="layui-btn-danger layui-btn layui-btn-xs">失败</span>';
                }elseif($pid == 1){
                    echo '<span class="layui-btn layui-btn-xs">成功</span>';
                }else{
                    echo '<span class="layui-btn layui-btn-xs layui-btn-normal">待审</span>';
                }
                ?>
            </div>
            <?php if($pid == 2): ?>
            <div class="layui-col-xs12 layui-col-md12">
                <b>失败提示：</b><?=$msg?>
            </div>
            <?php endif;?>
        </div>
        <div class="layui-row layui-col-space25" style="margin-bottom: 20px;">
            <blockquote class="layui-elem-quote" style="margin: 10px 12px;background-color: #fff;padding: 5px 15px;">收款银行信息</blockquote>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>真实姓名：</b><?=$user['realname']?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>证件号码：</b><?=$user['idcard']?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md4">
                <b>银行名称：</b><?=$user['bank']?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md6">
                <b>银行卡号：</b><?=$user['card']?>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md6">
                <b>开户行地址：</b><?=$user['bankcity']?>
            </div>
        </div>
        <?php if($pid == 0): ?>
        <div class="layui-row layui-col-space25" style="margin-bottom: 20px;">
            <blockquote class="layui-elem-quote" style="margin: 10px 12px;background-color: #fff;padding: 5px 15px;">打款操作</blockquote>
            <form class="layui-form" action="<?=links('pay','drawing_save',$id)?>">
                <div class="layui-col-xs12 layui-col-md12">
                    <b>打款状态:</b>
                    <div class="layui-input-inline">
                        <input lay-filter="msg" type="radio" name="pid" value="1" title="打款成功" checked>
                        <input lay-filter="msg" type="radio" name="pid" value="2" title="打款失败">
                    </div>
                    <div id="msg" class="layui-input-inline" style="display: none;">
                        <input type="text" name="msg" placeholder="请输入为啥失败" value="" class="layui-input"/>
                    </div>
                    <button class="layui-btn layui-btn-sm" lay-filter="*" lay-submit>确定提交</button>
                </div>
            </form>
        </div>
        <?php endif;?>
    </div>
</div>
<script type="text/javascript">
layui.use(['form'], function () {
    var form = layui.form;
    //监听
    form.on('radio(msg)', function (r) {
        if(r.value == 1){
            $('#msg').hide();
        }else{
            $('#msg').show();
        }
    });
})
</script>
</body>
</html>