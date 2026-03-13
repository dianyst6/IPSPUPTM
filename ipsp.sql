-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-03-2026 a las 18:20:38
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
(38, 31253521, '2025-05-13 05:02:40', '2026-03-12 17:06:23'),
(43, 30270492, '2026-02-19 04:31:30', '2026-02-19 04:31:30'),
(46, 8012649, '2026-02-24 02:34:01', '2026-03-03 01:14:16'),
(47, 30960254, '2026-03-12 17:11:53', '2026-03-12 17:11:53'),
(48, 11955376, '2026-03-12 20:08:18', '2026-03-12 20:08:18');

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
(13, 8012649, 38, '2025-05-13 05:16:57', '2025-05-13 05:22:12'),
(16, 14107471, 48, '2026-03-12 20:10:03', '2026-03-12 20:10:03');

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
(133, 'Admin', 'Eliminación de usuario', 'Se eliminó al usuario: flowernoni y sus respuestas de seguridad.', '2025-05-14 03:13:58'),
(135, 'Admin', 'Eliminación de usuario', 'Se eliminó al usuario: gabibi y sus respuestas de seguridad.', '2025-05-14 03:25:29'),
(136, 'Admin', 'Eliminación de usuario', 'Se eliminó al usuario: gabrielaGN y sus respuestas de seguridad.', '2025-05-14 03:25:38'),
(137, NULL, 'Registro de Beneficiario', 'Cédula: 10505948, Nombre: teresa, Apellido: perez', '2025-08-06 15:17:29'),
(138, 'admin', 'Registro de Afiliado', 'Cédula: 30270492, Nombre: gregory, Apellido: perez', '2026-02-19 03:34:57'),
(139, 'admin', 'Eliminación de Afiliado y Citas', 'Se eliminó al afiliado: gregory perez (Cédula: 30270492) y todas las citas relacionadas.', '2026-02-19 03:50:48'),
(140, 'admin', 'Registro Integral', 'Afiliado y Plan creados: 30270492', '2026-02-19 03:52:09'),
(141, 'grego', 'Eliminación de Afiliado y Citas', 'Se eliminó al afiliado: gregory perez (Cédula: 30270492) y todas las citas relacionadas.', '2026-02-19 04:24:33'),
(142, 'admin', 'Registro Integral', 'Afiliado y Plan creados: 30270492', '2026-02-19 04:25:05'),
(143, 'admin', 'Eliminación de Afiliado y Citas', 'Se eliminó al afiliado: gregory perez (Cédula: 30270492) y todas las citas relacionadas.', '2026-02-19 04:31:13'),
(144, 'admin', 'Registro Integral', 'Afiliado y Plan creados: 30270492', '2026-02-19 04:31:30'),
(145, NULL, 'Registro de Cita', ' Se ha registrado a un paciente del tipo Afiliado, para la fecha: 2026-02-19T11:03', '2026-02-19 15:03:28'),
(146, NULL, 'Registro de Cita', ' Se ha registrado a un paciente del tipo Beneficiario, para la fecha: 2026-02-19T11:21', '2026-02-19 15:21:50'),
(147, 'admin', 'Registro Integral', 'Afiliado y Plan creados: 10505948', '2026-02-19 16:49:52'),
(148, 'admin', 'Actualización de Beneficiario', 'Se actualizó al beneficiario con cédula 10505948 y nombre teresa perez', '2026-02-21 04:46:41'),
(149, 'admin', 'Eliminación de Afiliado y Citas', 'Se eliminó al afiliado: teresa perez (Cédula: 10505948) y todas las citas relacionadas.', '2026-02-21 04:53:45'),
(150, 'admin', 'Registro Integral', 'Afiliado y Plan creados: 10505948', '2026-02-21 04:54:10'),
(151, 'admin', 'Eliminación de Afiliado y Citas', 'Se eliminó al afiliado: teresa perez (Cédula: 10505948) y todas las citas relacionadas.', '2026-02-21 04:57:54'),
(152, 'admin', 'Registro Integral', 'Afiliado y Plan creados: 8012649', '2026-02-24 02:34:01'),
(153, NULL, 'Registro de Cita', ' Se ha registrado a un paciente del tipo Afiliado, para la fecha: 2026-02-25T22:34', '2026-02-24 02:34:37'),
(154, 'admin', 'Eliminación de Cita', 'Se eliminó la cita: ewewewe (ID Cita: 19), Paciente: ZULEYMAaa NUÑEZ (Afiliado), Fecha: 2026-02-25 22:34:00, Especialidad: Imagenología', '2026-02-24 22:20:25'),
(155, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Afiliado para la fecha: 2026-02-18T19:37', '2026-02-24 23:37:58'),
(156, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Afiliado para la fecha: 2026-02-18T19:37', '2026-02-24 23:37:59'),
(157, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Afiliado para la fecha: 2026-02-04T19:38', '2026-02-24 23:39:20'),
(158, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Comunidad UPTM (Externo) para la fecha: 2026-02-26T19:43', '2026-02-24 23:44:03'),
(159, 'admin', 'Eliminación de Cita', 'Se eliminó la cita: gfgfggf (ID Cita: 24), Paciente: No especificado (Desconocido), Fecha: 2026-02-26 19:43:00, Especialidad: Gastroenterología', '2026-02-24 23:45:08'),
(160, 'admin', 'Eliminación de Cita', 'Se eliminó la cita: sdds (ID Cita: 22), Paciente: No especificado (Desconocido), Fecha: 2026-02-04 19:38:00, Especialidad: Gastroenterología', '2026-02-24 23:46:40'),
(161, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Comunidad UPTM (Externo) para la fecha: 2026-02-24T19:54', '2026-02-24 23:54:59'),
(162, 'admin', 'Eliminación de Cita', 'Se eliminó la cita: sasdssd (ID Cita: 25), Paciente: No especificado (Desconocido), Fecha: 2026-02-24 19:54:00, Especialidad: Ginecología', '2026-02-24 23:57:15'),
(163, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Comunidad UPTM (Externo) para la fecha: 2026-02-19T19:59', '2026-02-24 23:59:21'),
(164, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Afiliado para la fecha: 2026-02-11T20:46', '2026-02-25 00:46:40'),
(165, 'admin', 'Actualización de Cita', 'Se actualizó una cita', '2026-02-25 01:33:23'),
(166, 'admin', 'Actualización de Cita', 'Se actualizó una cita', '2026-02-25 01:34:38'),
(167, 'admin', 'Actualización de Cita', 'Se modificó la cita ID: 18', '2026-02-25 01:39:19'),
(168, 'admin', 'Actualización de Cita', 'Se modificó la cita ID: 26', '2026-02-25 01:39:29'),
(169, 'admin', 'Actualización de Cita', 'Se modificó la cita ID: 26', '2026-02-25 01:39:39'),
(170, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Comunidad UPTM (Externo) para la fecha: 2026-02-12T21:50', '2026-02-25 01:50:42'),
(171, 'admin', 'Actualización de Cita', 'Se modificó la cita ID: 28', '2026-02-25 01:50:51'),
(172, 'admin', 'Eliminación de Cita', 'Se eliminó la cita: si (ID Cita: 16), Paciente: GABRIELA  GARCÍA NUÑEZ (Afiliado), Fecha: 2025-05-22 01:08:00, Especialidad: Ginecología', '2026-02-25 01:51:49'),
(173, 'admin', 'Eliminación de Cita', 'Se eliminó la cita: sdds (ID Cita: 23), Paciente: ZULEYMAaa NUÑEZ (Afiliado), Fecha: 2026-02-04 19:38:00, Especialidad: Gastroenterología', '2026-02-25 01:51:59'),
(174, 'admin', 'Pago Registrado', 'Se procesó pago de cita #26 por monto de 0 Bs.', '2026-02-27 05:03:27'),
(175, 'admin', 'Pago Registrado', 'Se procesó pago de cita #26 por monto de 0 Bs.', '2026-02-27 05:03:33'),
(176, 'admin', 'Pago Registrado', 'Se procesó pago de cita #26 por monto de 0 Bs.', '2026-02-27 05:03:36'),
(177, 'admin', 'Pago Registrado', 'Se procesó pago de cita #26 por monto de 0 Bs.', '2026-02-27 05:03:37'),
(178, 'admin', 'Pago Registrado', 'Se procesó pago de cita #26 por monto de 0 Bs.', '2026-02-27 05:03:38'),
(179, 'admin', 'Pago Registrado', 'Se procesó pago de cita #26 por monto de 0 Bs.', '2026-02-27 05:03:42'),
(180, 'admin', 'Pago Registrado', 'Se procesó pago de cita #26 por monto de 0 Bs.', '2026-02-27 05:05:52'),
(181, 'admin', 'Pago Registrado', 'Se procesó pago de cita #26 por monto de 0 Bs.', '2026-02-27 05:05:52'),
(182, 'admin', 'Pago Registrado', 'Se procesó pago de cita #26 por monto de 0 Bs.', '2026-02-27 05:07:03'),
(183, 'admin', 'Pago Registrado', 'Se procesó pago de cita #28 por monto de 2000 Bs.', '2026-02-27 05:08:54'),
(184, 'admin', 'Pago Registrado', 'Se procesó pago de cita #28 por monto de 20000 Bs.', '2026-02-27 05:08:55'),
(185, 'admin', 'Eliminación de Cita', 'Se eliminó la cita: asd (ID Cita: 18), Paciente: ZULEYMAaa NUÑEZ (Beneficiario), Fecha: 2026-02-19 11:21:00, Especialidad: Ginecología', '2026-02-27 05:16:55'),
(186, 'admin', 'Eliminación de Cita', 'Se eliminó la cita: sdsddssd (ID Cita: 26), Paciente: No especificado (Desconocido), Fecha: 2026-02-19 19:59:00, Especialidad: Ginecología', '2026-02-27 05:17:14'),
(187, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Comunidad UPTM (Externo) para la fecha: 2026-02-18T01:19', '2026-02-27 05:19:19'),
(188, 'admin', 'Pago Registrado', 'Se procesó pago de cita #29 por monto de 2000 Bs.', '2026-02-27 05:19:32'),
(189, 'admin', 'Eliminación de Cita', 'Se eliminó la cita: wqewwe (ID Cita: 29), Paciente: No especificado (Desconocido), Fecha: 2026-02-18 01:19:00, Especialidad: Oftamología', '2026-02-27 05:27:54'),
(190, 'admin', 'Eliminación de Cita', 'Se eliminó la cita: sasassa (ID Cita: 28), Paciente: No especificado (Desconocido), Fecha: 2026-02-12 21:50:00, Especialidad: Ginecología', '2026-02-27 05:27:59'),
(191, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Comunidad UPTM (Externo) para la fecha: 2026-03-18T20:50', '2026-03-03 00:50:45'),
(192, 'admin', 'Pago Registrado', 'Se procesó pago de cita #30 por monto de 0 Bs.', '2026-03-03 00:58:32'),
(193, 'admin', 'Pago Registrado', 'Se procesó pago de cita #30 por monto de 0 Bs.', '2026-03-03 00:58:35'),
(194, 'admin', 'Pago Registrado', 'Se procesó pago de cita #30 por monto de 0 Bs.', '2026-03-03 00:58:38'),
(195, 'admin', 'Pago Registrado', 'Se procesó pago de cita #30 por monto de 0 Bs.', '2026-03-03 00:58:39'),
(196, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Comunidad UPTM (Externo) para la fecha: 2026-03-26T21:06', '2026-03-03 01:07:00'),
(197, 'admin', 'Pago Registrado', 'Se procesó pago de cita #31 por 2000 Bs.', '2026-03-03 01:07:32'),
(198, 'Admin', 'Eliminación de usuario', 'Se eliminó al usuario: flowernoni y sus respuestas de seguridad.', '2026-03-03 01:08:54'),
(199, 'Admin', 'Eliminación de usuario', 'Se eliminó al usuario: Naomi y sus respuestas de seguridad.', '2026-03-03 01:09:05'),
(200, 'admin', 'Edición de Afiliado', 'Se han actualizado los datos del afiliado con cédula: 8012649, Nombre: ZULEYMA, Apellido: NUÑEZ', '2026-03-03 01:14:16'),
(201, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Comunidad UPTM (Externo) para la fecha: 2026-03-19T21:24', '2026-03-03 01:24:52'),
(202, 'admin', 'Pago Registrado', 'Se procesó pago de cita #32 por 500 Bs.', '2026-03-03 01:26:03'),
(203, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Comunidad UPTM (Externo) para la fecha: 2026-03-04T22:19', '2026-03-03 02:19:24'),
(204, 'admin', 'Pago Registrado', 'Se procesó pago de cita #33 por  Bs.', '2026-03-03 02:45:29'),
(205, 'admin', 'Edición de Comunidad UPTM', 'Se actualizaron los datos del externo C.I: 12913223 (José Perez)', '2026-03-03 03:39:16'),
(206, 'admin', 'Edición de Afiliado', 'Se han actualizado los datos del afiliado con cédula: 31253521, Nombre: GABRIELA , Apellido: GARCÍA NUÑEZ', '2026-03-12 17:06:23'),
(207, 'admin', 'Registro Integral', 'Afiliado y Plan creados: 30960254', '2026-03-12 17:11:53'),
(208, 'admin', 'Registro Integral', 'Afiliado y Plan creados: 11955376', '2026-03-12 20:08:18'),
(209, NULL, 'Registro de Beneficiario', 'Cédula: 14107471, Nombre: Maria, Apellido: Marquez', '2026-03-12 20:10:03'),
(210, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Beneficiario para la fecha: 2026-03-12T16:10', '2026-03-12 20:11:09'),
(211, 'admin', 'Registro de Cita', 'Se ha registrado una cita de tipo Afiliado para la fecha: 2026-04-30T09:30', '2026-03-12 20:20:52'),
(212, 'admin', 'Eliminación de Cita', 'Se eliminó la cita: sasa (ID Cita: 20), Paciente: GABRIELA  GARCÍA NUÑEZ (Afiliado), Fecha: 2026-02-18 19:37:00, Especialidad: Ginecología', '2026-03-12 20:21:03'),
(213, 'admin', 'Eliminación de Cita', 'Se eliminó la cita: sasa (ID Cita: 21), Paciente: GABRIELA  GARCÍA NUÑEZ (Afiliado), Fecha: 2026-02-18 19:37:00, Especialidad: Ginecología', '2026-03-12 20:21:43'),
(214, 'admin', 'Edición de Comunidad UPTM', 'Se actualizaron los datos del externo C.I: 8765345 (Melanie Martinez)', '2026-03-13 03:31:31'),
(215, 'medico2', 'Historia Ginecología', 'Paciente CI: 14107471 (beneficiario)', '2026-03-13 06:41:08'),
(216, 'medico2', 'Eliminación de Historia Médica', 'Historia Ginecología ID: 1 — Paciente CI: 14107471', '2026-03-13 07:04:57'),
(217, 'medico2', 'Historia Ginecología', 'Paciente CI: 14107471 (beneficiario)', '2026-03-13 07:18:14');

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
(17, 4, '2026-02-19 11:03:00', 'asd', '2026-02-19 15:03:28', '2026-02-19 15:03:28'),
(27, 5, '2026-02-11 20:46:00', 'dfsdfs', '2026-02-25 00:46:40', '2026-02-25 00:46:40'),
(30, 5, '2026-03-18 20:50:00', 'skasjad', '2026-03-03 00:50:45', '2026-03-03 00:50:45'),
(31, 1, '2026-03-26 21:06:00', 'sdssdds', '2026-03-03 01:06:56', '2026-03-03 01:06:56'),
(32, 1, '2026-03-19 21:24:00', 'gfddg', '2026-03-03 01:24:52', '2026-03-03 01:24:52'),
(33, 5, '2026-03-04 22:19:00', 'ghhhg', '2026-03-03 02:19:21', '2026-03-03 02:19:21'),
(34, 5, '2026-03-12 16:10:00', 'asdgg', '2026-03-12 20:11:09', '2026-03-12 20:11:09'),
(35, 2, '2026-04-30 09:30:00', 'sdagath', '2026-03-12 20:20:51', '2026-03-12 20:20:51');

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

--
-- Volcado de datos para la tabla `citas_afil`
--

INSERT INTO `citas_afil` (`id_citas_afil`, `idcita`, `id_afiliado`, `updated_at`, `created_at`) VALUES
(11, 17, 43, '2026-02-19 15:03:28', '2026-02-19 15:03:28'),
(16, 27, 43, '2026-02-25 00:46:40', '2026-02-25 00:46:40'),
(17, 35, 47, '2026-03-12 20:20:51', '2026-03-12 20:20:51');

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
(7, 34, 16, '2026-03-12 20:11:09', '2026-03-12 20:11:09');

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

--
-- Volcado de datos para la tabla `citas_uptm`
--

INSERT INTO `citas_uptm` (`id_citas_uptm`, `idcita`, `id_externo`, `created_at`, `updated_at`) VALUES
(6, 30, 1, '2026-03-03 00:50:45', '2026-03-03 00:50:45'),
(7, 31, 2, '2026-03-03 01:07:00', '2026-03-03 01:07:00'),
(8, 32, 1, '2026-03-03 01:24:52', '2026-03-03 01:24:52'),
(9, 33, 1, '2026-03-03 02:19:23', '2026-03-03 02:19:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `componentes_planes`
--

CREATE TABLE `componentes_planes` (
  `ID_componenteplan` int(11) NOT NULL,
  `ID_planes_componentes` int(11) NOT NULL,
  `ID_examen_componentes` int(11) NOT NULL,
  `cantidad_maxima` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `componentes_planes`
--

INSERT INTO `componentes_planes` (`ID_componenteplan`, `ID_planes_componentes`, `ID_examen_componentes`, `cantidad_maxima`) VALUES
(1, 1, 1, 3),
(2, 1, 2, 2),
(3, 2, 1, 2),
(4, 2, 2, 4),
(5, 3, 1, 1),
(6, 4, 1, 2);

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
(1, 12913223, 'José', 'Perez', '2026-02-24 23:44:02', '2026-03-03 03:39:15', NULL, NULL),
(2, 902322, 'Melanie', 'Martinez', '2026-02-27 05:19:19', '2026-03-03 01:06:58', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consumo_plan`
--

CREATE TABLE `consumo_plan` (
  `ID_consumo` int(11) NOT NULL,
  `ID_contrato_plan` int(11) NOT NULL,
  `ID_persona_plan` int(11) NOT NULL,
  `ID_examen_plan` int(11) NOT NULL,
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
(1, 1, 31253521, '2026-02-01', '2026-05-01', 120.00, 'mensual', 31, 'Activo'),
(4, 1, 30270492, '2026-02-19', '2026-12-31', 120.00, 'Mensual', 29, 'Activo'),
(7, 2, 8012649, '2026-02-24', '2026-02-25', 450.00, 'Mensual', 12, 'Activo'),
(8, 1, 30960254, '2026-03-12', '2027-12-30', 120.00, 'Mensual', 31, 'Activo'),
(9, 1, 11955376, '2026-03-12', '2026-12-12', 120.00, 'Mensual', 30, 'Activo');

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
-- Estructura de tabla para la tabla `examenes`
--

CREATE TABLE `examenes` (
  `ID_examen` int(11) NOT NULL,
  `nombre_examen` varchar(150) NOT NULL,
  `ID_especialidad_examenes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `examenes`
--

INSERT INTO `examenes` (`ID_examen`, `nombre_examen`, `ID_especialidad_examenes`) VALUES
(1, 'Consulta Ginecológica', 1),
(2, 'Consulta Oftalmologia', 4);

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
  `dirección` varchar(200) NOT NULL,
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

--
-- Volcado de datos para la tabla `historias_medicas_gine`
--

INSERT INTO `historias_medicas_gine` (`id_historia_g`, `ci_paciente`, `tipo_paciente`, `ci_medico`, `fecha`, `fecha_nacimiento`, `edad`, `direccion`, `motivo_consulta`, `enfermedad_actual`, `antecedentes_familiares`, `antecedentes_personales`, `gs`, `fuma`, `ant_gineco_obstetrico`, `c.m`, `prs`, `cs`, `mac`, `fuc`, `fum`, `gestaciones`, `rc`, `año`, `otros`, `ex.fisico.t.a`, `f.c`, `peso`, `talla`, `cabeza`, `o.r.l`, `c.v`, `tiroides`, `mamas`, `abdomen`, `ginecologico`, `ultrasonido`, `diagnostico`, `conducta`) VALUES
(2, 14107471, 'beneficiario', 11955376, '2026-03-13', '1980-10-15', 45, 'san juan', 'sgherhe', 'fhergsdfh', 'dfgetjg', 'sgjryj', 'AB+', 'No', 'dfgeth', 'regular', '3', '2', '45', '13546', '25734', '3', '4', '0000', 'dfsh', '120/80', '80', '65', '170', '40', '35', '436', '34', '346', '40', 'jwrj', 'sfgjry', 'sgherb', 'thbry');

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
  `metodo_pago` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos_contrato`
--

INSERT INTO `pagos_contrato` (`ID_pago`, `ID_contrato`, `monto_cuota`, `fecha_pago`, `numero_cuota`, `metodo_pago`) VALUES
(2, 1, 20.00, '2026-02-19', 2, 'Transferencia'),
(3, 1, 10.00, '2026-02-20', 3, 'Transferencia'),
(4, 1, 80.00, '2026-02-21', 4, 'Transferencia'),
(5, 1, 10.00, '2026-02-22', 5, 'Transferencia'),
(6, 4, 20.00, '2026-02-19', 1, 'Transferencia'),
(7, 4, 10.00, '2026-01-19', 2, 'Transferencia'),
(8, 4, 20.00, '2026-02-19', 3, 'Transferencia'),
(9, 4, 20.00, '2026-02-19', 4, 'Transferencia'),
(10, 4, 20.00, '2026-02-19', 5, 'Transferencia'),
(12, 4, 30.00, '2026-02-27', 6, 'Transferencia'),
(13, 7, 30.00, '2026-03-03', 1, 'Transferencia'),
(14, 7, 30.00, '2026-03-03', 2, 'Transferencia'),
(15, 7, 30.00, '2026-03-03', 3, 'Transferencia');

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

--
-- Volcado de datos para la tabla `pagos_externos`
--

INSERT INTO `pagos_externos` (`id_pago_ext`, `id_cita`, `monto_base`, `monto_final`, `metodo_pago`, `fecha_pago`) VALUES
(13, 30, 0.00, 0.00, 'Efectivo', '2026-03-03 00:58:25'),
(14, 30, 0.00, 0.00, 'Efectivo', '2026-03-03 00:58:33'),
(15, 30, 0.00, 0.00, 'Efectivo', '2026-03-03 00:58:36'),
(16, 30, 0.00, 0.00, 'Efectivo', '2026-03-03 00:58:38'),
(17, 31, 0.00, 2000.00, 'Efectivo', '2026-03-03 01:07:31'),
(18, 32, 0.00, 500.00, 'Efectivo', '2026-03-03 01:26:03'),
(19, 33, 20.00, 18.00, 'Pago Móvil', '2026-03-03 02:45:29');

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
(1, 'JUAN', 'NUÑEZ ', '2025-04-07', 'Masculino', 2147483647, 'WDRIIWEM@GMAIL.COM', 'SI ', ''),
(8012649, 'ZULEYMA', 'NUÑEZ', '2025-05-21', 'Femenino', 2147483647, 'zuleyma_nunez@gmail.com', 'Odontologo', ''),
(11955376, 'Miguel', 'Sosa', '1972-10-15', 'Masculino', 2147483647, 'miguel@gmail.com', 'mecanico', ''),
(14107471, 'Maria', 'Marquez', '1980-10-15', 'Femenino', 2147483647, 'maria@gmail.com', 'enfermera', ''),
(30270492, 'gregory', 'perez', '2002-08-31', 'Masculino', 46564654, 'gps.3108@gmail.com', 'profesor', ''),
(30960254, 'Juan', 'Rojas', '1999-08-13', 'Masculino', 2147483647, 'juan@gmail.com', 'Estudiante', ''),
(31253521, 'GABRIELA ', 'GARCÍA NUÑEZ', '2005-05-19', 'Femenino', 2147483647, 'GABRIELAVAL.GN31@GMAIL.COM', 'Estudiante', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes`
--

CREATE TABLE `planes` (
  `ID_planes` int(11) NOT NULL,
  `nombre_plan` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `planes`
--

INSERT INTO `planes` (`ID_planes`, `nombre_plan`, `precio`, `descripcion`) VALUES
(1, 'Plan salud 2026', 120.00, 'asd'),
(2, 'plan oro', 450.00, 'asd'),
(3, 'Plan Gold', 120.00, 'edfkikdfjfd\r\n'),
(4, 'Plan de prueba', 120.00, 'dssdsdsd');

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
  ADD KEY `componentes_planes_ibfk_1` (`ID_planes_componentes`);

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
  ADD KEY `ID_examen_plan` (`ID_examen_plan`);

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
  ADD KEY `fk_examen_especialidad` (`ID_especialidad_examenes`);

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `beneficiarios`
--
ALTER TABLE `beneficiarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `idbitacora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `citas_afil`
--
ALTER TABLE `citas_afil`
  MODIFY `id_citas_afil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `citas_benef`
--
ALTER TABLE `citas_benef`
  MODIFY `id_citas_benef` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `citas_uptm`
--
ALTER TABLE `citas_uptm`
  MODIFY `id_citas_uptm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `componentes_planes`
--
ALTER TABLE `componentes_planes`
  MODIFY `ID_componenteplan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `comunidad_uptm`
--
ALTER TABLE `comunidad_uptm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `consumo_plan`
--
ALTER TABLE `consumo_plan`
  MODIFY `ID_consumo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contrato_plan`
--
ALTER TABLE `contrato_plan`
  MODIFY `ID_contrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `examenes`
--
ALTER TABLE `examenes`
  MODIFY `ID_examen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `historias_medicas`
--
ALTER TABLE `historias_medicas`
  MODIFY `id_historia` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `ID_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `pagos_externos`
--
ALTER TABLE `pagos_externos`
  MODIFY `id_pago_ext` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `planes`
--
ALTER TABLE `planes`
  MODIFY `ID_planes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  ADD CONSTRAINT `componentes_planes_ibfk_2` FOREIGN KEY (`ID_examen_componentes`) REFERENCES `examenes` (`ID_examen`);

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
  ADD CONSTRAINT `consumo_plan_ibfk_3` FOREIGN KEY (`ID_examen_plan`) REFERENCES `examenes` (`ID_examen`);

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
