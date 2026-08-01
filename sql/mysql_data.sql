-- MySQL dump 10.13  Distrib 5.7.44, for Linux (x86_64)
--
-- Host: localhost    Database: 
-- ------------------------------------------------------
-- Server version	5.7.44-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 隐藏 / 1 显示',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'AI模型大放价','uploads/banners/6a6a0b5986d496.20686293.png','',0,1,'2026-07-29 22:16:57'),(2,'国内聚合','uploads/banners/6a6a0bf5e58a49.80478018.png','',1,1,'2026-07-29 22:19:33');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cat_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (4,'AI工具'),(2,'AI服务'),(1,'AI模型'),(13,'AI硬件'),(3,'AI算力'),(19,'图书文具'),(16,'家居生活'),(14,'数码电子'),(15,'服饰鞋包'),(17,'美妆个护'),(18,'食品生鲜');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `merchants`
--

DROP TABLE IF EXISTS `merchants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `merchants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `shop_name` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL DEFAULT '',
  `description` text,
  `audit_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 待审核 / 1 通过 / 2 拒绝',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6021 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `merchants`
--

LOCK TABLES `merchants` WRITE;
/*!40000 ALTER TABLE `merchants` DISABLE KEYS */;
INSERT INTO `merchants` VALUES (1,2,'AI模型市场','17596517381','专注AI大模型api',1,'2026-07-29 21:22:41'),(6001,2001,'智创 AI 科技','400-200-1001','专注 AI 硬件与智能终端，让科技融入日常。',1,'2026-07-29 23:14:34'),(6002,2002,'译界通','400-200-1002','多语种 AI 实时翻译设备，沟通无国界。',1,'2026-07-29 23:14:34'),(6003,2003,'声临科技','400-200-1003','AI 音频与语音交互硬件专家。',1,'2026-07-29 23:14:34'),(6004,2004,'极客机械坊','400-200-1004','桌面机械臂与开发套件，极客玩具集合店。',1,'2026-07-29 23:14:34'),(6005,2005,'镜界健身','400-200-1005','AI 智能健身镜与家庭运动方案。',1,'2026-07-29 23:14:34'),(6006,2006,'灵感画布','400-200-1006','AI 绘画 / 写作 / 设计会员一站式服务。',1,'2026-07-29 23:14:34'),(6007,2007,'码字星球','400-200-1007','AI 写作助手与内容生产力工具。',1,'2026-07-29 23:14:34'),(6008,2008,'安防智能','400-200-1008','AI 视觉安防与家庭摄像头。',1,'2026-07-29 23:14:34'),(6009,2009,'指尖声学','400-200-1009','AI 语音外设与智能鼠标。',1,'2026-07-29 23:14:34'),(6010,2010,'静界声学','400-200-1010','AI 降噪耳机与声学设备。',1,'2026-07-29 23:14:34'),(6011,2011,'净享家居','400-200-1011','AI 清洁家电，解放双手。',1,'2026-07-29 23:14:34'),(6012,2012,'明眸照明','400-200-1012','AI 护眼与智能照明。',1,'2026-07-29 23:14:34'),(6013,2013,'食光智能','400-200-1013','AI 厨房电器与智能烹饪。',1,'2026-07-29 23:14:34'),(6014,2014,'帘动智能','400-200-1014','AI 全屋智能与遮阳控制。',1,'2026-07-29 23:14:34'),(6015,2015,'悦肤实验室','400-200-1015','AI 肌肤检测与科学个护。',1,'2026-07-29 23:14:34'),(6016,2016,'皓齿智能','400-200-1016','AI 口腔护理仪器。',1,'2026-07-29 23:14:34'),(6017,2017,'暖冬智衣','400-200-1017','AI 温控服饰与可穿戴。',1,'2026-07-29 23:14:34'),(6018,2018,'步跃科技','400-200-1018','AI 智能运动鞋与穿戴设备。',1,'2026-07-29 23:14:34'),(6019,2019,'码上学院','400-200-1019','面向开发者的 AI 课程与训练营。',1,'2026-07-29 23:14:34'),(6020,2020,'膳养 AI','400-200-1020','AI 定制营养与健康管理服务。',1,'2026-07-29 23:14:34');
/*!40000 ALTER TABLE `merchants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `title` varchar(120) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `qty` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(32) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 待支付 / 1 已支付 / 2 已取消',
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_order_no` (`order_no`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(120) NOT NULL,
  `content` text NOT NULL,
  `topic` varchar(50) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 正常 / 1 已删除',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,2,'有没有好的gpt img2的api渠道','有没有好的gpt img2的api渠道，很多都用不了多久','求助',NULL,0,'2026-07-29 21:21:51'),(82,2001,'大家平时用哪款 AI 写作工具？求推荐','最近要写大量周报和方案，试了几个 AI 写作助手，各有千秋。你们最常用哪个？','闲聊',NULL,0,'2026-07-09 23:14:34'),(83,2002,'Midjourney 提示词怎么写出电影感？','同一主体参数差一点出图差别很大，有没有通用的电影感提示词结构？','求助',NULL,0,'2026-07-10 23:14:34'),(84,2003,'分享：我用 AI 把网店客服效率翻了 3 倍','接入智能客服 + 自动回复后，重复咨询基本不用人工，整理了一份落地清单。','经验分享',NULL,0,'2026-07-11 23:14:34'),(85,2004,'出一台九成新 AI 翻译机，低价转让','搬家清理，翻译机只用过两次，配件齐全，有意私聊。','闲聊',NULL,0,'2026-07-12 23:14:34'),(86,2005,'【公告】AI 商城社区新版上线，新增轮播与购物车','平台完成 AI 主题升级，首页轮播、数据看板、购物车与订单支付已上线，欢迎体验。','公告',NULL,0,'2026-07-13 23:14:34'),(87,2006,'AI 绘画会员季卡有人拼车吗？','季卡一个人用不完，想找几个人平摊，有意向的举手。','闲聊',NULL,0,'2026-07-14 23:14:34'),(88,2007,'本地部署大模型需要什么显卡配置？','想自己跑 7B/13B 模型，预算有限，求一份性价比配置单。','求助',NULL,0,'2026-07-15 23:14:34'),(89,2008,'经验分享：Prompt 工程的 5 个避坑点','1）指令具体；2）给示例；3）限定格式；4）分步骤；5）多轮纠偏。','经验分享',NULL,0,'2026-07-16 23:14:34'),(90,2009,'转让 AI 编程私教课程名额（官方可查）','报了课但时间冲突，名额可官方转移，价格好商量。','闲聊',NULL,0,'2026-07-17 23:14:34'),(91,2010,'用 AI 扫地机器人后，我家真的干净了','建图 + 分区清扫太香了，分享不同户型的设置技巧。','经验分享',NULL,0,'2026-07-18 23:14:34'),(92,2011,'灵感画布上新：AI 设计模板包','本周上线一批电商主图与社媒封面模板，反馈好持续更新。','公告',NULL,0,'2026-07-19 23:14:34'),(93,2012,'你们觉得 AI 会取代程序员吗？','日常搬砖被 AI 接管不少，但架构设计还得人想，聊聊看法。','闲聊',NULL,0,'2026-07-20 23:14:34'),(94,2013,'求助：智能健身镜动作识别不准怎么办','深蹲经常识别成错误动作，是环境还是校准问题？','求助',NULL,0,'2026-07-21 23:14:34'),(95,2014,'我把 AI 护肤仪数据接到了健康 APP','把肤质曲线同步到健康 APP，趋势一目了然，教程奉上。','经验分享',NULL,0,'2026-07-22 23:14:34'),(96,2015,'极客装备开箱：桌面机械臂真能画画？','到货实测，配合视觉模型画了个简笔头像，记录踩坑过程。','闲聊',NULL,0,'2026-07-23 23:14:34'),(97,2016,'求助：AI 降噪耳机通话有回声','新买的降噪耳机通话对方说有回声，是设置问题还是通病？','求助',NULL,0,'2026-07-24 23:14:34'),(98,2017,'出一双 AI 智能运动鞋 42 码','试穿两次，脚感不错但码数偏大，低价出给有缘人。','闲聊',NULL,0,'2026-07-25 23:14:34'),(99,2018,'经验分享：用 AI 写周报的模板我放这了','套用这个结构，5 分钟出一份像样的周报，已验证好用。','经验分享',NULL,0,'2026-07-26 23:14:34'),(100,2019,'【公告】码上学院 AI 实战训练营开放报名','20 小时体系课，带你用 AI 高效做项目，前 50 名送实战手册。','公告',NULL,0,'2026-07-27 23:14:34'),(101,2020,'AI 定制餐吃了一周，聊聊真实体验','按周计划备餐，省心也健康，记录一下体重与精力变化。','闲聊',NULL,0,'2026-07-29 23:14:34');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `merchant_id` int(10) unsigned NOT NULL,
  `title` varchar(120) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock` int(10) unsigned NOT NULL DEFAULT '0',
  `category` varchar(50) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `description` text,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 下架 / 1 上架',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_merchant` (`merchant_id`),
  KEY `idx_category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'DeepSeek-V4-Pro',3.00,689,'AI模型','uploads/products/6a6a0370d890c9.00368819.png','DeepSeek 1.6T MoE 大模型（激活 49B），1M 上下文，智能体能力对标 Opus 4.6。\r\n\r\n输入价格：¥3/M tokens\r\n\r\n输出价格：¥6/M tokens',1,'2026-07-29 21:43:12'),(82,6001,'AI 智能语音助手音箱 Pro',299.00,120,'AI硬件','uploads/products/p1.svg','内置大模型语音助手，多轮对话、控家、写文案一机搞定。',1,'2026-07-09 23:14:34'),(83,6002,'AI 实时翻译机（84 种语言）',599.00,80,'AI硬件','uploads/products/p2.svg','离线+在线双引擎，商务旅行实时同传，准确率 98%。',1,'2026-07-10 23:14:34'),(84,6003,'AI 同声传译翻译耳机',459.00,60,'AI硬件','uploads/products/p3.svg','佩戴即译，双人对话模式，跨国会议无障碍。',1,'2026-07-11 23:14:34'),(85,6004,'AI 桌面机械臂开发套件',899.00,40,'AI硬件','uploads/products/p4.svg','开放 SDK，配合视觉模型完成分拣、绘画等自动化任务。',1,'2026-07-12 23:14:34'),(86,6005,'AI 智能健身镜',1299.00,30,'AI硬件','uploads/products/p5.svg','AI 姿态识别实时纠错，在家也能私教级训练。',1,'2026-07-13 23:14:34'),(87,6006,'AI 绘画会员季卡（Midjourney）',168.00,999,'AI服务','uploads/products/p6.svg','官方渠道代充，即开即用，畅享文生图 / 图生图。',1,'2026-07-14 23:14:34'),(88,6007,'AI 写作助手年卡（ChatWrite）',299.00,999,'AI工具','uploads/products/p7.svg','一键生成公文、营销文案与周报，支持长文续写润色。',1,'2026-07-15 23:14:34'),(89,6008,'AI 人脸识别和家庭摄像头',249.00,150,'AI硬件','uploads/products/p8.svg','本地人脸建模，异常逗留推送，守护家庭安全。',1,'2026-07-16 23:14:34'),(90,6009,'AI 智能语音鼠标',159.00,200,'AI硬件','uploads/products/p9.svg','语音指令 + 语音转写，办公效率翻倍。',1,'2026-07-17 23:14:34'),(91,6010,'AI 主动降噪耳机',699.00,70,'AI硬件','uploads/products/p10.svg','AI 场景降噪，通话更清晰，长续航。',1,'2026-07-18 23:14:34'),(92,6011,'AI 全自动扫地机器人',1599.00,50,'AI硬件','uploads/products/p11.svg','AI 视觉避障 + 自动集尘，建图精准的清洁管家。',1,'2026-07-19 23:14:34'),(93,6012,'AI 智能护眼台灯',199.00,200,'AI硬件','uploads/products/p12.svg','自动调色温亮度，坐姿提醒，数据同步 APP。',1,'2026-07-20 23:14:34'),(94,6013,'AI 智能空气炸锅',399.00,90,'AI硬件','uploads/products/p13.svg','内置智能菜谱，扫码即烹，温度时间自动匹配。',1,'2026-07-21 23:14:34'),(95,6014,'AI 智能电动窗帘',559.00,60,'AI硬件','uploads/products/p14.svg','光照 / 定时联动，语音控制开合，全屋智能一环。',1,'2026-07-22 23:14:34'),(96,6015,'AI 肌肤检测美容仪',659.00,70,'AI硬件','uploads/products/p15.svg','拍照测肤质，定制护理方案，射频导入紧致提亮。',1,'2026-07-23 23:14:34'),(97,6016,'AI 智能声波牙刷',299.00,120,'AI硬件','uploads/products/p16.svg','AI 识别刷牙区域，生成洁牙报告，呵护牙龈。',1,'2026-07-24 23:14:34'),(98,6017,'AI 温控加热智能羽绒服',799.00,55,'AI硬件','uploads/products/p17.svg','APP 三档调温，续航 12 小时，寒冬通勤神器。',1,'2026-07-25 23:14:34'),(99,6018,'AI 智能运动鞋',899.00,45,'AI硬件','uploads/products/p18.svg','内置传感器分析步态，APP 生成跑步建议。',1,'2026-07-26 23:14:34'),(100,6019,'《Prompt 工程实战》纸质书',69.00,300,'AI模型','uploads/products/p19.svg','从零到进阶的提示词方法论，附大量可复制模板。',1,'2026-07-27 23:14:34'),(101,6020,'AI 健康定制餐周计划服务',199.00,500,'AI服务','uploads/products/p20.svg','AI 评估体质与口味，每周定制食谱与食材清单。',1,'2026-07-28 23:14:34');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `replies`
--

DROP TABLE IF EXISTS `replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `replies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 正常 / 1 已删除',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_post` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `replies`
--

LOCK TABLES `replies` WRITE;
/*!40000 ALTER TABLE `replies` DISABLE KEYS */;
INSERT INTO `replies` VALUES (1,1,2,'可以滴滴',0,'2026-07-29 21:44:04');
/*!40000 ALTER TABLE `replies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(100) NOT NULL DEFAULT '',
  `site_slogan` varchar(200) NOT NULL DEFAULT '',
  `icp` varchar(100) NOT NULL DEFAULT '',
  `copyright` varchar(200) NOT NULL DEFAULT '',
  `contact_email` varchar(120) NOT NULL DEFAULT '',
  `contact_phone` varchar(60) NOT NULL DEFAULT '',
  `about` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'海阔HaiKuo AI 商城社区','智能商品 · 活跃社区，让 AI 触手可及','京ICP备202611111号-1','© 2026 海阔HaiKuo AI 商城社区','team@chainedus.com','','海阔HaiKuo AI 商城社区，智能商品 · 活跃社区，让 AI 触手可及','2026-07-29 23:10:40','2026-07-29 23:12:46');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_topic_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topics`
--

LOCK TABLES `topics` WRITE;
/*!40000 ALTER TABLE `topics` DISABLE KEYS */;
INSERT INTO `topics` VALUES (12,'AI硬件'),(13,'二手交易'),(5,'公告'),(2,'求助'),(3,'经验分享'),(1,'闲聊');
/*!40000 ALTER TABLE `topics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `avatar` varchar(255) DEFAULT NULL,
  `role` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 普通用户 / 1 商家 / 2 管理员',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 正常 / 1 禁用',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2022 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'123456789','$2y$10$3e1DYdc1ViwU3XqKVvSvhOuL/OTKXrQ1XQCPknHUeQlqFbGzV4XhW','AI模型',NULL,2,0,'2026-07-29 21:20:20'),(2,'17896917271','$2y$10$aaOiDgpsIBH0YKljsi83gOoEslJRohVNfof3dax58kKWXDcOF63ly','AI模型家',NULL,1,0,'2026-07-29 21:20:50'),(2001,'ai_u_zhichuang','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','智创小助手',NULL,1,0,'2026-07-29 23:14:34'),(2002,'ai_u_yijie','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','译界通',NULL,1,0,'2026-07-29 23:14:34'),(2003,'ai_u_shenglin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','声临科技',NULL,1,0,'2026-07-29 23:14:34'),(2004,'ai_u_jike','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','极客机械坊',NULL,1,0,'2026-07-29 23:14:34'),(2005,'ai_u_jingjie','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','镜界健身',NULL,1,0,'2026-07-29 23:14:34'),(2006,'ai_u_huabu','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','灵感画布',NULL,1,0,'2026-07-29 23:14:34'),(2007,'ai_u_mazi','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','码字星球',NULL,1,0,'2026-07-29 23:14:34'),(2008,'ai_u_anfang','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','安防智能',NULL,1,0,'2026-07-29 23:14:34'),(2009,'ai_u_zhijian','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','指尖声学',NULL,1,0,'2026-07-29 23:14:34'),(2010,'ai_u_jingjie2','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','静界声学',NULL,1,0,'2026-07-29 23:14:34'),(2011,'ai_u_jingxiang','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','净享家居',NULL,1,0,'2026-07-29 23:14:34'),(2012,'ai_u_mingmou','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','明眸照明',NULL,1,0,'2026-07-29 23:14:34'),(2013,'ai_u_shiguang','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','食光智能',NULL,1,0,'2026-07-29 23:14:34'),(2014,'ai_u_liandong','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','帘动智能',NULL,1,0,'2026-07-29 23:14:34'),(2015,'ai_u_yuefu','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','悦肤实验室',NULL,1,0,'2026-07-29 23:14:34'),(2016,'ai_u_haochi','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','皓齿智能',NULL,1,0,'2026-07-29 23:14:34'),(2017,'ai_u_nuandong','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','暖冬智衣',NULL,1,0,'2026-07-29 23:14:34'),(2018,'ai_u_buyue','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','步跃科技',NULL,1,0,'2026-07-29 23:14:34'),(2019,'ai_u_maxue','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','码上学院',NULL,1,0,'2026-07-29 23:14:34'),(2020,'ai_u_shanyang','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','膳养 AI',NULL,1,0,'2026-07-29 23:14:34'),(2021,'17359258961','$2y$10$l/cKZOYwUFUve3oJ5GjKdexBLICd9l8nSqvlcebSIpsP3DQ1XaAqu','悠悠',NULL,0,0,'2026-07-30 09:35:51');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 
--

--
-- Dumping routines for database 
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-31 21:06:09
