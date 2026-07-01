/* 
 * ======================================================
 *                    DATABASE YARATISH                  
 * ======================================================
 */

-- 1. Avvalgi mavjud database bo‘lsa, uni o‘chirish
DROP DATABASE IF EXISTS `auth_db`;

-- 2. Yangi database yaratish
CREATE DATABASE `auth_db`;

-- 3. Databasa tanlash
USE `auth_db`;

-- 4. Foydalanuvchilar jadvali yaratish
CREATE TABLE `users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. Test foydalanuvchi qo‘shish
INSERT INTO `users` (`name`, `email`, `password`) VALUES
(
    'Marjona',
    'emarjona747@gmail.com',
    '$2y$10$vzdlXgvQgb0Y0i2BBVOape64X3Ffo2ghMWnprXkuTyB77orLspfW.'
)

