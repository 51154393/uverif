-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2023-06-13 12:56:43
-- 服务器版本： 5.7.26
-- PHP 版本： 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `www_user_com`
--

-- --------------------------------------------------------

--
-- 表的结构 `u_agent`
--

CREATE TABLE `u_agent` (
  `id` int(11) NOT NULL,
  `aggid` int(11) NOT NULL COMMENT '代理组ID',
  `uid` int(11) NOT NULL,
  `note` varchar(64) DEFAULT NULL,
  `pay_divide` int(3) NOT NULL COMMENT '充值分成',
  `km_discount` int(2) NOT NULL COMMENT '开卡折扣',
  `money` float(10,2) DEFAULT '0.00' COMMENT '余额',
  `cash_name` varchar(64) DEFAULT NULL COMMENT '提现姓名',
  `cash_account` varchar(64) DEFAULT NULL COMMENT '提现账号',
  `cash_way` enum('ali','wx') DEFAULT NULL COMMENT '提现方式',
  `time` int(10) NOT NULL COMMENT '创建时间',
  `state` enum('on','off') DEFAULT 'on' COMMENT '状态',
  `appid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `u_agent_cash`
--

CREATE TABLE `u_agent_cash` (
  `id` int(11) NOT NULL,
  `agid` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `account` varchar(64) NOT NULL,
  `way` enum('ali','wx') NOT NULL,
  `money` float(10,2) NOT NULL,
  `add_time` int(10) NOT NULL,
  `end_time` int(10) DEFAULT NULL,
  `state` int(1) NOT NULL DEFAULT '0',
  `rebut_msg` varchar(255) DEFAULT NULL,
  `appid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `u_agent_group`
--

CREATE TABLE `u_agent_group` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL COMMENT '卡密组名称',
  `pay_divide` int(3) DEFAULT '0' COMMENT '充值分成',
  `km_discount` int(2) DEFAULT '0' COMMENT '卡密折扣',
  `appid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `u_app`
--

CREATE TABLE `u_app` (
  `id` int(11) NOT NULL,
  `app_key` varchar(32) NOT NULL COMMENT 'app密钥',
  `app_name` varchar(64) NOT NULL COMMENT 'app名称',
  `app_logo` varchar(64) DEFAULT NULL COMMENT 'applogo',
  `app_mode` enum('y','n') DEFAULT 'y' COMMENT 'app模式：y=正常/n=免费',
  `app_state` enum('on','off') DEFAULT 'on' COMMENT 'app开关',
  `app_off_msg` varchar(255) DEFAULT NULL COMMENT 'app关闭消息',
  `reg_state` enum('on','off') DEFAULT 'on' COMMENT '注册开关',
  `reg_off_msg` varchar(255) DEFAULT NULL COMMENT '注册关闭消息',
  `reg_way` enum('phone','email','wordnum') DEFAULT 'email' COMMENT '注册方式',
  `reg_time_mc` int(10) DEFAULT '24' COMMENT '机器码注册间隔时间',
  `reg_time_ip` int(10) DEFAULT '24' COMMENT 'IP注册间隔时间',
  `reg_award` enum('vip','fen') DEFAULT 'vip' COMMENT '注册奖励类型',
  `reg_award_val` bigint(10) DEFAULT '86400' COMMENT '注册奖励：vip/秒',
  `logon_state` enum('on','off') DEFAULT 'on' COMMENT '登录开关',
  `logon_off_msg` varchar(255) DEFAULT NULL COMMENT '登录关闭消息',
  `logon_mc_num` int(2) DEFAULT '1' COMMENT '登录设备数',
  `logon_mc_unbdeType` enum('vip','fen') DEFAULT 'fen' COMMENT '解绑扣除类型',
  `logon_mc_unbdeVal` int(10) DEFAULT '100' COMMENT '解绑扣除值',
  `invitee_award` enum('vip','fen') DEFAULT 'vip' COMMENT '受邀者奖励类型',
  `invitee_award_val` int(10) DEFAULT '43200' COMMENT '受邀者奖励：vip/秒',
  `inviter_award` enum('vip','fen') DEFAULT 'vip' COMMENT '邀请人奖励',
  `inviter_award_val` int(10) DEFAULT '86400' COMMENT '邀请人奖励：vip/秒',
  `diary_award` enum('vip','fen') DEFAULT 'fen' COMMENT '签到奖励类型',
  `diary_award_val` int(10) DEFAULT '100' COMMENT '签到奖励：vip/秒',
  `smtp_state` enum('on','off') DEFAULT 'off' COMMENT '发信状态',
  `smtp_host` varchar(128) DEFAULT 'smtp.qq.com' COMMENT '邮箱服务器',
  `smtp_user` varchar(128) DEFAULT NULL COMMENT '邮箱账户',
  `smtp_pass` varchar(128) DEFAULT NULL COMMENT '邮箱密码',
  `smtp_port` int(4) DEFAULT '465' COMMENT '邮箱端口',
  `sms_state` enum('on','off') DEFAULT 'off' COMMENT '短信状态',
  `sms_type` varchar(24) DEFAULT 'jie' COMMENT '短信类型',
  `sms_config` text COMMENT '短信配置',
  `vc_time` int(2) DEFAULT '10' COMMENT '验证码有效期',
  `vc_length` int(1) DEFAULT '4' COMMENT '验证码长度',
  `pay_ali_state` enum('on','off') DEFAULT 'off' COMMENT '支付宝状态',
  `pay_ali_type` varchar(24) DEFAULT 'jie' COMMENT '支付宝类型',
  `pay_ali_config` text COMMENT '支付宝配置',
  `pay_wx_state` enum('on','off') DEFAULT 'off' COMMENT '微信状态',
  `pay_wx_type` varchar(24) DEFAULT 'jie' COMMENT '微信类型',
  `pay_wx_config` text COMMENT '微信配置'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `u_app_extend`
--

CREATE TABLE `u_app_extend` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `var_key` varchar(64) NOT NULL,
  `var_val` text NOT NULL,
  `appid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `u_app_notice`
--

CREATE TABLE `u_app_notice` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `visit` int(11) DEFAULT '0',
  `appid` int(11) DEFAULT NULL,
  `time` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `u_app_ver`
--

CREATE TABLE `u_app_ver` (
  `id` int(11) NOT NULL,
  `ver_name` varchar(64) DEFAULT '默认版本',
  `ver_key` varchar(12) DEFAULT 'default',
  `ver_val` varchar(10) DEFAULT '1.0' COMMENT '版本号',
  `ver_state` enum('on','off') DEFAULT 'on',
  `ver_off_msg` varchar(255) DEFAULT '当前版本维护中',
  `ver_new_url` varchar(128) DEFAULT NULL,
  `ver_new_content` text,
  `mi_state` enum('on','off') DEFAULT 'off',
  `mi_type` enum('rc4','aes','rsa') DEFAULT 'rc4',
  `mi_sign` enum('on','off') DEFAULT 'off',
  `mi_time` int(4) DEFAULT '100',
  `mi_key` text,
  `appid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `u_fen_event`
--

CREATE TABLE `u_fen_event` (
  `id` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT '事件名称',
  `fen` int(10) DEFAULT '0' COMMENT '积分数',
  `vip` bigint(10) DEFAULT '0' COMMENT '会员数',
  `vip_free` enum('y','n') COLLATE utf8_unicode_ci DEFAULT 'n' COMMENT 'VIP免费',
  `appid` int(11) NOT NULL COMMENT 'APPID',
  `state` enum('on','off') COLLATE utf8_unicode_ci DEFAULT 'on' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `u_fen_order`
--

CREATE TABLE `u_fen_order` (
  `id` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT '积分事件名称',
  `mark` text COLLATE utf8_unicode_ci COMMENT '事件标记',
  `fen` int(10) DEFAULT '0' COMMENT '积分数',
  `vip` int(10) DEFAULT '0' COMMENT '会员数',
  `time` int(10) NOT NULL COMMENT '时间',
  `appid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `u_goods`
--

CREATE TABLE `u_goods` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `type` enum('vip','fen','agent') NOT NULL,
  `val` bigint(10) NOT NULL,
  `money` float(10,2) NOT NULL,
  `blurb` text,
  `state` enum('y','n') DEFAULT 'y',
  `appid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `u_kami`
--

CREATE TABLE `u_kami` (
  `id` int(11) NOT NULL,
  `kgid` int(11) NOT NULL COMMENT '卡密组ID',
  `type` enum('vip','fen','addmc') NOT NULL,
  `cardNo` varchar(32) NOT NULL,
  `val` bigint(10) NOT NULL COMMENT '卡密值',
  `note` varchar(64) DEFAULT NULL COMMENT '备注',
  `use_uid` int(10) DEFAULT NULL COMMENT '使用者ID',
  `use_time` int(10) DEFAULT NULL COMMENT '使用时间',
  `use_ip` varchar(15) DEFAULT NULL COMMENT '使用IP',
  `add_uid` int(11) DEFAULT NULL COMMENT '创建者ID',
  `add_time` int(10) NOT NULL COMMENT '创建时间',
  `add_ip` varchar(15) DEFAULT NULL COMMENT '创建IP',
  `out_state` enum('y','n') DEFAULT 'n' COMMENT '导出状态',
  `out_time` int(10) DEFAULT NULL COMMENT '导出时间',
  `state` enum('y','n') DEFAULT 'y' COMMENT '状态',
  `appid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `u_kami_group`
--

CREATE TABLE `u_kami_group` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL COMMENT '组名称',
  `val` bigint(10) NOT NULL COMMENT '卡密组面值',
  `type` enum('vip','fen','addmc') NOT NULL,
  `price` float(10,2) DEFAULT '0.00' COMMENT '卡密组定价',
  `appid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `u_logs`
--

CREATE TABLE `u_logs` (
  `id` int(11) NOT NULL,
  `ug` enum('adm','agent','user') NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `type` varchar(64) NOT NULL,
  `data` text,
  `state` enum('y','n') NOT NULL DEFAULT 'y',
  `time` int(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `appid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `u_message`
--

CREATE TABLE `u_message` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `content` text NOT NULL,
  `reply_id` int(11) DEFAULT NULL,
  `file` text,
  `time` int(10) NOT NULL,
  `last_time` int(10) DEFAULT NULL COMMENT '最后时间',
  `state` int(1) DEFAULT '0',
  `appid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `u_order`
--

CREATE TABLE `u_order` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `order_no` varchar(40) NOT NULL,
  `trade_no` varchar(60) DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `money` float(10,2) NOT NULL,
  `type` enum('vip','fen','agent') NOT NULL,
  `val` bigint(10) NOT NULL,
  `ptype` enum('ali','wx') NOT NULL,
  `add_time` int(10) NOT NULL,
  `end_time` int(10) DEFAULT NULL,
  `state` int(1) DEFAULT '0',
  `appid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `u_user`
--

CREATE TABLE `u_user` (
  `id` int(11) NOT NULL,
  `email` varchar(64) DEFAULT NULL COMMENT '邮箱账号',
  `phone` bigint(11) DEFAULT NULL COMMENT '手机号码',
  `acctno` varchar(18) DEFAULT NULL COMMENT '自定义账号',
  `nickname` varchar(128) DEFAULT '一位神秘的网友' COMMENT '昵称',
  `avatars` varchar(128) DEFAULT NULL COMMENT '头像',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `inviter_id` int(11) DEFAULT NULL COMMENT '邀请人ID',
  `vip` bigint(10) DEFAULT NULL COMMENT 'vip到期时间',
  `fen` int(10) DEFAULT '0',
  `reg_time` int(11) NOT NULL COMMENT '注册时间',
  `reg_ip` varchar(15) NOT NULL COMMENT '注册IP',
  `reg_udid` varchar(64) DEFAULT NULL COMMENT '注册机器码',
  `client_list` text,
  `client_max` bigint(10) DEFAULT '0',
  `ban` bigint(10) DEFAULT NULL COMMENT '禁用到期时间',
  `ban_msg` varchar(255) DEFAULT NULL COMMENT '禁用消息',
  `appid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `u_vcode`
--

CREATE TABLE `u_vcode` (
  `id` int(11) NOT NULL,
  `eorp` varchar(64) NOT NULL COMMENT '邮箱或手机号',
  `type` enum('reg','repwd','ubind','remc') NOT NULL COMMENT '注册、重置密码、绑定账号、换绑机器码',
  `code` int(6) NOT NULL COMMENT '验证码',
  `usable` enum('y','n') DEFAULT 'y' COMMENT '可用',
  `time` int(10) NOT NULL COMMENT '时间',
  `ip` varchar(15) DEFAULT NULL,
  `appid` int(11) NOT NULL COMMENT 'APPID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转储表的索引
--

--
-- 表的索引 `u_agent`
--
ALTER TABLE `u_agent`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_agent_cash`
--
ALTER TABLE `u_agent_cash`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_agent_group`
--
ALTER TABLE `u_agent_group`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_app`
--
ALTER TABLE `u_app`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_app_extend`
--
ALTER TABLE `u_app_extend`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_app_notice`
--
ALTER TABLE `u_app_notice`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_app_ver`
--
ALTER TABLE `u_app_ver`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_fen_event`
--
ALTER TABLE `u_fen_event`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_fen_order`
--
ALTER TABLE `u_fen_order`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_goods`
--
ALTER TABLE `u_goods`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_kami`
--
ALTER TABLE `u_kami`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_kami_group`
--
ALTER TABLE `u_kami_group`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_logs`
--
ALTER TABLE `u_logs`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_message`
--
ALTER TABLE `u_message`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_order`
--
ALTER TABLE `u_order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_no` (`order_no`);

--
-- 表的索引 `u_user`
--
ALTER TABLE `u_user`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `u_vcode`
--
ALTER TABLE `u_vcode`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `u_agent`
--
ALTER TABLE `u_agent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_agent_cash`
--
ALTER TABLE `u_agent_cash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_agent_group`
--
ALTER TABLE `u_agent_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_app`
--
ALTER TABLE `u_app`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- 使用表AUTO_INCREMENT `u_app_extend`
--
ALTER TABLE `u_app_extend`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_app_notice`
--
ALTER TABLE `u_app_notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_app_ver`
--
ALTER TABLE `u_app_ver`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_fen_event`
--
ALTER TABLE `u_fen_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_fen_order`
--
ALTER TABLE `u_fen_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_goods`
--
ALTER TABLE `u_goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_kami`
--
ALTER TABLE `u_kami`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_kami_group`
--
ALTER TABLE `u_kami_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_logs`
--
ALTER TABLE `u_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_message`
--
ALTER TABLE `u_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_order`
--
ALTER TABLE `u_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_user`
--
ALTER TABLE `u_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `u_vcode`
--
ALTER TABLE `u_vcode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
