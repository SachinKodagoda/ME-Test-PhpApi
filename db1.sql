DROP DATABASE IF EXISTS db1;
CREATE DATABASE db1;

USE db1;
CREATE TABLE `user`(
	`user_email` VARCHAR(50) NOT NULL,
    `user_name`  VARCHAR(100) NOT NULL,
    `user_contact` VARCHAR(12) NOT NULL,
    `user_password` VARCHAR(100) NOT NULL,
    `user_url` VARCHAR(256) NOT NULL,
    `user_created` DATETIME DEFAULT NOW(),
    `user_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_email`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
