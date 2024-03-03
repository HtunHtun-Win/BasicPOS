-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: basic_pos
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Default Category','Default Category',0,'2024-02-04 16:28:54','2024-02-04 16:28:54'),(2,'snack','snackk',0,'2024-01-31 21:17:02','2024-01-31 21:17:02'),(3,'drink','drink jc',0,'2024-01-31 21:17:31','2024-01-31 21:17:31'),(4,'coffee','coffee mix',0,'2024-02-04 09:16:06','2024-02-04 09:16:06');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `isdeleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'DefaultCustomer','-','-',0,'2024-02-07 13:46:10'),(2,'AungAung','232423','YGN',0,'2024-02-07 13:46:39'),(3,'HtunHtun','097777777','Yangon',0,'2024-02-10 09:39:57'),(4,'AungMyint','3222','sdfdsf',0,'2024-02-10 09:44:50');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flow_type`
--

DROP TABLE IF EXISTS `flow_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flow_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flow_type`
--

LOCK TABLES `flow_type` WRITE;
/*!40000 ALTER TABLE `flow_type` DISABLE KEYS */;
INSERT INTO `flow_type` VALUES (1,'income'),(2,'expense');
/*!40000 ALTER TABLE `flow_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gen_id`
--

DROP TABLE IF EXISTS `gen_id`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gen_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `prefix` varchar(255) NOT NULL,
  `digit` int(11) NOT NULL,
  `no` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gen_id`
--

LOCK TABLES `gen_id` WRITE;
/*!40000 ALTER TABLE `gen_id` DISABLE KEYS */;
INSERT INTO `gen_id` VALUES (1,'sale','for sale voucher','INV#',6,78),(2,'purchase','for purchase voucher','P#',6,24);
/*!40000 ALTER TABLE `gen_id` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `income_expense`
--

DROP TABLE IF EXISTS `income_expense`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `income_expense` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `note` text NOT NULL,
  `flow_type_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `isdeleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `income_expense`
--

LOCK TABLES `income_expense` WRITE;
/*!40000 ALTER TABLE `income_expense` DISABLE KEYS */;
INSERT INTO `income_expense` VALUES (7,32000,'Wifi','mg mg',2,1,0,'2024-02-14 18:47:37'),(8,500,'Kpay pc','kpay pc',1,1,0,'2024-02-14 18:49:01'),(9,2000,'aya','sdfsd',1,1,1,'2024-02-28 11:53:32'),(10,2000,'aya','sdfsd',1,1,1,'2024-02-28 11:53:32'),(11,2,'s','cc',1,1,1,'2024-02-28 11:53:48'),(12,20000,'aya','aya',1,1,1,'2024-02-28 11:54:09'),(13,10000,'aya','min min',2,1,0,'2024-02-28 11:54:21');
/*!40000 ALTER TABLE `income_expense` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_type`
--

DROP TABLE IF EXISTS `payment_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_type`
--

LOCK TABLES `payment_type` WRITE;
/*!40000 ALTER TABLE `payment_type` DISABLE KEYS */;
INSERT INTO `payment_type` VALUES (1,'Cash','cash','2024-02-28 21:45:10'),(2,'Kpay','KBZ pay','2024-02-28 21:45:10'),(3,'Aya','Aya','2024-03-03 10:44:58'),(4,'dynamic','dddddd','2024-03-03 11:01:05');
/*!40000 ALTER TABLE `payment_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_log`
--

DROP TABLE IF EXISTS `product_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `note` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=218 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_log`
--

LOCK TABLES `product_log` WRITE;
/*!40000 ALTER TABLE `product_log` DISABLE KEYS */;
INSERT INTO `product_log` VALUES (196,'2024-02-28 12:40:56',1,100,'purchase',1),(197,'2024-02-28 12:40:56',2,100,'purchase',1),(198,'2024-02-28 12:40:56',3,100,'purchase',1),(199,'2024-02-28 12:52:33',1,-3,'sale',1),(200,'2024-02-28 12:52:33',2,-2,'sale',1),(201,'2024-02-28 14:54:07',1,-1,'sale',1),(202,'2024-02-28 14:54:13',2,-1,'sale',1),(203,'2024-02-28 15:10:33',1,-5,'sale',1),(204,'2024-02-28 16:16:59',3,-1,'sale',1),(205,'2024-02-28 22:02:01',2,-5,'sale',1),(206,'2024-02-28 22:21:13',3,45,'purchase',1),(207,'2024-03-01 14:49:39',1,10,'purchase',1),(208,'2024-03-01 15:15:55',4,1,'Initial Quantity',1),(209,'2024-03-01 15:16:45',4,1,'purchase',1),(210,'2024-03-01 15:17:31',4,1,'purchase',1),(211,'2024-03-02 10:20:48',4,-1,'sale',1),(212,'2024-03-02 21:07:19',4,1,'sale item remove',1),(213,'2024-03-02 21:07:20',1,-1,'sale',1),(214,'2024-03-02 21:56:15',3,-2,'sale',1),(215,'2024-03-02 22:01:00',4,10,'purchase',1),(216,'2024-03-03 09:30:33',3,-1,'sale',1),(217,'2024-03-03 09:30:39',1,-1,'sale',1);
/*!40000 ALTER TABLE `product_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `quantity` int(11) NOT NULL,
  `category_id` int(6) NOT NULL,
  `purchase_price` int(11) NOT NULL,
  `sale_price` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `isdeleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'00002','ToeToe','potato chip',99,2,500,650,'2024-03-03 09:30:39','2024-02-24 16:49:00',0),(2,'sp123','speed','sp',92,3,500,900,'2024-03-01 15:46:23','2024-02-24 21:36:40',0),(3,'sk234','shark','',141,3,1000,1700,'2024-03-03 09:30:33','2024-02-25 21:34:37',0),(4,'ttt','test','1',13,1,150,200,'2024-03-02 22:01:00','2024-03-01 15:15:55',0);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase`
--

DROP TABLE IF EXISTS `purchase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_no` varchar(255) NOT NULL,
  `supplier_id` int(11) NOT NULL DEFAULT 1,
  `user_id` int(11) NOT NULL,
  `net_price` int(11) NOT NULL,
  `discount` int(11) NOT NULL DEFAULT 0,
  `total_price` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase`
--

LOCK TABLES `purchase` WRITE;
/*!40000 ALTER TABLE `purchase` DISABLE KEYS */;
INSERT INTO `purchase` VALUES (8,'P#000019',1,1,190000,0,190000,'2024-02-28 12:40:55'),(9,'P#000020',2,1,45000,5000,40000,'2024-02-28 22:21:13'),(10,'P#000021',1,1,5000,0,5000,'2024-03-01 14:49:39'),(11,'P#000022',1,1,200,0,200,'2024-03-01 15:16:45'),(12,'P#000023',1,1,200,0,200,'2024-03-01 15:17:31'),(13,'P#000024',1,1,1500,500,1000,'2024-03-02 22:01:00');
/*!40000 ALTER TABLE `purchase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_detail`
--

DROP TABLE IF EXISTS `purchase_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_detail`
--

LOCK TABLES `purchase_detail` WRITE;
/*!40000 ALTER TABLE `purchase_detail` DISABLE KEYS */;
INSERT INTO `purchase_detail` VALUES (10,8,1,100,400,'2024-02-28 06:10:56'),(11,8,2,100,500,'2024-02-28 06:10:56'),(12,8,3,100,1000,'2024-02-28 06:10:56'),(13,9,3,45,1000,'2024-02-28 15:51:13'),(14,10,1,10,500,'2024-03-01 08:19:39'),(15,11,4,1,200,'2024-03-01 08:46:45'),(16,12,4,1,200,'2024-03-01 08:47:31'),(17,13,4,10,150,'2024-03-02 15:31:00');
/*!40000 ALTER TABLE `purchase_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_price`
--

DROP TABLE IF EXISTS `purchase_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_price`
--

LOCK TABLES `purchase_price` WRITE;
/*!40000 ALTER TABLE `purchase_price` DISABLE KEYS */;
INSERT INTO `purchase_price` VALUES (24,1,89,400,'2024-02-28 12:40:56'),(25,2,92,500,'2024-02-28 12:40:56'),(26,3,96,1000,'2024-02-28 12:40:56'),(27,3,45,1000,'2024-02-28 22:21:13'),(28,1,10,500,'2024-03-01 14:49:39'),(29,4,1,100,'2024-03-01 15:15:55'),(30,4,1,200,'2024-03-01 15:16:45'),(31,4,1,200,'2024-03-01 15:17:31'),(32,4,10,150,'2024-03-02 22:01:00');
/*!40000 ALTER TABLE `purchase_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `removed_item`
--

DROP TABLE IF EXISTS `removed_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `removed_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `removed_item`
--

LOCK TABLES `removed_item` WRITE;
/*!40000 ALTER TABLE `removed_item` DISABLE KEYS */;
INSERT INTO `removed_item` VALUES (30,56,4,'2024-03-02 21:07:19');
/*!40000 ALTER TABLE `removed_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin'),(2,'Sale'),(3,'Purchase');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_price_log`
--

DROP TABLE IF EXISTS `sale_price_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_price_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `old_price` int(11) NOT NULL,
  `new_price` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_price_log`
--

LOCK TABLES `sale_price_log` WRITE;
/*!40000 ALTER TABLE `sale_price_log` DISABLE KEYS */;
INSERT INTO `sale_price_log` VALUES (5,1,500,600,'2024-03-01 15:46:16'),(6,2,800,900,'2024-03-01 15:46:23'),(7,3,1500,1700,'2024-03-01 19:30:12'),(8,1,600,650,'2024-03-02 10:08:27');
/*!40000 ALTER TABLE `sale_price_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_no` varchar(255) NOT NULL,
  `customer_id` int(11) NOT NULL DEFAULT 1,
  `user_id` int(11) NOT NULL,
  `net_price` int(11) NOT NULL,
  `discount` int(11) NOT NULL DEFAULT 0,
  `total_price` int(11) NOT NULL,
  `payment_type_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (57,'INV#000076',3,1,3400,400,3000,2,'2024-03-02 21:56:15'),(58,'INV#000077',1,1,1700,0,1700,2,'2024-03-03 09:30:33'),(59,'INV#000078',1,1,650,0,650,1,'2024-03-03 09:30:39');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_detail`
--

DROP TABLE IF EXISTS `sales_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `pprice` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_detail`
--

LOCK TABLES `sales_detail` WRITE;
/*!40000 ALTER TABLE `sales_detail` DISABLE KEYS */;
INSERT INTO `sales_detail` VALUES (71,57,3,2,1700,1000,'2024-03-02 15:26:15'),(72,58,3,1,1700,1000,'2024-03-03 03:00:33'),(73,59,1,1,650,400,'2024-03-03 03:00:39');
/*!40000 ALTER TABLE `sales_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_info`
--

DROP TABLE IF EXISTS `shop_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_name` varchar(255) NOT NULL,
  `shop_address` text NOT NULL,
  `shop_phone` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_info`
--

LOCK TABLES `shop_info` WRITE;
/*!40000 ALTER TABLE `shop_info` DISABLE KEYS */;
INSERT INTO `shop_info` VALUES (1,'StarCity','YGN','097777');
/*!40000 ALTER TABLE `shop_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `isdeleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'DefaultSupplier','-','-',0,'2024-02-07 13:46:10'),(2,'SuperStar','1321312','YGN',0,'2024-02-07 13:46:39'),(3,'StarMobile','023432423','Yangon',0,'2024-02-10 09:39:57');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `login_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` tinyint(4) NOT NULL,
  `isactive` tinyint(4) NOT NULL DEFAULT 0,
  `isdeleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'RB','admin','admin',1,0,0,'2024-01-30 16:54:26'),(2,'Ma Ma','s1','1111',2,0,0,'2024-01-30 16:54:26'),(3,'MgMg','p1','mmmm',3,0,0,'2024-01-30 16:54:26'),(4,'CATCAT','ccat','1111',1,0,0,'2024-01-30 20:38:10'),(18,'a','a','aaaa',1,0,0,'2024-02-05 14:56:38');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-03 11:20:46
