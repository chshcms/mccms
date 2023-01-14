DROP TABLE IF EXISTS `{prefix}admin`;
CREATE TABLE `{prefix}admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '' COMMENT '登陆账号',
  `pass` varchar(64) DEFAULT '',
  `nichen` varchar(64) DEFAULT '' COMMENT '昵称',
  `sid` tinyint(1) DEFAULT '0' COMMENT '状态,0正常1禁用',
  `qx` varchar(255) DEFAULT '',
  `logip` varchar(20) DEFAULT '',
  `logtime` int(11) DEFAULT '0',
  `lognum` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='管理员';
DROP TABLE IF EXISTS `{prefix}admin_log`;
CREATE TABLE `{prefix}admin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `logip` varchar(20) DEFAULT '' COMMENT '登陆IP',
  `logtime` int(11) DEFAULT '0' COMMENT '登陆时间',
  `browser` varchar(255) DEFAULT '' COMMENT '浏览器',
  `machine` varchar(20) DEFAULT '' COMMENT 'pc或者wap',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='登陆日志';
DROP TABLE IF EXISTS `{prefix}ads`;
CREATE TABLE `{prefix}ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '' COMMENT '广告名称',
  `bs` varchar(64) DEFAULT '' COMMENT '唯一标示',
  `html` text COMMENT 'html内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告列表';
DROP TABLE IF EXISTS `{prefix}buy`;
CREATE TABLE `{prefix}buy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) DEFAULT '' COMMENT '消费简介',
  `mid` int(11) DEFAULT '0' COMMENT '漫画ID',
  `bid` int(11) DEFAULT '0' COMMENT '小说ID',
  `cid` int(11) DEFAULT '0' COMMENT '章节ID',
  `uid` int(11) DEFAULT '0' COMMENT '消费会员ID',
  `cion` int(11) DEFAULT '0' COMMENT '消费积分',
  `ip` varchar(20) DEFAULT '' COMMENT 'IP',
  `addtime` int(11) DEFAULT '0' COMMENT '消费时间',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`) USING BTREE,
  KEY `bid` (`bid`) USING BTREE,
  KEY `cid` (`cid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='消费记录';
DROP TABLE IF EXISTS `{prefix}card`;
CREATE TABLE `{prefix}card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pass` varchar(255) DEFAULT '' COMMENT '卡密',
  `sid` tinyint(1) DEFAULT '0' COMMENT '0积分卡，1VIP卡',
  `day` int(11) DEFAULT '0' COMMENT 'Vip天数',
  `cion` int(11) DEFAULT '0' COMMENT '积分数量',
  `uid` int(11) DEFAULT '0' COMMENT '使用会员ID',
  `usetime` int(11) DEFAULT '0' COMMENT '使用时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='卡密列表';
DROP TABLE IF EXISTS `{prefix}class`;
CREATE TABLE `{prefix}class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT '' COMMENT '名称',
  `yname` varchar(255) DEFAULT '' COMMENT '英文名称',
  `fid` int(11) DEFAULT '0' COMMENT '上级ID',
  `xid` int(11) DEFAULT '0' COMMENT '排序ID',
  `tpl` varchar(64) DEFAULT 'lists.html' COMMENT '模版',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='漫画分类';
DROP TABLE IF EXISTS `{prefix}comic`;
CREATE TABLE `{prefix}comic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT '' COMMENT '标题',
  `yname` varchar(128) DEFAULT '' COMMENT '英文别名',
  `pic` varchar(255) DEFAULT '' COMMENT '竖版封面',
  `picx` varchar(255) DEFAULT '' COMMENT '横版封面',
  `cid` int(11) DEFAULT '0' COMMENT '分类ID',
  `tid` tinyint(1) DEFAULT '0' COMMENT '1推荐，0未推',
  `serialize` varchar(20) DEFAULT '' COMMENT '状态',
  `author` varchar(64) DEFAULT '' COMMENT '漫画作者',
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `notice` varchar(255) DEFAULT '' COMMENT '公告',
  `pic_author` varchar(128) DEFAULT '' COMMENT '图作者',
  `txt_author` varchar(128) DEFAULT '' COMMENT '文作者',
  `text` varchar(64) DEFAULT '' COMMENT '一句话简介',
  `content` varchar(500) DEFAULT '' COMMENT '介绍',
  `hits` int(11) DEFAULT '0' COMMENT '总点击',
  `yhits` int(11) DEFAULT '0' COMMENT '月点击',
  `zhits` int(11) DEFAULT '0' COMMENT '周点击',
  `rhits` int(11) DEFAULT '0' COMMENT '日点击',
  `shits` int(11) DEFAULT '0' COMMENT '收藏人气',
  `pay` tinyint(1) DEFAULT '0' COMMENT '是否收费1金币，2VIP',
  `cion` int(11) DEFAULT '0' COMMENT '打赏总额',
  `ticket` int(11) DEFAULT '0' COMMENT '月票总额',
  `sid` tinyint(1) DEFAULT '0' COMMENT '0正常1锁定',
  `nums` int(11) DEFAULT '0' COMMENT '章节总数',
  `score` decimal(2,1) DEFAULT '9.8' COMMENT '总得分',
  `did` int(11) DEFAULT '0' COMMENT '采集资源ID',
  `ly` varchar(64) DEFAULT '' COMMENT '采集来源标识',
  `yid` tinyint(1) DEFAULT '0' COMMENT '0正常，1待审核',
  `msg` varchar(128) DEFAULT '' COMMENT '未审核原因',
  `addtime` int(11) DEFAULT '0' COMMENT '入库时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `serialize` (`serialize`) USING BTREE,
  KEY `hits` (`hits`) USING BTREE,
  KEY `yhits` (`yhits`) USING BTREE,
  KEY `zhits` (`zhits`) USING BTREE,
  KEY `rhits` (`rhits`) USING BTREE,
  KEY `shits` (`shits`) USING BTREE,
  KEY `cion` (`cion`) USING BTREE,
  KEY `yid` (`yid`) USING BTREE,
  KEY `addtime` (`addtime`) USING BTREE,
  KEY `pay` (`pay`) USING BTREE,
  KEY `ticket` (`ticket`) USING BTREE,
  KEY `score` (`score`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='漫画列表';
DROP TABLE IF EXISTS `{prefix}comic_buy`;
CREATE TABLE `{prefix}comic_buy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT '0' COMMENT '漫画ID',
  `cid` int(11) DEFAULT '0' COMMENT '章节ID',
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `auto` tinyint(1) DEFAULT '0' COMMENT '1开启自动购买',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_mid_cid` (`uid`,`mid`,`cid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='漫画购买记录';
DROP TABLE IF EXISTS `{prefix}comic_chapter`;
CREATE TABLE `{prefix}comic_chapter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT '0' COMMENT '漫画ID',
  `xid` int(11) DEFAULT '0' COMMENT '排序ID',
  `name` varchar(128) DEFAULT '' COMMENT '标题',
  `jxurl` varchar(255) DEFAULT '' COMMENT '解析地址',
  `vip` tinyint(1) DEFAULT '0' COMMENT 'VIP阅读，0否1是',
  `cion` int(11) DEFAULT '0' COMMENT '章节需要金币',
  `pnum` int(11) DEFAULT '0' COMMENT '图片数量',
  `yid` tinyint(1) DEFAULT '0' COMMENT '0已审核，1待审核，2未通过',
  `msg` varchar(128) DEFAULT '' COMMENT '未通过原因',
  `addtime` int(11) DEFAULT '0' COMMENT '入库时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mid_xid` (`mid`,`xid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='漫画章节';
DROP TABLE IF EXISTS `{prefix}comic_pic`;
CREATE TABLE `{prefix}comic_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) DEFAULT '0' COMMENT '章节ID',
  `mid` int(11) DEFAULT '0' COMMENT '漫画ID',
  `img` varchar(255) DEFAULT '' COMMENT '图片url地址',
  `width` int(11) DEFAULT '0' COMMENT '图片宽度',
  `height` int(11) DEFAULT '0' COMMENT '图片高度',
  `xid` int(11) DEFAULT '0' COMMENT '排序ID',
  `md5` varchar(40) DEFAULT '' COMMENT '源地址MD5',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`) USING BTREE,
  KEY `mid` (`mid`) USING BTREE,
  KEY `xid` (`xid`) USING BTREE,
  KEY `md5` (`md5`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='章节图片';
DROP TABLE IF EXISTS `{prefix}comic_score`;
CREATE TABLE `{prefix}comic_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT '0' COMMENT '漫画ID',
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `pf` tinyint(2) DEFAULT '0' COMMENT '评分，1-10',
  `addtime` int(11) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_mid` (`uid`,`mid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='漫画评分';
DROP TABLE IF EXISTS `{prefix}comic_type`;
CREATE TABLE `{prefix}comic_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT '0' COMMENT '类别ID',
  `mid` int(11) DEFAULT '0' COMMENT '漫画ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tid_mid` (`tid`,`mid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE,
  KEY `mid` (`mid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='类型关联';
DROP TABLE IF EXISTS `{prefix}comment`;
CREATE TABLE `{prefix}comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT '0' COMMENT '漫画ID',
  `bid` int(11) DEFAULT '0' COMMENT '小说ID',
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `text` varchar(500) DEFAULT '' COMMENT '评论内容',
  `reply_num` int(11) DEFAULT '0' COMMENT '回复总数',
  `machine` varchar(64) DEFAULT '' COMMENT '来自PC、wap、app',
  `ip` varchar(30) DEFAULT '' COMMENT 'IP',
  `zan` int(11) DEFAULT '0' COMMENT '被顶次数',
  `addtime` int(11) DEFAULT '0' COMMENT '评论时间',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`) USING BTREE,
  KEY `bid` (`bid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='评论记录';
DROP TABLE IF EXISTS `{prefix}comment_reply`;
CREATE TABLE `{prefix}comment_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) DEFAULT '0' COMMENT '评论ID',
  `fid` int(11) DEFAULT '0' COMMENT '上级ID',
  `mid` int(11) DEFAULT '0' COMMENT '漫画ID',
  `bid` int(11) DEFAULT '0' COMMENT '小说ID',
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `text` varchar(500) DEFAULT '' COMMENT '评论内容',
  `machine` varchar(64) DEFAULT '' COMMENT '来自PC、wap、app',
  `ip` varchar(30) DEFAULT '' COMMENT 'ip',
  `zan` int(11) DEFAULT '0' COMMENT '被顶次数',
  `addtime` int(11) DEFAULT '0' COMMENT '评论时间',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`) USING BTREE,
  KEY `bid` (`bid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `fid` (`fid`) USING BTREE,
  KEY `cid` (`cid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='评论回复';
DROP TABLE IF EXISTS `{prefix}comment_zan`;
CREATE TABLE `{prefix}comment_zan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) DEFAULT '0' COMMENT '评论ID',
  `fid` tinyint(1) DEFAULT '0' COMMENT '0评论，1回复',
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_cid_fid` (`cid`,`fid`,`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='评论顶记录';
DROP TABLE IF EXISTS `{prefix}drawing`;
CREATE TABLE `{prefix}drawing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dd` varchar(64) DEFAULT '' COMMENT '提现单号',
  `uid` int(11) DEFAULT '0' COMMENT '收入会员ID',
  `rmb` decimal(8,2) DEFAULT '0.00' COMMENT '金额',
  `pid` tinyint(1) DEFAULT '0' COMMENT '状态，0待审，1成功，2失败',
  `msg` varchar(255) DEFAULT '' COMMENT '失败信息',
  `ip` varchar(30) DEFAULT '' COMMENT 'IP',
  `addtime` int(11) DEFAULT '0' COMMENT '收入时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='提现记录';
DROP TABLE IF EXISTS `{prefix}fav`;
CREATE TABLE `{prefix}fav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `mid` int(11) DEFAULT '0' COMMENT '漫画ID',
  `addtime` int(11) DEFAULT '0' COMMENT '收藏时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_mid` (`uid`,`mid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='收藏记录';
DROP TABLE IF EXISTS `{prefix}gift`;
CREATE TABLE `{prefix}gift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '' COMMENT '礼物名称',
  `pic` varchar(255) DEFAULT '' COMMENT '礼物图片',
  `cion` int(11) DEFAULT '0' COMMENT '礼物价格',
  `text` varchar(255) DEFAULT '' COMMENT '礼物简介',
  `xid` int(11) DEFAULT '0' COMMENT '排序ID',
  `yid` tinyint(1) DEFAULT '0' COMMENT '状态0正常，1隐藏',
  PRIMARY KEY (`id`),
  KEY `xid` (`xid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='礼物列表';
DROP TABLE IF EXISTS `{prefix}gift_reward`;
CREATE TABLE `{prefix}gift_reward` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) DEFAULT '0' COMMENT '礼物ID',
  `mid` int(11) DEFAULT '0' COMMENT '漫画ID',
  `bid` int(11) DEFAULT '0' COMMENT '小说ID',
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `num` int(11) DEFAULT '0' COMMENT '打赏数量',
  `cion` int(11) DEFAULT '0' COMMENT '总金额',
  `text` varchar(255) DEFAULT '' COMMENT '打赏寄语',
  `addtime` int(11) DEFAULT '0' COMMENT '打赏时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `mid` (`mid`) USING BTREE,
  KEY `bid` (`bid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='礼物记录';
DROP TABLE IF EXISTS `{prefix}income`;
CREATE TABLE `{prefix}income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) DEFAULT '' COMMENT '收入简介',
  `mid` int(11) DEFAULT '0' COMMENT '漫画ID',
  `bid` int(11) DEFAULT '0' COMMENT '小说ID',
  `uid` int(11) DEFAULT '0' COMMENT '收入会员ID',
  `cion` int(11) DEFAULT '0' COMMENT '分成金额',
  `zcion` int(11) DEFAULT '0' COMMENT '总金额',
  `addtime` int(11) DEFAULT '0' COMMENT '收入时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `mid` (`mid`) USING BTREE,
  KEY `bid` (`bid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='收入记录';
DROP TABLE IF EXISTS `{prefix}links`;
CREATE TABLE `{prefix}links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT '' COMMENT '标题',
  `url` varchar(255) DEFAULT '' COMMENT '链接地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='友情链接';
DROP TABLE IF EXISTS `{prefix}message`;
CREATE TABLE `{prefix}message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `name` varchar(64) DEFAULT '' COMMENT '标题',
  `text` varchar(255) DEFAULT '' COMMENT '内容',
  `did` tinyint(1) DEFAULT '0' COMMENT '0未读，1已读',
  `addtime` int(11) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户消息';
DROP TABLE IF EXISTS `{prefix}order`;
CREATE TABLE `{prefix}order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `dd` varchar(64) DEFAULT '' COMMENT '订单号',
  `rmb` decimal(6,2) DEFAULT '0.00' COMMENT '金额',
  `pid` tinyint(1) DEFAULT '0' COMMENT '状态',
  `text` varchar(255) DEFAULT '' COMMENT '备注',
  `zd` varchar(255) DEFAULT '' COMMENT '预增加字段和数量',
  `type` varchar(20) DEFAULT '' COMMENT '支付方式',
  `addtime` int(11) DEFAULT '0' COMMENT '订单时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='订单记录';
DROP TABLE IF EXISTS `{prefix}read`;
CREATE TABLE `{prefix}read` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `mid` int(11) DEFAULT '0' COMMENT '漫画ID',
  `cid` int(11) DEFAULT '0' COMMENT '章节ID',
  `pid` int(11) DEFAULT '0' COMMENT '图片ID',
  `addtime` int(11) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_mid` (`uid`,`mid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='阅读记录';
DROP TABLE IF EXISTS `{prefix}telcode`;
CREATE TABLE `{prefix}telcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tel` varchar(20) DEFAULT '' COMMENT '手机号码',
  `code` varchar(10) DEFAULT '' COMMENT '验证码',
  `addtime` int(11) DEFAULT '0' COMMENT '发送时间',
  PRIMARY KEY (`id`),
  KEY `tel` (`tel`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='手机验证码';
DROP TABLE IF EXISTS `{prefix}ticket`;
CREATE TABLE `{prefix}ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT '0' COMMENT '漫画ID',
  `bid` int(11) DEFAULT '0' COMMENT '小说ID',
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `num` int(11) DEFAULT '0' COMMENT '打赏数量',
  `text` varchar(255) DEFAULT '' COMMENT '月票寄语',
  `addtime` int(11) DEFAULT '0' COMMENT '打赏时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `mid` (`mid`) USING BTREE,
  KEY `bid` (`bid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='月票记录';
DROP TABLE IF EXISTS `{prefix}type`;
CREATE TABLE `{prefix}type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '' COMMENT '名称',
  `fid` int(11) DEFAULT '0' COMMENT '上级ID',
  `zd` varchar(64) DEFAULT '' COMMENT '字段',
  `xid` int(11) DEFAULT '0' COMMENT '排序ID',
  `cid` tinyint(1) DEFAULT '0' COMMENT '0多选，1单选',
  PRIMARY KEY (`id`),
  KEY `zd_name` (`name`,`zd`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='漫画类型';
DROP TABLE IF EXISTS `{prefix}user`;
CREATE TABLE `{prefix}user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `signing` tinyint(1) DEFAULT '0' COMMENT '是否签约，0未1已',
  `name` varchar(64) DEFAULT '' COMMENT '用户名',
  `pass` varchar(64) DEFAULT '' COMMENT '密码',
  `nichen` varchar(64) DEFAULT '' COMMENT '昵称',
  `tel` varchar(15) DEFAULT '' COMMENT '手机',
  `pic` varchar(255) DEFAULT '' COMMENT '头像地址',
  `qq` varchar(20) DEFAULT '' COMMENT 'QQ',
  `email` varchar(128) DEFAULT '' COMMENT '邮箱',
  `city` varchar(128) DEFAULT '' COMMENT '地区',
  `sex` varchar(5) DEFAULT '保密' COMMENT '性别',
  `text` varchar(255) DEFAULT '' COMMENT '介绍',
  `vip` tinyint(1) DEFAULT '0' COMMENT '是否VIP',
  `rmb` decimal(6,2) DEFAULT '0.00' COMMENT '金额',
  `cion` int(11) DEFAULT '0' COMMENT '金币',
  `ticket` int(11) DEFAULT '0' COMMENT '月票',
  `viptime` int(11) DEFAULT '0' COMMENT 'vip到期时间',
  `sid` tinyint(1) DEFAULT '0' COMMENT '状态，1锁定0正常',
  `cid` tinyint(1) DEFAULT '0' COMMENT '认证状态',
  `realname` varchar(128) DEFAULT '' COMMENT '真实名字',
  `idcard` varchar(64) DEFAULT '' COMMENT '身份证号码',
  `bank` varchar(128) DEFAULT '' COMMENT '收款银行',
  `card` varchar(128) DEFAULT '' COMMENT '收款账号',
  `bankcity` varchar(255) DEFAULT '' COMMENT '开户行地址',
  `pass_err` int(10) DEFAULT '0' COMMENT '密码错误次数',
  `rz_type` tinyint(1) DEFAULT '1' COMMENT '认证方式，1个人，2企业',
  `rz_msg` varchar(128) DEFAULT '' COMMENT '认证失败原因',
  `addtime` int(11) DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`id`),
  KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户列表';
DROP TABLE IF EXISTS `{prefix}user_oauth`;
CREATE TABLE `{prefix}user_oauth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '会员ID',
  `oid` varchar(128) DEFAULT '' COMMENT '第三方平台ID',
  `type` varchar(20) DEFAULT '' COMMENT '类型',
  `nichen` varchar(64) DEFAULT '' COMMENT '昵称',
  `pic` varchar(255) DEFAULT '' COMMENT '头像地址',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `oid_type` (`oid`,`type`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='第三方登陆';
DROP TABLE IF EXISTS `{prefix}book_class`;
CREATE TABLE `{prefix}book_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT '' COMMENT '名称',
  `yname` varchar(255) DEFAULT '' COMMENT '英文名称',
  `fid` int(11) DEFAULT '0' COMMENT '上级ID',
  `xid` int(11) DEFAULT '0' COMMENT '排序ID',
  `tpl` varchar(64) DEFAULT 'lists.html' COMMENT '模版',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='小说分类';
DROP TABLE IF EXISTS `{prefix}book`;
CREATE TABLE `{prefix}book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT '' COMMENT '标题',
  `yname` varchar(128) DEFAULT '' COMMENT '英文别名',
  `pic` varchar(255) DEFAULT '' COMMENT '竖版封面',
  `picx` varchar(255) DEFAULT '' COMMENT '横版封面',
  `cid` int(11) DEFAULT '0' COMMENT '分类ID',
  `tid` tinyint(1) DEFAULT '0' COMMENT '1推荐，0未推',
  `serialize` varchar(20) DEFAULT '' COMMENT '状态',
  `author` varchar(64) DEFAULT '' COMMENT '小说作者',
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `notice` varchar(255) DEFAULT '' COMMENT '公告',
  `tags` varchar(128) DEFAULT '' COMMENT 'Tags标签',
  `text` varchar(64) DEFAULT '' COMMENT '一句话简介',
  `content` varchar(500) DEFAULT '' COMMENT '介绍',
  `text_num` int(11) DEFAULT '0' COMMENT '总字数',
  `hits` int(11) DEFAULT '0' COMMENT '总点击',
  `yhits` int(11) DEFAULT '0' COMMENT '月点击',
  `zhits` int(11) DEFAULT '0' COMMENT '周点击',
  `rhits` int(11) DEFAULT '0' COMMENT '日点击',
  `shits` int(11) DEFAULT '0' COMMENT '收藏人气',
  `pay` tinyint(1) DEFAULT '0' COMMENT '是否收费1金币，2VIP',
  `cion` int(11) DEFAULT '0' COMMENT '打赏总额',
  `ticket` int(11) DEFAULT '0' COMMENT '月票总额',
  `sid` tinyint(1) DEFAULT '0' COMMENT '0正常1锁定',
  `nums` int(11) DEFAULT '0' COMMENT '章节总数',
  `score` decimal(2,1) DEFAULT '9.8' COMMENT '总得分',
  `did` int(11) DEFAULT '0' COMMENT '采集资源ID',
  `ly` varchar(64) DEFAULT '' COMMENT '采集来源标识',
  `yid` tinyint(1) DEFAULT '0' COMMENT '0正常，1待审核',
  `msg` varchar(128) DEFAULT '' COMMENT '未审核原因',
  `addtime` int(11) DEFAULT '0' COMMENT '入库时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `serialize` (`serialize`) USING BTREE,
  KEY `hits` (`hits`) USING BTREE,
  KEY `yhits` (`yhits`) USING BTREE,
  KEY `zhits` (`zhits`) USING BTREE,
  KEY `rhits` (`rhits`) USING BTREE,
  KEY `shits` (`shits`) USING BTREE,
  KEY `cion` (`cion`) USING BTREE,
  KEY `yid` (`yid`) USING BTREE,
  KEY `text_num` (`text_num`) USING BTREE,
  KEY `addtime` (`addtime`) USING BTREE,
  KEY `pay` (`pay`) USING BTREE,
  KEY `ticket` (`ticket`) USING BTREE,
  KEY `score` (`score`) USING BTREE,
  KEY `tags` (`tags`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='小说列表';
DROP TABLE IF EXISTS `{prefix}book_buy`;
CREATE TABLE `{prefix}book_buy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) DEFAULT '0' COMMENT '小说ID',
  `cid` int(11) DEFAULT '0' COMMENT '章节ID',
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `auto` tinyint(1) DEFAULT '0' COMMENT '1开启自动购买',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_bid_cid` (`uid`,`bid`,`cid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小说购买记录';
DROP TABLE IF EXISTS `{prefix}book_fav`;
CREATE TABLE `{prefix}book_fav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `bid` int(11) DEFAULT '0' COMMENT '小说ID',
  `addtime` int(11) DEFAULT '0' COMMENT '收藏时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_bid` (`uid`,`bid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='小说收藏记录';
DROP TABLE IF EXISTS `{prefix}book_read`;
CREATE TABLE `{prefix}book_read` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `bid` int(11) DEFAULT '0' COMMENT '小说ID',
  `cid` int(11) DEFAULT '0' COMMENT '章节ID',
  `addtime` int(11) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_bid` (`uid`,`bid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='小说阅读记录';
DROP TABLE IF EXISTS `{prefix}book_score`;
CREATE TABLE `{prefix}book_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) DEFAULT '0' COMMENT '小说ID',
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `pf` tinyint(2) DEFAULT '0' COMMENT '评分，1-10',
  `addtime` int(11) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_bid` (`uid`,`bid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='小说评分';
DROP TABLE IF EXISTS `{prefix}task`;
CREATE TABLE `{prefix}task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yid` int(11) DEFAULT '0' COMMENT '是否关闭：0开启，1关闭',
  `name` varchar(64) DEFAULT '' COMMENT '任务标题',
  `text` varchar(64) DEFAULT '' COMMENT '任务内容',
  `cion` int(11) DEFAULT '0' COMMENT '奖励金币',
  `vip` int(11) DEFAULT '0' COMMENT '奖励VIP天数',
  `daynum` int(11) DEFAULT '0' COMMENT '每日奖励上限次数，0不限制',
  PRIMARY KEY (`id`),
  KEY `yid` (`yid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='APP每日任务';
DROP TABLE IF EXISTS `{prefix}task_list`;
CREATE TABLE `{prefix}task_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `tid` int(11) DEFAULT '0' COMMENT '任务ID',
  `vip` int(11) DEFAULT '0' COMMENT '奖励VIP天数',
  `cion` int(11) DEFAULT '0' COMMENT '获得金币',
  `addtime` int(11) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE,
  KEY `addtime` (`addtime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='APP任务记录表';
DROP TABLE IF EXISTS `{prefix}user_invite`;
CREATE TABLE `{prefix}user_invite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `inviteid` int(11) DEFAULT '0' COMMENT '邀请人ID',
  `deviceid` varchar(128) DEFAULT '' COMMENT '设备ID',
  `addtime` int(11) DEFAULT '0' COMMENT '邀请时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `inviteid` (`inviteid`) USING BTREE,
  KEY `deviceid` (`deviceid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='邀请记录表';
DROP TABLE IF EXISTS `{prefix}user_app`;
CREATE TABLE `{prefix}user_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `facility` varchar(20) DEFAULT '' COMMENT '来源',
  `deviceid` varchar(128) DEFAULT '' COMMENT '设备ID',
  `addtime` int(11) DEFAULT '0' COMMENT '安装时间',
  `uptime` int(11) DEFAULT '0' COMMENT '活跃时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  UNIQUE KEY `facility_deviceid` (`facility`,`deviceid`),
  KEY `addtime` (`addtime`) USING BTREE,
  KEY `uptime` (`uptime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='APP设备表';
DROP TABLE IF EXISTS `{prefix}user_app_nums`;
CREATE TABLE `{prefix}user_app_nums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `android_nums` int(11) DEFAULT '0' COMMENT '安卓日活量',
  `android_add` int(11) DEFAULT '0' COMMENT '安卓新增量',
  `ios_nums` int(11) DEFAULT '0' COMMENT '苹果日活量',
  `ios_add` int(11) DEFAULT '0' COMMENT '苹果新增量',
  `date` int(11) DEFAULT '0' COMMENT '日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='APP统计表';