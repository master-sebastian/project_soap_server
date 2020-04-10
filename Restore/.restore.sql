
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Table `clientes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `clientes` ;

CREATE TABLE IF NOT EXISTS `clientes` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(200) NOT NULL,
  `num_doc` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mesas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mesas` ;

CREATE TABLE IF NOT EXISTS `mesas` (
  `id` INT(2) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `esta_ocupada` INT(2) NOT NULL,
  `token` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `productos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `productos` ;

CREATE TABLE IF NOT EXISTS `productos` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` VARCHAR(45) NOT NULL,
  `url_img` TEXT NOT NULL,
  `estado` INT(1) NOT NULL,
  `fecha_y_hora` VARCHAR(45) NOT NULL,
  `costo` FLOAT(12,2) NOT NULL,
  `iva` FLOAT(3,2) NOT NULL,
  `precio` FLOAT(10,2) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roles` ;

CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT(2) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `servicios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `servicios` ;

CREATE TABLE IF NOT EXISTS `servicios` (
  `id` INT(10) NOT NULL,
  `alias` VARCHAR(50) NOT NULL,
  `descripcion` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `rolesxservice`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `rolesxservice` ;

CREATE TABLE IF NOT EXISTS `rolesxservice` (
  `id_rol` INT(2) NOT NULL,
  `id_servicio` INT(10) NOT NULL,
  PRIMARY KEY (`id_rol`, `id_servicio`),
  CONSTRAINT `fk_rolesxservice_roles1`
    FOREIGN KEY (`id_rol`)
    REFERENCES `roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rolesxservice_servicios1`
    FOREIGN KEY (`id_servicio`)
    REFERENCES `servicios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usuarios` ;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT(4) NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `clave` VARCHAR(200) NOT NULL,
  `token` TEXT NOT NULL,
  `id_rol` INT(2) NOT NULL,
  `clientes_id` INT(10) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_usuarios_roles`
    FOREIGN KEY (`id_rol`)
    REFERENCES `roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_clientes1`
    FOREIGN KEY (`clientes_id`)
    REFERENCES `clientes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `soportes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `soportes` ;

CREATE TABLE IF NOT EXISTS `soportes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `total_a_pagar` FLOAT(20,2) NOT NULL,
  `fecha_y_hora` DATETIME NOT NULL,
  `usuarios_id` INT(4) NOT NULL,
  `clientes_id` INT(10) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_soporte_usuarios1`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_soporte_clientes1`
    FOREIGN KEY (`clientes_id`)
    REFERENCES `clientes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `comandas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `comandas` ;

CREATE TABLE IF NOT EXISTS `comandas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ganancia` FLOAT(10,2) NOT NULL,
  `estado` INT(1) NOT NULL,
  `iva` FLOAT(3,2) NOT NULL,
  `fecha_y_hora` DATETIME NOT NULL,
  `id_mesa` INT(2) NOT NULL,
  `id_soporte` INT(2) NULL,
  `productos_id` INT(10) NOT NULL,
  PRIMARY KEY (`id`, `productos_id`),
  CONSTRAINT `fk_comandas_mesas1`
    FOREIGN KEY (`id_mesa`)
    REFERENCES `mesas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comandas_soporte1`
    FOREIGN KEY (`id_soporte`)
    REFERENCES `soportes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comandas_productos1`
    FOREIGN KEY (`productos_id`)
    REFERENCES `productos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

ALTER TABLE `productos` 
  CHANGE `costo` `costo` FLOAT(12,2) UNSIGNED ZEROFILL NOT NULL, 
  CHANGE `iva` `iva` FLOAT(4,2) UNSIGNED ZEROFILL NOT NULL, 
  CHANGE `ganancia` `ganancia` FLOAT(10,2) UNSIGNED ZEROFILL NOT NULL;

ALTER TABLE `mesas`
  DROP `esta_ocupada`;