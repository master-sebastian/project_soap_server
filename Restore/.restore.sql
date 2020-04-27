
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(10) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `num_doc` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comandas`
--

CREATE TABLE `comandas` (
  `id` int(11) NOT NULL,
  `precio` float(10,2) NOT NULL,
  `estado` int(1) NOT NULL,
  `iva` float(3,2) NOT NULL,
  `fecha_y_hora` datetime NOT NULL,
  `id_mesa` int(2) NOT NULL,
  `id_soporte` int(2) DEFAULT NULL,
  `productos_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(2) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `url_img` text NOT NULL,
  `estado` int(1) NOT NULL,
  `fecha_y_hora` varchar(45) NOT NULL,
  `costo` float(12,2) UNSIGNED ZEROFILL NOT NULL,
  `iva` float(4,2) UNSIGNED ZEROFILL NOT NULL,
  `ganancia` float(10,2) UNSIGNED ZEROFILL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(2) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rolesxservice`
--

CREATE TABLE `rolesxservice` (
  `id_rol` int(2) NOT NULL,
  `id_servicio` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(10) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `descripcion` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soportes`
--

CREATE TABLE `soportes` (
  `id` int(11) NOT NULL,
  `total_a_pagar` float(20,2) NOT NULL,
  `fecha_y_hora` datetime NOT NULL,
  `usuarios_id` int(4) NOT NULL,
  `clientes_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(4) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `token` text NOT NULL,
  `id_rol` int(2) DEFAULT NULL,
  `clientes_id` int(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comandas`
--
ALTER TABLE `comandas`
  ADD PRIMARY KEY (`id`,`productos_id`),
  ADD KEY `fk_comandas_mesas1` (`id_mesa`),
  ADD KEY `fk_comandas_soporte1` (`id_soporte`),
  ADD KEY `fk_comandas_productos1` (`productos_id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rolesxservice`
--
ALTER TABLE `rolesxservice`
  ADD PRIMARY KEY (`id_rol`,`id_servicio`),
  ADD KEY `fk_rolesxservice_servicios1` (`id_servicio`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `soportes`
--
ALTER TABLE `soportes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_soporte_usuarios1` (`usuarios_id`),
  ADD KEY `fk_soporte_clientes1` (`clientes_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuarios_roles` (`id_rol`),
  ADD KEY `fk_usuarios_clientes1` (`clientes_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comandas`
--
ALTER TABLE `comandas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `soportes`
--
ALTER TABLE `soportes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comandas`
--
ALTER TABLE `comandas`
  ADD CONSTRAINT `fk_comandas_mesas1` FOREIGN KEY (`id_mesa`) REFERENCES `mesas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comandas_productos1` FOREIGN KEY (`productos_id`) REFERENCES `productos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comandas_soporte1` FOREIGN KEY (`id_soporte`) REFERENCES `soportes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rolesxservice`
--
ALTER TABLE `rolesxservice`
  ADD CONSTRAINT `fk_rolesxservice_roles1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rolesxservice_servicios1` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `soportes`
--
ALTER TABLE `soportes`
  ADD CONSTRAINT `fk_soporte_clientes1` FOREIGN KEY (`clientes_id`) REFERENCES `clientes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_soporte_usuarios1` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_clientes1` FOREIGN KEY (`clientes_id`) REFERENCES `clientes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuarios_roles` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;


ALTER TABLE `comandas` ADD `nombre` VARCHAR(100) NOT NULL AFTER `productos_id`, ADD `url_img` TEXT NOT NULL AFTER `nombre`;

ALTER TABLE `soportes` CHANGE `clientes_id` `clientes_id` INT(10) NULL;


CREATE VIEW resumen AS (select me.id, me.nombre, co1.precio, co1.fecha_y_hora, 'd' as 'tipo' from  mesas as me, comandas as co1 where co1.id_soporte is null and co1.id_mesa = me.id
union
select me.id, me.nombre, co1.precio, co1.fecha_y_hora, 'r' as 'tipo' from  mesas as me, comandas as co1 where not co1.id_soporte is null and co1.id_mesa = me.id)