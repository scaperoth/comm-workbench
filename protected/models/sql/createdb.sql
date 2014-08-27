SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `pos` ;
CREATE SCHEMA IF NOT EXISTS `pos` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `pos` ;

-- -----------------------------------------------------
-- Table `pos`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`user` ;

CREATE TABLE IF NOT EXISTS `pos`.`user` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `f_name` VARCHAR(45) NULL,
  `l_name` VARCHAR(45) NULL,
  `username` VARCHAR(45) NULL,
  `pass` TEXT NULL,
  `active` INT NULL DEFAULT 1,
  PRIMARY KEY (`user_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`customer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`customer` ;

CREATE TABLE IF NOT EXISTS `pos`.`customer` (
  `cust_id` INT NOT NULL AUTO_INCREMENT,
  `reward_points` INT ZEROFILL NULL DEFAULT 0,
  PRIMARY KEY (`cust_id`),
  CONSTRAINT `customer`
    FOREIGN KEY (`cust_id`)
    REFERENCES `pos`.`user` (`user_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `pos`.`employee_role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`employee_role` ;

CREATE TABLE IF NOT EXISTS `pos`.`employee_role` (
  `role_id` INT NOT NULL AUTO_INCREMENT,
  `type` TEXT NOT NULL,
  `salary` DECIMAL(25) NULL,
  PRIMARY KEY (`role_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`employee`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`employee` ;

CREATE TABLE IF NOT EXISTS `pos`.`employee` (
  `emp_id` INT NOT NULL AUTO_INCREMENT,
  `role_id` INT NULL DEFAULT 0,
  PRIMARY KEY (`emp_id`),
  INDEX `role_idx` (`role_id` ASC),
  CONSTRAINT `employee`
    FOREIGN KEY (`emp_id`)
    REFERENCES `pos`.`user` (`user_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `role`
    FOREIGN KEY (`role_id`)
    REFERENCES `pos`.`employee_role` (`role_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`permissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`permissions` ;

CREATE TABLE IF NOT EXISTS `pos`.`permissions` (
  `permission_id` INT NOT NULL AUTO_INCREMENT,
  `permission_type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`permission_id`),
  UNIQUE INDEX `permission_type_UNIQUE` (`permission_type` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`item` ;

CREATE TABLE IF NOT EXISTS `pos`.`item` (
  `item_id` INT NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL,
  `price` DECIMAL(25) NULL,
  `release_date` DATE NULL,
  `sale_price` DECIMAL(25) NULL,
  PRIMARY KEY (`item_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`store`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`store` ;

CREATE TABLE IF NOT EXISTS `pos`.`store` (
  `store_id` INT NOT NULL AUTO_INCREMENT,
  `street_address` TEXT NOT NULL,
  `zip_code` VARCHAR(45) NOT NULL,
  `city` VARCHAR(45) NULL,
  `state` VARCHAR(45) NOT NULL,
  `size` VARCHAR(45) NULL,
  `num_emp` INT NULL,
  PRIMARY KEY (`store_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`warehouse`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`warehouse` ;

CREATE TABLE IF NOT EXISTS `pos`.`warehouse` (
  `warehouse_id` INT NOT NULL AUTO_INCREMENT,
  `street_address` TEXT NOT NULL,
  `zip_code` VARCHAR(45) NOT NULL,
  `city` VARCHAR(45) NULL,
  `state` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`warehouse_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`equipment_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`equipment_type` ;

CREATE TABLE IF NOT EXISTS `pos`.`equipment_type` (
  `equipment_type_id` INT NOT NULL,
  `type` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`equipment_type_id`),
  UNIQUE INDEX `type_UNIQUE` (`type` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`equiment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`equiment` ;

CREATE TABLE IF NOT EXISTS `pos`.`equiment` (
  `equip_id` INT NOT NULL AUTO_INCREMENT,
  `type_id` INT NULL,
  `name` TEXT NOT NULL,
  `price_per_unit` DOUBLE NULL,
  `quantity` VARCHAR(45) NULL,
  PRIMARY KEY (`equip_id`),
  INDEX `equipment_type_idx` (`type_id` ASC),
  CONSTRAINT `equipment_type`
    FOREIGN KEY (`type_id`)
    REFERENCES `pos`.`equipment_type` (`equipment_type_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`distributor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`distributor` ;

CREATE TABLE IF NOT EXISTS `pos`.`distributor` (
  `dist_id` INT NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL,
  `street_address` TEXT NULL,
  `zip_code` VARCHAR(45) NULL,
  `city` VARCHAR(45) NULL,
  `state` VARCHAR(45) NULL,
  PRIMARY KEY (`dist_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`manufacturer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`manufacturer` ;

CREATE TABLE IF NOT EXISTS `pos`.`manufacturer` (
  `manu_id` INT NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL,
  `street_address` TEXT NULL,
  `zip_code` VARCHAR(45) NULL,
  `city` VARCHAR(45) NULL,
  `state` VARCHAR(45) NULL,
  PRIMARY KEY (`manu_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`pre_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`pre_order` ;

CREATE TABLE IF NOT EXISTS `pos`.`pre_order` (
  `preorder_cust_id` INT NOT NULL,
  `preorder_item_id` INT NOT NULL,
  PRIMARY KEY (`preorder_cust_id`, `preorder_item_id`),
  INDEX `product_idx` (`preorder_item_id` ASC),
  CONSTRAINT `preorder_customer`
    FOREIGN KEY (`preorder_cust_id`)
    REFERENCES `pos`.`customer` (`cust_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `preorder_product`
    FOREIGN KEY (`preorder_item_id`)
    REFERENCES `pos`.`item` (`item_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`works`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`works` ;

CREATE TABLE IF NOT EXISTS `pos`.`works` (
  `store_emp_id` INT NULL,
  `employee_store_id` INT NULL,
  PRIMARY KEY (`store_emp_id`, `employee_store_id`),
  INDEX `store_idx` (`employee_store_id` ASC),
  CONSTRAINT `store_employee`
    FOREIGN KEY (`store_emp_id`)
    REFERENCES `pos`.`employee` (`emp_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `employee_store`
    FOREIGN KEY (`employee_store_id`)
    REFERENCES `pos`.`store` (`store_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`manages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`manages` ;

CREATE TABLE IF NOT EXISTS `pos`.`manages` (
  `store_mgr_id` INT NOT NULL,
  `manager_store_id` INT NOT NULL,
  PRIMARY KEY (`store_mgr_id`, `manager_store_id`),
  INDEX `store_idx` (`manager_store_id` ASC),
  CONSTRAINT `managers_store`
    FOREIGN KEY (`manager_store_id`)
    REFERENCES `pos`.`store` (`store_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `store_manager`
    FOREIGN KEY (`store_mgr_id`)
    REFERENCES `pos`.`employee` (`emp_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`store_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`store_item` ;

CREATE TABLE IF NOT EXISTS `pos`.`store_item` (
  `store_item_id` INT NOT NULL,
  `item_store_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`store_item_id`, `item_store_id`),
  INDEX `store_idx` (`item_store_id` ASC),
  CONSTRAINT `store_item`
    FOREIGN KEY (`store_item_id`)
    REFERENCES `pos`.`item` (`item_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `items_store`
    FOREIGN KEY (`item_store_id`)
    REFERENCES `pos`.`store` (`store_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `pos`.`store_warehouse`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`store_warehouse` ;

CREATE TABLE IF NOT EXISTS `pos`.`store_warehouse` (
  `warehouse_store_id` INT NOT NULL,
  `store_warehouse_id` INT NOT NULL,
  PRIMARY KEY (`warehouse_store_id`, `store_warehouse_id`),
  INDEX `warehouse_idx` (`store_warehouse_id` ASC),
  CONSTRAINT `store_warehouse`
    FOREIGN KEY (`store_warehouse_id`)
    REFERENCES `pos`.`warehouse` (`warehouse_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `warehouse_store`
    FOREIGN KEY (`warehouse_store_id`)
    REFERENCES `pos`.`store` (`store_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`store_equipment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`store_equipment` ;

CREATE TABLE IF NOT EXISTS `pos`.`store_equipment` (
  `store_equip_id` INT NOT NULL,
  `equipment_store_id` INT NOT NULL,
  PRIMARY KEY (`store_equip_id`, `equipment_store_id`),
  INDEX `store_idx` (`equipment_store_id` ASC),
  CONSTRAINT `store_equipment`
    FOREIGN KEY (`store_equip_id`)
    REFERENCES `pos`.`equiment` (`equip_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `equipments_store`
    FOREIGN KEY (`equipment_store_id`)
    REFERENCES `pos`.`store` (`store_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`produces`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`produces` ;

CREATE TABLE IF NOT EXISTS `pos`.`produces` (
  `item_manufacturer_id` INT NULL,
  `item_distributor_id` INT NULL,
  `production_item_id` INT NOT NULL,
  PRIMARY KEY (`item_manufacturer_id`, `production_item_id`, `item_distributor_id`),
  INDEX `product_idx` (`production_item_id` ASC),
  INDEX `distributor_idx` (`item_distributor_id` ASC),
  CONSTRAINT `item_manufacturer`
    FOREIGN KEY (`item_manufacturer_id`)
    REFERENCES `pos`.`manufacturer` (`manu_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `item_distributor`
    FOREIGN KEY (`item_distributor_id`)
    REFERENCES `pos`.`distributor` (`dist_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `production_item`
    FOREIGN KEY (`production_item_id`)
    REFERENCES `pos`.`item` (`item_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`has_permissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`has_permissions` ;

CREATE TABLE IF NOT EXISTS `pos`.`has_permissions` (
  `usr_id` INT NULL,
  `permission_id` INT NOT NULL,
  PRIMARY KEY (`permission_id`, `usr_id`),
  INDEX `permission_idx` (`permission_id` ASC),
  INDEX `user_idx` (`usr_id` ASC),
  CONSTRAINT `permission`
    FOREIGN KEY (`permission_id`)
    REFERENCES `pos`.`permissions` (`permission_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `user`
    FOREIGN KEY (`usr_id`)
    REFERENCES `pos`.`user` (`user_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pos`.`sale`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pos`.`sale` ;

CREATE TABLE IF NOT EXISTS `pos`.`sale` (
  `sale_cust_id` INT NOT NULL,
  `sale_item_id` INT NOT NULL,
  `sale_store_id` INT NOT NULL,
  `sale_emp_id` INT NULL,
  PRIMARY KEY (`sale_cust_id`, `sale_item_id`, `sale_store_id`),
  INDEX `product_idx` (`sale_item_id` ASC),
  INDEX `store_idx` (`sale_store_id` ASC),
  INDEX `salesperson_idx` (`sale_emp_id` ASC),
  CONSTRAINT `sale_customer`
    FOREIGN KEY (`sale_cust_id`)
    REFERENCES `pos`.`customer` (`cust_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `sale_product`
    FOREIGN KEY (`sale_item_id`)
    REFERENCES `pos`.`item` (`item_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `sale_store`
    FOREIGN KEY (`sale_store_id`)
    REFERENCES `pos`.`store` (`store_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `sale_salesperson`
    FOREIGN KEY (`sale_emp_id`)
    REFERENCES `pos`.`employee` (`emp_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

CREATE TRIGGER userpermission 
after INSERT ON  user
FOR EACH ROW
INSERT INTO has_permissions VALUES (new.user_id, '1');