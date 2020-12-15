-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 15-12-2020 a las 16:07:05
-- Versión del servidor: 10.3.24-MariaDB-cll-lve
-- Versión de PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `serendipity_Comandita`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `action` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id`, `user`, `role`, `action`, `created_at`, `updated_at`) VALUES
(8, 45, 2, 'Login', '2020-12-07 22:31:19', '2020-12-07'),
(9, 46, 1, 'Login', '2020-12-07 22:37:28', '2020-12-07'),
(10, 44, 4, 'Login', '2020-12-07 23:22:25', '2020-12-07'),
(11, 47, 1, 'Login', '2020-12-15 18:11:09', '2020-12-15'),
(12, 48, 3, 'Login', '2020-12-15 18:13:27', '2020-12-15'),
(13, 49, 6, 'Login', '2020-12-15 18:17:33', '2020-12-15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `description` tinytext COLLATE latin1_spanish_ci NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `name`, `description`, `price`, `stock`, `role`) VALUES
(1, 'Parrillada', 'Parrillada completa para compartir', 500.00, 20, 6),
(2, 'Pizza', 'Masa crujiente cocinada en horno de piedra con fino colchón de salsa de tomate y Mozzarella', 232.00, 50, 6),
(3, 'Salteado de vegetales', 'Alternativa para aquellos que no comen carne', 180.00, 20, 6),
(4, 'Empanadas', 'Carne, pollo, verdura o humita', 30.00, 100, 6),
(5, 'Daiquiri', 'hecho a partir de ron blanco y jugo de limón o lima', 300.00, 20, 4),
(6, 'Martini', 'compuesto de ginebra con un chorro de vermut', 22.00, 20, 4),
(7, 'Vino tinto', 'Malbec o Cabernet Sauvignon', 102.00, 28, 4),
(8, 'Cerveza Lager', 'Elaboradas por fermentación baja', 75.00, 50, 5),
(9, 'Cerveza Lambic', 'Cervezas de fermentación espontánea', 85.00, 50, 5),
(10, 'Cerveza Ipa', 'De alto contenido de lúpulos y malta.', 77.00, 50, 5),
(11, 'Lemon Pie', 'Base de masa hojaldrada con relleno de exquisita crema de limón.', 310.00, 30, 7),
(12, 'Cheesecake', 'Con salsa de frutos rojos', 280.00, 30, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `code` varchar(5) COLLATE latin1_spanish_ci NOT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `user` int(11) NOT NULL,
  `table` varchar(5) COLLATE latin1_spanish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `estimated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`code`, `state`, `user`, `table`, `created_at`, `updated_at`, `estimated_at`) VALUES
('5pvb9', 0, 43, 'me003', '2020-12-01 22:55:31', '2020-12-01 22:55:31', NULL),
('5v9gs', 0, 43, 'me004', '2020-12-01 20:53:59', '2020-12-01 20:53:59', NULL),
('82q0s', 3, 43, 'me003', '2020-12-01 22:56:23', '2020-12-02 00:11:07', NULL),
('abxmy', 0, 43, 'me004', '2020-12-01 22:17:29', '2020-12-01 22:17:29', NULL),
('fgp26', 0, 43, 'me001', '2020-12-15 18:38:10', '2020-12-15 18:38:10', NULL),
('fwwst', 3, 43, 'me004', '2020-12-15 18:14:31', '2020-12-15 19:01:34', NULL),
('o6tct', 3, 43, 'me003', '2020-12-01 22:56:03', '2020-12-01 23:47:16', NULL),
('qjw6p', 0, 43, 'me004', '2020-12-01 22:16:00', '2020-12-01 22:16:00', NULL),
('sv3mb', 0, 43, 'me002', '2020-12-15 18:41:22', '2020-12-15 18:41:22', NULL),
('vl9lg', 0, 43, 'me003', '2020-12-01 23:32:39', '2020-12-01 23:32:39', NULL),
('x3gcq', 0, 43, 'me003', '2020-12-01 22:53:51', '2020-12-01 22:53:51', NULL),
('xp4d8', 0, 43, 'me004', '2020-12-01 21:32:31', '2020-12-01 21:32:31', NULL),
('yywoe', 0, 30, 'me001', '2020-11-28 19:40:52', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders_det`
--

CREATE TABLE `orders_det` (
  `id` int(11) NOT NULL,
  `user` varchar(20) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `order` varchar(5) COLLATE utf8mb4_spanish_ci NOT NULL,
  `menu` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `state` int(11) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `estimated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `orders_det`
--

INSERT INTO `orders_det` (`id`, `user`, `order`, `menu`, `amount`, `state`, `created_at`, `updated_at`, `estimated_at`) VALUES
(6, '44', 'pwql7', 7, 1, 2, '2020-12-01', '2020-12-01', NULL),
(8, '44', '1jpep', 1, 1, 1, '2020-12-01', '2020-12-07', '2020-12-07 03:00:00'),
(9, '44', 't1xko', 8, 1, 2, '2020-12-01', '2020-12-01', NULL),
(13, '44', 'kje1y', 1, 1, 1, '2020-12-01', '2020-12-07', '2020-12-07 23:16:52'),
(14, '44', 'o6tct', 8, 1, 3, '2020-12-01', '2020-12-01', NULL),
(18, '49', 'fwwst', 1, 1, 3, '2020-12-15', '2020-12-15', NULL),
(19, NULL, 'fgp26', 1, 1, 0, '2020-12-15', '2020-12-15', NULL),
(20, NULL, 'sv3mb', 1, 1, 0, '2020-12-15', '2020-12-15', NULL),
(21, NULL, 'sv3mb', 9, 1, 0, '2020-12-15', '2020-12-15', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_states`
--

CREATE TABLE `order_states` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `order_states`
--

INSERT INTO `order_states` (`id`, `name`) VALUES
(5, 'Cancelled'),
(2, 'Done'),
(4, 'Late'),
(0, 'Pending'),
(1, 'Preparing'),
(3, 'Served');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `table` varchar(5) COLLATE latin1_spanish_ci NOT NULL,
  `order` varchar(5) COLLATE latin1_spanish_ci NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `receipts`
--

INSERT INTO `receipts` (`id`, `user`, `table`, `order`, `total`, `created_at`, `updated_at`) VALUES
(3, 28, 'me001', 'kq8tw', 178.00, '2020-12-04 10:41:27', NULL),
(5, 45, 'me001', 'vl9lg', 585.00, '2020-12-02 01:08:47', '2020-12-01'),
(6, 45, 'me004', 'fwwst', 500.00, '2020-12-15 19:02:33', '2020-12-15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `order` varchar(5) COLLATE latin1_spanish_ci NOT NULL,
  `name` varchar(25) COLLATE latin1_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `description` text COLLATE latin1_spanish_ci NOT NULL,
  `table_score` int(11) NOT NULL,
  `menu_score` int(11) NOT NULL,
  `service_score` int(11) NOT NULL,
  `environment_score` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `removed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `reviews`
--

INSERT INTO `reviews` (`id`, `order`, `name`, `email`, `description`, `table_score`, `menu_score`, `service_score`, `environment_score`, `created_at`, `updated_at`, `removed_at`) VALUES
(4, 'yywoe', 'ElPepe', 'ep@mail.com', 'muy buena atencion', 7, 8, 8, 9, '2020-12-02 01:19:58', '2020-12-02 01:19:58', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Admin'),
(4, 'Bartender'),
(5, 'Cervecero'),
(6, 'Chef'),
(7, 'Maestro pastelero'),
(3, 'Mozo'),
(2, 'Socio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tables`
--

CREATE TABLE `tables` (
  `code` varchar(5) COLLATE latin1_spanish_ci NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT 0,
  `state` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `tables`
--

INSERT INTO `tables` (`code`, `capacity`, `state`, `created_at`, `updated_at`) VALUES
('me001', 1, 0, '2020-02-27 04:07:07', '2020-12-15 19:02:03'),
('me002', 5, 0, '2020-02-27 04:16:24', '2020-12-15 19:02:06'),
('me003', 7, 0, '2020-02-27 04:16:29', '2020-12-15 19:02:09'),
('me004', 3, 3, '2020-12-01 11:23:48', '2020-12-15 19:02:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `table_states`
--

CREATE TABLE `table_states` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `table_states`
--

INSERT INTO `table_states` (`id`, `name`) VALUES
(0, 'Available'),
(3, 'Paying'),
(2, 'Served'),
(1, 'Waiting');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `first_name` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `password` longblob NOT NULL,
  `email` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login_at` timestamp NULL DEFAULT NULL,
  `role` int(11) NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `first_name`, `last_name`, `password`, `email`, `created_at`, `last_login_at`, `role`, `updated_at`) VALUES
(30, 'Manager', 'Ger', 'Mana', 0x243279243130242f6d58784d423146586f795059544d7048376269452e4d48595638727a4e53396b38463944474943526430706e49663769475a6a75, 'ger.mana@comanda.com', '2020-11-30 05:29:36', NULL, 2, NULL),
(42, 'st001', 'Subject', 'Test', 0x31323334, 'st@mail.com', '2020-12-01 01:59:09', NULL, 2, NULL),
(43, 'st002', 'Subject', 'Test2', 0x31323334, 'st@mail.com', '2020-12-01 06:20:49', '2001-12-20 15:19:29', 2, '2020-12-01'),
(44, 'Bartender', 'Bartender', 'PRU', 0x31323334, 'bart@mail.com', '2020-12-01 22:27:25', '2007-12-20 11:22:25', 4, '2020-12-07'),
(45, 'SocioA', 'Socio', 'PRU', 0x31323334, 'soc@mail.com', '2020-12-01 23:21:08', '2007-12-20 10:31:19', 2, '2020-12-07'),
(46, 'Admin', 'Admin', 'Comanditero', 0x61646d313233, 'adm@mail.com', '2020-12-07 22:37:20', '2007-12-20 10:37:28', 1, '2020-12-07'),
(47, 'st003', 'Subject', 'Test2', 0x31323334, 'st@mail.com', '2020-12-15 18:10:34', '2015-12-20 06:11:09', 1, '2020-12-15'),
(48, 'MozoSTD', 'Mozo', 'Standard', 0x31323334, 'Mozzo@mail.com', '2020-12-15 18:12:52', '2015-12-20 06:13:27', 3, '2020-12-15'),
(49, 'Donato', 'Donato', 'Di Santi', 0x31323334, 'Chefo_D@mail.com', '2020-12-15 18:17:10', '2015-12-20 06:17:33', 6, '2020-12-15');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_user_idx` (`user`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu_role_idx` (`role`);

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`code`),
  ADD KEY `fk_order_user_idx` (`user`),
  ADD KEY `fk_order_table` (`table`),
  ADD KEY `fk_order_state_idx` (`state`);

--
-- Indices de la tabla `orders_det`
--
ALTER TABLE `orders_det`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `order_states`
--
ALTER TABLE `order_states`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Indices de la tabla `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_UNIQUE` (`order`);

--
-- Indices de la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order` (`order`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`code`),
  ADD KEY `fk_table_state_idx` (`state`);

--
-- Indices de la tabla `table_states`
--
ALTER TABLE `table_states`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `fk_role_id_idx` (`role`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `orders_det`
--
ALTER TABLE `orders_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `order_states`
--
ALTER TABLE `order_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_role` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_state` FOREIGN KEY (`state`) REFERENCES `order_states` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_order_table` FOREIGN KEY (`table`) REFERENCES `tables` (`code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`order`) REFERENCES `orders` (`code`);

--
-- Filtros para la tabla `tables`
--
ALTER TABLE `tables`
  ADD CONSTRAINT `fk_table_state` FOREIGN KEY (`state`) REFERENCES `table_states` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_role_id` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
