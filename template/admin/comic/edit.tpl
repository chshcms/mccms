<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>漫画修改</title>
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
    <form class="layui-form layui-form-pane" action="<?=links('comic','save')?>">
        <div class="layui-form-item">
            <div class="layui-col-xs12 layui-col-md3">
                <label class="layui-form-label">漫画分类</label>
                <div class="layui-input-block">
                    <select name="cid">
                        <option value="">选择分类</option>
                    <?php
                    foreach($class as $row){
                        $sel = $row['id'] == $cid ? ' selected' : '';
                        echo '<option value="'.$row['id'].'"'.$sel.'>'.$row['name'].'</option>';
                        $array = $this->mcdb->get_select('class','id,name',array('fid'=>$row['id']),'xid ASC',100);
                        foreach($array as $row2){
                            $sel2 = $row2['id'] == $cid ? ' selected' : '';
                            echo '<option value="'.$row2['id'].'"'.$sel2.'>&nbsp;&nbsp;&nbsp;&nbsp;├─&nbsp;'.$row2['name'].'</option>';
                        }
                    }
                    ?>
                    </select>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md3">
                <label class="layui-form-label">漫画状态</label>
                <div class="layui-input-block">
                    <select name="serialize">
                        <option value="完结">已完结</option>
                        <option value="连载"<?php if(strpos($serialize,'连载') !== false) echo 'selected';?>>连载中</option>
                    </select>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md2">
                <label class="layui-form-label">是否锁定</label>
                <div class="layui-input-block">
                    <select name="sid">
                        <option value="0">未锁</option>
                        <option value="1"<?php if($sid == 1) echo 'selected';?>>已锁</option>
                    </select>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md2">
                <label class="layui-form-label">是否推荐</label>
                <div class="layui-input-block">
                    <select name="tid">
                        <option value="0">未推</option>
                        <option value="1"<?php if($tid == 1) echo 'selected';?>>已推</option>
                    </select>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md2 label80">
                <label class="layui-form-label">审核</label>
                <div class="layui-input-block">
                    <select name="yid" lay-filter="yid">
                        <option value="0">已通过</option>
                        <option value="1"<?php if($yid == 1) echo 'selected';?>>待审核</option>
                        <option value="2"<?php if($yid == 2) echo 'selected';?>>未通过</option>
                    </select>
                </div>
            </div>
            <div id="yid" class="layui-col-xs12 layui-col-md12"<?php if($yid < 2) echo ' style="display: none;"';?>>
                <label class="layui-form-label">未通过原因</label>
                <div class="layui-input-block">
                    <input type="text" name="msg" class="layui-input" value="<?=$msg?>" placeholder="请输入未通过原因">
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md4">
                <label class="layui-form-label">漫画作者</label>
                <div class="layui-input-block">
                    <input type="text" name="author" class="layui-input" value="<?=$author?>" placeholder="请输入漫画作者名字">
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md4">
                <label class="layui-form-label">英文别名</label>
                <div class="layui-input-block">
                    <input type="text" name="yname" class="layui-input" value="<?=$yname?>" placeholder="请输入漫画英文别名">
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md4">
                <div class="layui-input-inline">
                    <input type="checkbox" name="addtime" title="更新时间" checked>
                </div>
                <div class="layui-input-inline">
                    <input type="checkbox" name="push" title="URL推送"<?php if(Push_Type != '') echo 'checked';?>>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-col-xs12 layui-col-md4">
                <label class="layui-form-label">漫画标题</label>
                <div class="layui-input-block">
                    <input type="text" name="name" required lay-verify="required" class="layui-input" value="<?=$name?>" placeholder="请输入漫画标题">
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md3">
                <label class="layui-form-label">总得分</label>
                <div class="layui-input-block">
                    <input type="number" name="score" class="layui-input" value="<?=$score?>" placeholder="漫画评分总得分">
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md5">
                <label class="layui-form-label">简介</label>
                <div class="layui-input-block">
                    <input type="text" name="text" class="layui-input" value="<?=$text?>" placeholder="一句话介绍漫画，10个字以内">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-col-xs12 layui-col-md6">
                <label class="layui-form-label">竖版封面</label>
                <div class="layui-input-block">
                    <input type="text" id="pic" name="pic" class="layui-input" placeholder="请上传漫画竖版封面或者输入图片URL" value="<?=$pic?>">
                    <div class="layui-btn layui-btn-normal uppic" style="position: absolute;top: 0px;right: 0;">封面上传</div>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md6">
                <label class="layui-form-label">横版封面</label>
                <div class="layui-input-block">
                    <input type="text" id="picx" name="picx" class="layui-input" placeholder="请上传漫画横版封面或者输入图片URL" value="<?=$picx?>">
                    <div class="layui-btn layui-btn-normal uppicx" style="position: absolute;top: 0px;right: 0;">封面上传</div>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-col-xs12 layui-col-md3">
                <label class="layui-form-label">日人气</label>
                <div class="layui-input-block">
                    <input type="number" name="rhits" class="layui-input" value="<?=$rhits?>" placeholder="请输入漫画日人气">
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md3">
                <label class="layui-form-label">周人气</label>
                <div class="layui-input-block">
                    <input type="number" name="zhits" class="layui-input" value="<?=$zhits?>" placeholder="请输入漫画周人气">
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md3">
                <label class="layui-form-label">月人气</label>
                <div class="layui-input-block">
                    <input type="number" name="yhits" class="layui-input" value="<?=$yhits?>" placeholder="请输入漫画月人气">
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md3">
                <label class="layui-form-label">总人气</label>
                <div class="layui-input-block">
                    <input type="number" name="hits" class="layui-input" value="<?=$hits?>" placeholder="请输入漫画总人气">
                </div>
            </div>
        </div>
        <?php foreach($type as $k=>$row): ?>
        <div class="layui-col-xs12 layui-col-md6">
            <div class="layui-form-item" pane="">
                <label class="layui-form-label"><?=$row['name']?></label>
                <?php
                $n = $k+1;
                $array = $this->mcdb->get_select('type','id,name',array('fid'=>$row['id']),'xid ASC',100);
                
                echo '<div class="layui-input-block type-input">';
                foreach($array as $v){
                    $tt = $row['cid'] == 0 ? 'checkbox' : 'radio';
                    $check = '';
                    if($id > 0){
                        $isrow = $this->mcdb->get_row('comic_type','id',array('mid'=>$id,'tid'=>$v['id']));
                        if($isrow) $check = ' checked';
                    }
                    echo '<input value="'.$v['id'].'" type="'.$tt.'" name="type['.$row['zd'].'][]" lay-skin="primary" title="'.$v['name'].'"'.$check.'>';
                }
                ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="layui-form-item">
            <div class="layui-col-xs12 layui-col-md6">
                <label class="layui-form-label">图作者</label>
                <div class="layui-input-block">
                    <input type="text" name="pic_author" class="layui-input" placeholder="请填写图作者" value="<?=$pic_author?>">
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md6">
                <label class="layui-form-label">文作者</label>
                <div class="layui-input-block">
                    <input type="text" name="txt_author" class="layui-input" placeholder="请填写文作者" value="<?=$txt_author?>">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">作者公告</label>
            <div class="layui-input-block">
                <textarea id="editor2" name="notice" placeholder="作者漫画公告" class="layui-textarea"><?=$notice?></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">漫画简介</label>
            <div class="layui-input-block">
                <textarea id="editor2" name="content" placeholder="漫画简介" class="layui-textarea" style="min-height:100px;"><?=$content?></textarea>
            </div>
        </div>
        <div class="layui-form-item" style="text-align: center;">
            <input type="hidden" name="id" value="<?=$id?>">
            <button class="layui-btn" lay-filter="*" lay-submit>保存</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </form>
</div>
<script>
layui.use(['form','upload'], function(){
    var upload = layui.upload,
        form = layui.form;
    upload.render({
        elem: '.uppic',
        url: '<?=links('ajax','upload')?>',
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
    upload.render({
        elem: '.uppicx',
        url: '<?=links('ajax','upload')?>',
        accept: 'file',
        acceptMime: 'image/*',
        exts: '<?=Annex_Ext?>',
        done: function(res){
            if(res.code == 0){
                layer.msg(res.msg,{icon: 1});
                $('#picx').val(res.url);
            }else{
                layer.msg(res.msg,{icon: 2});
            }
        }
    });
    form.on('select(yid)', function(data){
        if(data.value == 2){
            $('#yid').show();
        }else{
            $('#yid').hide();
        }
    });
})
</script>
</body>
</html>