-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-02-2026 a las 03:10:09
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ipsp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `afiliados`
--

CREATE TABLE `afiliados` (
  `ID` int(11) NOT NULL,
  `cedula` int(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `afiliados`
--

INSERT INTO `afiliados` (`ID`, `cedula`, `created_at`, `updated_at`) VALUES
(40, 3830917, '2025-05-15 09:24:16', '2025-05-15 09:24:16'),
(41, 1539483, '2025-05-15 09:25:50', '2025-05-15 09:25:50'),
(42, 3732443, '2025-05-15 09:27:08', '2025-05-15 09:27:08'),
(43, 9443043, '2025-05-15 09:30:46', '2025-05-15 09:30:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `beneficiarios`
--

CREATE TABLE `beneficiarios` (
  `ID` int(11) NOT NULL,
  `cedula` int(20) NOT NULL,
  `cedula_afil` int(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `beneficiarios`
--

INSERT INTO `beneficiarios` (`ID`, `cedula`, `cedula_afil`, `created_at`, `updated_at`) VALUES
(14, 59540, 42, '2025-05-15 09:31:39', '2025-05-15 09:31:39'),
(15, 403449, 40, '2025-05-15 09:32:29', '2025-05-15 09:32:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `idbitacora` int(11) NOT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `accion` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `fecha_cita` datetime NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id_cita`, `id_especialidad`, `fecha_cita`, `descripcion`, `created_at`, `updated_at`) VALUES
(20, 5, '2025-04-16 09:14:00', 'consulta', '2025-05-15 13:14:54', '2025-05-15 13:14:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas_afil`
--

CREATE TABLE `citas_afil` (
  `id_citas_afil` int(11) NOT NULL,
  `idcita` int(11) NOT NULL,
  `id_afiliado` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas_benef`
--

CREATE TABLE `citas_benef` (
  `id_citas_benef` int(11) NOT NULL,
  `idcita` int(11) NOT NULL,
  `id_beneficiario` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas_benef`
--

INSERT INTO `citas_benef` (`id_citas_benef`, `idcita`, `id_beneficiario`, `updated_at`, `created_at`) VALUES
(6, 20, 14, '2025-05-15 13:14:54', '2025-05-15 13:14:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id_especialidad` int(11) NOT NULL,
  `nombre_especialidad` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id_especialidad`, `nombre_especialidad`) VALUES
(1, 'Ginecología'),
(2, 'Medicina interna'),
(3, 'Odontología'),
(4, 'Oftamología'),
(5, 'Gastroenterología'),
(6, 'Imagenología');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `cedula` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `fechanacimiento` date NOT NULL,
  `genero` enum('Masculino','Femenino','','') NOT NULL,
  `telefono` int(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `ocupacion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`cedula`, `nombre`, `apellido`, `fechanacimiento`, `genero`, `telefono`, `correo`, `ocupacion`) VALUES
(1, 'JUAN', 'NUÑEZ ', '2025-04-07', 'Masculino', 2147483647, 'WDRIIWEM@GMAIL.COM', 'SI '),
(23293, 'Noel', 'Rousseau', '2025-06-03', 'Masculino', 2147483647, 'noel@gmail.com', 'florista'),
(59540, 'Rosa', 'Perez', '2025-05-21', 'Femenino', 434433223, 'rosa@gmail.com', 'Abogada'),
(403449, 'Mery', 'Rojas', '2025-05-13', 'Femenino', 55454522, 'mery@gmail.com', 'Profesora'),
(1539483, 'Maria ', 'De los Angeles', '2025-05-26', 'Femenino', 33322, 'maria@gmail.com', 'Contadora'),
(3732443, 'juan', 'perez', '2025-05-19', 'Masculino', 2147483647, 'juan@gmail.com', 'Medico'),
(3830917, 'Orlando ', 'Garcia', '1954-11-13', 'Masculino', 2147483647, 'orlandogarcia13@gmail.com', 'Ingeniero'),
(8012649, 'ZULEYMA', 'NUÑEZ', '2025-05-21', 'Femenino', 2147483647, 'zuleyma_nunez@gmail.com', 'Odontologo'),
(9443043, 'Mariela', 'Rodriguez', '2025-05-14', 'Femenino', 5545454, 'mariela@gmail.com', 'Profesora');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_seguridad`
--

CREATE TABLE `preguntas_seguridad` (
  `ID` int(11) NOT NULL,
  `pregunta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas_seguridad`
--

INSERT INTO `preguntas_seguridad` (`ID`, `pregunta`) VALUES
(1, '¿En qué ciudad naciste?'),
(2, '¿Cuál es el nombre de tu escuela primaria?'),
(3, '¿Cuál es tu color favorito?'),
(4, '¿Cuál es tu animal favorito?'),
(5, '¿Cuál es el nombre de tu padre o madre?');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_seguridad`
--

CREATE TABLE `respuestas_seguridad` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `pregunta_seguridad_id` int(11) NOT NULL,
  `respuesta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuestas_seguridad`
--

INSERT INTO `respuestas_seguridad` (`id`, `usuario_id`, `pregunta_seguridad_id`, `respuesta`) VALUES
(31, 24, 3, 'morado'),
(32, 24, 4, 'nutria'),
(33, 25, 1, 'Merida'),
(34, 25, 3, 'azul'),
(35, 26, 1, 'mérida'),
(36, 26, 5, 'zuleyma');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `ID` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`ID`, `Nombre`) VALUES
(1, 'administrador'),
(2, 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `role_id`) VALUES
(24, 'Gabriela Garcia', '$2y$10$NH.gm1O8LB7E8Eg5AhtUM.YqJ9nMeW2c5k16CIhjyEpVtAu5UFpEW', 1),
(25, 'user', '$2y$10$/RUoBwTznlBAZpksmAcMbuakQUCzdTjq2SdnMLDmWexN2F/eRI5be', 2),
(26, 'gabi', '$2y$10$Am4tuyAquT5qL/NZ4K45cu348mdS8UoIlNVe0XUQg4PlKW51gFAWW', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `afiliados`
--
ALTER TABLE `afiliados`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indices de la tabla `beneficiarios`
--
ALTER TABLE `beneficiarios`
  ADD PRIMARY KEY (`cedula`),
  ADD UNIQUE KEY `Unico` (`ID`),
  ADD KEY `dependencia` (`cedula_afil`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`idbitacora`),
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `id_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `citas_afil`
--
ALTER TABLE `citas_afil`
  ADD PRIMARY KEY (`id_citas_afil`),
  ADD UNIQUE KEY `idcita` (`idcita`,`id_afiliado`),
  ADD KEY `id_afiliado` (`id_afiliado`);

--
-- Indices de la tabla `citas_benef`
--
ALTER TABLE `citas_benef`
  ADD PRIMARY KEY (`id_citas_benef`),
  ADD UNIQUE KEY `idcita` (`idcita`,`id_beneficiario`),
  ADD KEY `id_beneficiario` (`id_beneficiario`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id_especialidad`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`cedula`);

--
-- Indices de la tabla `preguntas_seguridad`
--
ALTER TABLE `preguntas_seguridad`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `respuestas_seguridad`
--
ALTER TABLE `respuestas_seguridad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_pregunta` (`usuario_id`,`pregunta_seguridad_id`),
  ADD KEY `pregunta_seguridad_id` (`pregunta_seguridad_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `roles` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `afiliados`
--
ALTER TABLE `afiliados`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `beneficiarios`
--
ALTER TABLE `beneficiarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `idbitacora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `citas_afil`
--
ALTER TABLE `citas_afil`
  MODIFY `id_citas_afil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `citas_benef`
--
ALTER TABLE `citas_benef`
  MODIFY `id_citas_benef` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `preguntas_seguridad`
--
ALTER TABLE `preguntas_seguridad`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `respuestas_seguridad`
--
ALTER TABLE `respuestas_seguridad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `afiliados`
--
ALTER TABLE `afiliados`
  ADD CONSTRAINT `relacion` FOREIGN KEY (`cedula`) REFERENCES `persona` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `beneficiarios`
--
ALTER TABLE `beneficiarios`
  ADD CONSTRAINT `beneficiarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `persona` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dependencia` FOREIGN KEY (`cedula_afil`) REFERENCES `afiliados` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `usuario` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`);

--
-- Filtros para la tabla `citas_afil`
--
ALTER TABLE `citas_afil`
  ADD CONSTRAINT `citas_afil_ibfk_1` FOREIGN KEY (`idcita`) REFERENCES `citas` (`id_cita`) ON DELETE CASCADE,
  ADD CONSTRAINT `citas_afil_ibfk_2` FOREIGN KEY (`id_afiliado`) REFERENCES `afiliados` (`ID`) ON DELETE CASCADE;

--
-- Filtros para la tabla `citas_benef`
--
ALTER TABLE `citas_benef`
  ADD CONSTRAINT `citas_benef_ibfk_1` FOREIGN KEY (`idcita`) REFERENCES `citas` (`id_cita`) ON DELETE CASCADE,
  ADD CONSTRAINT `citas_benef_ibfk_2` FOREIGN KEY (`id_beneficiario`) REFERENCES `beneficiarios` (`ID`) ON DELETE CASCADE;

--
-- Filtros para la tabla `respuestas_seguridad`
--
ALTER TABLE `respuestas_seguridad`
  ADD CONSTRAINT `respuestas_seguridad_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `respuestas_seguridad_ibfk_4` FOREIGN KEY (`pregunta_seguridad_id`) REFERENCES `preguntas_seguridad` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
