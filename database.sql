CREATE DATABASE IF NOT EXISTS `users_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `users_db`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `firstname` VARCHAR(100) NOT NULL,
    `middlename` VARCHAR(100) DEFAULT NULL,
    `lastname` VARCHAR(100) NOT NULL,
    `username` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_username_unique` (`username`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `faculty` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `firstname` VARCHAR(100) NOT NULL,
    `middlename` VARCHAR(100) DEFAULT NULL,
    `lastname` VARCHAR(100) NOT NULL,
    `status` VARCHAR(50) NOT NULL DEFAULT 'Active',
    `position` VARCHAR(100) NOT NULL,
    `department` VARCHAR(100) NOT NULL,
    `contact_num` VARCHAR(30) NOT NULL,
    `gender` VARCHAR(20) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `employment_status` VARCHAR(50) NOT NULL DEFAULT 'Permanent',
    `specialization` VARCHAR(150) NOT NULL,
    `date_hired` DATE NOT NULL,
    `birthday` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
