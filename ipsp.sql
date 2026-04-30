-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-05-2026 a las 01:20:51
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
CREATE DATABASE IF NOT EXISTS `ipsp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ipsp`;

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
(52, 30270492, '2026-03-28 00:58:32', '2026-03-28 00:58:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `beneficiarios`
--

CREATE TABLE `beneficiarios` (
  `ID` int(11) NOT NULL,
  `cedula` int(20) NOT NULL,
  `cedula_afil` int(20) NOT NULL,
  `parentesco` enum('Hijo','Esposo/a','Padre','Madre','Otro') NOT NULL DEFAULT 'Otro',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `beneficiarios`
--

INSERT INTO `beneficiarios` (`ID`, `cedula`, `cedula_afil`, `parentesco`, `created_at`, `updated_at`) VALUES
(19, 10505948, 52, 'Otro', '2026-03-28 15:11:11', '2026-03-28 15:11:11'),
(20, 22657594, 52, 'Hijo', '2026-04-30 22:36:32', '2026-04-30 22:36:32');

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

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`idbitacora`, `usuario`, `accion`, `descripcion`, `fecha`) VALUES
(1, NULL, 'Registro de Beneficiario', 'Cédula: 22657594, Nombre: Luis, Apellido: Mendoza', '2026-04-30 22:36:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_examenes`
--

CREATE TABLE `categorias_examenes` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `monto_maximo_cobertura` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias_examenes`
--

INSERT INTO `categorias_examenes` (`id_categoria`, `nombre_categoria`, `descripcion`, `monto_maximo_cobertura`) VALUES
(1, 'Consultas', 'Todas las consultas médicas generales y especializadas', 0.00),
(3, 'Estudios Ecograficos', '', 0.00),
(4, 'Rayos X', '', 0.00),
(5, 'Procedimientos Medicos', '', 0.00),
(6, 'Procedimientos especiales', '', 0.00),
(7, 'Odontologia', '', 0.00),
(8, 'Emergencia Ambulatoria', '', 0.00),
(9, 'Laboratorio', '', 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `fecha_cita` datetime NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado_pago` enum('Por Pagar','Pagada','Deducida de Póliza') NOT NULL DEFAULT 'Por Pagar',
  `estado` enum('activa','cancelada') DEFAULT 'activa',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas_examenes`
--

CREATE TABLE `citas_examenes` (
  `id_cita_examen` int(11) NOT NULL,
  `id_cita` int(11) NOT NULL,
  `id_examen` int(11) NOT NULL,
  `precio_historico` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas_uptm`
--

CREATE TABLE `citas_uptm` (
  `id_citas_uptm` int(11) NOT NULL,
  `idcita` int(11) NOT NULL,
  `id_externo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `componentes_planes`
--

CREATE TABLE `componentes_planes` (
  `ID_componenteplan` int(11) NOT NULL,
  `ID_planes_componentes` int(11) NOT NULL,
  `ID_examen_componentes` int(11) DEFAULT NULL,
  `id_categoria_componente` int(11) DEFAULT NULL,
  `cantidad_maxima` int(11) DEFAULT NULL,
  `monto_maximo` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `componentes_planes`
--

INSERT INTO `componentes_planes` (`ID_componenteplan`, `ID_planes_componentes`, `ID_examen_componentes`, `id_categoria_componente`, `cantidad_maxima`, `monto_maximo`) VALUES
(24, 6, NULL, 1, 8, 300.00),
(25, 6, NULL, 3, 4, 80.00),
(26, 6, NULL, 4, NULL, 60.00),
(27, 6, NULL, 6, NULL, 60.00),
(28, 6, NULL, 7, NULL, 0.00),
(29, 6, NULL, 8, NULL, 300.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunidad_uptm`
--

CREATE TABLE `comunidad_uptm` (
  `id` int(11) NOT NULL,
  `cedula` int(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `id_tipo_ext` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comunidad_uptm`
--

INSERT INTO `comunidad_uptm` (`id`, `cedula`, `nombre`, `apellido`, `created_at`, `updated_at`, `deleted_at`, `id_tipo_ext`) VALUES
(5, 22657594, 'Luis', 'Mendoza', '2026-04-06 21:16:27', '2026-04-09 01:24:22', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consumo_plan`
--

CREATE TABLE `consumo_plan` (
  `ID_consumo` int(11) NOT NULL,
  `ID_contrato_plan` int(11) NOT NULL,
  `id_cita` int(11) DEFAULT NULL,
  `ID_persona_plan` int(11) NOT NULL,
  `ID_examen_plan` int(11) DEFAULT NULL,
  `nombre_estudio_externo` varchar(255) DEFAULT NULL,
  `id_categoria_externa` int(11) DEFAULT NULL,
  `monto_descontado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_consumo` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contrato_plan`
--

CREATE TABLE `contrato_plan` (
  `ID_contrato` int(11) NOT NULL,
  `ID_planes_contrato` int(11) NOT NULL,
  `ID_afiliado_contrato` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `frecuencia_pago` varchar(50) DEFAULT NULL,
  `dia_pago_mensual` int(11) DEFAULT NULL,
  `estado_contrato` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contrato_plan`
--

INSERT INTO `contrato_plan` (`ID_contrato`, `ID_planes_contrato`, `ID_afiliado_contrato`, `fecha_inicio`, `fecha_fin`, `monto_total`, `frecuencia_pago`, `dia_pago_mensual`, `estado_contrato`) VALUES
(13, 6, 30270492, '2026-01-01', '2027-03-27', 180.00, 'Mensual', 29, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id_especialidad` int(11) NOT NULL,
  `nombre_especialidad` varchar(50) NOT NULL,
  `descuento` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id_especialidad`, `nombre_especialidad`, `descuento`) VALUES
(1, 'Ginecología', 0.00),
(2, 'Medicina interna', 0.00),
(3, 'Odontología', 30.00),
(4, 'Oftamología', 50.00),
(5, 'Gastroenterología', 0.00),
(6, 'Imagenología', 0.00),
(7, 'Laboratorio', 40.00),
(8, 'Urologia', 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes`
--

CREATE TABLE `examenes` (
  `ID_examen` int(11) NOT NULL,
  `nombre_examen` varchar(150) NOT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ID_especialidad_examenes` int(11) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `examenes`
--

INSERT INTO `examenes` (`ID_examen`, `nombre_examen`, `precio`, `ID_especialidad_examenes`, `id_categoria`, `estado`) VALUES
(1, 'Consulta Ginecológica', 20.00, 1, 1, 'activo'),
(2, 'Consulta Oftalmologia', 40.00, 4, 1, 'activo'),
(4, 'Consulta gatroenterologia', 20.00, 5, 1, 'activo'),
(5, 'Consulta Medicina interna', 20.00, 2, 1, 'activo'),
(6, 'Ecografa Abdominal', 35.00, 6, 3, 'activo'),
(7, 'Ecografa Plvica', 30.00, 6, 3, 'activo'),
(8, 'Ecografa Renal', 40.00, 6, 3, 'activo'),
(9, 'Rayos X de Trax', 25.00, 6, 4, 'activo'),
(10, 'Rayos X de Columna Cervical', 50.00, 6, 4, 'activo'),
(11, 'Rayos X de Miembro Inferior', 20.00, 6, 4, 'activo'),
(12, 'Endoscopia', 50.00, 5, 6, 'activo'),
(13, 'Glicemia post pandrial', 20.00, 7, 9, 'activo'),
(14, 'Rayos x de muñeca', 50.00, 6, 4, 'activo'),
(16, 'Rayos x de Codo', 20.00, 6, 4, 'activo'),
(18, 'Citologia', 20.00, 1, 6, 'inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historias_medicas`
--

CREATE TABLE `historias_medicas` (
  `id_historia` int(11) NOT NULL,
  `ci_paciente` int(11) NOT NULL,
  `tipo_paciente` varchar(20) NOT NULL,
  `ci_medico` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `edad` int(3) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `motivo_consulta` text NOT NULL,
  `enfermedad_actual` text NOT NULL,
  `antecedentes_familiares` text NOT NULL,
  `antecedentes_personales` text NOT NULL,
  `info_adicional` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historias_medicas_gine`
--

CREATE TABLE `historias_medicas_gine` (
  `id_historia_g` int(11) NOT NULL,
  `ci_paciente` int(11) NOT NULL,
  `tipo_paciente` varchar(20) NOT NULL,
  `ci_medico` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `edad` int(3) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `motivo_consulta` text NOT NULL,
  `enfermedad_actual` text NOT NULL,
  `antecedentes_familiares` text NOT NULL,
  `antecedentes_personales` text NOT NULL,
  `gs` varchar(20) NOT NULL,
  `fuma` varchar(20) NOT NULL,
  `ant_gineco_obstetrico` varchar(100) NOT NULL,
  `c.m` varchar(50) NOT NULL,
  `prs` varchar(50) NOT NULL,
  `cs` varchar(50) NOT NULL,
  `mac` varchar(50) NOT NULL,
  `fuc` varchar(100) NOT NULL,
  `fum` varchar(50) NOT NULL,
  `gestaciones` varchar(50) NOT NULL,
  `rc` varchar(50) NOT NULL,
  `año` year(4) NOT NULL,
  `otros` varchar(50) NOT NULL,
  `ex.fisico.t.a` varchar(20) NOT NULL,
  `f.c` varchar(20) NOT NULL,
  `peso` varchar(20) NOT NULL,
  `talla` varchar(20) NOT NULL,
  `cabeza` varchar(20) NOT NULL,
  `o.r.l` varchar(20) NOT NULL,
  `c.v` varchar(20) NOT NULL,
  `tiroides` varchar(20) NOT NULL,
  `mamas` varchar(20) NOT NULL,
  `abdomen` varchar(20) NOT NULL,
  `ginecologico` varchar(100) NOT NULL,
  `ultrasonido` varchar(100) NOT NULL,
  `diagnostico` varchar(100) NOT NULL,
  `conducta` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicos`
--

CREATE TABLE `medicos` (
  `ci_medico` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `especialidad` int(11) NOT NULL,
  `telefono_personal` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medicos`
--

INSERT INTO `medicos` (`ci_medico`, `id_usuario`, `especialidad`, `telefono_personal`) VALUES
(11955376, 30, 1, '04146578453'),
(14107471, 29, 2, '04247653467');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_contrato`
--

CREATE TABLE `pagos_contrato` (
  `ID_pago` int(11) NOT NULL,
  `ID_contrato` int(11) NOT NULL,
  `monto_cuota` decimal(10,2) NOT NULL,
  `fecha_pago` date NOT NULL,
  `numero_cuota` int(11) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `tipo_pago` varchar(20) NOT NULL DEFAULT 'Cuota'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos_contrato`
--

INSERT INTO `pagos_contrato` (`ID_pago`, `ID_contrato`, `monto_cuota`, `fecha_pago`, `numero_cuota`, `metodo_pago`, `tipo_pago`) VALUES
(23, 13, 60.00, '2026-01-21', NULL, 'transferencia', 'Pago inicial');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_externos`
--

CREATE TABLE `pagos_externos` (
  `id_pago_ext` int(11) NOT NULL,
  `id_cita` int(11) NOT NULL,
  `monto_base` decimal(10,2) NOT NULL,
  `monto_final` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT 'Efectivo',
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `ocupacion` varchar(50) NOT NULL,
  `estado` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`cedula`, `nombre`, `apellido`, `fechanacimiento`, `genero`, `telefono`, `correo`, `ocupacion`, `estado`) VALUES
(10505948, 'Teresa', 'Pérez', '1974-03-21', 'Femenino', 2147483647, 'tere_34_11_3@gmail.com', 'Nada', ''),
(22657594, 'Luis', 'Mendoza', '1980-03-23', 'Masculino', 465465465, 'adsdad@addasd', 'asd', ''),
(30270492, 'Gregory', 'Pérez', '2002-08-31', 'Masculino', 46544, 'gps.3108@gmail.com', 'asdasd', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes`
--

CREATE TABLE `planes` (
  `ID_planes` int(11) NOT NULL,
  `nombre_plan` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `monto_cobertura` decimal(10,2) NOT NULL DEFAULT 0.00,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `planes`
--

INSERT INTO `planes` (`ID_planes`, `nombre_plan`, `precio`, `monto_cobertura`, `descripcion`) VALUES
(6, 'Plan salud 2026', 180.00, 800.00, '');

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
(33, 25, 1, 'merida'),
(34, 25, 3, 'azul'),
(35, 27, 1, 'merida'),
(36, 27, 3, 'morado'),
(37, 29, 1, 'merida'),
(38, 29, 4, 'perro'),
(39, 30, 1, 'merida'),
(40, 30, 4, 'gato'),
(41, 31, 1, 'caracas'),
(42, 31, 3, 'verde');

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
(2, 'usuario'),
(3, 'medico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_externos`
--

CREATE TABLE `tipos_externos` (
  `id_tipos_ext` int(11) NOT NULL,
  `nombre_tipo` varchar(50) NOT NULL,
  `descuento` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_externos`
--

INSERT INTO `tipos_externos` (`id_tipos_ext`, `nombre_tipo`, `descuento`) VALUES
(1, 'Estudiante', 10.00),
(2, 'Profesor no Afiliado', 15.00),
(3, 'Externo General', 0.00);

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
(7, 'admin', '$2y$10$XBg1P/iKyVALsB1oMK8Bv.PhjgjaVakouIwz0wjAl0tV/My/ow6ba', 1),
(25, 'grego', '$2y$10$Mg0yHFSuZJn9IuosEniBqOzf.duGs9/I2YyLpfxEEma0HWtItKiZG', 1),
(27, 'usuario', '$2y$10$/1OPBwmDoMLB6KKeMXGxMejHfMviGhH7FkkBc7ams3niG/fmfY3TK', 2),
(29, 'medico', '$2y$10$wFE1ke/Z6ZDXzVF/4DcxP.3cuO1OQk.0A5LZ0tHDucnA1jWJdaZem', 3),
(30, 'medico2', '$2y$10$njIBWtQyDX4IX/OT82tqlOCceu8QTb5xPpm5b8D7PqA8OOo6BmE7K', 3),
(31, 'secretaria', '$2y$10$6c9yiRlxmWalX4aP6vCAOORlE5MKmdLmPOMzdIh1vaLbsaPajkEJK', 2);

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
-- Indices de la tabla `categorias_examenes`
--
ALTER TABLE `categorias_examenes`
  ADD PRIMARY KEY (`id_categoria`);

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
-- Indices de la tabla `citas_examenes`
--
ALTER TABLE `citas_examenes`
  ADD PRIMARY KEY (`id_cita_examen`),
  ADD KEY `id_cita` (`id_cita`),
  ADD KEY `id_examen` (`id_examen`);

--
-- Indices de la tabla `citas_uptm`
--
ALTER TABLE `citas_uptm`
  ADD PRIMARY KEY (`id_citas_uptm`),
  ADD KEY `idcita` (`idcita`),
  ADD KEY `id_externo` (`id_externo`);

--
-- Indices de la tabla `componentes_planes`
--
ALTER TABLE `componentes_planes`
  ADD PRIMARY KEY (`ID_componenteplan`),
  ADD KEY `ID_examen_componentes` (`ID_examen_componentes`),
  ADD KEY `componentes_planes_ibfk_1` (`ID_planes_componentes`),
  ADD KEY `fk_componente_categoria` (`id_categoria_componente`);

--
-- Indices de la tabla `comunidad_uptm`
--
ALTER TABLE `comunidad_uptm`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `fk_comunidad_tipo` (`id_tipo_ext`);

--
-- Indices de la tabla `consumo_plan`
--
ALTER TABLE `consumo_plan`
  ADD PRIMARY KEY (`ID_consumo`),
  ADD KEY `ID_contrato_plan` (`ID_contrato_plan`),
  ADD KEY `ID_persona_plan` (`ID_persona_plan`),
  ADD KEY `ID_examen_plan` (`ID_examen_plan`),
  ADD KEY `fk_consumo_cita` (`id_cita`);

--
-- Indices de la tabla `contrato_plan`
--
ALTER TABLE `contrato_plan`
  ADD PRIMARY KEY (`ID_contrato`),
  ADD KEY `ID_planes_contrato` (`ID_planes_contrato`),
  ADD KEY `contrato_plan_ibfk_2` (`ID_afiliado_contrato`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id_especialidad`);

--
-- Indices de la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD PRIMARY KEY (`ID_examen`),
  ADD KEY `fk_examen_especialidad` (`ID_especialidad_examenes`),
  ADD KEY `fk_examen_categoria` (`id_categoria`);

--
-- Indices de la tabla `historias_medicas`
--
ALTER TABLE `historias_medicas`
  ADD PRIMARY KEY (`id_historia`),
  ADD KEY `ci_medico` (`ci_medico`);

--
-- Indices de la tabla `historias_medicas_gine`
--
ALTER TABLE `historias_medicas_gine`
  ADD PRIMARY KEY (`id_historia_g`),
  ADD KEY `ci_medico` (`ci_medico`);

--
-- Indices de la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`ci_medico`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `especialidad` (`especialidad`);

--
-- Indices de la tabla `pagos_contrato`
--
ALTER TABLE `pagos_contrato`
  ADD PRIMARY KEY (`ID_pago`),
  ADD KEY `ID_contrato` (`ID_contrato`);

--
-- Indices de la tabla `pagos_externos`
--
ALTER TABLE `pagos_externos`
  ADD PRIMARY KEY (`id_pago_ext`),
  ADD KEY `id_cita` (`id_cita`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`cedula`);

--
-- Indices de la tabla `planes`
--
ALTER TABLE `planes`
  ADD PRIMARY KEY (`ID_planes`);

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
-- Indices de la tabla `tipos_externos`
--
ALTER TABLE `tipos_externos`
  ADD PRIMARY KEY (`id_tipos_ext`);

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `beneficiarios`
--
ALTER TABLE `beneficiarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `idbitacora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categorias_examenes`
--
ALTER TABLE `categorias_examenes`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `citas_afil`
--
ALTER TABLE `citas_afil`
  MODIFY `id_citas_afil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `citas_benef`
--
ALTER TABLE `citas_benef`
  MODIFY `id_citas_benef` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `citas_examenes`
--
ALTER TABLE `citas_examenes`
  MODIFY `id_cita_examen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `citas_uptm`
--
ALTER TABLE `citas_uptm`
  MODIFY `id_citas_uptm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `componentes_planes`
--
ALTER TABLE `componentes_planes`
  MODIFY `ID_componenteplan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `comunidad_uptm`
--
ALTER TABLE `comunidad_uptm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `consumo_plan`
--
ALTER TABLE `consumo_plan`
  MODIFY `ID_consumo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `contrato_plan`
--
ALTER TABLE `contrato_plan`
  MODIFY `ID_contrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `examenes`
--
ALTER TABLE `examenes`
  MODIFY `ID_examen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `historias_medicas`
--
ALTER TABLE `historias_medicas`
  MODIFY `id_historia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `historias_medicas_gine`
--
ALTER TABLE `historias_medicas_gine`
  MODIFY `id_historia_g` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `medicos`
--
ALTER TABLE `medicos`
  MODIFY `ci_medico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14107472;

--
-- AUTO_INCREMENT de la tabla `pagos_contrato`
--
ALTER TABLE `pagos_contrato`
  MODIFY `ID_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `pagos_externos`
--
ALTER TABLE `pagos_externos`
  MODIFY `id_pago_ext` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `planes`
--
ALTER TABLE `planes`
  MODIFY `ID_planes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `preguntas_seguridad`
--
ALTER TABLE `preguntas_seguridad`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `respuestas_seguridad`
--
ALTER TABLE `respuestas_seguridad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipos_externos`
--
ALTER TABLE `tipos_externos`
  MODIFY `id_tipos_ext` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
-- Filtros para la tabla `citas_examenes`
--
ALTER TABLE `citas_examenes`
  ADD CONSTRAINT `citas_examenes_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`) ON DELETE CASCADE,
  ADD CONSTRAINT `citas_examenes_ibfk_2` FOREIGN KEY (`id_examen`) REFERENCES `examenes` (`ID_examen`);

--
-- Filtros para la tabla `citas_uptm`
--
ALTER TABLE `citas_uptm`
  ADD CONSTRAINT `citas_uptm_ibfk_1` FOREIGN KEY (`idcita`) REFERENCES `citas` (`id_cita`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `citas_uptm_ibfk_2` FOREIGN KEY (`id_externo`) REFERENCES `comunidad_uptm` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `componentes_planes`
--
ALTER TABLE `componentes_planes`
  ADD CONSTRAINT `componentes_planes_ibfk_1` FOREIGN KEY (`ID_planes_componentes`) REFERENCES `planes` (`ID_planes`),
  ADD CONSTRAINT `componentes_planes_ibfk_2` FOREIGN KEY (`ID_examen_componentes`) REFERENCES `examenes` (`ID_examen`),
  ADD CONSTRAINT `fk_componente_categoria` FOREIGN KEY (`id_categoria_componente`) REFERENCES `categorias_examenes` (`id_categoria`) ON DELETE CASCADE;

--
-- Filtros para la tabla `comunidad_uptm`
--
ALTER TABLE `comunidad_uptm`
  ADD CONSTRAINT `fk_comunidad_tipo` FOREIGN KEY (`id_tipo_ext`) REFERENCES `tipos_externos` (`id_tipos_ext`);

--
-- Filtros para la tabla `consumo_plan`
--
ALTER TABLE `consumo_plan`
  ADD CONSTRAINT `consumo_plan_ibfk_1` FOREIGN KEY (`ID_contrato_plan`) REFERENCES `contrato_plan` (`ID_contrato`),
  ADD CONSTRAINT `consumo_plan_ibfk_2` FOREIGN KEY (`ID_persona_plan`) REFERENCES `persona` (`cedula`),
  ADD CONSTRAINT `consumo_plan_ibfk_3` FOREIGN KEY (`ID_examen_plan`) REFERENCES `examenes` (`ID_examen`),
  ADD CONSTRAINT `fk_consumo_cita` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`) ON DELETE SET NULL;

--
-- Filtros para la tabla `contrato_plan`
--
ALTER TABLE `contrato_plan`
  ADD CONSTRAINT `contrato_plan_ibfk_1` FOREIGN KEY (`ID_planes_contrato`) REFERENCES `planes` (`ID_planes`),
  ADD CONSTRAINT `contrato_plan_ibfk_2` FOREIGN KEY (`ID_afiliado_contrato`) REFERENCES `afiliados` (`cedula`);

--
-- Filtros para la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD CONSTRAINT `fk_examen_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias_examenes` (`id_categoria`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_examen_especialidad` FOREIGN KEY (`ID_especialidad_examenes`) REFERENCES `especialidades` (`id_especialidad`);

--
-- Filtros para la tabla `historias_medicas`
--
ALTER TABLE `historias_medicas`
  ADD CONSTRAINT `historias_medicas_ibfk_1` FOREIGN KEY (`ci_medico`) REFERENCES `medicos` (`ci_medico`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historias_medicas_gine`
--
ALTER TABLE `historias_medicas_gine`
  ADD CONSTRAINT `historias_medicas_gine_ibfk_1` FOREIGN KEY (`ci_medico`) REFERENCES `medicos` (`ci_medico`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `medicos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `medicos_ibfk_2` FOREIGN KEY (`especialidad`) REFERENCES `especialidades` (`id_especialidad`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos_contrato`
--
ALTER TABLE `pagos_contrato`
  ADD CONSTRAINT `pagos_contrato_ibfk_1` FOREIGN KEY (`ID_contrato`) REFERENCES `contrato_plan` (`ID_contrato`);

--
-- Filtros para la tabla `pagos_externos`
--
ALTER TABLE `pagos_externos`
  ADD CONSTRAINT `pagos_externos_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`);

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
