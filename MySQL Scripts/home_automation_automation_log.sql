CREATE DATABASE  IF NOT EXISTS `home_automation`;
USE `home_automation`;

DROP TABLE IF EXISTS `automation_log`;
CREATE TABLE `automation_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_service` varchar(45) NOT NULL,
  `event_name` varchar(45) NOT NULL,
  `event_caller` varchar(45) DEFAULT NULL,
  `event_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` varchar(45) DEFAULT NULL,
  `notes2` varchar(45) DEFAULT NULL,
  `notes3` varchar(80) DEFAULT NULL,
  `return_value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
