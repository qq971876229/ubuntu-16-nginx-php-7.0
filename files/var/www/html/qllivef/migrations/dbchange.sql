#add choose options string in setting table
ALTER TABLE `ql_setting` ADD `options` varchar(255) NOT NULL DEFAULT '' COMMENT '选项值，以|间隔' AFTER `name`;
ALTER TABLE `ql_setting` ADD `options_name` varchar(255) NOT NULL DEFAULT '' COMMENT '选项名称，以|间隔' AFTER `options`;
ALTER TABLE `ql_setting` ADD `selected_name` varchar(255) NOT NULL DEFAULT '' COMMENT '选择值的名称' AFTER `options_name`;
ALTER TABLE `ql_setting` ADD `input_value` varchar(255) NOT NULL DEFAULT '' COMMENT '手动输入值' AFTER `selected_name`;
ALTER TABLE `ql_setting` ADD `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间' AFTER `remark`;
ALTER TABLE `ql_setting` ADD `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间' AFTER `start_time`;
ALTER TABLE `ql_setting` ADD `order_type` varchar(20) NOT NULL DEFAULT 'default' COMMENT '排序方式 default 默认排序，主要靠刷数据时排序  rand 随机排序 ' AFTER `input_value`;
ALTER TABLE `ql_setting` MODIFY COLUMN `name` VARCHAR (255)  NOT NULL DEFAULT '' COMMENT '设置名称';
ALTER TABLE `ql_setting` ADD `order_index` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间' AFTER `name`;


#video table
CREATE TABLE `user_video` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(10) unsigned NOT NULL COMMENT '所属用户id',
  `video` VARCHAR(255)  NOT NULL COMMENT '视频地址',
  `img` VARCHAR(255)  NOT NULL COMMENT '视频截图',
  `stick` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '置顶 1 默认 0',
  `stick_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '置顶时间',
  `created_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户个人秀视频表';


#charm ranking
CREATE TABLE `charm_ranking`(
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(10) UNSIGNED NOT NULL  COMMENT '用户id',
  `nickname` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '昵称',
  `img` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
  `birthday` INT(11) NOT NULL DEFAULT 0 COMMENT '生日',
  `total_income` INT(11) NOT NULL DEFAULT 0 COMMENT '总收入',
  `location` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '地址',
  `online_state` INT(10) NOT NULL DEFAULT 10 COMMENT '在线状态',
  `created_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='魅力排行榜';

ALTER TABLE `charm_ranking` ADD `price` int(11) NOT NULL DEFAULT '0' COMMENT '价格' AFTER `birthday`;
ALTER TABLE `charm_ranking` ADD `total_money` int(11) NOT NULL DEFAULT '0' COMMENT '总收入，方便手机端' AFTER `birthday`;



#rich ranking
CREATE TABLE `rich_ranking`(
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '用户id',
  `img` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
  `nickname` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '昵称',
  `birthday` INT(11) NOT NULL DEFAULT 0 COMMENT '生日',
  `total_consumption` INT(11) NOT NULL DEFAULT 0 COMMENT '总消费',
  `location` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '地址',
  `online_state` INT(10) NOT NULL DEFAULT 10 COMMENT '在线状态',
  `created_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='富豪排行榜';

ALTER TABLE `rich_ranking` ADD `price` int(11) NOT NULL DEFAULT '0' COMMENT '价格' AFTER `birthday`;
ALTER TABLE `rich_ranking` ADD `total_money` int(11) NOT NULL DEFAULT '0' COMMENT '总消费，方便手机端' AFTER `birthday`;


#ad
ALTER TABLE `ad` ADD `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序 越大越靠前' AFTER `type`;

#users
ALTER TABLE `users` ADD `nickname` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '用户昵称' AFTER `login_name`;
ALTER TABLE `users` ADD `request_time` int(11) NOT NULL DEFAULT '10' COMMENT '请求时间' AFTER `online_state`;
ALTER TABLE `users` ADD `sex` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '用户性别' AFTER `nickname`;
ALTER TABLE `users` ADD `birthday` int(11) NOT NULL DEFAULT '0' COMMENT '用户生日' AFTER `sex`;
ALTER TABLE `users` ADD `phone_type` int(11) NOT NULL DEFAULT '0' COMMENT '手机类型 ios/android' AFTER `login_moblie`;
ALTER TABLE `users` ADD `phone_system` int(11) NOT NULL DEFAULT '0' COMMENT '手机系统 ios 11/android vivox9' AFTER `phone_type`;
ALTER TABLE `users` MODIFY COLUMN `phone_type` VARCHAR (20)  NOT NULL DEFAULT '' COMMENT '手机类型 ios/android  只记录第一次的机型';
ALTER TABLE `users` MODIFY COLUMN `phone_system` VARCHAR (255)  NOT NULL DEFAULT '' COMMENT '手机系统 ios 11/android vivox9 只记录第一次的机型';
ALTER TABLE `users` ADD `uid` int(20) NOT NULL DEFAULT '0' COMMENT 'uid 生成的9位数的id放在这里，防止修改数据出错' AFTER `id`;
ALTER TABLE `users` ADD `status` VARCHAR(20) NOT NULL DEFAULT 'normal' COMMENT '用户状态，默认 normal=正常  delete=删除  ' AFTER `request_time`;
ALTER TABLE `users` ADD `signature` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '用户个性签名' AFTER `nickname`;

#money
ALTER TABLE `money` ADD `old_uid` int(20) NOT NULL DEFAULT '0' COMMENT 'old_uid 生成的9位数的uid放在这里，防止修改数据出错' AFTER `id`;
ALTER TABLE `money` ADD `session_id` int(20) NOT NULL DEFAULT '0' COMMENT '会话id,合并账单时使用' AFTER `remark`;

#new ranking(暂时不用了)
CREATE TABLE `new_ranking`(
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '用户id',
  `img` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '头像',
  `nickname` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '昵称',
  `birthday` INT(11) NOT NULL DEFAULT 0 COMMENT '生日',
  `total_consumption` INT(11) NOT NULL DEFAULT 0 COMMENT '总消费',
  `location` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '地址',
  `online_state` INT(10) NOT NULL DEFAULT 10 COMMENT '在线状态',
  `created_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新人榜（最近注册的50位）';

#online time
CREATE TABLE `online_time`(
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '用户id',
  `time_long` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '时长，单位为秒',
  `date` INT(11) NOT NULL DEFAULT 0 COMMENT '在线日期',
  `created_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户在线时长';


#recharge calculate from the money table
CREATE TABLE `recharge_calc`(
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `num` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '充值人数',
  `ios` int(10) NOT NULL DEFAULT '0' COMMENT '当日充值ios',
  `android` int(10) NOT NULL DEFAULT '0' COMMENT '当日充值android',
  `first` int(10) NOT NULL DEFAULT '0' COMMENT '首充人数',
  `total_money` int(10) NOT NULL DEFAULT '0' COMMENT '总充值金额',
  `reference_money` int(10) NOT NULL DEFAULT '0' COMMENT '邀请人充值金额',
  `average_money` int(10) NOT NULL DEFAULT '0' COMMENT '人均充值金额',
  `total_order` int(10) NOT NULL DEFAULT '0' COMMENT '总定单数',
  `ten` int(10) NOT NULL DEFAULT '0' COMMENT '10元定单数',
  `thirty` int(10) NOT NULL DEFAULT '0' COMMENT '30元定单数',
  `fifty` int(10) NOT NULL DEFAULT '0' COMMENT '50元定单数',
  `one_hundred` int(10) NOT NULL DEFAULT '0' COMMENT '100元定单数',
  `two_hundred` int(10) NOT NULL DEFAULT '0' COMMENT '200元定单数',
  `five_hundred` int(10) NOT NULL DEFAULT '0' COMMENT '500元定单数',
  `one_thousand` int(10) NOT NULL DEFAULT '0' COMMENT '1000元定单数',
  `two_thousand` int(10) NOT NULL DEFAULT '0' COMMENT '2000元定单数',
  `created_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值统计';

ALTER TABLE `recharge_calc` ADD `date` VARCHAR(50) NOT NULL DEFAULT '0' COMMENT '日期' AFTER `id`;
ALTER TABLE `recharge_calc` MODIFY COLUMN `average_money` DECIMAL(18,2)  NOT NULL DEFAULT '0' COMMENT '人均充值金额';
ALTER TABLE `recharge_calc` ADD `register` int(20) NOT NULL DEFAULT '0' COMMENT '注册人数' AFTER `date`;
ALTER TABLE `recharge_calc` ADD `register_ios` int(20) NOT NULL DEFAULT '0' COMMENT '注册人数ios' AFTER `register`;
ALTER TABLE `recharge_calc` ADD `register_android` int(20) NOT NULL DEFAULT '0' COMMENT '注册人数android' AFTER `register_ios`;
ALTER TABLE `recharge_calc` ADD `login_view` int(20) NOT NULL DEFAULT '0' COMMENT '登录用户人数' AFTER `register_android`;
ALTER TABLE `recharge_calc` ADD `login_live` int(20) NOT NULL DEFAULT '0' COMMENT '登录主播人数' AFTER `login_view`;

#login log ,record the user's login time
CREATE TABLE `log_login`(
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` VARCHAR(20)  NOT NULL DEFAULT '' COMMENT '用户id',
  `created_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='登录日志';



