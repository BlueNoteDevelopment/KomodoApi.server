CREATE DATABASE `customer1_komodo_db` /*!40100 DEFAULT CHARACTER SET utf8 */;
CREATE TABLE `migration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DELETE FROM mysql.user WHERE Host='localhost' AND User='customer1_root';
GRANT ALL PRIVILEGES ON customer1_komodo_db.* TO 'customer1_root'@'localhost' IDENTIFIED BY 'customer1_1@2#API';
FLUSH PRIVILEGES;


--First User
/*
INSERT INTO user_account (user_account_name,encrypted_password,is_active,user_token_guid,created_datetime)
	VALUES('test','$2y$10$71eaOBA9wHKZjmVIhAmij.uTgc2kIhNb8MnyhzzXlXTaDWJc5ZE/q',1,UUID(),now())
*/