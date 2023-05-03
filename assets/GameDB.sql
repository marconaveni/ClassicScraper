
-- -----------------------------------------------------
-- Schema GameDB
-- -----------------------------------------------------

create database if not exists `gamedb` default char set utf8mb4 default collate utf8mb4_general_ci;

use `gamedb`;


-- table developer
CREATE TABLE IF NOT EXISTS `developer` (
    `id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(100) not NULL,
    PRIMARY KEY (`id`)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `publisher` (
    `id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(100) not NULL,
    PRIMARY KEY (`id`)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `genre` (
    `id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(100) not NULL,
    PRIMARY KEY (`id`)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS `plataform` (
    `id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(100) not NULL,
    `alias` VARCHAR(100) not NULL,
    PRIMARY KEY (`id`)
)  ENGINE=INNODB;


CREATE TABLE IF NOT EXISTS `game` (
    `id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(100) NOT NULL,
    `description` TEXT(1300) NULL,
    `developers` INT UNSIGNED NULL,
    `publishers` INT UNSIGNED NULL,
    `releasedate` DATE NULL,
    `players` TINYINT(2) NULL,
    `genres` INT UNSIGNED NULL,
    `cover` VARCHAR(150) NULL,
    `screenshot` VARCHAR(150) NULL,
    `plataforms` INT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`developers`)
        REFERENCES `developer` (`id`),
    FOREIGN KEY (`publishers`)
        REFERENCES `publisher` (`id`),
    FOREIGN KEY (`genres`)
        REFERENCES `genre` (`id`),
    FOREIGN KEY (`plataforms`)
        REFERENCES `plataform` (`id`)
)  ENGINE=INNODB;

