-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-10-2023 a las 20:20:37
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `productos_tpe`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre_categoria` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion_categoria` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre_categoria`, `descripcion_categoria`) VALUES
(28, 'Uñas', 'Productos para embellezer tus uñas '),
(33, 'Pelo', 'Productos capilares'),
(34, 'Cuidado Corporal', 'Productos para el cuidado corporal,hidratantes,exfoliantes y dermo protectores'),
(35, 'Maquillaje', 'Productos de alta calidad maquillantes para resaltar tu belleza');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `ID` int(11) NOT NULL,
  `nombre` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `descripcion` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `precio` int(11) NOT NULL,
  `marca` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`ID`, `nombre`, `descripcion`, `precio`, `marca`, `id_categoria`, `imagen`) VALUES
(58, 'Esmalte', 'Color Rojo', 4500, 'Meliné', 28, 'https://lasmargaritas.vtexassets.com/arquivos/ids/2030915-800-auto?v=638321054111930000&width=800&height=auto&aspect=true'),
(65, 'Lima', 'Gramaje 100/180', 350, 'CheryMoya', 28, 'https://distrinailsanfco.com/wp-content/uploads/2022/08/lima-media-luna-cheri-100-180-600x600.png'),
(66, 'Shampoo ', 'Shampoo para nutrir y reforzar tu cabello ', 2500, 'Pantene', 33, 'https://farmacityar.vtexassets.com/arquivos/ids/241821/220335_shampoo-pantene-miracle-fuerza-y-reconstruccion-x-400-ml___imagen-1.jpg?v=638188273621570000'),
(67, 'Acondicionador', 'Restaura tu cabello dejandolo fuerte', 2500, 'Pantene', 33, 'https://farmacityar.vtexassets.com/arquivos/ids/241822/220331_acondicionador-pantene-miracle-restauracion-x-400-ml__imagen-1.jpg?v=638188279419800000'),
(68, 'Crema Corporal con Manteca de Karite', '5 en 1 Serum de humectacion profunda,Vitamina', 3000, 'Nivea', 34, 'https://images-us.nivea.com/-/media/miscellaneous/media-center-items/6/5/2/c7d558138e8a4c558d7064e6b42c0ba3-screen.jpg'),
(69, 'Crema Exfoliante', 'Prepara la piel para una óptima absorción de ', 4800, 'IDRAET Probody', 34, 'https://acdn.mitiendanube.com/stores/997/647/products/body_exfoliant1-50a219469fcc43614f15885397508021-640-0.png'),
(70, 'Paleta de Colores', 'Colores frios y neutros', 10000, 'James Charles', 35, 'https://http2.mlstatic.com/D_NQ_NP_915798-MLA40978244092_032020-O.webp'),
(71, 'Labial', 'Color Nude y larga duracion', 25000, 'MAC', 35, 'https://http2.mlstatic.com/D_NQ_NP_773956-MLU70076797816_062023-O.webp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `clave` varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `clave`) VALUES
(2, 'admin', '$2y$10$H9yCQ/hfWD4oQw.HXAEcyOk6MRz0VguxS9/rl85/9AhM/PXBALYG6'),
(3, 'admin@gmail.com', '$2y$10$H9yCQ/hfWD4oQw.HXAEcyOk6MRz0VguxS9/rl85/9AhM/PXBALYG6');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `FK_CATEGORIA` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
