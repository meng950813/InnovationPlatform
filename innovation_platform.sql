-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-08-20 10:10:37
-- 服务器版本： 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `innovation_platform`
--

-- --------------------------------------------------------

--
-- 表的结构 `ip_award`
--

CREATE TABLE `ip_award` (
  `id` int(11) NOT NULL COMMENT '主键，自增',
  `award_owner` int(11) DEFAULT NULL COMMENT '获奖人/团队id,不是外键',
  `isteam` tinyint(1) DEFAULT '0' COMMENT '是否是团队，1表示是团队奖项，默认为0，个人奖项',
  `research_id` int(11) DEFAULT NULL COMMENT '获奖研究的id，外键',
  `award_title` varchar(20) DEFAULT NULL COMMENT '奖项名称',
  `get_time` date DEFAULT NULL COMMENT '获奖时间',
  `sort` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '排序时间戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ip_award`
--

INSERT INTO `ip_award` (`id`, `award_owner`, `isteam`, `research_id`, `award_title`, `get_time`, `sort`) VALUES
(1, 2, 1, 1, '奖项名称', '2015-08-11', '2015-08-12 16:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `ip_city`
--

CREATE TABLE `ip_city` (
  `id` int(11) NOT NULL COMMENT '城市id',
  `cityname` varchar(10) NOT NULL COMMENT '城市名'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='城市表，用于确定企业城市';

--
-- 转存表中的数据 `ip_city`
--

INSERT INTO `ip_city` (`id`, `cityname`) VALUES
(1, '南京'),
(2, '苏州'),
(3, '上海'),
(4, '深圳');

-- --------------------------------------------------------

--
-- 表的结构 `ip_company_user`
--

CREATE TABLE `ip_company_user` (
  `uid` int(11) NOT NULL COMMENT '外键，对应login表中用户id',
  `city` int(11) NOT NULL COMMENT '企业所在城市，外键',
  `area` varchar(50) NOT NULL COMMENT '企业地址',
  `research` set('电子信息','机械设计与制造','新能源与环保','生物技术与医药','新材料','现代农业','其他') NOT NULL COMMENT '主营方向',
  `company_type` varchar(50) DEFAULT NULL COMMENT '企业标签：例如 创业公司、民营公司',
  `info` text NOT NULL COMMENT '企业简介',
  `linkman` varchar(8) NOT NULL COMMENT '联系人姓名',
  `jobname` varchar(10) DEFAULT NULL COMMENT '联系人职务',
  `link_phone` varchar(13) DEFAULT NULL COMMENT '联系人电话',
  `website` varchar(20) NOT NULL COMMENT '企业官网',
  `linkEmail` varchar(20) DEFAULT NULL COMMENT '联系人邮箱'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='企业用户信息表';

--
-- 转存表中的数据 `ip_company_user`
--

INSERT INTO `ip_company_user` (`uid`, `city`, `area`, `research`, `company_type`, `info`, `linkman`, `jobname`, `link_phone`, `website`, `linkEmail`) VALUES
(5, 1, '江南路183号', '电子信息,机械设计与制造,生物技术与医药', '创业公司、互联网公司', '企业简介', '联系人姓名', '项目经理', '13813813813', 'www.baidu.com', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `ip_cooperation`
--

CREATE TABLE `ip_cooperation` (
  `id` int(4) NOT NULL,
  `name` varchar(30) NOT NULL COMMENT '合作单位名称',
  `website` varchar(30) NOT NULL COMMENT '合作单位官网',
  `sort` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '排序字段',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '标识字段，1->可用；0->不可用',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '合作对象类型：0-> 学校；1-> 协会；2->公司',
  `logo` varchar(50) NOT NULL COMMENT '图标',
  `click` int(11) NOT NULL DEFAULT '11' COMMENT '点击量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='协同合作单位';

--
-- 转存表中的数据 `ip_cooperation`
--

INSERT INTO `ip_cooperation` (`id`, `name`, `website`, `sort`, `status`, `type`, `logo`, `click`) VALUES
(1, '江苏科技大学', 'www.baidu.con', '2015-08-18 11:57:42', 1, 0, '', 21),
(2, '江苏科技大学张家港校区', 'www.baidu.con', '2015-08-18 11:57:42', 1, 0, '', 19),
(3, '江苏科技大学苏州理工学院', 'www.baidu.con', '2015-08-18 11:57:42', 1, 0, '', 23);

-- --------------------------------------------------------

--
-- 表的结构 `ip_demand`
--

CREATE TABLE `ip_demand` (
  `demand_id` int(11) NOT NULL COMMENT '需求id',
  `title` varchar(50) NOT NULL COMMENT '需求名称',
  `demand_type` set('电子信息','机械设计与制造','新能源与环保','生物技术与医药','现代农业','新材料','其他') NOT NULL DEFAULT '电子信息' COMMENT '需求所属范围',
  `demand_com` int(11) NOT NULL COMMENT '外键，发布需求的企业id',
  `demand_logo` varchar(40) NOT NULL DEFAULT 'Public/images/demand_default.png',
  `total_info` text NOT NULL COMMENT '综合介绍',
  `tech_target` text COMMENT '技术要点',
  `other_info` text COMMENT '其他要求',
  `publish_time` date NOT NULL COMMENT '需求发布时间',
  `end_time` varchar(10) DEFAULT NULL COMMENT '需求截至时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '需求是否可用，默认1，可用；0表示过期不可用',
  `sort` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '当前时间戳，用于排序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='需求发布';

--
-- 转存表中的数据 `ip_demand`
--

INSERT INTO `ip_demand` (`demand_id`, `title`, `demand_type`, `demand_com`, `demand_logo`, `total_info`, `tech_target`, `other_info`, `publish_time`, `end_time`, `status`, `sort`) VALUES
(1, '需求名称', '机械设计与制造,新能源与环保', 5, 'Public/images/demand_default.png', '需求详情', NULL, NULL, '2016-07-18', NULL, 1, '2016-07-18 14:05:31'),
(2, '需求名称', '电子信息,机械设计与制造', 5, 'Public/images/demand_default.png', '需求详情', NULL, NULL, '2016-07-18', NULL, 1, '2016-07-18 14:05:36'),
(3, '测试需求名称', '机械设计与制造,新能源与环保', 5, 'Public/images/demand_default.png', '需求详情', NULL, NULL, '2016-07-30', NULL, 1, '2016-07-19 14:05:31'),
(10, '测试需求发布2', '机械设计与制造,现代农业', 5, 'Public/images/demand_default.png', 'test', NULL, NULL, '2016-07-30', NULL, 1, '2016-07-30 10:58:48'),
(13, 'test', '电子信息', 5, 'Public/images/demand_default.png', '', NULL, NULL, '0000-00-00', NULL, 1, '2016-08-03 15:28:32'),
(14, '长需求名长需求名长需求名长需求名长需求名长需求名长需求名长需求名长需求名长需求名长需求名长需求名', '电子信息', 5, 'Public/images/demand_default.png', '', '', '', '2016-08-03', '', 1, '2016-08-03 15:28:32'),
(23, 'test add demand', '电子信息', 5, 'Public/images/demand_default.png', 'test add demand \r\ntime:2016/8/10 20:21\r\nby cm', '', '', '2015-08-10', '', 1, '2015-08-10 12:22:18');

-- --------------------------------------------------------

--
-- 表的结构 `ip_edu_train`
--

CREATE TABLE `ip_edu_train` (
  `edu_id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL COMMENT '教育培训标题',
  `content` text NOT NULL COMMENT '培训内容',
  `publish_time` date NOT NULL COMMENT '发布时间',
  `sort` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '排序字段',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0->学历教育；1->资格教育；2>技能培训',
  `document` varchar(50) DEFAULT NULL COMMENT '上传文件路径',
  `click` int(11) NOT NULL DEFAULT '10' COMMENT '点击量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='教育培训';

--
-- 转存表中的数据 `ip_edu_train`
--

INSERT INTO `ip_edu_train` (`edu_id`, `title`, `content`, `publish_time`, `sort`, `type`, `document`, `click`) VALUES
(1, '夜校招生', '夜校招生javascript:void(0);\r\njavascript:void(0);夜校招生\r\n夜校招生\r\n夜校招生', '2015-08-26', '2015-08-19 01:19:22', 0, NULL, 10);

-- --------------------------------------------------------

--
-- 表的结构 `ip_expert_team`
--

CREATE TABLE `ip_expert_team` (
  `teamid` int(11) NOT NULL COMMENT '团队id',
  `teamname` varchar(20) NOT NULL COMMENT '团队名称，唯一',
  `team_logo` varchar(50) NOT NULL COMMENT '团队logo，存储相对路径',
  `teamleader` int(11) NOT NULL COMMENT '团队领导人id，外键',
  `team_research` set('电子信息','机械设计与制造','新能源与环保','生物技术与医药','新材料','现代农业','其他') NOT NULL COMMENT '团队研究方向',
  `research_content` text NOT NULL COMMENT '具体研究内容',
  `teaminfo` text NOT NULL COMMENT '团队简介',
  `setup_time` date NOT NULL COMMENT '团队成立时间',
  `focusNum` int(11) NOT NULL DEFAULT '0' COMMENT '关注度',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '标志团队是否可用，默认1，可用，0->不可用',
  `sort` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '时间戳，用于排序',
  `workplace` varchar(30) NOT NULL COMMENT '工作地点'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='专家团队';

--
-- 转存表中的数据 `ip_expert_team`
--

INSERT INTO `ip_expert_team` (`teamid`, `teamname`, `team_logo`, `teamleader`, `team_research`, `research_content`, `teaminfo`, `setup_time`, `focusNum`, `status`, `sort`, `workplace`) VALUES
(2, '团队名称，具有唯一性', 'Uploads/team_logo/2016-08-14/57b07aaace399.png', 2, '机械设计与制造,新材料', '具体研究内容', '团队简介\r\n有换行\r\n有换行\r\n', '2015-01-13', 0, 1, '2015-08-10 07:47:56', '工作地点'),
(3, '团队名称', 'Public/images/demand_default.png', 2, '电子信息,新能源与环保', '', '团队简介', '2015-01-13', 0, 1, '2015-08-10 07:47:56', 'xxx大学');

-- --------------------------------------------------------

--
-- 表的结构 `ip_expert_user`
--

CREATE TABLE `ip_expert_user` (
  `uid` int(11) NOT NULL COMMENT '用户id',
  `gender` tinyint(1) DEFAULT '1' COMMENT '性别，1->男; 2->女',
  `birthday` date DEFAULT NULL COMMENT '出生年月',
  `identify` varchar(18) NOT NULL COMMENT '身份证号',
  `work_college` varchar(30) NOT NULL COMMENT '工作院校',
  `jobname` varchar(30) DEFAULT NULL COMMENT '职务',
  `level` enum('教授','副教授','研究员','副研究员','高级工程师','其他类正高级','其他类副高级','中级','初级及以下','讲师') NOT NULL COMMENT '职称，枚举型',
  `degree` enum('博士后','博士','硕士','学士','其他') NOT NULL DEFAULT '其他' COMMENT '学位',
  `telphone` varchar(13) NOT NULL COMMENT '办公室电话',
  `political_status` enum('其他','无党派人士','其他党派人士','预备党员','党员') DEFAULT '无党派人士' COMMENT '政治面貌',
  `research` set('电子信息','机械设计与制造','新能源与环保','生物技术与医药','新材料','现代农业','其他') NOT NULL COMMENT '研究方向',
  `research_content` text NOT NULL COMMENT '具体研究内容',
  `info` text NOT NULL COMMENT '个人简介',
  `focusNum` int(11) NOT NULL DEFAULT '0' COMMENT '关注度',
  `setup_time` date NOT NULL COMMENT '注册时间',
  `sort` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '排序字段'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='专家用户信息表';

--
-- 转存表中的数据 `ip_expert_user`
--

INSERT INTO `ip_expert_user` (`uid`, `gender`, `birthday`, `identify`, `work_college`, `jobname`, `level`, `degree`, `telphone`, `political_status`, `research`, `research_content`, `info`, `focusNum`, `setup_time`, `sort`) VALUES
(2, 0, '2016-08-07', '32132131231233223', '在职院校', '职务', '初级及以下', '硕士', '0516-12345678', '无党派人士', '新能源与环保,新材料', '具体研究什么我也不知道\r\n可以换行\r\n换行', '个人简介', 0, '2015-08-02', '2015-08-13 03:23:20'),
(4, 1, '1995-08-13', '123456789789456123', '江苏科技大学', '院长', '教授', '博士', '0516-12345678', '党员', '电子信息,新材料', 'first test  2016/8/9 20:35 by cm', '个人简介个人简介：\n可以换行个人简介：\n可以换行', 0, '2015-08-03', '2015-08-13 03:23:23'),
(6, 1, '1980-03-25', '', '江苏科技大学', '电信院院长', '教授', '博士后', '0516-12345678', '无党派人士', '机械设计与制造', '', '个人简介：\r\n可以换行', 0, '2015-08-12', '2015-08-13 03:23:24'),
(7, 1, '1995-08-13', '123456789789456123', '江苏科技大学', '院长', '教授', '博士', '0516-12345678', '党员', '新材料', 'first test  2016/8/9 20:35 by cm', '个人简介个人简介个人简介：\n可以换行个人简介：\n可以换行', 0, '2015-08-05', '2015-08-13 03:23:25');

-- --------------------------------------------------------

--
-- 表的结构 `ip_linked`
--

CREATE TABLE `ip_linked` (
  `require_id` int(11) NOT NULL COMMENT '请求连接用户id',
  `request_id` int(11) NOT NULL COMMENT '接收请求用户id',
  `link_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '联系类型：0->个人联系个人；1->个人联系团队',
  `teamid` int(11) DEFAULT NULL COMMENT '团队id，不是外键，默认null'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='好友表';

--
-- 转存表中的数据 `ip_linked`
--

INSERT INTO `ip_linked` (`require_id`, `request_id`, `link_type`, `teamid`) VALUES
(1, 2, 1, 2),
(1, 4, 1, 2),
(1, 5, 0, NULL),
(4, 2, 1, 2),
(5, 2, 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `ip_login`
--

CREATE TABLE `ip_login` (
  `id` int(11) NOT NULL COMMENT '用户id',
  `username` varchar(16) NOT NULL COMMENT '用户名，唯一',
  `realname` varchar(16) NOT NULL COMMENT '真实姓名/企业名',
  `logo` varchar(50) NOT NULL DEFAULT 'Uploads/user_logo/default_icon.jpg' COMMENT '头像，存储相对路径',
  `password` varchar(50) NOT NULL,
  `type` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '用户类型，0：普通用户；1:专家；2:企业',
  `mobile_phone` varchar(14) NOT NULL COMMENT '手机号',
  `email` varchar(20) NOT NULL COMMENT '邮箱'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='注册表';

--
-- 转存表中的数据 `ip_login`
--

INSERT INTO `ip_login` (`id`, `username`, `realname`, `logo`, `password`, `type`, `mobile_phone`, `email`) VALUES
(1, '测试用户名', 'cm', 'Uploads/user_logo/default_icon.jpg', 'c20ad4d76fe97759aa27a0c99bff6710', '0', '', ''),
(2, '测试用户名1', 'cm', 'Uploads/user_logo/default_icon.jpg', 'd41d8cd98f00b204e9800998ecf8427e', '1', '13813613133', 'email@123.com'),
(4, '测试用户名2', 'cm', 'Uploads/user_logo/default_icon.jpg', 'd41d8cd98f00b204e9800998ecf8427e', '1', '13813613133', 'email@123.com'),
(5, '测试用户名3', 'XX公司', 'Uploads/user_logo/2015-08-17/55d1c73ad6c86.jpg', 'd41d8cd98f00b204e9800998ecf8427e', '2', '13813613133', 'eail@345.com'),
(6, '测试注册专家用户', 'cm', 'Uploads/user_logo/2016-08-03/57a20a9882606.jpg', '202cb962ac59075b964b07152d234b70', '1', '12312312312', '123@qq.com'),
(7, '测试新专家注册', 'cm', 'Uploads/user_logo/default_icon.jpg', 'c4ca4238a0b923820dcc509a6f75849b', '1', '', '1@we.com'),
(8, 'cm', 'cm', 'Uploads/user_logo/2015-08-17/55d1c84dd38ae.jpg', '202cb962ac59075b964b07152d234b70', '1', '12321321321', '1@qq.com');

-- --------------------------------------------------------

--
-- 表的结构 `ip_news`
--

CREATE TABLE `ip_news` (
  `news_id` int(11) NOT NULL,
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0->政策法规；1->市场动态；2->业内资讯',
  `title` varchar(40) NOT NULL COMMENT '资讯标题',
  `content` text NOT NULL COMMENT '资讯内容',
  `click` int(11) NOT NULL DEFAULT '11' COMMENT '点击量',
  `publish_time` date NOT NULL COMMENT '发布时间',
  `sort` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '排序字段',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0表示数据不可用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='最新资讯表';

--
-- 转存表中的数据 `ip_news`
--

INSERT INTO `ip_news` (`news_id`, `type`, `title`, `content`, `click`, `publish_time`, `sort`, `status`) VALUES
(1, 1, '新闻发布标题', '新闻发布内容\r\n内容页\r\n可以换行', 33, '2015-08-18', '2015-08-18 02:05:58', 1),
(2, 0, '政策法规标题', '政策法规内容\r\n也可以换行', 13, '2015-08-18', '2015-08-16 16:00:00', 1),
(3, 2, '业内资讯发布标题', '业内资讯、内容\n内容页\n可以换行', 16, '2015-08-18', '2015-08-18 02:06:30', 1),
(4, 0, '政策法规标题', '政策法规内容\r\n也可以换行', 14, '2015-08-18', '2015-08-16 16:00:00', 1),
(5, 0, '测试发布', '<p>测试内容。</p><p><span style="line-height: 1em; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;加入HTML样式</span></p><p><span style="line-height: 1em; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;包括换行</span></p>', 14, '2015-08-20', '2015-08-20 05:33:41', 1);

-- --------------------------------------------------------

--
-- 表的结构 `ip_require_link`
--

CREATE TABLE `ip_require_link` (
  `id` int(11) NOT NULL COMMENT '主键',
  `require_id` int(11) DEFAULT NULL COMMENT '请求建立连接用户id，外键',
  `request_id` int(11) DEFAULT NULL COMMENT '接受请求的用户id，外键',
  `link_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '标志本条信息类型，默认0：个人-个人；1->个人 - 团队；2->团队 邀请  个人',
  `teamid` int(11) DEFAULT NULL COMMENT '团队id,不是外键',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '标识请求信息是否已被处理，默认1，表示未处理；0表示已处理',
  `title` varchar(50) NOT NULL COMMENT '连接标题',
  `content` text NOT NULL COMMENT '内容',
  `remark` text NOT NULL COMMENT '备注',
  `sort` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '排序字段'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='请求建立连接信息表';

--
-- 转存表中的数据 `ip_require_link`
--

INSERT INTO `ip_require_link` (`id`, `require_id`, `request_id`, `link_type`, `teamid`, `status`, `title`, `content`, `remark`, `sort`) VALUES
(1, 1, 2, 0, NULL, 0, '请求连接的标题', '具体内容', '', '2015-01-02 11:59:41'),
(2, 1, 5, 0, NULL, 0, '测试需求名称', '测试连接', 'first times , 2016-8-9 20:13  by chenmeng', '2015-01-02 12:17:37'),
(16, 5, 1, 0, NULL, 1, '请求联系', '测试对接科技成果', '', '2015-08-15 01:41:26'),
(17, 5, 2, 0, NULL, 0, '请求联系2', '', '', '2015-08-15 01:43:12'),
(18, 5, 1, 0, NULL, 1, '连接', '是打发', '', '2015-08-15 01:45:11'),
(19, 5, 7, 0, NULL, 1, '请求连接专家', '对接说明', '备注\r\n', '2015-08-15 01:58:59'),
(24, 5, 2, 0, NULL, 0, '请求连接科技成果', '', '', '2015-08-15 02:03:34'),
(25, 5, 5, 0, NULL, 0, '请求连接企业需求', '', '', '2015-08-15 02:05:56');

-- --------------------------------------------------------

--
-- 表的结构 `ip_research_result`
--

CREATE TABLE `ip_research_result` (
  `result_id` int(11) NOT NULL COMMENT '成果id',
  `result_name` varchar(50) NOT NULL COMMENT '成果名称',
  `research_type` set('电子信息','机械设计与制造','新能源与环保','生物技术与医药','新材料','现代农业','其他') NOT NULL COMMENT '研究成果的类别',
  `research_level` enum('其他','个人项目','院级项目','校级项目','市级项目','省级项目','国家级项目') DEFAULT '其他' COMMENT '研究课题级别',
  `result_picture` text NOT NULL COMMENT '研究成果图片，存储相对路径',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否可用，默认1，可用；0表示不可用',
  `owner_id` int(11) NOT NULL COMMENT '成果拥有人id/teamleader id',
  `isteam` tinyint(4) NOT NULL DEFAULT '0' COMMENT '个人/团队：个人成果->0；团队成果->1',
  `total_info` text NOT NULL COMMENT '综合介绍',
  `tech_target` text NOT NULL COMMENT '技术要点',
  `publish_time` date NOT NULL COMMENT '发布时间',
  `end_time` varchar(4) DEFAULT NULL COMMENT '计划完成年份',
  `sort` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '当前时间戳，用于排序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='研究成果';

--
-- 转存表中的数据 `ip_research_result`
--

INSERT INTO `ip_research_result` (`result_id`, `result_name`, `research_type`, `research_level`, `result_picture`, `status`, `owner_id`, `isteam`, `total_info`, `tech_target`, `publish_time`, `end_time`, `sort`) VALUES
(1, '一项关于编程语言的研究', '电子信息', '其他', '', 1, 1, 0, '研究简介', '此处是上传文档的路径', '2016-07-19', NULL, '2016-07-19 09:43:40'),
(2, '一项关于编程语言的研究', '电子信息', '其他', 'Uploads/research_picture/2016-08-14/57b07a805bc3a.png', 1, 2, 1, '研究简介', '此处是上传文档的路径\r\n加入还行', '2016-07-19', '2016', '2016-07-19 09:43:40'),
(3, '一项关于计算机散热的研究', '电子信息,机械设计与制造', '其他', '此处是成果图片的路径', 1, 2, 1, '研究简介', '此处是上传文档的路径', '2016-07-19', NULL, '2016-07-19 09:43:40'),
(4, '测试成果发布', '机械设计与制造,新材料', '市级项目', '', 1, 5, 0, '项目介绍\r\n1：第1点\r\n2：第2点\r\n3：第3点', '', '2015-08-12', '2016', '2015-08-15 01:12:17'),
(5, '测试成果发布2', '机械设计与制造,生物技术与医药', '市级项目', '', 1, 5, 0, '', '', '2015-08-15', '2016', '2015-08-15 01:16:41');

-- --------------------------------------------------------

--
-- 表的结构 `ip_team_member`
--

CREATE TABLE `ip_team_member` (
  `teamid` int(11) NOT NULL COMMENT '团队id，外键',
  `uid` int(11) NOT NULL COMMENT '成员id，外键',
  `member_name` varchar(16) NOT NULL COMMENT '成员正真实姓名'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='团队成员表';

--
-- 转存表中的数据 `ip_team_member`
--

INSERT INTO `ip_team_member` (`teamid`, `uid`, `member_name`) VALUES
(2, 2, 'cm'),
(2, 4, 'cm'),
(3, 2, 'cm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ip_award`
--
ALTER TABLE `ip_award`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cm_researach_award` (`research_id`);

--
-- Indexes for table `ip_city`
--
ALTER TABLE `ip_city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ip_company_user`
--
ALTER TABLE `ip_company_user`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `cm_city` (`city`);

--
-- Indexes for table `ip_cooperation`
--
ALTER TABLE `ip_cooperation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ip_demand`
--
ALTER TABLE `ip_demand`
  ADD PRIMARY KEY (`demand_id`),
  ADD KEY `cm_demand_com` (`demand_com`);

--
-- Indexes for table `ip_edu_train`
--
ALTER TABLE `ip_edu_train`
  ADD PRIMARY KEY (`edu_id`);

--
-- Indexes for table `ip_expert_team`
--
ALTER TABLE `ip_expert_team`
  ADD PRIMARY KEY (`teamid`),
  ADD UNIQUE KEY `teamname` (`teamname`),
  ADD KEY `cm_teamleader` (`teamleader`);

--
-- Indexes for table `ip_expert_user`
--
ALTER TABLE `ip_expert_user`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `cm_job_level` (`level`);

--
-- Indexes for table `ip_linked`
--
ALTER TABLE `ip_linked`
  ADD PRIMARY KEY (`require_id`,`request_id`),
  ADD KEY `cm_request` (`request_id`);

--
-- Indexes for table `ip_login`
--
ALTER TABLE `ip_login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `ip_news`
--
ALTER TABLE `ip_news`
  ADD PRIMARY KEY (`news_id`);

--
-- Indexes for table `ip_require_link`
--
ALTER TABLE `ip_require_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cm_require_id` (`require_id`),
  ADD KEY `cm_request_id` (`request_id`);

--
-- Indexes for table `ip_research_result`
--
ALTER TABLE `ip_research_result`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `cm_research_owner` (`owner_id`);

--
-- Indexes for table `ip_team_member`
--
ALTER TABLE `ip_team_member`
  ADD PRIMARY KEY (`teamid`,`uid`),
  ADD KEY `cm_userid` (`uid`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ip_award`
--
ALTER TABLE `ip_award`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键，自增', AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `ip_city`
--
ALTER TABLE `ip_city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '城市id', AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `ip_cooperation`
--
ALTER TABLE `ip_cooperation`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `ip_demand`
--
ALTER TABLE `ip_demand`
  MODIFY `demand_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '需求id', AUTO_INCREMENT=24;
--
-- 使用表AUTO_INCREMENT `ip_edu_train`
--
ALTER TABLE `ip_edu_train`
  MODIFY `edu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `ip_expert_team`
--
ALTER TABLE `ip_expert_team`
  MODIFY `teamid` int(11) NOT NULL AUTO_INCREMENT COMMENT '团队id', AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `ip_login`
--
ALTER TABLE `ip_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id', AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `ip_news`
--
ALTER TABLE `ip_news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `ip_require_link`
--
ALTER TABLE `ip_require_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=26;
--
-- 使用表AUTO_INCREMENT `ip_research_result`
--
ALTER TABLE `ip_research_result`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '成果id', AUTO_INCREMENT=6;
--
-- 限制导出的表
--

--
-- 限制表 `ip_award`
--
ALTER TABLE `ip_award`
  ADD CONSTRAINT `cm_researach_award` FOREIGN KEY (`research_id`) REFERENCES `ip_research_result` (`result_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `ip_company_user`
--
ALTER TABLE `ip_company_user`
  ADD CONSTRAINT `cm_city` FOREIGN KEY (`city`) REFERENCES `ip_city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cm_login_company` FOREIGN KEY (`uid`) REFERENCES `ip_login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `ip_demand`
--
ALTER TABLE `ip_demand`
  ADD CONSTRAINT `cm_demand_com` FOREIGN KEY (`demand_com`) REFERENCES `ip_login` (`id`);

--
-- 限制表 `ip_expert_team`
--
ALTER TABLE `ip_expert_team`
  ADD CONSTRAINT `cm_teamleader` FOREIGN KEY (`teamleader`) REFERENCES `ip_login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `ip_expert_user`
--
ALTER TABLE `ip_expert_user`
  ADD CONSTRAINT `cm_login_expert` FOREIGN KEY (`uid`) REFERENCES `ip_login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `ip_linked`
--
ALTER TABLE `ip_linked`
  ADD CONSTRAINT `cm_request` FOREIGN KEY (`request_id`) REFERENCES `ip_login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cm_require` FOREIGN KEY (`require_id`) REFERENCES `ip_login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `ip_require_link`
--
ALTER TABLE `ip_require_link`
  ADD CONSTRAINT `cm_request_id` FOREIGN KEY (`request_id`) REFERENCES `ip_login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cm_require_id` FOREIGN KEY (`require_id`) REFERENCES `ip_login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `ip_team_member`
--
ALTER TABLE `ip_team_member`
  ADD CONSTRAINT `cm_teamid` FOREIGN KEY (`teamid`) REFERENCES `ip_expert_team` (`teamid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cm_userid` FOREIGN KEY (`uid`) REFERENCES `ip_login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
