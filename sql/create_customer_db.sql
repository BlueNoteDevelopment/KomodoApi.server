CREATE DATABASE `customer1_komodo_db` /*!40100 DEFAULT CHARACTER SET utf8 */;
CREATE TABLE `migration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DELETE FROM mysql.user WHERE Host='localhost' AND User='customer1_root';
GRANT ALL PRIVILEGES ON customer1_komodo_db.* TO 'customer1_root'@'localhost' IDENTIFIED BY 'customer1_1@2#API';
FLUSH PRIVILEGES;