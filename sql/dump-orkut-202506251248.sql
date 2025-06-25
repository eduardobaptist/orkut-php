-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: orkut
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `friends`
--

DROP TABLE IF EXISTS `friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `friends` (
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`,`friend_id`),
  KEY `friends_users_FK_1` (`friend_id`),
  CONSTRAINT `friends_users_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `friends_users_FK_1` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `CONSTRAINT_1` CHECK (`user_id` <> `friend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friends`
--

LOCK TABLES `friends` WRITE;
/*!40000 ALTER TABLE `friends` DISABLE KEYS */;
INSERT INTO `friends` VALUES (7,8,'accepted','2025-06-20 01:35:33'),(7,9,'accepted','2025-06-24 21:23:11'),(8,9,'accepted','2025-06-24 20:41:54'),(8,10,'accepted','2025-06-21 21:03:52'),(9,8,'rejected','2025-06-20 20:23:17'),(10,7,'accepted','2025-06-21 21:39:55'),(10,9,'accepted','2025-06-21 21:39:57'),(11,7,'accepted','2025-06-25 04:53:20'),(11,10,'pending','2025-06-25 04:11:39');
/*!40000 ALTER TABLE `friends` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_sender` (`sender_id`),
  KEY `idx_receiver` (`receiver_id`),
  KEY `idx_conversation` (`sender_id`,`receiver_id`),
  KEY `idx_unread` (`receiver_id`,`is_read`),
  CONSTRAINT `fk_messages_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_messages_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `CONSTRAINT_1` CHECK (`sender_id` <> `receiver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,7,8,'teste',0,'2025-06-20 20:04:33'),(2,7,8,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis id ante ante. Nam eget nisl ac purus laoreet ullamcorper vel vel ipsum. Suspendisse justo diam, faucibus nec arcu eu, euismod iaculis lorem. Sed id orci rhoncus, consectetur leo at, posuere velit. Aenean augue sapien, euismod eu viverra ut, condimentum eu mauris. Sed in facilisis ipsum, sed dignissim tortor. Proin sapien sapien, ornare a nulla a, dapibus blandit ipsum. Suspendisse quis egestas leo. Duis libero turpis, porta quis ipsum ac, commodo aliquet nisi. Curabitur a felis faucibus, fermentum dolor quis, vulputate ante. Curabitur id justo vel ante fringilla hendrerit. Ut tortor.',0,'2025-06-20 20:34:41'),(3,7,9,'teste',0,'2025-06-21 03:01:51'),(4,8,7,'testeeeee',0,'2025-06-21 03:10:45'),(5,7,8,'teste 2',0,'2025-06-21 03:11:10'),(6,7,8,'a',0,'2025-06-21 03:11:13'),(7,8,7,'abc',0,'2025-06-21 04:36:05'),(8,7,8,'a',0,'2025-06-21 04:36:56'),(9,7,8,'b',0,'2025-06-21 04:36:57'),(10,7,8,'c',0,'2025-06-21 04:36:58'),(11,7,8,'d',0,'2025-06-21 04:37:00'),(12,10,7,'olá',0,'2025-06-24 21:22:21'),(13,7,11,'teste',0,'2025-06-25 04:53:47');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (7,'teste','teste','$2y$10$qt1CHJuvnIwQrfS/AmrVi.n1h/o/KNwaO8vhOENaELWlsumY1.F6q',NULL,'2025-06-19 05:39:17'),(8,'brackmann','Christian Brackmann','$2y$10$T86pR/ixkN0kouudrw0RYOUSj1UgUIXcvEQ9r6wGjIHsvqDi5NWCu','uploads/6854bac498f98.jpg','2025-06-20 01:35:00'),(9,'mark_face','Mark Zuckerberg','$2y$10$RC9yu.9nR8lbqgrzO/Py5ehIUpo8R5PN8ckYyRdlrXux1PLwdGjfq','uploads/68559dba826b7.jpg','2025-06-20 17:43:22'),(10,'gates','Bill Gates','$2y$10$iUnqcZigjOhMnuDLV7.oHukmkJ.8KQ3dLjT5PjvqMIWDTJ5CV0DIK','uploads/6856fcca6c112.jpg','2025-06-21 18:41:14'),(11,'coringa','coringa','$2y$10$Hkt43/sGC5F5wgY.8qcR5u1Vo081nSl07.ZzYi.153rozaaw4ysfq','uploads/685b7499d60c8.jpg','2025-06-25 04:01:29');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'orkut'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-25 12:48:05
