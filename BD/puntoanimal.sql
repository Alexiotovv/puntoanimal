-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-09-2024 a las 17:41:10
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
-- Base de datos: `puntoanimal`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`cpses_nitrl2rip2`@`localhost` PROCEDURE `add_detalle_temp` (IN `codigo` VARCHAR(50), IN `cantidad` INT, IN `precio` DECIMAL(11,2), IN `token_user` VARCHAR(50))   BEGIN
		-- DECLARE precio_actual DECIMAL(11,2);
		DECLARE repit_prod INT;
		DECLARE cant_actual INT default 0;
		DECLARE cant_final INT default 0;
		-- SELECT prodserviPrecio INTO precio_actual FROM productoservicio WHERE codProdservi = codigo;
		SET repit_prod=(SELECT COUNT(*) FROM detalle_temp tmp WHERE tmp.codproducto=codigo AND tmp.token_user=token_user); 
		IF (repit_prod>0) THEN
			SET cant_actual=(SELECT tmp.cantidad FROM detalle_temp tmp WHERE tmp.codproducto=codigo AND tmp.token_user=token_user);
			SET cant_final=cant_actual+cantidad;
			UPDATE detalle_temp tmp SET tmp.cantidad=cant_final WHERE tmp.codproducto=codigo AND tmp.token_user=token_user;
		ELSE
			INSERT INTO detalle_temp(token_user,codproducto,cantidad,precio_venta) VALUES(token_user,codigo,cantidad,precio);
		END IF;
		SELECT tmp.correlativo, tmp.codproducto,p.prodserviNombre,tmp.cantidad,tmp.precio_venta FROM detalle_temp tmp
		INNER JOIN productoservicio p
		ON tmp.codproducto = p.codProdservi
		WHERE tmp.token_user = token_user;
	END$$

CREATE DEFINER=`cpses_nitrl2rip2`@`localhost` PROCEDURE `del_detalle_temp` (IN `id_detalle` INT, IN `token` VARCHAR(50))   BEGIN 
  		DELETE FROM detalle_temp WHERE correlativo = id_detalle;
  		SELECT tmp.correlativo, tmp.codproducto,p.prodserviNombre,tmp.cantidad,tmp.precio_venta FROM detalle_temp tmp
  		INNER JOIN productoservicio p
  		ON tmp.codproducto = p.codProdservi
  		WHERE tmp.token_user = token;
  END$$

CREATE DEFINER=`cpses_nitrl2rip2`@`localhost` PROCEDURE `procesar_venta` (IN `cod_usuario` INT, IN `cod_cliente` VARCHAR(50), IN `token` VARCHAR(50), IN `tipo_pago` VARCHAR(50))   BEGIN
		DECLARE factura INT;
		DECLARE registros INT;
		DECLARE total DECIMAL(10,2);
		DECLARE nueva_existencia INT;
		DECLARE existencia_actual INT;
		DECLARE tmp_cod_producto varchar(50);
		DECLARE tmp_cant_producto INT;
		DECLARE a INT;
		SET a = 1;
		DROP TABLE IF EXISTS tbl_tmp_tokenuser;
		CREATE TEMPORARY TABLE tbl_tmp_tokenuser(
		id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		cod_prod VARCHAR(50),
		cant_prod INT(11));
		SET registros = (SELECT COUNT(*) FROM detalle_temp WHERE token_user = token);
		IF registros > 0 THEN 
			INSERT INTO tbl_tmp_tokenuser(cod_prod,cant_prod) SELECT codproducto,cantidad FROM detalle_temp WHERE  token_user = token;
			INSERT INTO venta(ventUsuario,dniCliente,ventMetodoPago) VALUES (cod_usuario,cod_cliente,tipo_pago);
			SET factura = LAST_INSERT_ID();
			INSERT INTO detalleventa(codFactura,codProducto,detalleCantidad,precio_venta) SELECT (factura) AS nofactura,codproducto,cantidad,precio_venta 
			FROM detalle_temp WHERE token_user = token;
			while a <= registros DO
				SELECT cod_prod,cant_prod INTO tmp_cod_producto,tmp_cant_producto FROM tbl_tmp_tokenuser WHERE id = a;
				SELECT prodserviStock INTO existencia_actual FROM productoservicio WHERE codProdservi = tmp_cod_producto;
				SET nueva_existencia = existencia_actual - tmp_cant_producto;
				UPDATE productoservicio SET prodserviStock = nueva_existencia WHERE codProdservi = tmp_cod_producto AND prodserviTipo = 'Producto';
				SET a=a+1; 
			END while;
			SET total = (SELECT SUM(cantidad*precio_venta) FROM detalle_temp WHERE token_user = token);
			UPDATE venta SET ventTotal = total WHERE idVenta = factura;
			DELETE FROM detalle_temp WHERE token_user = token;
			TRUNCATE TABLE tbl_tmp_tokenuser;
			SELECT * FROM venta WHERE idVenta = factura;
		ELSE
			SELECT 0;
		END IF;
	END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adjuntoshistorial`
--

CREATE TABLE `adjuntoshistorial` (
  `idAdjunto` int(11) NOT NULL,
  `codHistorialM` varchar(50) DEFAULT NULL,
  `adjTipo` varchar(50) DEFAULT NULL,
  `adjFileName` text DEFAULT NULL,
  `adjTitulo` varchar(100) DEFAULT NULL,
  `adjFecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `adjuntoshistorial`
--

INSERT INTO `adjuntoshistorial` (`idAdjunto`, `codHistorialM`, `adjTipo`, `adjFileName`, `adjTitulo`, `adjFecha`) VALUES
(6, 'HM-01745-13', 'jpg', 'adjuntos/historial-mascota/HM-01745-13/2024_02_13_192141_771_dietas humedas gatos.jpg', 'prueba', '2024-02-13'),
(7, 'HM-59126-40', 'pdf', 'adjuntos/historial-mascota/HM-59126-40/2024_02_24_105303_614_Lupita - Examen directo 22Feb24.pdf', '', '2024-02-24'),
(8, 'HM-02197-140', 'pdf', 'adjuntos/historial-mascota/HM-02197-140/2024_03_07_175846_343_HISTORIA CLINICA DE ISIS RECAIDO.pdf', '', '2023-12-21'),
(9, 'HM-07763-141', 'pdf', 'adjuntos/historial-mascota/HM-07763-141/2024_03_07_180545_637_HISTORIA CLINICA DE ISIS RECAIDA DE OTITIS DE ENERO DEL 2024.pdf', '', '2024-01-15'),
(10, 'HM-90606-142', 'pdf', 'adjuntos/historial-mascota/HM-90606-142/2024_03_07_182448_639_HISTORIA CLINICA DE PANCHA.pdf', '', '2023-12-15'),
(11, 'HM-01502-143', 'pdf', 'adjuntos/historial-mascota/HM-01502-143/2024_03_07_182737_201_HISTORIA CLINICA DE PANCHA DEL MES DE DICIEMBRE.pdf', 'HISTORIA CLINICA DEL MES DE DICIEMBRE', '2023-12-26'),
(12, 'HM-65275-145', 'pdf', 'adjuntos/historial-mascota/HM-65275-145/2024_03_07_183546_658_HISTORIA CLINICA DE CONIE.pdf', 'HISTORIA CLINICA DE CONIE', '2024-02-08'),
(13, 'HM-12041-165', 'pdf', 'adjuntos/historial-mascota/HM-12041-165/2024_03_08_180057_155_HISTORIA CLINICA DE HASHI.pdf', '', '2023-09-05'),
(14, 'HM-20962-168', 'pdf', 'adjuntos/historial-mascota/HM-20962-168/2024_03_08_180202_719_HISTORIA CLINICA DE HASHI.pdf', '', '2023-04-24'),
(15, 'HM-51693-328', 'pdf', 'adjuntos/historial-mascota/HM-51693-328/2024_03_11_142130_915_Zeus - Examen directo 14Feb24.pdf', '', '2024-03-11'),
(16, 'HM-68368-1033', 'pdf', 'adjuntos/historial-mascota/HM-68368-1033/2024_04_24_174800_517_Mojito - Hemograma canino 23Abr24.pdf', 'hemograma', '2024-04-23'),
(17, 'HM-68368-1033', 'pdf', 'adjuntos/historial-mascota/HM-68368-1033/2024_04_24_174800_407_Mojito - Bioquimica canino 23Abr24.pdf', 'bioquimica', '2024-04-23'),
(18, 'HM-68368-1033', 'pdf', 'adjuntos/historial-mascota/HM-68368-1033/2024_04_24_174800_242_Mojito - Hemoparasitos y ci 23Abr24.pdf', 'hemoparasitos', '2024-04-23'),
(19, 'HM-95015-1076', 'pdf', 'adjuntos/historial-mascota/HM-95015-1076/2024_05_12_111427_156_Abby - Hemograma canino 11May24.pdf', 'hemograma', '2024-05-12'),
(20, 'HM-95015-1076', 'pdf', 'adjuntos/historial-mascota/HM-95015-1076/2024_05_12_111427_536_Abby - Bioquimica canino 11May24.pdf', 'bioquimica', '2024-05-12'),
(21, 'HM-95015-1076', 'pdf', 'adjuntos/historial-mascota/HM-95015-1076/2024_05_12_111427_524_Abby - Hemoparasitos y ci 11May24.pdf', 'frotis', '2024-05-12'),
(22, 'HM-91370-5', 'jpg', 'adjuntos/historial-mascota/HM-91370-5/2024_06_02_141905_238_veterinariapublicidad.jpg', 'Prueba', '2024-06-02'),
(23, 'HM-08091-8', 'png', 'adjuntos/historial-mascota/HM-08091-8/2024_06_17_155010_610_829216.png', 'prueba', '2024-06-17'),
(24, 'HM-47983-10', 'png', 'adjuntos/historial-mascota/HM-47983-10/2024_06_18_133853_377_Sin título.png', 'analisis prologando', '2024-06-18'),
(25, 'HM-66791-12', 'png', 'adjuntos/historial-mascota/HM-66791-12/2024_06_21_163501_108_imprimir2.PNG', 'prueba', '2024-06-21'),
(26, 'HM-40345-3', 'pdf', 'adjuntos/historial-mascota/HM-40345-3/2024_08_26_135101_768_namecheap-order-150534643.pdf', '', '2024-08-26'),
(27, 'HM-89216-4', 'pdf', 'adjuntos/historial-mascota/HM-89216-4/2024_09_05_180613_461_31.08 LUNA.pdf', '', '2024-09-05'),
(29, 'HM-43632-7', 'pdf', 'adjuntos/historial-mascota/HM-43632-7/2024_09_09_150652_988_03.09 APOLO.pdf', 'doc2', '2024-09-09'),
(30, 'HM-68057-6', 'pdf', 'adjuntos/historial-mascota/HM-68057-6/2024_09_13_141908_370_09.09 ZEUS.pdf', '', '2024-09-13'),
(31, 'HM-49854-7', 'pdf', 'adjuntos/historial-mascota/HM-49854-7/2024_09_13_165144_697_13.09 BRUCKY.pdf', 'HEMOGRAMA', '2024-09-13'),
(32, 'HM-49854-7', 'pdf', 'adjuntos/historial-mascota/HM-49854-7/2024_09_13_165144_998_13.09 BRUCKY QUIMICA.pdf', 'QUIMICA SANGUINEA', '2024-09-13'),
(33, 'HM-50089-9', 'pdf', 'adjuntos/historial-mascota/HM-50089-9/2024_09_15_181046_455_12.09 DRACO.pdf', '', '2024-09-15'),
(34, 'HM-53970-21', 'pdf', 'adjuntos/historial-mascota/HM-53970-21/2024_09_19_145543_583_22.08 PATO.pdf', 'HEMOGRAMA', '2024-09-19'),
(35, 'HM-53970-21', 'pdf', 'adjuntos/historial-mascota/HM-53970-21/2024_09_19_145543_688_08.09 PATO.pdf', 'HEMOGRAMA', '2024-09-19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `idCita` int(11) NOT NULL,
  `codCita` varchar(50) DEFAULT NULL,
  `codMascota` varchar(50) DEFAULT NULL,
  `dniCliente` int(11) DEFAULT NULL,
  `citafechaEmitida` date DEFAULT NULL,
  `citaFechaProxima` date DEFAULT NULL,
  `citaHora` varchar(50) DEFAULT NULL,
  `citaMotivo` varchar(100) DEFAULT NULL,
  `citaEstado` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`idCita`, `codCita`, `codMascota`, `dniCliente`, `citafechaEmitida`, `citaFechaProxima`, `citaHora`, `citaMotivo`, `citaEstado`) VALUES
(41, 'CT-61698-1', 'CM35888-4', 12630724, '2024-09-06', '2024-09-07', '10:40 AM', 'SEGUNDO DIA DE TRATAMIENTO', 'Pendiente'),
(43, 'CT-87211-2', 'CM02820-4', 8188187, '2024-09-13', '2024-09-13', '11:51 AM', 'TRATAMIENTO', 'Pendiente'),
(44, 'CT-93079-3', 'CM28451-5', 68647263, '2024-09-13', '2024-09-14', '2:19 PM', 'tratamiento', 'Pendiente'),
(45, 'CT-68755-4', 'CM68776-12', 72623987, '2024-09-16', '2024-09-16', '1:12 PM', 'AQDSFADG', 'Procesada'),
(46, 'CT-24373-5', 'CM76512-10', 76314882, '2024-09-16', '2024-09-16', '7:33 PM', 'tratamiento', 'Pendiente'),
(47, 'CT-47535-6', 'CM17216-14', 76848355, '2024-09-16', '2024-09-17', '3:42 PM', 'tratamiento diarrea', 'Pendiente'),
(48, 'CT-79214-7', 'CM92854-7', 70078399, '2024-09-16', '2024-09-30', '7:10 PM', 'tratamiento , colocar tristezan y hemograma.', 'Pendiente'),
(49, 'CT-14635-8', 'CM76532-15', 73154314, '2024-09-17', '2024-09-18', '9:56 AM', 'tratamiento mamas', 'Pendiente'),
(50, 'CT-79776-9', 'CM01543-16', 63504999, '2024-09-17', '2024-10-07', '10:01 AM', 'REFUERZO OCTA', 'Pendiente'),
(51, 'CT-55117-10', 'CM68776-12', 72623987, '2024-09-17', '2024-10-02', '5:45 PM', 'inyectar tristezan', 'Pendiente'),
(52, 'CT-60432-11', 'CM17216-14', 76848355, '2024-09-17', '2024-09-19', '4:35 PM', 'vacuna parvovirus', 'Pendiente'),
(53, 'CT-96024-12', 'CM50492-18', 73160033, '2024-09-19', '2024-10-04', '9:12 AM', 'REFUERZO HEXAVALENTE', 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idCliente` int(11) NOT NULL,
  `clienteDniCedula` varchar(250) NOT NULL,
  `clienteNombre` varchar(50) DEFAULT NULL,
  `clienteApellido` varchar(50) DEFAULT NULL,
  `clienteGenero` varchar(20) DEFAULT NULL,
  `clienteTelefono` varchar(20) DEFAULT NULL,
  `clienteCorreo` varchar(150) DEFAULT NULL,
  `clienteDomicilio` varchar(150) DEFAULT NULL,
  `clienteFotoUrl` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idCliente`, `clienteDniCedula`, `clienteNombre`, `clienteApellido`, `clienteGenero`, `clienteTelefono`, `clienteCorreo`, `clienteDomicilio`, `clienteFotoUrl`) VALUES
(1182, '6357306', 'Jose', 'Camacho', 'Masculino', '71360245', 'jmcamacho2014@gmail.com', 'Av G77', 'vistas/images/avatar_user_cli/avatar_cli_5.svg'),
(1183, '9725961', 'WILMER', 'ARANCIBIA', 'Masculino', '63373021', '', 'VILLA LOPEZ 1', NULL),
(1184, '12630724', 'MAYI', 'MAIZANI', 'Femenino', '69195519', '', 'CORTEZ', NULL),
(1187, '8188187', 'AIDE', 'MOLINA', 'Masculino', '59178088572', '', 'CORTEZ', 'vistas/images/avatar_user_cli/avatar_cli_10.svg'),
(1188, '68647263', 'JAVIER', 'BASPINEIRO', 'Masculino', '68647263', '', 'CORTEZ', NULL),
(1189, '76354470', 'DAYANA', 'LEON', 'Femenino', '76354470', '', 'B/ LOMAS DEL SUR', NULL),
(1190, '70078399', 'YOSELIN', 'CARDENAS', 'Femenino', '70078399', '', 'B/ 4 DE AGOSTO', NULL),
(1191, '68836839', 'CINTHIA', 'CAHUAYA', 'Femenino', '68836839', '', 'CORTEZ', NULL),
(1192, '76314882', 'DANIELA', 'TERCEROS', 'Femenino', '76314882', '', 'B/ TRANSPORTISTA SUR SEXTO ANILLO', NULL),
(1193, '62120193', 'REYCHEL', 'FLORES', 'Femenino', '62120193', '', 'CORTEZ', NULL),
(1194, '72623987', 'EDWIN', 'SOTOMAYOR', 'Masculino', '72623987', 'soto@gmail.com', 'B/ CORTEZ', 'adjuntos/clientes-foto/72623987_17_09_2024_155926ImagendeWhatsApp2024-09-12alas12.17.57_b039cffc.jpg'),
(1195, '76848355', 'JUAN', 'FLORES', 'Masculino', '76848355', '', 'PLAN 4000', 'adjuntos/clientes-foto/76848355_18_09_2024_160608ImagendeWhatsApp2024-09-12alas12.17.57_b039cffc.jpg'),
(1196, '73154314', 'ESTHER LIMA', 'LIMA', 'Femenino', '73154314', '', 'CORTEZ', 'vistas/images/avatar_user_cli/avatar_cli_2.svg'),
(1197, '63504999', 'GLADIS', 'BRITO', 'Femenino', '63504999', '', 'CORTEZ', 'adjuntos/clientes-foto/63504999_17_09_2024_152919ImagendeWhatsApp2024-09-12alas12.17.57_b039cffc.jpg'),
(1198, '12345678', 'sD', 'SAF', 'Femenino', '12345678', 'jmca2014@gmail.com', 'ASF', 'adjuntos/clientes-foto/12345678_17_09_2024_154946ImagendeWhatsApp2024-09-12alas12.28.39_d092fdec.jpg'),
(1199, '6357305', 'SDG', 'LEON', 'Masculino', '76354470', 'jmcacho2014@gmail.com', 'DSGDF', 'adjuntos/clientes-foto/6357305_17_09_2024_154646ImagendeWhatsApp2024-09-12alas12.28.39_d092fdec.jpg'),
(1200, '8195257', 'Juan', 'Quespia', 'Masculino', '75507990', '', 'Cortez', 'adjuntos/clientes-foto/8195257_19_09_2024_093448ImagendeWhatsApp2024-09-12alas12.28.39_d092fdec.jpg'),
(1203, '70895369', 'miguel', 'mendoza', 'Masculino', '70895369', '', 'b/ bethesda', NULL),
(1204, '73160033', 'ESPERANZA', 'PAZ', 'Femenino', '73160033', '', 'B/ CORTEZ', NULL),
(1205, '123456789', 'MICHEL', 'QUES', 'Masculino', '78945612', '', 'CORTEZ', 'adjuntos/clientes-foto/123456789_20_09_2024_111502ImagendeWhatsApp2024-09-20alas11.13.30_c514f020.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleventa`
--

CREATE TABLE `detalleventa` (
  `idDetalle` int(11) NOT NULL,
  `codFactura` int(11) DEFAULT NULL,
  `codProducto` varchar(50) DEFAULT NULL,
  `detalleCantidad` int(11) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `detalleventa`
--

INSERT INTO `detalleventa` (`idDetalle`, `codFactura`, `codProducto`, `detalleCantidad`, `precio_venta`) VALUES
(2610, 934, 'CS-10148-2', 1, 150.00),
(2611, 935, 'CP-56830-417', 1, 22.00),
(2612, 936, 'CS-10148-2', 1, 150.00),
(2613, 936, 'CS-59111-72', 1, 50.00),
(2615, 937, 'CP-25692-327', 1, 12.00),
(2616, 938, 'CS-54806-7', 1, 60.00),
(2617, 939, 'CS-10148-2', 1, 150.00),
(2618, 940, 'CS-10148-2', 1, 150.00),
(2619, 941, 'CS-14134-118', 1, 200.00),
(2620, 942, 'CS-10148-2', 1, 150.00),
(2621, 943, 'CS-43447-411', 1, 100.00),
(2622, 944, 'CS-04177-108', 2, 122.00),
(2623, 945, 'CS-27803-8', 3, 68.00),
(2624, 945, 'CS-10148-2', 1, 150.00),
(2626, 946, 'CS-10148-2', 1, 150.00),
(2627, 946, 'CS-42508-9', 1, 70.00),
(2629, 947, 'CS-10148-2', 1, 150.00),
(2630, 948, 'CS-66997-139', 1, 50.00),
(2631, 949, 'CS-66997-139', 2, 50.00),
(2632, 949, 'CS-88988-63', 1, 75.00),
(2634, 950, 'CP-33311-146', 1, 3.00),
(2635, 950, 'CP-66674-416', 2, 30.00),
(2637, 951, 'CP-66674-416', 1, 30.00),
(2638, 952, 'CP-66674-416', 1, 30.00),
(2639, 953, 'CP-53031-299', 1, 35.00),
(2640, 954, 'CP-65052-3', 1, 50.00),
(2641, 954, 'CS-79635-60', 1, 55.00),
(2642, 955, 'CP-65052-3', 1, 50.00),
(2643, 956, 'CP-65052-3', 1, 50.00),
(2644, 957, 'CP-65052-3', 1, 50.00),
(2645, 958, 'CP-65052-3', 1, 50.00),
(2646, 959, 'CP-86506-276', 1, 18.00),
(2647, 960, 'CP-65052-3', 1, 50.00),
(2648, 961, 'CP-65052-3', 1, 50.00),
(2649, 962, 'CP-65052-3', 1, 50.00),
(2650, 963, 'CP-65052-3', 1, 50.00),
(2651, 964, 'CP-65052-3', 1, 50.00),
(2652, 965, 'CP-92787-412', 1, 20.00),
(2653, 965, 'CS-43447-411', 1, 30.00),
(2655, 966, 'CP-65052-3', 1, 50.00),
(2656, 967, 'CP-65052-3', 1, 50.00),
(2657, 968, 'CP-46771-152', 2, 4.00),
(2658, 969, 'CS-01799-404', 1, 20.00),
(2659, 969, 'CS-43447-411', 1, 30.00),
(2660, 969, 'CP-92787-412', 1, 20.00),
(2661, 970, 'CP-65052-3', 1, 50.00),
(2662, 971, 'CP-65052-3', 1, 50.00),
(2663, 972, 'CP-28740-274', 1, 50.00),
(2664, 973, 'CP-28740-274', 1, 50.00),
(2665, 974, 'CP-53031-299', 2, 35.00),
(2666, 974, 'CP-37261-164', 2, 35.00),
(2668, 975, 'CS-01799-404', 1, 20.00),
(2669, 976, 'CP-83570-405', 2, 34.00),
(2670, 976, 'CS-76437-105', 1, 150.00),
(2671, 977, 'CP-83570-405', 2, 35.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `correlativo` int(11) NOT NULL,
  `token_user` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `codproducto` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_venta` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `detalle_temp`
--

INSERT INTO `detalle_temp` (`correlativo`, `token_user`, `codproducto`, `cantidad`, `precio_venta`) VALUES
(21, 'c4ca4238a0b923820dcc509a6f75849b', 'CP-65052-3', 1, 50.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `idempresa` int(20) NOT NULL,
  `rif` varchar(50) NOT NULL DEFAULT '',
  `empresaNombre` varchar(100) DEFAULT NULL,
  `empresaDireccion` varchar(200) DEFAULT NULL,
  `empresaTelefono` varchar(20) DEFAULT NULL,
  `empresaCorreo` varchar(100) DEFAULT NULL,
  `empresaFotoUrl` text DEFAULT NULL,
  `empresaMoneda` varchar(10) DEFAULT NULL,
  `empresaIva` decimal(10,2) DEFAULT NULL,
  `estado_certificado` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`idempresa`, `rif`, `empresaNombre`, `empresaDireccion`, `empresaTelefono`, `empresaCorreo`, `empresaFotoUrl`, `empresaMoneda`, `empresaIva`, `estado_certificado`) VALUES
(2, '6357306', 'Veterinaria Punto Animal', 'Barrio Cortez', '+59175507990', 'jmcamacho2014@gmail.com', 'adjuntos/logo-empresa/logo.jpeg', 'Bs', 0.00, 'PRODUCCION');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especie`
--

CREATE TABLE `especie` (
  `idEspecie` int(11) NOT NULL,
  `espNombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `especie`
--

INSERT INTO `especie` (`idEspecie`, `espNombre`) VALUES
(10, 'CANINO'),
(11, 'FELINO'),
(12, 'AVE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historialmascota`
--

CREATE TABLE `historialmascota` (
  `idHistorial` int(11) NOT NULL,
  `codHistorialM` varchar(50) NOT NULL,
  `histFecha` date NOT NULL,
  `histHora` time NOT NULL,
  `histMotivo` varchar(350) NOT NULL,
  `histAnamnesis` varchar(400) DEFAULT NULL,
  `histSintomas` varchar(350) NOT NULL,
  `histDiagnostico` varchar(350) NOT NULL,
  `histDiagnosticoPre` varchar(350) DEFAULT NULL,
  `histTratamiento` varchar(350) NOT NULL,
  `histCreador` varchar(100) NOT NULL,
  `codMascota` varchar(50) NOT NULL,
  `vetDni` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `historialmascota`
--

INSERT INTO `historialmascota` (`idHistorial`, `codHistorialM`, `histFecha`, `histHora`, `histMotivo`, `histAnamnesis`, `histSintomas`, `histDiagnostico`, `histDiagnosticoPre`, `histTratamiento`, `histCreador`, `codMascota`, `vetDni`) VALUES
(1112, 'HM-44081-1083', '2024-05-13', '18:46:00', 'vacuna cuadruple', '', '', '', '', 'vac cuadruple', 'Usuario uno', 'CM31713-1415', 40921809),
(1126, 'HM-89216-4', '2024-09-05', '17:54:00', 'tos', 'Hace una semana esta tosiendo y ya se le medico y la tos va disminuyendo de a poco, apetito normal', '', 'hemograma', '', 'bovomicina 0.28 ml\r\nbroncafor 0.2 ml\r\ndexametasona 0.18\r\nrp// ambroxol 0.5ml cada 12horas\r\n       doxican 0.7 ml c/24 x 14 dias', 'juan daniel Quespia', 'CM19996-3', 6357306),
(1127, 'HM-56283-5', '2024-09-06', '08:36:00', 'HACE 2 DIAS QUE NO COME', 'TIENE FIEBRE 39.5 ESTA DECAIDA, TRISTE', 'FIEBRE', 'HEMOGERAMA', 'ERLICHIOSIS', 'BOVOMICINA 3 ML\r\nBIOXAN 15 ML\r\nDESALGINA 1 ML', 'juan daniel Quespia', 'CM35888-4', 6357306),
(1128, 'HM-93688-6', '2024-09-03', '10:36:00', 'MOTIVO', 'ASCF', 'FXV', 'XZVV', 'DSG', 'DS', 'juan daniel Quespia', 'CM46476-5', 8195257),
(1130, 'HM-43632-7', '2024-09-09', '15:06:00', 'motivo3', 'sdffdfg', 'sdg', 'saffas', 'dgsgds', 'dsggds', 'juan daniel Quespia', 'CM46476-5', 8195257),
(1131, 'HM-68057-6', '2024-09-13', '14:13:00', 'la anterior semana estaba sin mucho apetito', 'fiebre, falta de apetito, decaido\r\nhoy quizo vomitar, comio poco', 'T 40°c\r\npeso 10.4', 'hemograma', 'moquillo \r\nerlichiosis', 'bovomicina 2ml\r\nbioxan 10ml\r\nmercepton 1 ml', 'juan daniel Quespia', 'CM28451-5', 9784134),
(1132, 'HM-49854-7', '2024-09-13', '16:44:00', 'FALTA DE APETITO', 'HACE 5 DIAS PRESENTA PROBLEMA OCULAR(OJO EMPAÑADO AZULADO) HACE 2 MESES LE DIERON NEXGARD', 'GANGLIO LEVEMENTE INFLAMADO', 'HEMOGRAMA\r\nQUIMICA SANGUINEA', 'ERLICHIOSIS\r\nBABESIOSIS\r\nHEPATITIS', 'BIOXAN 11 ML IV\r\nBOVOMICINA 2 ML IV \r\nMERCEPTON 1 ML IV\r\nCOMPLEJO B 0.5 ML IV \r\nDESALGINA IM', 'MAX IBARRA', 'CM92854-7', 9784134),
(1133, 'HM-92331-8', '2024-09-13', '18:06:00', 'Tiene la pata derecha trasera , la almohadilla pelada', 'tiene niguas, almohadilla trasera pelada, con apetito normal . no asienta la pata trasera', '', '', '', 'dexametasona 0.5\r\npenduo 0.5\r\nrp// fipronil local cada 12 horas por 3 dias\r\nrifamicina , curar 2 veces al dia hasta ver mejoria', 'MAX IBARRA', 'CM65089-8', 9784134),
(1134, 'HM-50089-9', '2024-09-15', '18:04:00', 'DECAIDO', 'HACE UNAS SEMANAS EMPEZO A DECAER,DE APETITO NORMAL,TIENE ANTECEDENTE DE ERLICHIOSIS', 'TEMPERATURA ALTA(40)', 'HEMOGRAMA', 'ERLICHIOSIS\r\nBABESIOSIS', 'BOVOMICINA\r\nBIOXAN\r\nDESALGINA\r\nCOMPLEJO B\r\nMERCEPTON', 'MAX IBARRA', 'CM68776-12', 9784134),
(1135, 'HM-30201-10', '2024-09-21', '12:51:00', 'ADSF', 'Afsad', 'wfqr', 'wafqr', 'efwsdg', 'WFQER', 'juan daniel Quespia', 'CM10854-13', 9784134),
(1136, 'HM-49228-11', '2024-09-16', '16:53:00', 'Adfwasdgs', 'fwsa', 'saf', 'fwsa', 'saf', 'aDS', 'juan daniel Quespia', 'CM10854-13', 9784134),
(1137, 'HM-33616-12', '2024-09-16', '14:24:00', 'VIENE A RECONSULTA, TRISTEZAN', 'ESTA CON BUEN APETITO, ANIMADO\r\nVIENE POR SEGUNDA DOSIS DE TRISTESAN\r\nP 10.55', '', '', '', 'TRISTEZAN 0.85 ML\r\nMERCEPTON 1 ML\r\nCOMPLEJO B 0.5', 'juan daniel Quespia', 'CM14086-6', 8195257),
(1138, 'HM-70982-13', '2024-09-16', '17:39:00', 'falta de apetito y diarrea', 'hoy no quizo comer nada, tiene diarrea fetida cafe, intentos de vomito', 't 37.8\r\np 0.8 k', '', 'parasitosis\r\nparvovirus', 'albexnort\r\npenduo\r\ndexa', 'juan daniel Quespia', 'CM17216-14', 8195257),
(1139, 'HM-28220-14', '2024-09-16', '18:30:00', 'tratamiento', 'esta mejor , sigue decaido, esta comiendo', 'peso 18.5 kg\r\nt 38.4', '', 'erlichiosis', 'bovomicina, bioxan, mercepton', 'juan daniel Quespia', 'CM68776-12', 8195257),
(1140, 'HM-63995-15', '2024-09-16', '18:49:00', 'tratamientoerlichiosis y hepatitis', 'esta mejor, sus ojos ya estan mas claros, tiene respuesta fotoreceptora\r\npeso 11.03 kg\r\ntemp 38.9', '', 'erlichiosis', '', 'dexamino, bovomicina, bioxan, mercepton, complejo b\r\ntristesan', 'juan daniel Quespia', 'CM92854-7', 8195257),
(1141, 'HM-16339-16', '2024-09-17', '08:59:00', 'Ttratamiento de diarrea', 'ya vino hace 2 dias y se le puso penduo y dexa, ya esta mejor \r\npeso 0.85 kg', 'ano inflamado \r\ndiarrea no muy liquida', 'hemograma', 'parasitosis\r\ninfeccion', 'albexnort, penduo, dexametasona', 'juan daniel Quespia', 'CM84450-11', 8195257),
(1142, 'HM-69782-17', '2024-09-17', '09:48:00', 'ayer estaba inchada la pata', 'se ve una herida en almohadilla izq trasera\r\ntiene 2 mamas inflamadas q desprenden secresion\r\nesta comiendo bien y no esta deprimida', 'p 19.7\r\nt 38', '', '', 'dexametasona, pen duo, mercepton', 'juan daniel Quespia', 'CM76532-15', 8195257),
(1143, 'HM-48613-18', '2024-09-17', '17:43:00', 'tratamiento', 'YA ESTA MAS ANIMADO , APETITO NORMAL', '', '', '', 'bovomicina, mercepton, bioxan vitamina comp b y tristezan', 'juan daniel Quespia', 'CM68776-12', 8195257),
(1144, 'HM-40048-19', '2024-09-17', '18:33:00', 'tratamiento', 'ya no esta con diarrea ni intentos de vomitos, esta con buen apetito, esta animico\r\napetito normal\r\npeso0.95 kg', '', '', '', 'penduo , dexametasona', 'juan daniel Quespia', 'CM17216-14', 8195257),
(1145, 'HM-09326-20', '2024-09-18', '19:45:00', 'inflamacion en el cuello', 'hace 3 dias tiene una picazon en la parte de debajo del cuello q se formo una inflamacion q baja hasta la pata y sube hasta el cuello.\r\ntiene 2 masa alarededor de las amigdalas', '', 'hemograma \r\nperfil hepatico\r\nperfil renal', 'alergia', 'dexametasona\r\npenduo\r\nmercepton', 'juan daniel Quespia', 'CM75194-17', 9784134),
(1146, 'HM-53970-21', '2024-09-19', '14:49:00', 'DECAIMIENTO', 'TRATAMIENTO ERLICHIOSIS EL 22-08-2024\r\nCONTROL 08-09-2024\r\nULTIMO DIA TTO 24-09-24', '', 'HEMOGRAMA', 'ERLICHIOSIS', 'BOVOMICINA\r\nMERCEPTON\r\nCOMPLEJO B\r\nBIOXAN', 'MAX IBARRA', 'CM50492-18', 9784134);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historialvacuna`
--

CREATE TABLE `historialvacuna` (
  `idHistoriaVacuna` int(11) NOT NULL,
  `idVacuna` int(11) DEFAULT 0,
  `historiavFecha` date DEFAULT NULL,
  `historiavFechaProxVacuna` date DEFAULT NULL,
  `historiavProducto` varchar(150) DEFAULT NULL,
  `historiavObser` varchar(150) DEFAULT NULL,
  `codMascota` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `historialvacuna`
--

INSERT INTO `historialvacuna` (`idHistoriaVacuna`, `idVacuna`, `historiavFecha`, `historiavFechaProxVacuna`, `historiavProducto`, `historiavObser`, `codMascota`) VALUES
(1, 7, '2024-05-11', NULL, 'nobivac', 'se desp con fripets', 'CM53314-1414'),
(65, 3, '2024-05-14', '2024-05-17', 'anti pulgas', 'aaa', 'CM37148-4'),
(66, 7, '2024-05-14', '2024-05-18', 'vacunen', 'ddd', 'CM37148-4'),
(67, 11, '2024-05-16', '2024-06-04', 'vacunen', 'e', 'CM20373-2'),
(68, 4, '2024-05-23', '2024-05-24', 'vacunen', 'w', 'CM60811-4'),
(69, 4, '2024-05-24', '2024-05-29', 'vacunen', 's', 'CM60811-4'),
(70, 4, '2024-05-28', '2024-05-30', 'vacunen', 'eee', 'CM18314-3'),
(71, 4, '2024-05-28', '2024-06-04', 'h', 'h', 'CM60811-4'),
(72, 3, '2024-06-03', '2024-06-18', '8125', 'jnj', 'CM22122-5'),
(73, 10, '2024-06-17', '2024-06-18', 'vacunen', 'ninguna', 'CM45850-7'),
(74, 9, '2024-06-17', '2024-06-19', 'Prueba', 'gg', 'CM45850-7'),
(75, 9, '2024-06-18', '2024-06-19', 'anti pulgas', 'temporal', 'CM07019-8'),
(76, 9, '2024-06-19', '2024-06-21', 'fi9', 's', 'CM07019-8'),
(77, 10, '2024-06-21', '2024-06-23', 'pueba', 'pueba', 'CM07019-8'),
(78, 9, '2024-06-21', '2024-06-25', 'pueba', 'puebapueba', 'CM07019-8'),
(79, 9, '2024-06-26', '2024-06-30', 'ñ', '', 'CM07019-8'),
(82, 4, '2024-09-16', '2024-09-05', 'rtttt', 'awfed', 'CM10854-13'),
(83, 3, '2024-09-16', '0000-00-00', 'ACsf', 'DAFS', 'CM10854-13'),
(84, 13, '2024-09-17', '0000-00-00', 'OSTAVALENTE', 'TAMBIEN SE LO DESPARASITO', 'CM01543-16'),
(85, 4, '2024-09-19', '0000-00-00', 'ZOETIS', 'SE EMPEZO CON SU VACUNA , PERO CONTINUA CON PROBLEMA DE PIEL, SE DESP. EL 08.09', 'CM50492-18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascota`
--

CREATE TABLE `mascota` (
  `idmascota` int(11) NOT NULL,
  `codMascota` varchar(50) DEFAULT NULL,
  `mascotaNombre` varchar(100) DEFAULT NULL,
  `mascotaFechaN` date DEFAULT NULL,
  `mascotaPeso` varchar(20) DEFAULT NULL,
  `mascotaColor` varchar(100) DEFAULT NULL,
  `idEspecie` int(11) DEFAULT NULL,
  `idRaza` int(11) DEFAULT NULL,
  `mascotaFoto` text DEFAULT NULL,
  `mascotaSexo` varchar(10) DEFAULT NULL,
  `castrado` varchar(10) DEFAULT NULL,
  `mascotaAdicional` varchar(200) DEFAULT NULL,
  `dniDueno` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `mascota`
--

INSERT INTO `mascota` (`idmascota`, `codMascota`, `mascotaNombre`, `mascotaFechaN`, `mascotaPeso`, `mascotaColor`, `idEspecie`, `idRaza`, `mascotaFoto`, `mascotaSexo`, `castrado`, `mascotaAdicional`, `dniDueno`) VALUES
(1457, 'CM19996-3', 'LUNA', '2024-07-28', '1.4', 'NEGRO', 10, 16, NULL, 'Hembra', 'No', '', '9725961'),
(1458, 'CM35888-4', 'MINI HAN', '2023-12-01', '14.9', 'NEGRO', 10, 16, NULL, 'Hembra', 'Si', '', '12630724'),
(1459, 'CM46476-5', 'Luzmila', '2024-07-12', '1.4', 'marrón', 10, 52, NULL, 'Hembra', 'No', 'SADGDFGFDHHFD', '6357306'),
(1461, 'CM02820-4', 'KIARA', '2018-11-01', '40', 'NEGRO', 10, 47, NULL, 'Hembra', 'No', '', '8188187'),
(1462, 'CM28451-5', 'ZEUS', '2024-06-10', '10.4', 'NEGRO', 10, 42, NULL, 'Macho', 'No', '', '68647263'),
(1463, 'CM14086-6', 'ATLAS', '2024-02-20', '8.7', 'ATIGRADO', 10, 34, NULL, 'Macho', 'No', '', '76354470'),
(1464, 'CM92854-7', 'BRUCKY', '2024-03-03', '10.7', 'BLANCO/AMARILLO', 10, 16, NULL, 'Macho', 'No', '', '70078399'),
(1465, 'CM65089-8', 'ANI', '2018-11-01', '5.1', 'BLANCO', 10, 16, NULL, 'Hembra', 'No', 'SE LE COLOCA ANTICONCEPTIVOS SEMESTRAL', '12630724'),
(1466, 'CM26961-9', 'KIARA', '2024-06-10', '10.45', 'AMARRILLO', 10, 16, NULL, 'Hembra', 'No', '', '68836839'),
(1467, 'CM76512-10', 'OTTO', '2024-01-26', '6.75', 'NEGRO/CAFE', 10, 16, NULL, 'Macho', 'Si', '', '76314882'),
(1468, 'CM84450-11', 'VAQUITA', '2024-06-10', '0.75', 'OVERA NEGRO Y BLANCO', 11, 7, NULL, 'Hembra', 'No', '', '62120193'),
(1469, 'CM68776-12', 'DRACO', '2023-08-10', '18.7', 'BLANCO/NEGRO/PLOMO', 10, 16, NULL, 'Macho', 'No', '', '72623987'),
(1471, 'CM10854-13', 'Felipe', '2024-07-26', '40', 'marron', 10, 42, 'adjuntos/mascotas-foto/72623987_16_09_2024_124954_cielo.jpg', 'Macho', 'No', '', '72623987'),
(1472, 'CM17216-14', 'FLOR', '2024-08-15', '0.8', 'BLANCO', 10, 16, NULL, 'Hembra', 'No', '', '76848355'),
(1473, 'CM76532-15', 'ROSY', '2018-09-15', '19.7', 'AMARRILLO', 10, 16, 'adjuntos/mascotas-foto/73154314_17_09_2024_145947_YUTAKA.jpg', 'Hembra', 'No', '', '73154314'),
(1474, 'CM01543-16', 'YUTAKA', '2024-03-21', '5.2', 'NEGRO', 10, 16, 'adjuntos/mascotas-foto/63504999_17_09_2024_152837_YUTAKA.jpg', 'Macho', 'No', 'RECUPERO DE PARVOVIRUS', '63504999'),
(1477, 'CM75194-17', 'REYNA', '2006-01-31', '11.6', 'NEGRO', 10, 16, NULL, 'Hembra', 'Si', '', '70895369'),
(1478, 'CM50492-18', 'PATO', '2024-05-22', '6.75', 'BLANCO/NEGRO/CAFE', 10, 16, NULL, 'Macho', 'No', '', '73160033');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notasmascotas`
--

CREATE TABLE `notasmascotas` (
  `idNota` int(11) NOT NULL,
  `codMascota` varchar(50) DEFAULT NULL,
  `notaDescripcion` varchar(140) DEFAULT NULL,
  `notaFecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `notasmascotas`
--

INSERT INTO `notasmascotas` (`idNota`, `codMascota`, `notaDescripcion`, `notaFecha`) VALUES
(32, 'CM31177-145', 'BAÑO MEDICADO Y RETOQUES COSTO 75.00', '2024-05-11'),
(35, 'CM18873-6', 'les gusta morder con cuidado', '2024-06-17'),
(36, 'CM45850-7', 'el perro es delicado y nervisoso', '2024-06-17'),
(37, 'CM07019-8', 'el felino araña', '2024-06-18'),
(38, 'CM07019-8', 'kjkjkj', '2024-06-24'),
(40, 'CM46476-5', 'NOTA2fdsgds', '2024-09-09'),
(41, 'CM28451-5', 'SOSPECHOSOS DE MOQUILLO', '2024-09-13'),
(42, 'CM28451-5', 'SE DIO RECETA POR 2 SEMANAS, EN 1 SEMANA VOLVER PARA CONTROL', '2024-09-15'),
(43, 'CM92854-7', 'ESTA MAS ANIMADO, LO DEL OJO TAMBIEN ESTA MEJORANDO', '2024-09-15'),
(44, 'CM14086-6', 'DESPARASITACION TRIPLEX P10.5 KG', '2024-09-16'),
(45, 'CM76532-15', 'mamas inflamadas sospecha de tumor mamario', '2024-09-17'),
(46, 'CM01543-16', 'YA TIENE UNA VACUNA DE OCTAVALENTE 27-08-2024', '2024-09-17'),
(47, 'CM50492-18', 'EMPEZO CON SU VACUNA 18-09', '2024-09-19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productoservicio`
--

CREATE TABLE `productoservicio` (
  `idProdservi` int(11) NOT NULL,
  `codProdservi` varchar(50) DEFAULT NULL,
  `prodserviNombre` varchar(100) DEFAULT NULL,
  `prodserviTipo` varchar(50) DEFAULT NULL,
  `prodserviPrecio` decimal(11,2) DEFAULT NULL,
  `prodserviStock` int(11) DEFAULT NULL,
  `fVencimiento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `productoservicio`
--

INSERT INTO `productoservicio` (`idProdservi`, `codProdservi`, `prodserviNombre`, `prodserviTipo`, `prodserviPrecio`, `prodserviStock`, `fVencimiento`) VALUES
(4, 'CS-10148-2', 'Grooming', 'Servicio', 150.00, 1, NULL),
(5, 'CP-65052-3', 'Collar AntiPulga', 'Producto', 50.00, 0, NULL),
(8, 'CS-88359-6', 'CORTE DE PELO', 'Servicio', 30.00, 1, NULL),
(9, 'CS-54806-7', 'BANO MEDICADO Y CORTE 1', 'Servicio', 60.00, 1, NULL),
(10, 'CS-27803-8', 'BAÑO MEDICADO Y CORTE 2', 'Servicio', 65.00, 1, NULL),
(11, 'CS-42508-9', 'BAÑO MEDICADO Y CORTE 3', 'Servicio', 70.00, 1, NULL),
(12, 'CS-85321-10', 'BAÑO MEDICADO Y CORTE 4', 'Servicio', 75.00, 1, NULL),
(13, 'CS-75064-11', 'BAÑO MEDICADO Y CORTE 5', 'Servicio', 80.00, 1, NULL),
(14, 'CS-99904-12', 'BAÑO MEDICADO Y CORTE 6', 'Servicio', 85.00, 1, NULL),
(15, 'CS-50914-13', 'BAÑO MEDICADO Y CORTE 7', 'Servicio', 90.00, 1, NULL),
(16, 'CS-08561-14', 'BAÑO MEDICADO Y CORTE 8', 'Servicio', 95.00, 1, NULL),
(17, 'CS-91500-15', 'BAÑO MEDICADO Y CORTE 9', 'Servicio', 100.00, 1, NULL),
(18, 'CS-03965-16', 'BAÑO NORMAL 1', 'Servicio', 30.00, 1, NULL),
(19, 'CP-35584-17', 'HEMOLITAN DE 10ml', 'Producto', 65.00, 3, NULL),
(20, 'CS-40238-18', 'CONSULTA', 'Servicio', 15.00, 1, NULL),
(21, 'CS-74066-19', 'DESPARASITACIÓN HASTA 10KG', 'Servicio', 10.00, 1, NULL),
(23, 'CS-03624-21', 'DESPARASITACIÓN DE 10 A 20KG', 'Servicio', 20.00, 1, NULL),
(24, 'CS-30427-22', 'DESPARASITACIÓN DE 20 A 30KG', 'Servicio', 30.00, 1, NULL),
(25, 'CS-91250-23', 'DESPARASITACIÓN DE 30 A 40KG', 'Servicio', 40.00, 1, NULL),
(26, 'CS-76360-24', 'DESPARASITACIÓN DE 40 A 50KG', 'Servicio', 50.00, 1, NULL),
(27, 'CS-80088-24', 'TRATAMIENTO INYECTABLE 1', 'Servicio', 20.00, 1, NULL),
(28, 'CS-53956-25', 'TRATAMIENTO INYECTABLE 2', 'Servicio', 25.00, 1, NULL),
(29, 'CS-99328-26', 'TRATAMIENTO INYECTABLE 3', 'Servicio', 30.00, 1, NULL),
(30, 'CS-45514-27', 'TRATAMIENTO INYECTABLE 4', 'Servicio', 35.00, 1, NULL),
(31, 'CS-79966-28', 'TRATAMIENTO INYECTABLE 5', 'Servicio', 40.00, 1, NULL),
(32, 'CS-93997-29', 'TRATAMIENTO INYECTABLE 6', 'Servicio', 45.00, 1, NULL),
(33, 'CS-84769-30', 'TRATAMIENTO INYECTABLE 7', 'Servicio', 50.00, 1, NULL),
(34, 'CS-75664-31', 'TRATAMIENTO INYECTABLE 8', 'Servicio', 55.00, 1, NULL),
(35, 'CS-50343-32', 'TRATAMIENTO INYECTABLE 9', 'Servicio', 60.00, 1, NULL),
(36, 'CS-57251-33', 'TRATAMIENTO INYECTABLE 10', 'Servicio', 65.00, 1, NULL),
(37, 'CS-05610-34', 'TRATAMIENTO INYECTABLE 11', 'Servicio', 70.00, 1, NULL),
(38, 'CS-28565-35', 'TRATAMIENTO INYECTABLE 12', 'Servicio', 75.00, 1, NULL),
(39, 'CS-94015-36', 'TRATAMIENTO INYECTABLE 13', 'Servicio', 80.00, 1, NULL),
(40, 'CS-29227-37', 'TRATAMIENTO INYECTABLE 14', 'Servicio', 85.00, 1, NULL),
(41, 'CS-08811-38', 'TRATAMIENTO INYECTABLE 15', 'Servicio', 90.00, 1, NULL),
(42, 'CS-02462-39', 'TRATAMIENTO INYECTABLE 16', 'Servicio', 95.00, 1, NULL),
(43, 'CS-80642-40', 'TRATAMIENTO INYECTABLE 17', 'Servicio', 100.00, 1, NULL),
(44, 'CS-15723-41', 'TRATAMIENTO Y FLUIDOTERAPIA 1', 'Servicio', 40.00, 1, NULL),
(45, 'CS-91461-42', 'TRATAMIENTO Y FLUIDOTERAPIA 2', 'Servicio', 45.00, 1, NULL),
(46, 'CS-84353-43', 'TRATAMIENTO Y FLUIDOTERAPIA 3', 'Servicio', 50.00, 1, NULL),
(47, 'CS-45117-44', 'TRATAMIENTO Y FLUIDOTERAPIA 4', 'Servicio', 55.00, 1, NULL),
(48, 'CS-69114-45', 'TRATAMIENTO Y FLUIDOTERAPIA 5', 'Servicio', 60.00, 1, NULL),
(49, 'CS-45742-46', 'TRATAMIENTO Y FLUIDOTERAPIA 6', 'Servicio', 65.00, 1, NULL),
(50, 'CS-55079-47', 'TRATAMIENTO Y FLUIDOTERAPIA 7', 'Servicio', 70.00, 1, NULL),
(51, 'CS-62082-48', 'TRATAMIENTO Y FLUIDOTERAPIA 8', 'Servicio', 75.00, 1, NULL),
(52, 'CS-97649-49', 'TRATAMIENTO Y FLUIDOTERAPIA 9', 'Servicio', 80.00, 1, NULL),
(53, 'CS-21982-50', 'TRATAMIENTO Y FLUIDOTERAPIA 10', 'Servicio', 85.00, 1, NULL),
(54, 'CS-42933-51', 'TRATAMIENTO Y FLUIDOTERAPIA 11', 'Servicio', 90.00, 1, NULL),
(55, 'CS-26310-52', 'TRATAMIENTO Y FLUIDOTERAPIA 12', 'Servicio', 95.00, 1, NULL),
(56, 'CS-89171-53', 'TRATAMIENTO Y FLUIDOTERAPIA 13', 'Servicio', 100.00, 1, NULL),
(57, 'CS-13151-54', 'CURACIÓN SIMPLE 1', 'Servicio', 10.00, 1, NULL),
(58, 'CS-64147-55', 'CURACIÓN SIMPLE 2', 'Servicio', 15.00, 1, NULL),
(59, 'CS-63178-56', 'LIMPIEZA Y CURACIÓN', 'Servicio', 20.00, 1, NULL),
(60, 'CS-80266-57', 'QUIMIOTERAPIA 1', 'Servicio', 80.00, 1, NULL),
(61, 'CS-22962-58', 'QUIMIOTERAPIA 2', 'Servicio', 90.00, 1, NULL),
(62, 'CS-67636-59', 'QUMIOTERAPIA 3', 'Servicio', 150.00, 1, NULL),
(63, 'CS-79635-60', 'VACUNA PUPPY DP', 'Servicio', 55.00, 1, NULL),
(64, 'CS-86138-61', 'VACUNA CUADRUPLE', 'Servicio', 45.00, 1, NULL),
(65, 'CS-61525-62', 'VACUNA QUINTUPLE', 'Servicio', 50.00, 1, NULL),
(66, 'CS-88988-63', 'VACUNA SEXTUPLE', 'Servicio', 75.00, 1, NULL),
(67, 'CS-77866-64', 'VACUNA KC', 'Servicio', 50.00, 1, NULL),
(68, 'CS-00800-65', 'VACUNA LEUCEMIA FELINA', 'Servicio', 80.00, 1, NULL),
(69, 'CS-36722-66', 'VACUNA TRIPLE FELINA', 'Servicio', 45.00, 1, NULL),
(70, 'CS-17359-67', 'VACUNA ANTIRRABICA', 'Servicio', 35.00, 1, NULL),
(71, 'CS-51363-68', 'IDEXX 4DX', 'Servicio', 140.00, 1, NULL),
(72, 'CS-83403-69', 'IDEXX COMBO FELINO', 'Servicio', 140.00, 1, NULL),
(73, 'CS-44740-70', 'DESCARTE DE PARVOVIRUS', 'Servicio', 85.00, 1, NULL),
(74, 'CS-60982-71', 'DESCARTE DE DISTEMPER', 'Servicio', 85.00, 1, NULL),
(75, 'CS-59111-72', 'ANTICONCEPTIVO EN GATAS', 'Servicio', 50.00, 1, NULL),
(76, 'CS-77853-73', 'ANTICONCEPTIVO EN PERRAS MENOS DE 20KG', 'Servicio', 50.00, 1, NULL),
(77, 'CS-12698-74', 'ANTICONCEPTIVO EN PERRAS HASTA MÁS DE 20KG', 'Servicio', 60.00, 1, NULL),
(78, 'CS-32785-75', 'INYECCIÓN DEL DÍA SIGUIENTE UNA DOSIS', 'Servicio', 50.00, 1, NULL),
(79, 'CS-73038-76', 'LIMPIEZA DE OÍDOS 1', 'Servicio', 15.00, 1, NULL),
(80, 'CS-90917-77', 'LIMPIEZA DE OÍDOS 2', 'Servicio', 20.00, 1, NULL),
(81, 'CS-72518-78', 'LIMPIEZA DE OÍDOS 3', 'Servicio', 25.00, 1, NULL),
(82, 'CS-09976-79', 'LIMPIEZA DE OÍDOS 4', 'Servicio', 30.00, 1, NULL),
(83, 'CS-69250-80', 'SEDACIÓN 1', 'Servicio', 20.00, 1, NULL),
(84, 'CS-86450-81', 'SEDACIÓN 2', 'Servicio', 25.00, 1, NULL),
(85, 'CS-35684-82', 'SEDACIÓN 3', 'Servicio', 30.00, 1, NULL),
(86, 'CS-18617-83', 'SEDACIÓN 4', 'Servicio', 35.00, 1, NULL),
(87, 'CS-11040-84', 'SEDACION 5', 'Servicio', 40.00, 1, NULL),
(88, 'CS-45221-85', 'SEDACION 6', 'Servicio', 45.00, 1, NULL),
(89, 'CS-26733-86', 'SEDACIÓN 7', 'Servicio', 50.00, 1, NULL),
(90, 'CS-48978-87', 'SEDACIÓN 8', 'Servicio', 55.00, 1, NULL),
(91, 'CS-14285-88', 'SEDACIÓN 9', 'Servicio', 60.00, 1, NULL),
(92, 'CS-25641-89', 'CIRUGIA OVH CANINAS HASTA 10KG', 'Servicio', 250.00, 1, NULL),
(93, 'CS-22877-90', 'CIRUGIA OVH CANINAS DE 10KG A 20KG', 'Servicio', 350.00, 1, NULL),
(94, 'CS-01905-91', 'CIRUGIA OVH CANINAS DE 20KG A 40KG', 'Servicio', 400.00, 1, NULL),
(95, 'CS-65201-92', 'CIRUGIA OVH CANINAS MAYOR A 40KG', 'Servicio', 500.00, 1, NULL),
(96, 'CS-74923-93', 'ORQUIECTOMÍA CANINOS HASTA 10KG', 'Servicio', 200.00, 1, NULL),
(97, 'CS-29219-94', 'ORQUIECTOMÍA CANINOS DE 10KG A 20KG', 'Servicio', 250.00, 1, NULL),
(98, 'CS-40807-95', 'ORQUIECTOMÍA CANINOS DE 20KG A 40KG', 'Servicio', 300.00, 1, NULL),
(99, 'CS-95774-96', 'ORQUIECTOMÍA CANINOS MAYOR A 40KG', 'Servicio', 350.00, 1, NULL),
(100, 'CS-41130-97', 'CASTRACIÓN GATOS', 'Servicio', 100.00, 1, NULL),
(101, 'CS-06643-98', 'OVH GATAS', 'Servicio', 120.00, 1, NULL),
(102, 'CS-71166-99', 'PIOMETRA CANINAS HASTA 10 KG', 'Servicio', 350.00, 1, NULL),
(103, 'CS-27016-100', 'PIOMETRA CANINAS DE 10 KG A 20KG', 'Servicio', 500.00, 1, NULL),
(104, 'CS-47729-101', 'PIOMETRA CANINAS DE 20KG A MÁS', 'Servicio', 600.00, 1, NULL),
(105, 'CS-05275-102', 'PIOMETRA EN GATAS', 'Servicio', 300.00, 1, NULL),
(106, 'CS-27087-103', 'OTOHEMATOMA HASTA 20KG CADA OREJA', 'Servicio', 250.00, 1, NULL),
(107, 'CS-10635-104', 'OTOHEMATOMA MAYOR A 20KG CADA OREJA', 'Servicio', 300.00, 1, NULL),
(108, 'CS-76437-105', 'PROFILAXIS DENTAL HASTA 10KG', 'Servicio', 150.00, 1, NULL),
(109, 'CS-35475-106', 'PROFILAXIS DENTAL DE 10KG A 20KG', 'Servicio', 200.00, 1, NULL),
(110, 'CS-78592-107', 'PROFILAXIS DENTAL MAYOR A 20KG', 'Servicio', 250.00, 1, NULL),
(111, 'CS-04177-108', 'MONITOREO COMPLETO', 'Servicio', 122.00, 1, NULL),
(112, 'CS-35005-109', 'PERFIL RENAL BÁSICO', 'Servicio', 70.00, 1, NULL),
(113, 'CS-24607-110', 'PERFIL RENAL COMPLETO', 'Servicio', 150.00, 1, NULL),
(114, 'CS-21094-111', 'PERFIL HEPÁTICO BÁSICO', 'Servicio', 80.00, 1, NULL),
(115, 'CS-95069-112', 'PERFIL HEPÁTICO COMPLETO', 'Servicio', 110.00, 1, NULL),
(116, 'CS-99926-113', 'FROTIS SANGUÍNEO', 'Servicio', 45.00, 1, NULL),
(117, 'CS-68527-114', 'CONTEO RETICULOCITOS', 'Servicio', 20.00, 1, NULL),
(118, 'CS-99668-115', 'GGT', 'Servicio', 20.00, 1, NULL),
(119, 'CS-16285-116', 'CA', 'Servicio', 20.00, 1, NULL),
(120, 'CS-94501-117', 'P', 'Servicio', 20.00, 1, NULL),
(121, 'CS-14134-118', 'PERFIL GERIÁTRICO', 'Servicio', 200.00, 1, NULL),
(122, 'CS-81987-119', 'PERFIL PANCREATICO', 'Servicio', 120.00, 1, NULL),
(123, 'CS-30997-120', 'PERFIL TIROIDEO', 'Servicio', 120.00, 1, NULL),
(124, 'CS-51884-121', 'COPROPARASITOLOGICO COMPLETO', 'Servicio', 45.00, 1, NULL),
(125, 'CS-11497-122', 'PERFIL MICROBIOLÓGICO', 'Servicio', 85.00, 1, NULL),
(126, 'CS-96365-123', 'COMPOSICIÓN DE CÁLCULO', 'Servicio', 80.00, 1, NULL),
(127, 'CS-52129-124', 'EXÁMEN DE ORINA', 'Servicio', 40.00, 1, NULL),
(128, 'CS-47549-125', 'HEMOGRAMA', 'Servicio', 45.00, 1, NULL),
(129, 'CS-99713-126', 'RETIRO DE PUNTOS 4', 'Servicio', 20.00, 1, NULL),
(130, 'CS-57563-127', 'ECOGRAFIA GESTACIONAL', 'Servicio', 50.00, 1, NULL),
(131, 'CS-88393-128', 'EUTANASIA PARA GATO', 'Servicio', 60.00, 1, NULL),
(132, 'CS-33863-129', 'EUTANASIA PARA PERRO', 'Servicio', 60.00, 1, NULL),
(133, 'CS-22132-130', 'SONDEO DE GATO 1', 'Servicio', 60.00, 1, NULL),
(134, 'CS-46365-131', 'DESCARTE DE HEMOPARASITOS', 'Servicio', 140.00, 1, NULL),
(135, 'CS-79725-132', 'CITOLOGÍA', 'Servicio', 150.00, 1, NULL),
(136, 'CS-94172-133', 'EXTRACCIÓN DE DIENTE', 'Servicio', 20.00, 1, NULL),
(137, 'CS-38994-134', 'APLICACIÓN DE SPRAY ANTIPULGAS 1', 'Servicio', 10.00, 1, NULL),
(138, 'CS-67779-135', 'APLICACIÓN DE SPRAY ANTIPULGAS 2', 'Servicio', 15.00, 1, NULL),
(139, 'CS-68618-136', 'APLICACIÓN DE SPRAY ANTIPULGAS 3', 'Servicio', 20.00, 1, NULL),
(140, 'CS-79312-137', 'RASPADO DE PIEL', 'Servicio', 40.00, 1, NULL),
(141, 'CS-63117-138', 'AUTOHEMOTERAPIA', 'Servicio', 80.00, 1, NULL),
(142, 'CS-66997-139', 'PRUEBA BIOGUARD DISTEMPER', 'Servicio', 50.00, 1, NULL),
(143, 'CS-00377-140', 'PRUEBA BIOGUARD PARVOVIRUS', 'Servicio', 50.00, 1, NULL),
(144, 'CS-29497-141', 'ANTICONCEPTIVO GATAS', 'Servicio', 50.00, 1, NULL),
(145, 'CS-70451-142', 'GLUCOSA', 'Servicio', 10.00, 1, NULL),
(146, 'CS-13848-143', 'CERTIFICADO DE SALUD', 'Servicio', 80.00, 1, NULL),
(147, 'CP-64701-144', 'DOXITEL DE 100MG', 'Producto', 3.00, 159, NULL),
(148, 'CP-36474-145', 'DOXITEL DE 200MG', 'Producto', 3.00, 226, NULL),
(149, 'CP-33311-146', 'CEFAVET DE 300MG CAJA X 32 COMPRIMIDOS', 'Producto', 3.00, 123, NULL),
(150, 'CP-85215-147', 'CEFAVET DE 600MG CAJA X 32 COMPRIMIDOS', 'Producto', 3.00, 238, NULL),
(151, 'CP-29280-148', 'KETOVET 10MG', 'Producto', 3.00, 188, NULL),
(152, 'CP-65239-149', 'KETOVET DE 20MG', 'Producto', 3.00, 211, NULL),
(153, 'CP-95607-150', 'MELOXIVET DE 1MG', 'Producto', 3.00, 200, NULL),
(154, 'CP-64882-151', 'MELOXIVET DE 4MG', 'Producto', 4.00, 197, NULL),
(155, 'CP-46771-152', 'PREDNOVET DE 20MG', 'Producto', 4.00, 329, NULL),
(156, 'CP-34794-153', 'ALBEX', 'Producto', 8.00, 39, NULL),
(157, 'CP-51633-154', 'SINCELAR', 'Producto', 7.00, 271, NULL),
(158, 'CP-62823-155', 'HISTAPET', 'Producto', 3.00, 49, NULL),
(160, 'CP-86404-157', 'OMEGA S', 'Producto', 3.00, 403, NULL),
(161, 'CP-48615-158', 'OMEGA M', 'Producto', 3.00, 483, NULL),
(162, 'CP-48369-159', 'HEPATIN EN PASTA', 'Producto', 50.00, 11, NULL),
(163, 'CP-82876-160', 'NUTRICAL', 'Producto', 50.00, 13, NULL),
(164, 'CP-42208-161', 'FELOVITE', 'Producto', 50.00, 0, NULL),
(165, 'CP-81013-162', 'SPRAY ANTIPULGAS MARCA RAZA', 'Producto', 36.00, 1, NULL),
(166, 'CP-86490-163', 'FIPRONEX G5 DE 1.5KG A 10KG', 'Producto', 30.00, 6, NULL),
(167, 'CP-37261-164', 'FIPRONEX G5 DE 10KG A 20KG', 'Producto', 35.00, 2, NULL),
(168, 'CP-87913-165', 'FIPRONEX G5 DE 20KG A 40KG', 'Producto', 45.00, 4, NULL),
(169, 'CP-92091-166', 'FIPRONEX G5 DE 40KG A MÁS', 'Producto', 55.00, 2, NULL),
(170, 'CP-19024-167', 'SIMPARICA DE 2.5KG A 5KG', 'Producto', 65.00, 9, NULL),
(171, 'CP-40223-168', 'SIMPARICA DE 5KG A 10KG', 'Producto', 70.00, 15, NULL),
(172, 'CP-70068-169', 'SIMPARICA DE 10KG A 20KG', 'Producto', 80.00, 3, NULL),
(173, 'CP-96547-170', 'SIMPARICA DE 20KG A 40KG', 'Producto', 90.00, 8, NULL),
(174, 'CP-10637-171', 'ATREVIA ONE MINI DE 2KG A 4.5KG', 'Producto', 50.00, 9, NULL),
(175, 'CP-13125-172', 'ATREVIA ONE SMALL DE 4.5KG A 10KG', 'Producto', 55.00, -1, NULL),
(176, 'CP-89683-173', 'ATREVIA ONE MEDIUM DE 10KG A 20KG', 'Producto', 65.00, 24, NULL),
(177, 'CP-76438-174', 'ATREVIA ONE LARGE DE 20KG A 40KG', 'Producto', 75.00, 49, NULL),
(178, 'CP-98818-175', 'ATREVIA XR MINI DE 2KG A 4.5KG', 'Producto', 110.00, 4, NULL),
(179, 'CP-80284-176', 'ATREVIA XR SMALL DE 4.5KG A 10KG', 'Producto', 120.00, 0, NULL),
(180, 'CP-26639-177', 'ATREVIA XR MEDIUM DE 10KG A 20KG', 'Producto', 130.00, 11, NULL),
(181, 'CP-35792-178', 'ATREVIA XR LARGE DE 20KG A 40KG', 'Producto', 150.00, 5, NULL),
(182, 'CP-04233-179', 'CLINDAVET DE 75 MG', 'Producto', 3.00, 226, NULL),
(183, 'CP-49515-180', 'CLINDAVET DE 150MG', 'Producto', 3.00, 139, NULL),
(184, 'CP-42383-181', 'GASTROPET', 'Producto', 3.00, 44, NULL),
(185, 'CP-56592-182', 'FENOBARBITAL', 'Producto', 4.00, 5, NULL),
(186, 'CP-98661-183', 'HEPATIOPET', 'Producto', 3.00, 142, NULL),
(187, 'CP-97185-184', 'BACTRINA EN JARABE', 'Producto', 25.00, 2, NULL),
(188, 'CP-54645-185', 'BACTRINA TABLETAS', 'Producto', 3.00, 8, NULL),
(189, 'CP-55033-186', 'MICOPET', 'Producto', 3.00, 37, NULL),
(190, 'CP-62020-187', 'APOQUEL DE 5.4MG', 'Producto', 10.00, 25, NULL),
(191, 'CP-80491-188', 'APOQUEL DE 16MG', 'Producto', 16.00, 30, NULL),
(192, 'CP-38583-189', 'PROPANTELINA DE TRITON', 'Producto', 4.00, 38, NULL),
(193, 'CP-76349-190', 'PROTELIV EN PASTILLAS', 'Producto', 5.00, 9, NULL),
(194, 'CP-34371-191', 'HEMO STOP TABS', 'Producto', 4.00, 9, NULL),
(195, 'CP-75834-192', 'CLAMOVET DE 250MG CAJA DE 40 TABLETAS', 'Producto', 3.50, 78, NULL),
(196, 'CP-05220-193', 'DOXITEL FLOW CONT.20ML', 'Producto', 45.00, 6, NULL),
(197, 'CP-18689-194', 'MELOXIVET EN GOTAS', 'Producto', 30.00, 13, NULL),
(198, 'CP-17499-195', 'PET MILK DE 100ML', 'Producto', 52.00, 0, NULL),
(199, 'CP-79157-196', 'PET MILK DE 300ML', 'Producto', 125.00, 1, NULL),
(200, 'CS-28447-197', 'BAÑO NORMAL 2', 'Servicio', 35.00, 1, NULL),
(201, 'CS-54750-198', 'BAÑO NORMAL 3', 'Servicio', 40.00, 1, NULL),
(202, 'CS-67816-199', 'BAÑO NORMAL 4', 'Servicio', 45.00, 1, NULL),
(203, 'CS-77842-200', 'BAÑO NORMAL 5', 'Servicio', 50.00, 1, NULL),
(204, 'CS-70500-201', 'BAÑO NORMAL 6', 'Servicio', 55.00, 1, NULL),
(205, 'CS-86560-202', 'BAÑO NORMAL 7', 'Servicio', 60.00, 1, NULL),
(206, 'CS-52359-203', 'BAÑO NORMAL 8', 'Servicio', 65.00, 1, NULL),
(207, 'CS-12645-204', 'BAÑO NORMAL 9', 'Servicio', 70.00, 1, NULL),
(208, 'CS-99295-205', 'BAÑO NORMAL 10', 'Servicio', 75.00, 1, NULL),
(209, 'CP-25891-206', 'BAÑO NORMAL 11', 'Servicio', 80.00, 1, NULL),
(210, 'CS-19173-207', 'BAÑO NORMAL 12', 'Servicio', 85.00, 1, NULL),
(211, 'CP-21718-208', 'DERMASEP DE 120ML', 'Producto', 10.00, 18, NULL),
(212, 'CP-64075-209', 'CREMA 6A', 'Producto', 35.00, -1, NULL),
(213, 'CP-60298-210', 'LAX NATUR', 'Producto', 45.00, 5, NULL),
(214, 'CP-27587-211', 'ARTROSAMINE', 'Producto', 3.00, 59, NULL),
(215, 'CP-18480-212', 'PROTELIV EN GOTAS', 'Producto', 65.00, 4, NULL),
(216, 'CP-67379-213', 'AL OJO G', 'Producto', 20.00, 0, NULL),
(217, 'CP-00700-214', 'CIPROVET CONT. 5ML', 'Producto', 75.00, -1, NULL),
(218, 'CP-87003-215', 'ORAL CARE CANINOS DE  80GR', 'Producto', 48.00, 2, NULL),
(219, 'CP-04046-216', 'GINGIVET', 'Producto', 46.00, -2, NULL),
(220, 'CP-80799-217', 'XELAMEC HASTA 2.5KG', 'Producto', 45.00, 6, NULL),
(221, 'CP-42808-218', 'XELAMEC DE 2.5KG A 5KG', 'Producto', 50.00, 21, NULL),
(222, 'CP-53506-219', 'XELAMEC DE 5KG A 10KG', 'Producto', 55.00, 2, NULL),
(224, 'CP-97478-221', 'BRAVECTO PARA PERRO DE 2KG A 4KG', 'Producto', 145.00, 0, NULL),
(225, 'CP-16146-222', 'BRAVECTO PARA PERRO DE 4KG A 10KG', 'Producto', 160.00, 0, NULL),
(226, 'CP-03021-223', 'BRAVECTO PARA PERRO DE 10KG A 20KG', 'Producto', 175.00, 0, NULL),
(227, 'CP-87713-224', 'BRAVECTO PARA PERRO DE 20KG A 40KG', 'Producto', 215.00, 0, NULL),
(228, 'CP-41151-225', 'BRAVECTO PARA PERRO DE 40KG A 60KG', 'Producto', 260.00, 0, NULL),
(229, 'CP-55547-226', 'BRAVECTO PARA GATOS DE 2KG A 6KG', 'Producto', 185.00, 0, NULL),
(230, 'CP-18191-227', 'BRAVECTO PARA GATOS DE 6KG A 12KG', 'Producto', 200.00, 0, NULL),
(231, 'CP-32818-228', 'SERESTO MENOS DE 8KG', 'Producto', 245.00, 0, NULL),
(232, 'CP-76545-229', 'SERESTO MAYOR DE 8KG', 'Producto', 295.00, 0, NULL),
(233, 'CP-13183-230', 'SHAMPOO ALOE VERA EN SACHET', 'Producto', 2.50, -4, NULL),
(234, 'CP-19031-231', 'SHAMPOO ANTIPULGAS EN SACHET', 'Producto', 2.50, 9, NULL),
(235, 'CP-23943-232', 'FLOXATEL DE 50MG', 'Producto', 3.00, 124, NULL),
(236, 'CP-29759-233', 'FLOXATEL DE 100MG', 'Producto', 3.00, 82, NULL),
(237, 'CP-57144-234', 'ENTEREX', 'Producto', 7.00, 0, NULL),
(238, 'CP-12044-235', 'OCUBIOTIC CON ESTROIDES', 'Producto', 48.00, 6, NULL),
(239, 'CP-07053-236', 'OCUBIOTIC SIN ESTEROIDES', 'Producto', 48.00, 7, NULL),
(240, 'CS-46779-237', 'RETIRO DE PUNTOS 1', 'Servicio', 5.00, 1, NULL),
(241, 'CS-87510-238', 'UROANÁLISIS', 'Servicio', 45.00, 1, NULL),
(242, 'CS-51020-239', 'DESPARASITACIÓN 10KG A 15KG', 'Servicio', 15.00, 1, NULL),
(243, 'CS-23810-240', 'CORTE DE UÑAS', 'Servicio', 8.00, 1, NULL),
(244, 'CS-71190-241', 'CORTE DE UÑAS 2', 'Servicio', 10.00, 1, NULL),
(245, 'CS-24467-242', 'BAÑO NORMAL Y CORTE 1', 'Servicio', 55.00, 1, NULL),
(246, 'CS-96327-243', 'BAÑO NORMAL Y CORTE 2', 'Servicio', 60.00, 1, NULL),
(247, 'CS-47929-244', 'CALCIO', 'Servicio', 20.00, 1, NULL),
(248, 'CS-91235-245', 'FOSFORO', 'Servicio', 20.00, 1, NULL),
(249, 'CP-18241-246', '1/4 DE CAT CHOW GATOS', 'Producto', 3.00, 7, NULL),
(250, 'CP-83647-247', '1/2 KILO DE CAT CHOW GATOS', 'Producto', 6.00, 6, NULL),
(251, 'CP-16578-248', 'UN KILO DE CAT CHOW GATOS', 'Producto', 12.00, 2, NULL),
(252, 'CP-68614-249', 'CHURU NOURISH POLLO', 'Producto', 5.00, -8, NULL),
(253, 'CP-55891-250', 'CHURU NOURISH PESCADO', 'Producto', 5.00, -5, NULL),
(254, 'CS-65117-251', 'BAÑO MEDICADO 1', 'Servicio', 35.00, 1, NULL),
(255, 'CS-69309-252', 'BAÑO MEDICADO 2', 'Servicio', 40.00, 1, NULL),
(256, 'CS-27741-253', 'BAÑO MEDICADO 3', 'Servicio', 45.00, 1, NULL),
(257, 'CS-98828-254', 'BAÑO MEDICADO 4', 'Servicio', 50.00, 1, NULL),
(262, 'CS-24449-255', 'CIRUGIA 1', 'Servicio', 150.00, 1, NULL),
(263, 'CP-71211-256', 'FAJA TALLA 0', 'Producto', 30.00, 1, NULL),
(264, 'CP-67295-257', 'FAJA TALLA 1', 'Producto', 32.00, 1, NULL),
(265, 'CP-03145-258', 'FAJA TALLA 2', 'Producto', 34.00, 2, NULL),
(266, 'CP-43709-259', 'FAJA TALLA 3', 'Producto', 45.00, -1, NULL),
(267, 'CP-51984-260', 'LATA PRO PLAN EN 369GR', 'Producto', 20.00, 3, NULL),
(268, 'CP-52258-261', 'FORTI FLORA PARA PERROS', 'Producto', 5.00, 2, NULL),
(269, 'CP-60706-262', 'FORTI FLORA PARA GATOS', 'Producto', 5.00, 34, NULL),
(270, 'CP-78754-263', '1/4 DE CAT CHOW GATITO', 'Producto', 3.00, 6, NULL),
(271, 'CP-78284-264', '1/2 KILO DE CAT CHOW GATITO', 'Producto', 6.00, 1, NULL),
(272, 'CP-88976-265', '1 KILO DE CAT CHOW GATITOS', 'Producto', 12.00, -1, NULL),
(273, 'CP-94967-266', 'MATANOX', 'Producto', 7.00, -2, NULL),
(275, 'CP-44407-267', 'prueba001', 'Producto', 2.00, 1, NULL),
(276, 'CP-07424-268', 'SHAMPOO ANTIPULGAS', 'Producto', 18.00, 5, NULL),
(277, 'CP-91037-269', 'PROTECT URINARY FELINE', 'Producto', 110.00, 1, NULL),
(278, 'CS-83929-270', 'BAÑO NORMAL Y CORTE 3', 'Servicio', 65.00, 1, NULL),
(279, 'CS-14998-271', 'BAÑO MEDICADO PROMO 1', 'Servicio', 20.00, 1, NULL),
(280, 'CS-55956-272', 'CURACIÓN SIMPLE 3', 'Servicio', 20.00, 1, NULL),
(281, 'CS-09521-273', 'CURACIÓN SIMPLE 4', 'Servicio', 25.00, 1, NULL),
(282, 'CP-28740-274', 'COLLAR ISOBELINO XXL', 'Producto', 50.00, 4, NULL),
(283, 'CP-72488-275', 'TIRO PARA PERRO', 'Producto', 8.00, 5, NULL),
(284, 'CP-86506-276', 'COLLAR PARA PERRO', 'Producto', 18.00, 4, NULL),
(285, 'CS-63031-277', 'MOVILIDAD', 'Servicio', 10.00, 1, NULL),
(286, 'CP-68218-278', 'ELECTROLITICO PETS', 'Producto', 9.00, 5, NULL),
(287, 'CS-63169-279', 'CITOQUIMICA DE FLUIDOS', 'Servicio', 70.00, 1, NULL),
(288, 'CS-67539-280', 'BAÑO NORMAL Y CORTE 4', 'Servicio', 70.00, 1, NULL),
(289, 'CP-07859-281', 'MOOCHIE SWEET PURPLE', 'Producto', 5.00, 1, NULL),
(290, 'CS-79368-282', 'MOVILIDAD', 'Servicio', 5.00, 1, NULL),
(291, 'CP-06610-283', 'AURIZON', 'Producto', 65.00, 5, NULL),
(292, 'CS-33465-284', 'DESPARASITACION', 'Servicio', 15.00, 1, NULL),
(293, 'CP-66543-285', 'LATA HILL\'S A/D PARA PERRO Y GATO 156GR', 'Producto', 22.00, 15, NULL),
(294, 'CP-22799-286', 'TRAMADOL COMPRIMIDOS', 'Producto', 4.00, 25, NULL),
(295, 'CP-15714-287', 'BRONCOPET JARABE', 'Producto', 25.00, 8, NULL),
(296, 'CP-91447-288', 'BONY CAN VETLINE X CONTRA PULGAS Y GARRAPATAS', 'Producto', 22.00, 3, NULL),
(297, 'CP-97403-289', 'COLONIA FRUPPY BABY', 'Producto', 15.00, 2, NULL),
(298, 'CP-78660-290', 'COLONIA FRUPPY FRUITY', 'Producto', 15.00, 2, NULL),
(299, 'CP-66282-291', 'COLONIA FRUPPY LADY', 'Producto', 15.00, 2, NULL),
(300, 'CP-18630-292', 'COLONIA FRUPPY CITRIC', 'Producto', 15.00, 3, NULL),
(301, 'CP-83463-293', 'COLONIA FRUPPY FRESA', 'Producto', 15.00, 0, NULL),
(302, 'CP-40882-294', 'COLONIA FRUPPY FRUTOS TROPICALES', 'Producto', 15.00, 1, NULL),
(303, 'CP-48141-295', 'COLONIA FRUPPY FLOWERS', 'Producto', 15.00, 1, NULL),
(304, 'CP-98845-296', 'COLONIA FRUPPY COCONUT', 'Producto', 15.00, 1, NULL),
(305, 'CP-08196-297', 'COLONIA FRUPPY UVA', 'Producto', 15.00, 2, NULL),
(306, 'CP-26345-298', 'COLONIA FRUPPY DANDY', 'Producto', 15.00, 1, NULL),
(307, 'CP-53031-299', 'SHAMPOO AVENA + GLICERINA', 'Producto', 35.00, 0, NULL),
(308, 'CP-80313-300', 'SHAMPOO DERMASAN', 'Producto', 38.00, 2, NULL),
(309, 'CP-71919-301', 'SHAMPOO CLOREXIHIDINA', 'Producto', 38.00, 1, NULL),
(310, 'CP-42424-302', 'SHAMPOO KETOCONAZOL', 'Producto', 38.00, 2, NULL),
(311, 'CP-86285-303', 'CANI TABS BRAIN & NEURO', 'Producto', 3.00, 15, NULL),
(312, 'CP-09296-304', 'CANI TABS SKIN+COAT', 'Producto', 3.00, 148, NULL),
(313, 'CP-78163-305', 'CANI TABS CALMING+RELAX', 'Producto', 3.00, 136, NULL),
(314, 'CP-91087-306', 'GERIPET', 'Producto', 3.00, 1, NULL),
(315, 'CP-51507-307', 'PROBIOTICS', 'Producto', 1.00, 52, NULL),
(316, 'CP-32701-308', 'PET CAL', 'Producto', 3.00, 28, NULL),
(317, 'CP-71942-309', 'PET TABS PLUS', 'Producto', 2.00, 63, NULL),
(318, 'CS-56630-310', 'BAÑO MEDICADO 5', 'Servicio', 55.00, 1, NULL),
(319, 'CP-02482-311', 'ATREVIA TRIO CATS DE 2.9 A 6.25KG', 'Producto', 120.00, 3, NULL),
(320, 'CP-01558-312', 'HILL\'S K/D FELINE 1.8KG', 'Producto', 138.00, 1, NULL),
(321, 'CP-27212-313', 'AMERITON DE 35GR', 'Producto', 35.00, 4, NULL),
(322, 'CP-01859-314', 'HILL\'S S/D FELINE URINARY CARE 2KG', 'Producto', 138.00, 0, NULL),
(323, 'CP-75325-315', 'PRO PLAN CORDERO ADULTO 15KG', 'Producto', 400.00, 1, NULL),
(324, 'CP-86682-316', 'PRO PLAN SENSITIVE SKIN (PIEL SENSIBLE) CORDERO 15KG', 'Producto', 400.00, 1, NULL),
(325, 'CP-96753-317', 'CAT CHOW GATOS SACO DE 15KG DELI MIX', 'Producto', 170.00, 1, NULL),
(326, 'CP-46960-318', 'CAT CHOW GATITOS SACO DE 15KG', 'Producto', 170.00, 2, NULL),
(327, 'CS-19252-319', 'LAVADO DE VEJIGA 1', 'Servicio', 30.00, 1, NULL),
(328, 'CP-90007-320', 'COLLAR ISABELINO XXS', 'Producto', 28.00, 5, NULL),
(329, 'CP-81357-321', 'COLLAR ISABELINO XS', 'Producto', 30.00, 4, NULL),
(330, 'CP-90929-322', 'CALMING CARE', 'Producto', 5.00, 10, NULL),
(331, 'CP-77745-323', 'PATE FELIX SALMÓN DE 156GR', 'Producto', 6.50, 22, NULL),
(332, 'CP-02303-324', 'PATE FELIX PESCADO Y ATÚN', 'Producto', 6.50, 23, NULL),
(333, 'CP-48008-325', 'LATA HILL\'S I/D PARA PERRO 369GR', 'Producto', 22.00, 8, NULL),
(334, 'CP-39029-326', 'LATA HILL\'S I/D FELINE 156GR', 'Producto', 22.00, 4, NULL),
(335, 'CP-25692-327', 'CEPILLO PEQUEÑO', 'Producto', 12.00, 2, NULL),
(336, 'CP-76623-328', 'OTIFLEX C', 'Producto', 60.00, 3, NULL),
(337, 'CP-53748-329', 'CERULINE DE 120ML', 'Producto', 48.00, 1, NULL),
(338, 'CP-00052-330', 'FAJA POST QUIRÚRGICA TALLA 0', 'Producto', 30.00, 3, NULL),
(339, 'CP-78116-331', 'FAJA POST QUIRÚRGICA TALLA 1', 'Producto', 35.00, 5, NULL),
(340, 'CP-47612-332', 'FAJA POST QUIRÚRGICA TALLA 2', 'Producto', 40.00, 2, NULL),
(341, 'CP-41440-333', 'FAJA POST QUIRÚRGICA TALLA 3', 'Producto', 45.00, 3, NULL),
(342, 'CP-83643-334', 'FAJA POST QUIRÚRGICA TALLA 4', 'Producto', 50.00, 1, NULL),
(343, 'CP-89706-335', 'FAJA POST QUIRÚRGICA TALLA 6', 'Producto', 55.00, 2, NULL),
(344, 'CP-19007-336', 'FAJA POST QUIRÚRGICA TALLA 8', 'Producto', 60.00, 2, NULL),
(345, 'CS-96598-337', 'EXAMEN DE FLUIDOS', 'Servicio', 80.00, 1, NULL),
(346, 'CP-18001-338', 'JUGUETE PEQUEÑO PARA GATO', 'Producto', 10.00, 0, NULL),
(347, 'CP-81047-339', 'JUGUETE PARA GATO CON PALO Y SOGA', 'Producto', 18.00, 0, NULL),
(348, 'CP-41716-340', 'LATA HILL\'S C/D FELINE 156GR', 'Producto', 22.00, 0, NULL),
(349, 'CS-55468-341', 'HEMOGRAMA Y FROTIS SANGUINEO', 'Servicio', 85.00, 1, NULL),
(350, 'CP-80917-342', 'P.I. +4 KG', 'Producto', 30.00, 7, NULL),
(351, 'CP-32337-343', 'P.I - 4KG', 'Producto', 28.00, 8, NULL),
(352, 'CP-49922-344', 'FIP FORTE DE 40KG A 60KG', 'Producto', 40.00, 1, NULL),
(353, 'CP-57987-344', 'LIQUAMOX', 'Producto', 40.00, 0, NULL),
(354, 'CP-11853-345', 'CARNAZA GRANDE PARA PERRO', 'Producto', 25.00, 0, NULL),
(355, 'CS-01575-346', 'BAÑO MEDICADO 6', 'Servicio', 60.00, 1, NULL),
(356, 'CS-13894-347', 'BAÑO MEDICADO 7', 'Servicio', 65.00, 1, NULL),
(357, 'CS-81263-348', 'BAÑO MEDICADO 8', 'Servicio', 70.00, 1, NULL),
(358, 'CP-48115-346', 'LATA HILL\'S K/D CANINO 369GR', 'Producto', 28.00, 2, NULL),
(359, 'CP-48899-347', 'LATA HILL\'S K/D FELINE 156GR', 'Producto', 22.00, 2, NULL),
(360, 'CP-69476-348', 'HILL\'S C/D MULTICARE FELINE 1.8KG', 'Producto', 138.00, 2, NULL),
(361, 'CS-03754-349', 'CIRUGIA 2', 'Servicio', 200.00, 1, NULL),
(362, 'CS-02427-350', 'CIRUGIA 3', 'Servicio', 250.00, 1, NULL),
(363, 'CS-67341-351', 'CIRUGIA 4', 'Servicio', 300.00, 1, NULL),
(364, 'CS-74457-352', 'BIOPSIA', 'Servicio', 150.00, 1, NULL),
(365, 'CP-30867-353', 'ARENERO GRANDE PARA GATOS', 'Producto', 15.00, 4, NULL),
(366, 'CP-91459-354', 'POTENZA CATS', 'Producto', 45.00, -2, NULL),
(367, 'CP-26460-355', 'COLLAR ISOBELINO L', 'Producto', 42.00, 7, NULL),
(368, 'CP-81912-356', 'COLLAR ISOBELINO M', 'Producto', 38.00, 5, NULL),
(369, 'CP-98029-357', 'TRANSPORTADOR DE GATOS DE TELA SIN TAPA', 'Producto', 40.00, 0, NULL),
(370, 'CP-88794-358', 'BOZAL TALLA 2', 'Producto', 12.00, 0, NULL),
(371, 'CS-74605-359', 'MEDICADO', 'Servicio', 10.00, 1, NULL),
(372, 'CS-51116-360', 'BAÑO PREMIUM', 'Servicio', 50.00, 1, NULL),
(373, 'CS-31997-361', 'BAÑO PREMIUM 2', 'Servicio', 60.00, 1, NULL),
(374, 'CP-91307-362', 'CARNAZA S/1.00', 'Producto', 1.00, 0, NULL),
(375, 'CP-24618-363', 'CARNAZA S/.2.00', 'Producto', 2.00, 0, NULL),
(376, 'CP-85568-364', 'CARNAZA S/.3.00', 'Producto', 3.00, 0, NULL),
(377, 'CP-70360-365', 'CARNAZA S/.4.00', 'Producto', 4.00, 0, NULL),
(378, 'CP-76777-366', 'CARNAZA S/.5.00', 'Producto', 5.00, 0, NULL),
(379, 'CP-11363-367', 'JUGUETE DE POLLO CHICO', 'Producto', 8.00, -1, NULL),
(380, 'CP-67685-368', 'JUGUETE DE POLLO MEDIANO', 'Producto', 15.00, 1, NULL),
(381, 'CP-94804-369', 'JUGUETE DE POLLO GRANDE', 'Producto', 18.00, 1, NULL),
(382, 'CP-24499-370', 'JUGUETE PELOTA GRANDE', 'Producto', 12.00, 0, NULL),
(383, 'CP-79719-371', 'SHAMPOO MEDICADO SEBOCALM SPHERULITES', 'Producto', 105.00, 0, NULL),
(384, 'CP-28666-372', 'EPIOTIC SPHERULITES FCO X 100ML', 'Producto', 62.00, 2, NULL),
(385, 'CP-42875-373', 'FRIPETS OTICO LIMPIADOR FCO X 100ML', 'Producto', 48.00, 1, NULL),
(403, 'CP-50068-390', 'aminort gotas 15 ml', 'Producto', 25.00, 3, '2027-07-07'),
(404, 'CP-76372-391', 'triplex comprimido', 'Producto', 10.00, 12, '2025-11-11'),
(405, 'CP-95881-392', 'biomisol gotas', 'Producto', 20.00, 6, '2026-11-11'),
(406, 'CP-15269-393', 'ferrocan gotas', 'Producto', 35.00, 3, '2027-12-12'),
(407, 'CP-68923-394', 'sarnafin pasta', 'Producto', 25.00, 1, '2027-02-02'),
(408, 'CP-99455-395', 'ferrocan compuesto oral', 'Producto', 2.00, 90, '2027-07-07'),
(409, 'CP-26803-396', 'vetacef 250 suspension', 'Producto', 45.00, 3, '2026-09-09'),
(410, 'CP-17858-397', 'sultrin forte jarabe', 'Producto', 35.00, 2, '2026-06-06'),
(411, 'CP-39087-398', 'ferrocan jarabe', 'Producto', 35.00, 2, '2027-09-09'),
(412, 'CS-34414-399', 'bronquifarma suspension', 'Producto', 50.00, 2, '2026-11-11'),
(413, 'CP-41405-400', 'enrofloxacina 10% gotas', 'Producto', 25.00, 1, '2027-02-02'),
(414, 'CP-71630-401', 'bactrovet plata am', 'Producto', 50.00, 3, '2027-01-01'),
(415, 'CP-41754-402', 'curabicheras galmedic', 'Producto', 40.00, 3, '2027-04-04'),
(416, 'CP-87957-403', 'matabicheiras forte sv zoetis', 'Producto', 35.00, 5, '2025-11-11'),
(417, 'CS-01799-404', 'gentax-pet colirio', 'Producto', 20.00, 8, '2025-12-12'),
(418, 'CP-83570-405', 'gentavet colirio', 'Producto', 35.00, 0, '2026-09-09'),
(419, 'CS-67498-406', 'metoclopramida gotas', 'Producto', 35.00, 4, '2027-07-07'),
(420, 'CS-85821-407', 'domperidona gotas', 'Producto', 30.00, 4, '2027-02-02'),
(421, 'CS-23388-408', 'midapet', 'Producto', 25.00, 4, '2024-09-30'),
(422, 'CS-54376-409', 'ANICEDAN GOTAS', 'Producto', 35.00, 2, '2026-05-01'),
(424, 'CS-43447-411', 'Shampoo Shapadog Clorhexidina 550 ml', 'Producto', 30.00, 3, '2024-12-31'),
(425, 'CP-92787-412', 'Shampoo Shapadog antifungico 200 ml', 'Producto', 20.00, 1, '2025-09-28'),
(426, 'CP-77181-413', 'Shampoo Shapadog Antifungico 450 ml', 'Producto', 40.00, 10, '2025-09-28'),
(427, 'CP-80026-414', 'Shampoo Shapadog pulguicida 200 ml', 'Producto', 18.00, 3, '2025-09-28'),
(428, 'CS-24629-415', 'Shampoo Shapadog pulguicida 600 ml', 'Producto', 35.00, 16, '2025-09-28'),
(429, 'CP-66674-416', 'Shampoo Shapadog control caida 200 ml', 'Producto', 18.00, 3, '2025-06-30'),
(430, 'CP-56830-417', 'Shampoo Shapadog Control Caida 600 ml', 'Producto', 30.00, 18, '2025-06-30'),
(431, 'CP-01434-418', 'Shampoo shapadog antisarnico 450 ml', 'Producto', 35.00, 14, '2026-07-20'),
(437, 'CP-27315-401', 'atrevia 10-20 kg', 'Producto', 120.00, 10, '2025-12-26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `raza`
--

CREATE TABLE `raza` (
  `idRaza` int(11) NOT NULL,
  `razaNombre` varchar(100) DEFAULT NULL,
  `idEspecie` int(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `raza`
--

INSERT INTO `raza` (`idRaza`, `razaNombre`, `idEspecie`) VALUES
(5, 'Pug', 10),
(6, 'Dalmata', 10),
(7, 'Angora', 11),
(8, 'Persa', 11),
(10, 'ESFINGE', 11),
(11, 'MASTIN NAPOLITANO', 10),
(13, 'pequines', 10),
(14, 'BEAGLE', 10),
(15, 'DOGO', 10),
(16, 'MESTIZO', 10),
(17, 'SHITH ZU', 10),
(18, 'SCHNAUZER', 10),
(19, 'CRUCE SCHNAUZER', 10),
(20, 'COCKER AMERICANO', 10),
(21, 'COCKER INGLES', 10),
(22, 'PITTBULL', 10),
(23, 'AMERICAN PITBULL TERRRIER', 10),
(24, 'DALMATA', 10),
(25, 'PASTOR ALEMAN', 10),
(26, 'PEKINES', 10),
(27, 'CHIHUAHUA', 10),
(28, 'OVEJERO INGLES', 10),
(29, 'LABRADOR', 10),
(30, 'GOLDEN RETRIEVER', 10),
(31, 'SCOTISH TERRIER', 10),
(32, 'YORKSHIRE TERRIER', 10),
(33, 'BULLDOG INGLES', 10),
(34, 'BULLDOG FRANCES', 10),
(35, 'FOX TERRIER', 10),
(36, 'CRUCE SCHNAUZER', 10),
(37, 'BOXER', 10),
(38, 'BULL TERRIER', 10),
(39, 'POODLE', 10),
(40, 'SIBERIAN HUSKY', 10),
(41, 'SAMOYEDO', 10),
(42, 'AKITA', 10),
(43, 'PUG', 10),
(44, 'SHAR PEI', 10),
(45, 'WEIMERANER', 10),
(46, 'SAN BERNARDO', 10),
(47, 'ROTTWEILLER', 10),
(48, 'FILA BRASILERO', 10),
(49, 'SALCHICHA', 10),
(50, 'POMERANIAN', 10),
(51, 'BASSET HOUND', 10),
(52, 'ALASKAN MALAMUTE', 10),
(53, 'PASTOR BELGA', 10),
(54, 'BICHON FRANCES', 10),
(56, 'DOBERMAN', 10),
(57, 'CHOW CHOW', 10),
(58, 'SIN PELO DEL PERU', 10),
(59, 'COLLIE', 10),
(60, 'BORDER', 10),
(61, 'AMERICAN BULLY', 10),
(62, 'SIAMES', 11),
(63, 'SUDAMERICANO PELO CORTO', 11),
(64, 'SUDAMERICANO PELO LARGO', 11),
(65, 'PERSA PEKINES', 11),
(66, 'RUSO AZUL', 11),
(68, 'BENGALA', 11),
(69, 'MAINE COON', 11),
(70, 'MESTIZO', 11),
(71, 'PASTOR AUSTRALIANO', 10),
(72, 'RUSO AZUL', 11),
(74, 'PASTOR AUSTRALIANO', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `userDni` int(20) DEFAULT NULL,
  `userNombre` varchar(50) DEFAULT NULL,
  `userApellido` varchar(50) DEFAULT NULL,
  `userTelefono` varchar(20) DEFAULT NULL,
  `userDomicilio` varchar(150) DEFAULT NULL,
  `userEmail` varchar(150) DEFAULT NULL,
  `userFoto` text DEFAULT NULL,
  `userUsuario` varchar(50) DEFAULT NULL,
  `userClave` varchar(500) DEFAULT NULL,
  `userEstado` varchar(50) DEFAULT NULL,
  `userPrivilegio` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `userDni`, `userNombre`, `userApellido`, `userTelefono`, `userDomicilio`, `userEmail`, `userFoto`, `userUsuario`, `userClave`, `userEstado`, `userPrivilegio`) VALUES
(1, 2147483647, 'Veterinaria', 'Asuncion', '986689786', 'Jr. Asunción 571 Urb El Parral Comas', 'centroveterinarioasuncion@gmail.com', 'adjuntos/user-sistema-foto/20608915665_08_02_2024_170936logo.jpg', 'admin', 'YldwbjlkRktaUmxsUnpKWU5oUXVWUT09', 'Activa', '1'),
(9, 8195257, 'juan daniel', 'Quespia', '75507990', 'Barrio Cortez', 'j.daniquespia26@gmail.com', '', 'daniel', 'cmtGSGt2Wm5zNnlsUk1OTU5lV0VWdz09', 'Activa', '1'),
(12, 9784134, 'MAX', 'IBARRA', '78539084', 'PAMPA DE LA ISLA', 'maxibarra391@gmail.com', '', 'MAX', 'ajR6NkZwZHFQNkd1amk1Kzgwc296UT09', 'Activa', '2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacunas`
--

CREATE TABLE `vacunas` (
  `idVacuna` int(11) NOT NULL,
  `vacunaNombre` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `especieId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `vacunas`
--

INSERT INTO `vacunas` (`idVacuna`, `vacunaNombre`, `especieId`) VALUES
(3, 'VACUNA PUPPY DP', 10),
(4, 'VACUNA HEXAVALENTE', 10),
(7, 'VACUNA CUADRUPLE', 10),
(9, 'VACUNA TRIPLE FELINA', 11),
(10, 'VACUNA ANTIRRABICA', 11),
(11, 'VACUNA ANTIRRABICA', 10),
(13, 'VACUNA OCTAVALENTE', 10),
(14, 'VACUNA PARVOVIRUS', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `idVenta` int(11) NOT NULL,
  `dniCliente` varchar(250) DEFAULT NULL,
  `ventUsuario` int(11) DEFAULT NULL,
  `ventFecha` datetime DEFAULT NULL,
  `ventMetodoPago` varchar(50) DEFAULT NULL,
  `tipo_comprobante` varchar(20) DEFAULT NULL,
  `serie_comprobante` varchar(7) DEFAULT NULL,
  `num_comprobante` varchar(10) DEFAULT NULL,
  `ventTotal` decimal(11,2) DEFAULT NULL,
  `estado` varchar(250) DEFAULT NULL,
  `dov_Estado` varchar(250) DEFAULT NULL,
  `dov_Nombre` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`idVenta`, `dniCliente`, `ventUsuario`, `ventFecha`, `ventMetodoPago`, `tipo_comprobante`, `serie_comprobante`, `num_comprobante`, `ventTotal`, `estado`, `dov_Estado`, `dov_Nombre`) VALUES
(954, '6357306', 6, '2024-08-26 08:36:40', 'Efectivo', 'Boleta', 'B001', '00000001', 105.00, NULL, NULL, NULL),
(955, '6357306', 6, '2024-08-26 08:41:53', 'Efectivo', 'Boleta', 'B001', '00000002', 50.00, NULL, NULL, NULL),
(965, '12630724', 9, '2024-09-11 07:19:15', 'Efectivo', 'Boleta', 'B001', '00000012', 50.00, NULL, NULL, NULL),
(966, '6357306', 9, '2024-09-12 05:37:01', 'Efectivo', 'Boleta', 'B001', '00000013', 50.00, NULL, NULL, NULL),
(968, '8188187', 9, '2024-09-13 05:04:43', 'Efectivo', 'Boleta', 'B001', '00000015', 8.00, NULL, NULL, NULL),
(969, '8188187', 9, '2024-09-14 02:56:25', 'Efectivo', 'Boleta', 'B001', '00000016', 70.00, NULL, NULL, NULL),
(971, '72623987', 9, '2024-09-16 11:15:33', 'Efectivo', 'Boleta', 'B001', '00000017', 50.00, NULL, NULL, NULL),
(972, '72623987', 9, '2024-09-16 13:20:19', 'Efectivo', 'Boleta', 'B001', '00000018', 50.00, NULL, NULL, NULL),
(973, '72623987', 9, '2024-09-16 13:20:54', 'Efectivo', 'Boleta', 'B001', '00000019', 50.00, NULL, NULL, NULL),
(974, '72623987', 9, '2024-09-16 14:36:12', 'Efectivo', 'Boleta', 'B001', '00000020', 140.00, NULL, NULL, NULL),
(975, '8188187', 9, '2024-09-17 10:58:33', 'Efectivo', 'Boleta', 'B001', '00000021', 20.00, NULL, NULL, NULL),
(976, '8195257', 9, '2024-09-17 15:37:57', 'Efectivo', 'Boleta', 'B001', '00000022', 218.00, NULL, NULL, NULL),
(977, '123456789', 9, '2024-09-20 11:15:31', 'Efectivo', 'Boleta', 'B001', '00000023', 70.00, NULL, NULL, NULL);

--
-- Disparadores `venta`
--
DELIMITER $$
CREATE TRIGGER `before_insert_venta` BEFORE INSERT ON `venta` FOR EACH ROW SET NEW.ventFecha = CURRENT_TIMESTAMP - INTERVAL 6 HOUR
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_venta2` BEFORE INSERT ON `venta` FOR EACH ROW SET NEW.ventFecha = CURRENT_TIMESTAMP
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `veterinario`
--

CREATE TABLE `veterinario` (
  `idVeterinario` int(11) NOT NULL,
  `vetDni` varchar(20) NOT NULL,
  `vetNombre` varchar(50) DEFAULT NULL,
  `vetApellido` varchar(50) DEFAULT NULL,
  `vetGenero` varchar(20) DEFAULT NULL,
  `vetTelefono` varchar(20) DEFAULT NULL,
  `vetEspecialidad` varchar(150) DEFAULT NULL,
  `vetDomicilio` varchar(150) DEFAULT NULL,
  `vetFotoUrl` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `veterinario`
--

INSERT INTO `veterinario` (`idVeterinario`, `vetDni`, `vetNombre`, `vetApellido`, `vetGenero`, `vetTelefono`, `vetEspecialidad`, `vetDomicilio`, `vetFotoUrl`) VALUES
(3, '1245456', 'NAYRA', 'PEREZ', 'Femenino', '986689996', 'ESTUDIANTE MÉDICO VETERINARIO', 'Jr. mascot', 'vistas/images/avatar_user_cli/avatar_cli_4.svg'),
(4, '8195257', 'JUAN DANIEL', 'QUESPIA', 'Masculino', '75507990', 'Veterinario', 'CORTEZ', 'vistas/images/avatar_user_cli/avatar_cli_12.svg'),
(6, '9784134', 'Max', 'Ibarra Condori', 'Masculino', '78539084', 'MEDICO VETERINARIO', 'Pampa de la Isla', 'adjuntos/veterinario-foto/9784134_18_09_2024_155525ImagendeWhatsApp2024-09-12alas12.17.57_b039cffc.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `adjuntoshistorial`
--
ALTER TABLE `adjuntoshistorial`
  ADD PRIMARY KEY (`idAdjunto`),
  ADD KEY `codHistorialM` (`codHistorialM`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`idCita`),
  ADD UNIQUE KEY `codCita` (`codCita`),
  ADD KEY `codMascota` (`codMascota`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idCliente`);

--
-- Indices de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD PRIMARY KEY (`idDetalle`),
  ADD KEY `codProducto` (`codProducto`),
  ADD KEY `codFactura` (`codFactura`);

--
-- Indices de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD PRIMARY KEY (`correlativo`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`idempresa`);

--
-- Indices de la tabla `especie`
--
ALTER TABLE `especie`
  ADD PRIMARY KEY (`idEspecie`);

--
-- Indices de la tabla `historialmascota`
--
ALTER TABLE `historialmascota`
  ADD PRIMARY KEY (`idHistorial`),
  ADD UNIQUE KEY `codHistorialM` (`codHistorialM`),
  ADD KEY `codMascota` (`codMascota`);

--
-- Indices de la tabla `historialvacuna`
--
ALTER TABLE `historialvacuna`
  ADD PRIMARY KEY (`idHistoriaVacuna`),
  ADD KEY `Índice 2` (`idVacuna`),
  ADD KEY `Índice 3` (`codMascota`);

--
-- Indices de la tabla `mascota`
--
ALTER TABLE `mascota`
  ADD PRIMARY KEY (`idmascota`),
  ADD UNIQUE KEY `CodMascota` (`codMascota`),
  ADD KEY `idEspecie` (`idEspecie`),
  ADD KEY `idRaza` (`idRaza`),
  ADD KEY `dniDueno` (`dniDueno`);

--
-- Indices de la tabla `notasmascotas`
--
ALTER TABLE `notasmascotas`
  ADD PRIMARY KEY (`idNota`),
  ADD KEY `codMascota` (`codMascota`);

--
-- Indices de la tabla `productoservicio`
--
ALTER TABLE `productoservicio`
  ADD PRIMARY KEY (`idProdservi`),
  ADD UNIQUE KEY `codProducto` (`codProdservi`);

--
-- Indices de la tabla `raza`
--
ALTER TABLE `raza`
  ADD PRIMARY KEY (`idRaza`),
  ADD KEY `idEspecie` (`idEspecie`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userDni` (`userDni`);

--
-- Indices de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  ADD PRIMARY KEY (`idVacuna`),
  ADD KEY `Índice 2` (`especieId`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`idVenta`),
  ADD KEY `Índice 3` (`ventUsuario`);

--
-- Indices de la tabla `veterinario`
--
ALTER TABLE `veterinario`
  ADD PRIMARY KEY (`idVeterinario`),
  ADD UNIQUE KEY `vetDni` (`vetDni`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `adjuntoshistorial`
--
ALTER TABLE `adjuntoshistorial`
  MODIFY `idAdjunto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `idCita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1206;

--
-- AUTO_INCREMENT de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  MODIFY `idDetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2672;

--
-- AUTO_INCREMENT de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `idempresa` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `especie`
--
ALTER TABLE `especie`
  MODIFY `idEspecie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `historialmascota`
--
ALTER TABLE `historialmascota`
  MODIFY `idHistorial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1147;

--
-- AUTO_INCREMENT de la tabla `historialvacuna`
--
ALTER TABLE `historialvacuna`
  MODIFY `idHistoriaVacuna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de la tabla `mascota`
--
ALTER TABLE `mascota`
  MODIFY `idmascota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1479;

--
-- AUTO_INCREMENT de la tabla `notasmascotas`
--
ALTER TABLE `notasmascotas`
  MODIFY `idNota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `productoservicio`
--
ALTER TABLE `productoservicio`
  MODIFY `idProdservi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=438;

--
-- AUTO_INCREMENT de la tabla `raza`
--
ALTER TABLE `raza`
  MODIFY `idRaza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  MODIFY `idVacuna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idVenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=978;

--
-- AUTO_INCREMENT de la tabla `veterinario`
--
ALTER TABLE `veterinario`
  MODIFY `idVeterinario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `FK_citas_mascota` FOREIGN KEY (`codMascota`) REFERENCES `mascota` (`codMascota`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
