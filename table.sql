CREATE DATABASE `php`;
USE `php`;
CREATE TABLE `properties`
(
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`       VARCHAR(255) NULL DEFAULT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `created_at`  TIMESTAMP NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) COLLATE='utf8mb3_general_ci';

CREATE TABLE `informations`
(
    `uuid`             CHAR(50) NOT NULL,
    `property_type_id` INT UNSIGNED NULL DEFAULT NULL,
    `county`           CHAR(100) NULL DEFAULT NULL,
    `country`          CHAR(100) NULL DEFAULT NULL,
    `town`             CHAR(50) NULL DEFAULT NULL,
    `description`      TEXT NULL DEFAULT NULL,
    `address`          VARCHAR(255) NULL DEFAULT NULL,
    `image_full`       VARCHAR(50) NULL DEFAULT NULL,
    `image_thumbnail`  VARCHAR(50) NULL DEFAULT NULL,
    `latitude`         VARCHAR(50) NULL DEFAULT NULL,
    `longitude`        VARCHAR(50) NULL DEFAULT NULL,
    `num_bedrooms`     TINYINT NULL DEFAULT NULL,
    `num_bathrooms`    TINYINT NULL DEFAULT NULL,
    `price`            INT NULL DEFAULT NULL,
    `type`             VARCHAR(50) NULL DEFAULT NULL,
    `created_at`       TIMESTAMP NULL DEFAULT NULL,
    `updated_at`       TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`uuid`),
    INDEX              `property_type_id` (`property_type_id`),
    CONSTRAINT `FK__properties` FOREIGN KEY (`property_type_id`) REFERENCES `properties` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) COLLATE='utf8mb3_general_ci';
