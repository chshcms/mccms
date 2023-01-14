<?php if (!defined('FCPATH')) exit('No direct script access allowed');
return array (
  array (
    'name' => '系统配置',
    'icon' => '&#xe653;',
    'file' => 'setting/',
    'list' => 
    array (
      array (
        'name' => '网站配置',
        'url' => 'setting/index',
        'init' => 1,
      ),
      array (
        'name' => '网站配置修改',
        'url' => 'setting/save',
        'init' => 0,
      ),
      array (
        'name' => '模版配置',
        'url' => 'setting/skins',
        'init' => 1,
      ),
      array (
        'name' => '模版配置修改',
        'url' => 'setting/skins_save,skins/down',
        'init' => 0,
      ),
      array (
        'name' => '模版云平台',
        'url' => 'skins/index',
        'init' => 0,
      ),
      array (
        'name' => '用户配置',
        'url' => 'setting/user',
        'init' => 1,
      ),
      array (
        'name' => '用户配置修改',
        'url' => 'setting/user_save',
        'init' => 0,
      ),
      array (
        'name' => '缓存配置',
        'url' => 'setting/cache',
        'init' => 1,
      ),
      array (
        'name' => '缓存配置修改',
        'url' => 'setting/cache_save',
        'init' => 0,
      ),
      array (
        'name' => '存储配置',
        'url' => 'setting/annex',
        'init' => 1,
      ),
      array (
        'name' => '存储配置修改',
        'url' => 'setting/annex_save',
        'init' => 0,
      ),
      array (
        'name' => '财务配置',
        'url' => 'setting/pay',
        'init' => 1,
      ),
      array (
        'name' => '财务配置修改',
        'url' => 'setting/pay_save',
        'init' => 0,
      ),
      array (
        'name' => 'URL推送',
        'url' => 'setting/push',
        'init' => 1,
      ),
      array (
        'name' => 'URL推送修改',
        'url' => 'setting/push_save',
        'init' => 0,
      ),
      array (
        'name' => '资源站配置',
        'url' => 'setting/zyz',
        'init' => 1,
      ),
      array (
        'name' => '资源站修改',
        'url' => 'setting/zyz_save',
        'init' => 0,
      ),
    ),
  ),
  array (
    'name' => '漫画管理',
    'icon' => '&#xe663;',
    'file' => 'comic/',
    'list' => 
    array (
      array (
        'name' => '漫画列表',
        'url' => 'comic/index,comic/ajax',
        'init' => 1,
      ),
      array (
        'name' => '漫画修改',
        'url' => 'comic/edit,comic/save,comic/init,comic/del',
        'init' => 0,
      ),
      array (
        'name' => '漫画删除',
        'url' => 'comic/del',
        'init' => 0,
      ),
      array (
        'name' => '章节浏览',
        'url' => 'comic/chapter,comic/chapter_ajax',
        'init' => 0,
      ),
      array (
        'name' => '章节操作',
        'url' => 'comic/chapter_edit,comic/chapter_init/vip,comic/chapter_init/cion,comic/chapter_init/px,comic/chapter_save,comic/chapter_del,comic/pic_del,comic/tbpic,comic/tbpic_save,comic/pic_api,comic/pic_save',
        'init' => 0,
      ),
      array (
        'name' => '分类列表',
        'url' => 'comic/lists',
        'init' => 1,
      ),
      array (
        'name' => '分类修改',
        'url' => 'comic/lists_edit,comic/lists_save,comic/lists_edit_save',
        'init' => 0,
      ),
      array (
        'name' => '分类删除',
        'url' => 'comic/lists_del',
        'init' => 0,
      ),
      array (
        'name' => '类型列表',
        'url' => 'comic/type',
        'init' => 1,
      ), 
      array (
        'name' => '类型修改',
        'url' => 'comic/type_add,comic/type_save,comic/type_add_save',
        'init' => 0,
      ),
      array (
        'name' => '类型删除',
        'url' => 'comic/type_del',
        'init' => 0,
      ),
    ),
  ),
  array (
    'name' => '小说管理',
    'icon' => '&#xe705;',
    'file' => 'book/',
    'list' => 
    array (
      array (
        'name' => '小说列表',
        'url' => 'book/index',
        'init' => 1,
      ),
      array (
        'name' => '小说修改',
        'url' => 'book/edit,book/save,book/init,book/del',
        'init' => 0,
      ),
      array (
        'name' => '小说删除',
        'url' => 'book/del',
        'init' => 0,
      ),
      array (
        'name' => '章节浏览',
        'url' => 'book/chapter,book/chapter_ajax',
        'init' => 0,
      ),
      array (
        'name' => '章节操作',
        'url' => 'book/chapter_edit,book/chapter_init/vip,book/chapter_init/cion,book/chapter_init/px,book/chapter_save,book/chapter_del,book/chapter_txt',
        'init' => 0,
      ),
      array (
        'name' => '分类列表',
        'url' => 'book/lists',
        'init' => 1,
      ),
      array (
        'name' => '分类修改',
        'url' => 'book/lists_edit,book/lists_save,book/lists_edit_save',
        'init' => 0,
      ),
      array (
        'name' => '分类删除',
        'url' => 'book/lists_del',
        'init' => 0,
      ),
    ),
  ),
  array (
    'name' => '采集管理',
    'icon' => '&#xe609;',
    'file' => 'caiji/',
    'list' => 
    array (
      array (
        'name' => '漫画采集',
        'url' => 'caiji/index/comic,caiji/json/comic',
        'init' => 1,
      ),
      array (
        'name' => '小说采集',
        'url' => 'caiji/index/book,caiji/json/book',
        'init' => 1,
      ),
      array (
        'name' => '采集入库',
        'url' => 'caiji/ruku',
        'init' =>0,
      ),
      array (
        'name' => '采集配置',
        'url' => 'caiji/setting,caiji/save,caiji/book_save',
        'init' =>0,
      ),
      array (
        'name' => '资源库浏览',
        'url' => 'caiji/show',
        'init' =>0,
      ),
      array (
        'name' => '资源库操作',
        'url' => 'caiji/init,caiji/del,caiji/daochu,caiji/uptxt,caiji/edit,caiji/zysave,caiji/bind',
        'init' =>0,
      ),
      array (
        'name' => '定时任务',
        'url' => 'caiji/timming,caiji/timming_edit,caiji/timming_save,caiji/timming_init,caiji/timming_del,caiji/timming_url',
        'init' =>0,
      ),
    ),
  ),
  array (
    'name' => '静态生成',
    'icon' => '&#xe656;',
    'file' => 'generate/',
    'list' => 
    array (
      array (
        'name' => '漫画生成',
        'url' => 'generate/comic',
        'init' => 1,
      ),
      array (
        'name' => '小说生成',
        'url' => 'generate/book',
        'init' => 1,
      ),
      array (
        'name' => '自定义模板生成',
        'url' => 'generate/custom',
        'init' => 1,
      ),
      array (
        'name' => '生成操作',
        'url' => 'generate/mark,generate/custom_save,generate/save,generate/lists_save,generate/comic_save,generate/chapter_save,generate/book_index,generate/blist_save,generate/info_save,generate/read_save',
        'init' => 0,
      ),
    ),
  ),
  array (
    'name' => '会员管理',
    'icon' => '&#xe770;',
    'file' => 'user/,comment/',
    'list' => 
    array (
      array (
        'name' => '用户列表',
        'url' => 'user/index,user/ajax',
        'init' => 1,
      ),
      array (
        'name' => '用户详细',
        'url' => 'user/show',
        'init' => 0,
      ),
      array (
        'name' => '用户编辑',
        'url' => 'user/edit,user/init,user/save',
        'init' => 0,
      ),
      array (
        'name' => '用户删除',
        'url' => 'user/del',
        'init' => 0,
      ),
      array (
        'name' => '签约作者',
        'url' => 'user/signing,user/signing_ajax',
        'init' => 1,
      ),
      array (
        'name' => '签约删除',
        'url' => 'user/signing_del',
        'init' => 0,
      ),
      array (
        'name' => '等待认证',
        'url' => 'user/index/2',
        'init' => 1,
      ),
      array (
        'name' => '评论管理',
        'url' => 'comment/index,comment/ajax',
        'init' => 1,
      ),
      array (
        'name' => '评论操作',
        'url' => 'comment/show,comment/reply_del,comment/del',
        'init' => 0,
      ),
    ),
  ),
  array (
    'name' => '礼物打赏',
    'icon' => '&#xe735;',
    'file' => 'gift/',
    'list' => 
    array (
      array (
        'name' => '礼物列表',
        'url' => 'gift/index,gift/ajax',
        'init' => 1,
      ),
      array (
        'name' => '礼物操作',
        'url' => 'gift/edit,gift/save',
        'init' => 0,
      ),
      array (
        'name' => '礼物删除',
        'url' => 'gift/del',
        'init' => 0,
      ),
      array (
        'name' => '打赏记录',
        'url' => 'gift/reward,gift/reward_ajax',
        'init' => 1,
      ),
      array (
        'name' => '打赏记录删除',
        'url' => 'gift/reward_del',
        'init' => 0,
      ),
      array (
        'name' => '月票记录',
        'url' => 'gift/ticket,gift/ticket_ajax',
        'init' => 1,
      ),
      array (
        'name' => '月票记录删除',
        'url' => 'gift/ticket_del',
        'init' => 0,
      ),
    ),
  ),
  array (
    'name' => '财务管理',
    'icon' => '&#xe65e;',
    'file' => 'pay/,card/',
    'list' => 
    array (
      array (
        'name' => '充值订单',
        'url' => 'pay/index,pay/ajax',
        'init' => 1,
      ),
      array (
        'name' => '订单记录删除',
        'url' => 'pay/del,pay/pldel',
        'init' => 0,
      ),
      array (
        'name' => '消费记录',
        'url' => 'pay/buy,pay/buy_ajax',
        'init' => 1,
      ),
      array (
        'name' => '消费记录删除',
        'url' => 'pay/buy_del',
        'init' => 0,
      ),
      array (
        'name' => '提现记录',
        'url' => 'pay/drawing,pay/drawing_ajax',
        'init' => 1,
      ),
      array (
        'name' => '提现操作',
        'url' => 'pay/drawing_show,pay/drawing_save',
        'init' => 0,
      ),
      array (
        'name' => '提现记录删除',
        'url' => 'pay/drawing_del',
        'init' => 0,
      ),
      array (
        'name' => '收入分成',
        'url' => 'pay/income,pay/income_ajax',
        'init' => 1,
      ),
      array (
        'name' => '收入分成删除',
        'url' => 'pay/income_del',
        'init' => 0,
      ),
      array (
        'name' => '卡密管理',
        'url' => 'card/index,card/ajax',
        'init' => 1,
      ),
      array (
        'name' => '卡密操作',
        'url' => 'card/add,card/edit,card/save,card/pladd,card/daochu',
        'init' => 0,
      ),
      array (
        'name' => '卡密删除',
        'url' => 'card/del',
        'init' => 0,
      ),
    ),
  ),
  array (
    'name' => '运营管理',
    'icon' => '&#xe7ae;',
    'file' => 'links/,ads/',
    'list' => 
    array (
      array (
        'name' => '友情链接',
        'url' => 'links/index,links/ajax',
        'init' => 1,
      ),
      array (
        'name' => '友情链接编辑',
        'url' => 'links/edit,links/save',
        'init' => 0,
      ),
      array (
        'name' => '友情链接删除',
        'url' => 'links/del',
        'init' => 0,
      ),
      array (
        'name' => '广告管理',
        'url' => 'ads/index,ads/ajax',
        'init' => 1,
      ),
      array (
        'name' => '广告编辑',
        'url' => 'ads/edit,ads/save',
        'init' => 0,
      ),
      array (
        'name' => '广告删除',
        'url' => 'ads/del',
        'init' => 0,
      ),
    ),
  ),
  array (
    'name' => 'APP管理',
    'icon' => '&#xe67f;',
    'file' => 'app/',
    'list' => 
    array (
      array (
        'name' => 'APP配置',
        'url' => 'app/index',
        'init' => 1,
      ),
      array (
        'name' => 'APP配置保存',
        'url' => 'app/setting',
        'init' => 0,
      ),
      array (
        'name' => '任务列表',
        'url' => 'app/task,app/task_ajax',
        'init' => 1,
      ),
      array (
        'name' => '任务编辑',
        'url' => 'app/edit,app/save',
        'init' => 0,
      ),
      array (
        'name' => '任务记录',
        'url' => 'app/task_list,task/task_list_ajax',
        'init' => 1,
      ),
      array (
        'name' => '任务记录删除',
        'url' => 'app/task_list_del',
        'init' => 0,
      ),
      array (
        'name' => '邀请记录',
        'url' => 'app/invite,app/invite_ajax',
        'init' => 1,
      ),
      array (
        'name' => '邀请记录删除',
        'url' => 'app/invite_del',
        'init' => 0,
      ),
      array (
        'name' => '数据统计',
        'url' => 'app/user,app/user_ajax',
        'init' => 1,
      ),
    ),
  ),
  array (
    'name' => '管理员管理',
    'icon' => '&#xe66f;',
    'file' => 'sys/,backups/',
    'list' => 
    array (
      array (
        'name' => '管理员列表',
        'url' => 'sys/index,sys/ajax',
        'init' => 1,
      ),
      array (
        'name' => '管理员修改',
        'url' => 'sys/edit,sys/init,sys/save',
        'init' => 0,
      ),
      array (
        'name' => '管理员删除',
        'url' => 'sys/del',
        'init' => 0,
      ),
      array (
        'name' => '登陆日志',
        'url' => 'sys/log,sys/logajax',
        'init' => 1,
      ),
      array (
        'name' => '登陆日志删除',
        'url' => 'sys/log_del',
        'init' => 0,
      ),
      array (
        'name' => '备份还原',
        'url' => 'backups/index',
        'init' => 1,
      ),
      array (
        'name' => '备份还原操作',
        'url' => 'backups/restore,backups/optimize,backups/repair,backups/truncate,backups/fileds,backups/beifen,backups/zip,backups/restore_del,backups/restore_save',
        'init' => 0,
      ),
      array (
        'name' => '系统更新',
        'url' => 'update/index',
        'init' => 0,
      ),
    ),
  ),
);