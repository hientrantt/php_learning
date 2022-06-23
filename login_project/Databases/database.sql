create table php_learning.user(
  name varchar(127),
  phone varchar(15),
  email varchar(31),
  password varchar(31),
  status int,
  isAdmin boolean
);

ALTER TABLE `php_learning`.`user` 
ADD COLUMN `id` INT NOT NULL AUTO_INCREMENT AFTER `isAdmin`,
ADD PRIMARY KEY (`id`);
;

ALTER TABLE `php_learning`.`user` 
CHANGE COLUMN `id` `id` INT NOT NULL AUTO_INCREMENT FIRST;

INSERT INTO `php_learning`.`user` (`name`, `phone`, `email`, `password`, `status`, `isAdmin`) VALUES ('hien tran', '0966102030', 'hientrantt211@gmail.com', '123Abc@#$', '1', '1');

INSERT INTO `php_learning`.`user` (`name`, `phone`, `email`, `password`, `status`, `isAdmin`) VALUES ('hi', '096', 'hi@gmail.com', '123', 1, true);
UPDATE `php_learning`.`user` SET `isAdmin` = false WHERE (`id` = '2');

INSERT INTO `php_learning`.`user` (`name`, `phone`, `email`, `password`, `status`, `isAdmin`) VALUES ('abc', '097', 'abc@gmail.com', 'abc', 0, false);

SELECT * FROM php_learning.user;


-- http://localhost/php_learning/login_project/Components/
-- 0: block
-- 1: active