-- ---
-- Globals
-- ---

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET FOREIGN_KEY_CHECKS=0;

-- ---
-- Table 'user'
-- 
-- ---

DROP TABLE IF EXISTS `user`;
		
CREATE TABLE `user` (
  `id_user` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `login` VARCHAR(40) NULL DEFAULT NULL,
  `password` VARCHAR NULL DEFAULT NULL,
  PRIMARY KEY (`id_user`)
);

-- ---
-- Table 'world'
-- 
-- ---

DROP TABLE IF EXISTS `world`;
		
CREATE TABLE `world` (
  `id_world` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `id_obj` INTEGER NULL DEFAULT NULL,
  `x0` INTEGER NULL DEFAULT NULL,
  `y0` INTEGER NULL DEFAULT NULL,
  `x1` INTEGER NULL DEFAULT NULL,
  `y1` INTEGER NULL DEFAULT NULL,
  `radius` INTEGER NULL DEFAULT NULL
);

-- ---
-- Table 'object'
-- 
-- ---

DROP TABLE IF EXISTS `object`;
		
CREATE TABLE `object` (
  `id_obj` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `speed` INTEGER NULL DEFAULT NULL,
  `color` VARCHAR NULL DEFAULT NULL,
  PRIMARY KEY (`id_obj`)
);

-- ---
-- Table 'server'
-- 
-- ---

DROP TABLE IF EXISTS `server`;
		
CREATE TABLE `server` (
  `ip` VARCHAR NULL AUTO_INCREMENT DEFAULT NULL,
  `port` INTEGER NULL DEFAULT NULL
);

-- ---
-- Table 'person'
-- 
-- ---

DROP TABLE IF EXISTS `person`;
		
CREATE TABLE `person` (
  `id_user` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `name` VARCHAR NULL DEFAULT NULL,
  `radius` INTEGER NULL DEFAULT NULL,
  PRIMARY KEY (`id_user`)
);

-- ---
-- Foreign Keys 
-- ---

ALTER TABLE `user` ADD FOREIGN KEY (id_user) REFERENCES `person` (`id_user`);
ALTER TABLE `world` ADD FOREIGN KEY (id_obj) REFERENCES `object` (`id_obj`);

-- ---
-- Table Properties
-- ---

-- ALTER TABLE `user` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `world` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `object` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `server` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `person` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Test Data
-- ---

-- INSERT INTO `user` (`id_user`,`login`,`password`) VALUES
-- ('','','');
-- INSERT INTO `world` (`id_world`,`id_obj`,`x0`,`y0`,`x1`,`y1`,`radius`) VALUES
-- ('','','','','','','');
-- INSERT INTO `object` (`id_obj`,`speed`,`color`) VALUES
-- ('','','');
-- INSERT INTO `server` (`ip`,`port`) VALUES
-- ('','');
-- INSERT INTO `person` (`id_user`,`name`,`radius`) VALUES
-- ('','','');