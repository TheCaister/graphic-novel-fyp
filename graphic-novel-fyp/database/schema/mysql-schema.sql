/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `approved_moderators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `approved_moderators` (
  `approved_moderators_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `approver_id` bigint unsigned NOT NULL,
  `approvee_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`approved_moderators_id`),
  KEY `approved_moderators_approver_id_foreign` (`approver_id`),
  KEY `approved_moderators_approvee_id_foreign` (`approvee_id`),
  CONSTRAINT `approved_moderators_approvee_id_foreign` FOREIGN KEY (`approvee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `approved_moderators_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `chapters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chapters` (
  `chapter_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `series_id` bigint unsigned NOT NULL,
  `chapter_number` int NOT NULL,
  `chapter_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chapter_thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chapter_notes` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments_enabled` tinyint(1) NOT NULL,
  `scheduled_publish` timestamp NOT NULL,
  PRIMARY KEY (`chapter_id`),
  KEY `chapters_series_id_foreign` (`series_id`),
  CONSTRAINT `chapters_series_id_foreign` FOREIGN KEY (`series_id`) REFERENCES `series` (`series_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `chapters_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chapters_comments` (
  `chapters_comments_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chapter_id` bigint unsigned NOT NULL,
  `comment_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`chapters_comments_id`),
  KEY `chapters_comments_chapter_id_foreign` (`chapter_id`),
  KEY `chapters_comments_comment_id_foreign` (`comment_id`),
  CONSTRAINT `chapters_comments_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE CASCADE,
  CONSTRAINT `chapters_comments_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `chapters_elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chapters_elements` (
  `chapter_elements_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chapter_id` bigint unsigned NOT NULL,
  `element_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`chapter_elements_id`),
  KEY `chapters_elements_chapter_id_foreign` (`chapter_id`),
  KEY `chapters_elements_element_id_foreign` (`element_id`),
  CONSTRAINT `chapters_elements_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE CASCADE,
  CONSTRAINT `chapters_elements_element_id_foreign` FOREIGN KEY (`element_id`) REFERENCES `elements` (`element_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `comment_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `commenter_id` bigint unsigned NOT NULL,
  `replying_to` bigint unsigned DEFAULT NULL,
  `comment_content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `commentable_id` bigint unsigned NOT NULL,
  `commentable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `comments_commenter_id_foreign` (`commenter_id`),
  KEY `comments_replying_to_foreign` (`replying_to`),
  CONSTRAINT `comments_commenter_id_foreign` FOREIGN KEY (`commenter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_replying_to_foreign` FOREIGN KEY (`replying_to`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `compositions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compositions` (
  `composition_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `universe_id` bigint unsigned NOT NULL,
  `composition_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `composition_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`composition_id`),
  KEY `compositions_universe_id_foreign` (`universe_id`),
  CONSTRAINT `compositions_universe_id_foreign` FOREIGN KEY (`universe_id`) REFERENCES `universes` (`universe_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `elements` (
  `element_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `hidden` tinyint(1) NOT NULL,
  PRIMARY KEY (`element_id`),
  KEY `elements_owner_id_foreign` (`owner_id`),
  CONSTRAINT `elements_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `followers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `followers` (
  `followers_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `follower_id` bigint unsigned NOT NULL,
  `followee_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`followers_id`),
  KEY `followers_follower_id_foreign` (`follower_id`),
  KEY `followers_followee_id_foreign` (`followee_id`),
  CONSTRAINT `followers_followee_id_foreign` FOREIGN KEY (`followee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `followers_follower_id_foreign` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `page_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chapter_id` bigint unsigned NOT NULL,
  `page_number` bigint NOT NULL,
  `page_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`page_id`),
  KEY `pages_chapter_id_foreign` (`chapter_id`),
  CONSTRAINT `pages_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pages_elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages_elements` (
  `pages_elements_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint unsigned NOT NULL,
  `element_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`pages_elements_id`),
  KEY `pages_elements_page_id_foreign` (`page_id`),
  KEY `pages_elements_element_id_foreign` (`element_id`),
  CONSTRAINT `pages_elements_element_id_foreign` FOREIGN KEY (`element_id`) REFERENCES `elements` (`element_id`) ON DELETE CASCADE,
  CONSTRAINT `pages_elements_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`page_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `series`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `series` (
  `series_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `universe_id` bigint unsigned NOT NULL,
  `series_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `series_genre` enum('ACTION','ADVENTURE','COMEDY','DRAMA','FANTASY','HORROR','MYSTERY','ROMANCE','THRILLER') COLLATE utf8mb4_unicode_ci NOT NULL,
  `series_summary` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `series_thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` double(8,2) NOT NULL,
  PRIMARY KEY (`series_id`),
  KEY `series_universe_id_foreign` (`universe_id`),
  CONSTRAINT `series_universe_id_foreign` FOREIGN KEY (`universe_id`) REFERENCES `universes` (`universe_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `series_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `series_comments` (
  `series_comments_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `series_id` bigint unsigned NOT NULL,
  `comment_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`series_comments_id`),
  KEY `series_comments_series_id_foreign` (`series_id`),
  KEY `series_comments_comment_id_foreign` (`comment_id`),
  CONSTRAINT `series_comments_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE,
  CONSTRAINT `series_comments_series_id_foreign` FOREIGN KEY (`series_id`) REFERENCES `series` (`series_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `series_elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `series_elements` (
  `series_elements_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `series_id` bigint unsigned NOT NULL,
  `element_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`series_elements_id`),
  KEY `series_elements_series_id_foreign` (`series_id`),
  KEY `series_elements_element_id_foreign` (`element_id`),
  CONSTRAINT `series_elements_element_id_foreign` FOREIGN KEY (`element_id`) REFERENCES `elements` (`element_id`) ON DELETE CASCADE,
  CONSTRAINT `series_elements_series_id_foreign` FOREIGN KEY (`series_id`) REFERENCES `series` (`series_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `universes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `universes` (
  `universe_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` bigint unsigned NOT NULL,
  `universe_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`universe_id`),
  KEY `universes_owner_id_foreign` (`owner_id`),
  CONSTRAINT `universes_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_chapter_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_chapter_likes` (
  `user_chapter_rating_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `chapter_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`user_chapter_rating_id`),
  KEY `user_chapter_likes_user_id_foreign` (`user_id`),
  KEY `user_chapter_likes_chapter_id_foreign` (`chapter_id`),
  CONSTRAINT `user_chapter_likes_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE CASCADE,
  CONSTRAINT `user_chapter_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_series_rating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_series_rating` (
  `user_series_rating_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `series_id` bigint unsigned NOT NULL,
  `rating` int NOT NULL,
  PRIMARY KEY (`user_series_rating_id`),
  KEY `user_series_rating_user_id_foreign` (`user_id`),
  KEY `user_series_rating_series_id_foreign` (`series_id`),
  CONSTRAINT `user_series_rating_series_id_foreign` FOREIGN KEY (`series_id`) REFERENCES `series` (`series_id`) ON DELETE CASCADE,
  CONSTRAINT `user_series_rating_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date_of_birth` timestamp NULL DEFAULT NULL,
  `is_banned` tinyint(1) NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `bio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` VALUES (2,'2014_10_12_100000_create_password_reset_tokens_table',1);
INSERT INTO `migrations` VALUES (3,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` VALUES (4,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` VALUES (5,'2023_05_21_131540_create_element_table',1);
INSERT INTO `migrations` VALUES (6,'2023_05_21_131541_create_chapters_comments_table',1);
INSERT INTO `migrations` VALUES (7,'2023_05_21_131542_create_pages_elements_table',1);
INSERT INTO `migrations` VALUES (8,'2023_05_21_131544_create_series_elements_table',1);
INSERT INTO `migrations` VALUES (9,'2023_05_21_131545_create_series_table',1);
INSERT INTO `migrations` VALUES (10,'2023_05_21_131546_create_chapters_elements_table',1);
INSERT INTO `migrations` VALUES (11,'2023_05_21_131547_create_universes_table',1);
INSERT INTO `migrations` VALUES (12,'2023_05_21_131548_create_chapter_table',1);
INSERT INTO `migrations` VALUES (13,'2023_05_21_131549_create_comments_table',1);
INSERT INTO `migrations` VALUES (14,'2023_05_21_131550_create_followers_table',1);
INSERT INTO `migrations` VALUES (15,'2023_05_21_131551_create_series_comments_table',1);
INSERT INTO `migrations` VALUES (16,'2023_05_21_131552_create_approved_moderators_table',1);
INSERT INTO `migrations` VALUES (17,'2023_05_21_131553_create_page_table',1);
INSERT INTO `migrations` VALUES (18,'2023_05_23_115934_create_user_series_rating_table',1);
INSERT INTO `migrations` VALUES (19,'2023_05_23_120425_create_user_chapter_rating_table',1);
INSERT INTO `migrations` VALUES (20,'2023_05_23_120753_create_compositions_table',1);
