/*
Navicat MySQL Data Transfer

Source Server         : f14
Source Server Version : 50505
Source Host           : srv:3306
Source Database       : omsobse

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2024-06-23 13:53:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `acl_permissions`
-- ----------------------------
DROP TABLE IF EXISTS `acl_permissions`;
CREATE TABLE `acl_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10045 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of acl_permissions
-- ----------------------------
INSERT INTO `acl_permissions` VALUES ('10001', 'Admin - Permissions Grid', 'admin-permission.index', '2024-05-08 12:00:00', '2024-03-08 19:24:07');
INSERT INTO `acl_permissions` VALUES ('10002', 'Admin - Permission Add Save', 'admin-permission.store', '2024-05-08 12:00:00', '2024-03-08 19:37:23');
INSERT INTO `acl_permissions` VALUES ('10003', 'Admin - Permission Add Screen', 'admin-permission.create', '2024-05-08 12:00:00', '2024-03-08 19:35:23');
INSERT INTO `acl_permissions` VALUES ('10004', 'Admin - Permission Route Update', 'admin-permission.routes.update', '2024-05-08 12:00:00', '2024-03-08 19:36:00');
INSERT INTO `acl_permissions` VALUES ('10005', 'Admin - Permission Update', 'admin-permission.update', '2024-05-08 12:00:00', '2024-03-08 19:51:35');
INSERT INTO `acl_permissions` VALUES ('10006', 'Admin - Permission Delete', 'admin-permission.destroy', '2024-05-08 12:00:00', '2024-03-08 20:06:12');
INSERT INTO `acl_permissions` VALUES ('10007', 'Admin - Permission Edit Screen', 'admin-permission.edit', '2024-05-08 12:00:00', '2024-03-08 20:13:21');
INSERT INTO `acl_permissions` VALUES ('10008', 'Admin - Permission Roles', 'admin-permission.roles', '2024-05-08 12:00:00', '2024-03-08 20:14:32');
INSERT INTO `acl_permissions` VALUES ('10009', 'Admin - Permission Roles Update', 'admin-permission.rolesupdate', '2024-05-08 12:00:00', '2024-03-08 20:15:03');
INSERT INTO `acl_permissions` VALUES ('10010', 'Admin - Permission Users', 'admin-permission.users', '2024-05-08 12:00:00', '2024-03-08 20:16:53');
INSERT INTO `acl_permissions` VALUES ('10011', 'Admin- Roles Grid', 'admin-role.index', '2024-05-08 12:00:00', '2024-03-08 20:18:08');
INSERT INTO `acl_permissions` VALUES ('10012', 'Admin - Role Add Save', 'admin-role.store', '2024-05-08 12:00:00', '2024-03-08 20:18:33');
INSERT INTO `acl_permissions` VALUES ('10013', 'Admin - Role Add Screen', 'admin-role.create', '2024-05-08 12:00:00', '2024-03-08 20:18:55');
INSERT INTO `acl_permissions` VALUES ('10014', 'Admin - Role Update', 'admin-role.update', '2024-05-08 12:00:00', '2024-03-08 20:19:31');
INSERT INTO `acl_permissions` VALUES ('10015', 'Admin - Role Destroy', 'admin-role.destroy', '2024-05-08 12:00:00', '2024-03-08 20:19:49');
INSERT INTO `acl_permissions` VALUES ('10016', 'Admin - Role Edit Screen', 'admin-role.edit', '2024-05-08 12:00:00', '2024-03-08 20:20:17');
INSERT INTO `acl_permissions` VALUES ('10017', 'Admin - Users Grid', 'admin-users.index', '2024-05-08 12:00:00', '2024-03-08 20:20:40');
INSERT INTO `acl_permissions` VALUES ('10018', 'Admin - Users Add Save', 'admin-users.store', '2024-05-08 12:00:00', '2024-05-08 12:00:00');
INSERT INTO `acl_permissions` VALUES ('10019', 'Admin - Users Add Screen', 'admin-users.create', '2024-05-08 12:00:00', '2024-05-08 12:00:00');
INSERT INTO `acl_permissions` VALUES ('10020', 'Admin - Users Update', 'admin-users.update', '2024-05-08 12:00:00', '2024-05-08 12:00:00');
INSERT INTO `acl_permissions` VALUES ('10021', 'Admin - Users Delete', 'admin-users.destroy', '2024-05-08 12:00:00', '2024-05-08 12:00:00');
INSERT INTO `acl_permissions` VALUES ('10022', 'Admin - Users Edit Screen', 'admin-users.edit', '2024-05-08 12:00:00', '2024-05-08 12:00:00');
INSERT INTO `acl_permissions` VALUES ('10023', 'User - Logout', 'user-logout', '2024-03-12 13:24:39', '2024-03-12 13:24:39');
INSERT INTO `acl_permissions` VALUES ('10024', 'User - Livewire Preview File', 'user-livewire-preview-file', '2024-03-12 13:26:22', '2024-03-12 13:26:22');
INSERT INTO `acl_permissions` VALUES ('10025', 'User - Livewire Update', 'user-livewire-update', '2024-03-12 13:26:55', '2024-03-12 13:26:55');
INSERT INTO `acl_permissions` VALUES ('10026', 'User - Livewire Upload File', 'user-livewire-upload-file', '2024-03-12 13:27:58', '2024-03-12 13:27:58');
INSERT INTO `acl_permissions` VALUES ('10027', 'Admin Dashboard', 'dashboard', '2024-03-12 13:28:57', '2024-03-12 13:28:57');
INSERT INTO `acl_permissions` VALUES ('10028', 'User - Two Factor Secret Key', 'user-two-factor.secret-key', '2024-03-12 13:59:06', '2024-03-12 13:59:06');
INSERT INTO `acl_permissions` VALUES ('10029', 'User Two Factor Recovery Codes', 'user-two-factor.recovery-codes', '2024-03-12 13:59:55', '2024-03-12 13:59:55');
INSERT INTO `acl_permissions` VALUES ('10030', 'User - Two Factor QR Code', 'user-two-factor.qr-code', '2024-03-12 14:00:49', '2024-03-12 14:00:49');
INSERT INTO `acl_permissions` VALUES ('10031', 'User Two Factor Login', 'user-two-factor.login', '2024-03-12 14:01:27', '2024-03-12 14:01:27');
INSERT INTO `acl_permissions` VALUES ('10032', 'User - Two Factor Enable', 'user-two-factor.enable', '2024-03-12 14:02:00', '2024-03-12 14:02:00');
INSERT INTO `acl_permissions` VALUES ('10033', 'User - Two Factor Disable', 'user-two-factor.disable', '2024-03-12 14:02:31', '2024-03-12 14:02:31');
INSERT INTO `acl_permissions` VALUES ('10034', 'User Two Factor Confirm', 'user-two-factor.confirm', '2024-03-12 14:03:01', '2024-03-12 14:03:01');
INSERT INTO `acl_permissions` VALUES ('10035', 'User - Profile Show', 'user-profile-show', '2024-03-12 14:05:37', '2024-03-12 14:05:37');
INSERT INTO `acl_permissions` VALUES ('10036', 'User - Teams Create', 'user-teams.create', '2024-03-12 14:10:29', '2024-03-12 14:10:29');
INSERT INTO `acl_permissions` VALUES ('10037', 'User - Teams Show', 'user-teams.show', '2024-03-12 14:11:02', '2024-03-12 14:11:02');
INSERT INTO `acl_permissions` VALUES ('10038', 'User - Current Team Update', 'user-current-team.update', '2024-03-12 14:11:33', '2024-03-12 14:11:33');
INSERT INTO `acl_permissions` VALUES ('10039', 'User Team Invitations Accept', 'user-team-invitations.accept', '2024-03-12 14:12:04', '2024-03-12 14:12:04');
INSERT INTO `acl_permissions` VALUES ('10040', 'User - API Tokens', 'user-api-tokens.index', '2024-03-12 14:15:00', '2024-03-12 14:15:00');
INSERT INTO `acl_permissions` VALUES ('10041', 'User - Password Update', 'user-password.update', null, null);
INSERT INTO `acl_permissions` VALUES ('10042', 'User - Password Confirmation', 'password.confirmation', null, null);
INSERT INTO `acl_permissions` VALUES ('10043', 'User -Password Confirm', 'password.confirm', null, null);
INSERT INTO `acl_permissions` VALUES ('10044', 'User - Sanctum CSRF Cookie', 'user-sanctum.csrf-cookie', null, null);

-- ----------------------------
-- Table structure for `acl_roles`
-- ----------------------------
DROP TABLE IF EXISTS `acl_roles`;
CREATE TABLE `acl_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10009 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of acl_roles
-- ----------------------------
INSERT INTO `acl_roles` VALUES ('10001', 'Admin - Access Control Menu', 'admin-access-control', '2024-05-08 12:00:00', '2024-03-08 22:05:52');
INSERT INTO `acl_roles` VALUES ('10002', 'Admin - Users Menu', 'admin-users', '2024-05-08 12:00:00', '2024-03-08 22:06:01');
INSERT INTO `acl_roles` VALUES ('10003', 'Frontend - Website Menu', 'frontend-website', '2024-05-08 12:00:00', '2024-03-08 22:07:29');
INSERT INTO `acl_roles` VALUES ('10004', 'User - System Essentials', 'user-system-essentials', '2024-03-12 13:29:58', '2024-03-12 13:29:58');
INSERT INTO `acl_roles` VALUES ('10005', 'User - Manage', 'user-manage', '2024-03-12 14:06:24', '2024-03-12 14:13:46');
INSERT INTO `acl_roles` VALUES ('10006', 'User - Teams', 'user-teams', '2024-03-12 14:12:31', '2024-03-12 14:13:57');
INSERT INTO `acl_roles` VALUES ('10007', 'User - API Tokens', 'user-api-tokens', '2024-03-12 14:15:26', '2024-03-12 14:15:26');
INSERT INTO `acl_roles` VALUES ('10008', 'Admin - Admin Dashboard', 'admin-dashboard', '2024-05-08 12:00:00', '2024-05-08 12:00:00');

-- ----------------------------
-- Table structure for `acl_roles_permissions`
-- ----------------------------
DROP TABLE IF EXISTS `acl_roles_permissions`;
CREATE TABLE `acl_roles_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `acl_roles_permissions_role_id_index` (`role_id`),
  KEY `acl_roles_permissions_permission_id_index` (`permission_id`),
  CONSTRAINT `acl_roles_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `acl_permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `acl_roles_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `acl_roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10024 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of acl_roles_permissions
-- ----------------------------
INSERT INTO `acl_roles_permissions` VALUES ('10001', '10001', '10001', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10002', '10001', '10002', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10003', '10001', '10003', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10004', '10001', '10004', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10006', '10001', '10005', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10007', '10001', '10006', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10008', '10001', '10007', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10009', '10001', '10008', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10010', '10001', '10009', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10011', '10001', '10010', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10012', '10001', '10011', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10013', '10001', '10012', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10014', '10001', '10013', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10015', '10001', '10014', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10016', '10001', '10015', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10017', '10001', '10016', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10018', '10002', '10017', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10020', '10002', '10018', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10021', '10002', '10019', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10022', '10002', '10020', null, null);
INSERT INTO `acl_roles_permissions` VALUES ('10023', '10002', '10021', null, null);

-- ----------------------------
-- Table structure for `acl_users_permissions`
-- ----------------------------
DROP TABLE IF EXISTS `acl_users_permissions`;
CREATE TABLE `acl_users_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `acl_users_permissions_user_id_index` (`user_id`),
  KEY `acl_users_permissions_permission_id_index` (`permission_id`),
  CONSTRAINT `acl_users_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `acl_permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `acl_users_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of acl_users_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for `acl_users_roles`
-- ----------------------------
DROP TABLE IF EXISTS `acl_users_roles`;
CREATE TABLE `acl_users_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `acl_users_roles_user_id_index` (`user_id`),
  KEY `acl_users_roles_role_id_index` (`role_id`),
  CONSTRAINT `acl_users_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `acl_roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `acl_users_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10008 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of acl_users_roles
-- ----------------------------
INSERT INTO `acl_users_roles` VALUES ('10001', '10001', '10001', null, null);
INSERT INTO `acl_users_roles` VALUES ('10002', '10001', '10002', null, null);
INSERT INTO `acl_users_roles` VALUES ('10003', '10001', '10004', '2024-06-18 14:59:01', '2024-06-18 14:59:01');
INSERT INTO `acl_users_roles` VALUES ('10005', '10001', '10005', '2024-06-18 14:59:01', '2024-06-18 14:59:01');

-- ----------------------------
-- Table structure for `failed_jobs`
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for `jobs`
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=10002 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of jobs
-- ----------------------------
INSERT INTO `jobs` VALUES ('10001', 'default', '{\"uuid\":\"68d954f4-51f9-4495-b1e1-c277c501517a\",\"displayName\":\"App\\\\Notifications\\\\VerifyEmailQueued\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:10001;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:35:\\\"App\\\\Notifications\\\\VerifyEmailQueued\\\":1:{s:2:\\\"id\\\";s:36:\\\"271c4c1b-91d3-4dec-bc5d-b350f753e203\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', '0', null, '1718298903', '1718298903');

-- ----------------------------
-- Table structure for `migrations`
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('1', '2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2', '2014_10_12_100000_create_password_reset_tokens_table', '1');
INSERT INTO `migrations` VALUES ('3', '2014_10_12_200000_add_two_factor_columns_to_users_table', '1');
INSERT INTO `migrations` VALUES ('4', '2019_08_19_000000_create_failed_jobs_table', '1');
INSERT INTO `migrations` VALUES ('5', '2019_12_14_000001_create_personal_access_tokens_table', '1');
INSERT INTO `migrations` VALUES ('6', '2020_05_21_100000_create_teams_table', '1');
INSERT INTO `migrations` VALUES ('7', '2020_05_21_200000_create_team_user_table', '1');
INSERT INTO `migrations` VALUES ('8', '2020_05_21_300000_create_team_invitations_table', '1');
INSERT INTO `migrations` VALUES ('9', '2024_03_05_180312_create_sessions_table', '1');
INSERT INTO `migrations` VALUES ('10', '2024_03_05_205210_create_jobs_table', '1');
INSERT INTO `migrations` VALUES ('11', '2024_03_05_210806_create_website', '1');
INSERT INTO `migrations` VALUES ('12', '2024_03_05_222929_create_permissions', '1');
INSERT INTO `migrations` VALUES ('13', '2024_03_05_223622_create_roles', '1');
INSERT INTO `migrations` VALUES ('14', '2024_03_05_224519_create_role_permissions_table', '1');
INSERT INTO `migrations` VALUES ('15', '2024_03_05_224633_create_user_permissions_table', '1');
INSERT INTO `migrations` VALUES ('16', '2024_03_05_224725_create_user_roles_table', '1');
INSERT INTO `migrations` VALUES ('17', '2025_03_05_211110_create_finalize', '1');

-- ----------------------------
-- Table structure for `password_reset_tokens`
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for `personal_access_tokens`
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for `sessions`
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('BA6HPcqwOCHPvLX8CYxXeGIvmCBDiPdTUSAR6XAl', '10001', '192.168.56.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiNndLa0JVOXc5VmtVTklhNGpvMldFdTY1NHh5RUhVNVV1VjdJU3pobyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI5OiJodHRwczovL29tcy5vY3IubGFuL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEwMDAxO3M6MjE6InBhc3N3b3JkX2hhc2hfc2FuY3R1bSI7czo2MDoiJDJ5JDEyJEI2SXJHY0J3ejlZcXN1aXFQT0lDWHVvV2I5U0llTTBiNldueDN0VzhVb24veXJ0Y3VmTXUyIjt9', '1719011646');
INSERT INTO `sessions` VALUES ('VpzTfT6nTknKXGL73gkWE47O5o8ZzeB2HmWiPQvu', '10001', '192.168.56.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiQ2p2WlJ1VTZFVGg5UzhGc2pwczRiRXNWRGJMZFJ2aHdsYXN4cjc2ZyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI5OiJodHRwczovL29tcy5vY3IubGFuL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEwMDAxO3M6MjE6InBhc3N3b3JkX2hhc2hfc2FuY3R1bSI7czo2MDoiJDJ5JDEyJEI2SXJHY0J3ejlZcXN1aXFQT0lDWHVvV2I5U0llTTBiNldueDN0VzhVb24veXJ0Y3VmTXUyIjt9', '1718991533');
INSERT INTO `sessions` VALUES ('zl4900tMrR0ddxXqLujUCwPWGsr65Ja3EB6VDlHS', '10001', '192.168.56.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiN1hLelhBQWR3a2o2NUp4UHJwZ0wwbmQwUmdjTnRtWURGZWxvT3kwUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vb21zLm9jci5sYW4vZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTAwMDE7czoyMToicGFzc3dvcmRfaGFzaF9zYW5jdHVtIjtzOjYwOiIkMnkkMTIkQjZJckdjQnd6OVlxc3VpcVBPSUNYdW9XYjlTSWVNMGI2V254M3RXOFVvbi95cnRjdWZNdTIiO30=', '1719071778');

-- ----------------------------
-- Table structure for `team_invitations`
-- ----------------------------
DROP TABLE IF EXISTS `team_invitations`;
CREATE TABLE `team_invitations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_invitations_team_id_email_unique` (`team_id`,`email`),
  CONSTRAINT `team_invitations_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of team_invitations
-- ----------------------------

-- ----------------------------
-- Table structure for `team_user`
-- ----------------------------
DROP TABLE IF EXISTS `team_user`;
CREATE TABLE `team_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_user_team_id_user_id_unique` (`team_id`,`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of team_user
-- ----------------------------

-- ----------------------------
-- Table structure for `teams`
-- ----------------------------
DROP TABLE IF EXISTS `teams`;
CREATE TABLE `teams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `personal_team` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teams_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10002 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of teams
-- ----------------------------
INSERT INTO `teams` VALUES ('10001', '10001', 'Sol\'s Team', '1', '2024-06-13 19:15:03', '2024-06-13 19:15:03');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `api_token` varchar(255) DEFAULT NULL,
  `html_theme` varchar(255) DEFAULT NULL,
  `grid_items_per_page` int(11) NOT NULL DEFAULT 30,
  `show_welcome_screen` tinyint(1) NOT NULL DEFAULT 0,
  `theme_logo` varchar(255) DEFAULT NULL,
  `theme_color_primary` varchar(255) DEFAULT NULL,
  `theme_color_secondary` varchar(255) DEFAULT NULL,
  `theme_color_header` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10002 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('10001', null, 'Sol Rou', 'sollie11@gmail.com', '2024-06-13 19:15:03', '$2y$12$B6IrGcBwz9YqsuiqPOICXuoWb9SIeM0b6Wnx3tW8Uon/yrtcufMu2', null, null, null, null, '10001', null, null, null, '30', '0', null, '#3579AC', '#123456', '#654321', '2024-06-13 19:15:03', '2024-06-18 14:59:01');

-- ----------------------------
-- Table structure for `website`
-- ----------------------------
DROP TABLE IF EXISTS `website`;
CREATE TABLE `website` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `language` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10003 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of website
-- ----------------------------
INSERT INTO `website` VALUES ('10001', 'en', 'header', 'text', '{\n    \"topbluemenu1\": \"Human Risk Management\",\n    \"topbluemenu2\": \"Cyber Security Training\",\n    \"login\": \"Log in\",\n    \"register\": \"Register\",\n    \"dashboard\": \"Dashboard\",\n    \"home\": \"Home\",\n    \"introvideos\": \"Intro Videos\",\n    \"aboutus\": \"About\",\n    \"contactus\": \"Contact\",\n    \"ourservices\": \"Services\",\n    \"ourproducts\": \"Products\",\n    \"cyberawarenesstraining\": \"Cyber Awareness Training\",\n    \"darkwebrecognisance\": \"Dark Web Recognisance\",\n    \"simulatedphishingattacks\": \"Simulated Phishing Attacks\",\n    \"policymanagementsuite\": \"Policy Management Suite\",\n    \"humanriskreporting\": \"Human Risk Reporting\"\n}', null, null);
INSERT INTO `website` VALUES ('10002', 'en', 'auth', 'text', '{\r\n   \"li_heading\": \"Log in Account\",\r\n   \"li_copy\": \"Enter your credentials\",\r\n   \"li_rememberme\": \"Remember me\",\r\n    \"li_forgotpassword\": \"Forgot Password\",\r\n    \"li_button\": \"Login\",\r\n    \"li_email\": \"Email\",\r\n    \"li_password\": \"Password\",\r\n    \"failed\": \"These credentials do not match our records.\",\r\n    \"password\": \"The provided password is incorrect.\",\r\n    \"throttle\": \"Too many login attempts. Please try again in :seconds seconds.\",\r\n    \"somethingwentwrong\": \"Something went wrong\",\r\n   \"rg_heading\": \"Register Account\",\r\n   \"rg_copy\": \"Please supply these details to create an account.\",\r\n    \"rg_name\": \"Name\",\r\n    \"rg_email\": \"Email\",\r\n    \"rg_password\": \"Password\",\r\n    \"rg_confirmpassword\": \"Confrim Password\",\r\n    \"rg_iagreetothe\": \"I agree to the\",\r\n    \"rg_and\": \" and \",\r\n    \"rg_terms\": \"Terms of Service\",\r\n    \"rg_policy\": \"Privacy Policy\",\r\n    \"rg_backtologin\": \"Back to Login\",\r\n    \"rg_button\": \"Register Account\",\r\n    \"fp_heading\": \"Forgot Password\",\r\n    \"fp_copy\": \"We\'ll email a password reset link that will allow you to choose a new one.\",\r\n    \"fp_email\": \"Email\",\r\n    \"fp_backtologin\": \"Back to Login\",\r\n    \"fp_button\": \"Forgot Password\",\r\n    \"ve_heading\": \"Verify Email Address\",\r\n    \"ve_copy\": \"Congratulations! You are registered. Please use the verification link in your email to progress.\",\r\n    \"ve_resendlink\": \"Resend Link\",\r\n    \"ve_logout\": \"Log Out\"\r\n    \r\n}', null, null);
