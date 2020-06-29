-- MySQL dump 10.17  Distrib 10.3.16-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: repo
-- ------------------------------------------------------
-- Server version	10.3.16-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `repo`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `repo` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `repo`;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `id_cliente` int(10) unsigned NOT NULL,
  `nom_cliente` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `rut_cliente` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `giro_cliente` text CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `descuento` int(2) NOT NULL,
  `dire_cliente` varchar(80) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `comuna_cliente` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `ciudad_cliente` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `correo_cliente` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `telef_cliente` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `contac_cliente` varchar(80) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`rut_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'EMPRESA DE CAMIONES ÑECO','12344444-9','ALIMENTOS',0,'CALLLE 5 #42','LAMPA','CHILLAN','a@update.com','55555555555555','JUAN RODRIGUEZ'),(15,'AVICOLA LA GALLINA','12345667-7','AVICOLA',0,'EL GALLINERO # 45','LA GRANJA','SANTIAGO','cristina.fernandez@gmail.com','+56944991122','CRISTINA FERNANDEZ DE KIRCHNER'),(0,'PESCADERIA EL PESCADO','1234567-K','ALIMENTOS',0,'CALLE EL PUERTO #12','VALPARAISO','VALPARAISO','juan@pesca.com','+56 912345678','JUAN PESCADOR'),(2,'LA GRAN REPARADORA DE VEHICULO','12345678-9','TALLER',0,'Avenida Los Pelados 20123 Camino a Lonquen','Cerro Navia','Santiago','greppa@gmail.com','912345678','Pedro Perez Alfonso'),(16,'TALLER LA LATA','12345678-K','MECANICA',0,'CALLE LA LATA #12','RENCA','SANTIAGO','lata@gmail.com','912345678','LATERO LATON'),(3,'FUNERARIA LA FE','123456789-0','FUNERARIA',0,'CEMENTERIO METROPOLITANO','RECOLETA','SANTIAGO','dracula@dios.com','94556789','DRACULA'),(4,'EMPRESA DE CAMIONES','123456789-9','CONSTRUCCION',0,'Avenida 4 casa 3','La Reina','Chillan','empresa@gmail.com','912345678','Pedro Perez'),(5,'FALABELLA','23185226-2','RETAIL',0,'Callle 5 #00','Lampa','Santiago','sara@gmail.com','99912345678','Aldo Valenzuela'),(6,'COMERCIALIZADORA DE LACTEOS MI VACA','234567890-9','GANADERIA',0,'Callle 5 #0088','Las Palmas','Osorno','empresa@gmail.com','111111111111111','Juan Rodriguez'),(0,'MUNECAS APESTOSAS','26767898-8','JUGUETES',0,'10 DE JULIO 909','SANTIAGO','SANTIAGO','luli@luli.cl','555555555','LA LULI'),(8,'REPARADORA DE VEHICULOS 1','455667788-9','CONSTRUCCION',0,'Avenida Los Pelados 20123 Camino a Lonquen','La Reina','Osorno','a@php.com','55555555555555','Juan Rodriguez'),(10,'REPO1','6273643-1','MINERIA',0,'Callle 5 #111','Ñuñoa','Santiago','a@update.com','111111111111111','Pedro Perez'),(13,'ALDO','6373643-0','RETAIL',0,'calle cauquenes 60 casa 45','Ñuñoa','Santiago','aldo@gmail.com','912345678','Pedro Perez'),(0,'FABRICA CORONA VIRUS','66666666-6','FUENERALES',0,'EL CEMENTERIO SIN NUMERO','LA PINTANA','SANTIAGO','rip1@cementerio.cl','999999999','DRACULA'),(14,'MINERA LOS COLIGUES','89321678-3','MINERIA',0,'Carretera 5 Norte Zona Industrial Copaima #6543','Copiapo','Copiapo','coligues@gmail.com','941234567','Jose Manquilef');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cotizaciones`
--

DROP TABLE IF EXISTS `cotizaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cotizaciones` (
  `rut_cliente` varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  `num_cotizacion` int(10) NOT NULL,
  `fecha_cotizacion` date NOT NULL,
  `codigo_parte` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `cantidad` int(3) NOT NULL,
  `descuento` int(3) NOT NULL,
  `movil` char(20) COLLATE utf8_spanish_ci NOT NULL,
  `solcompra` char(20) COLLATE utf8_spanish_ci NOT NULL,
  `userid` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cotizaciones`
--

LOCK TABLES `cotizaciones` WRITE;
/*!40000 ALTER TABLE `cotizaciones` DISABLE KEYS */;
INSERT INTO `cotizaciones` VALUES ('123456789-0',201911161,'2019-11-16','KIT12345Y',11,22,'','','9'),('123456789-0',201911161,'2019-11-16','COMP0123456789',2,22,'','','9'),('234567890-9',201911231,'2019-11-23','FF324RF11',1,10,'HHHW58','ASDF123','9'),('234567890-9',201911231,'2019-11-23','KIT12345Y',10,10,'HHHW58','ASDF123','9'),('234567890-9',201911231,'2019-11-23','CORREA12345',1,10,'HHHW58','ASDF123','9'),('23185226-2',201912021,'2019-12-02','OPT4567890',2,20,'QWAS23','123.456','9'),('23185226-2',201912021,'2019-12-02','VW12345K789',2,20,'QWAS23','123.456','9'),('23185226-2',201912021,'2019-12-02','XSWQAZ12',2,20,'QWAS23','123.456','9'),('12345667-7',201912091,'2019-12-09','FF324RF11',1,10,'KRYF19$%','123,456','9'),('12345667-7',201912091,'2019-12-09','KIT12345Y',12,10,'KRYF19$%','123,456','9'),('1234567-K',201912101,'2019-12-10','XSWQAZ12',12,20,'KRYF19','SC2019/23','9'),('1234567-K',201912101,'2019-12-10','OPT4567890',4,20,'KRYF19','SC2019/23','9'),('12345667-7',202002071,'2020-02-07','BVC5678XZ',11,12,'QWAS34','123.456','9'),('12345667-7',202002071,'2020-02-07','MB160948',12,12,'QWAS34','123.456','9'),('12345667-7',202004041,'2020-04-04','SUBFORES9423895832X',1,8,'KRYF19','OC1120420','9'),('12345667-7',202004041,'2020-04-04','SUBFORES948975489RY',2,8,'KRYF19','OC1120420','9'),('12345667-7',202004041,'2020-04-04','VW12345K789',3,8,'KRYF19','OC1120420','9'),('12345667-7',202004041,'2020-04-04','KIT12345Y',10,8,'KRYF19','OC1120420','9'),('12345678-K',202004061,'2020-04-06','PTA65348BT',5,12,'','','9'),('12345678-K',202004061,'2020-04-06','VW12345K789',6,12,'','','9'),('66666666-6',202004101,'2020-04-10','FF324RF11',5,5,'KRYF19','OCER3444','9'),('66666666-6',202004101,'2020-04-10','KIT12345Y',5,5,'KRYF19','OCER3444','9'),('66666666-6',202004101,'2020-04-10','KK2345',5,5,'KRYF19','OCER3444','9'),('1234567-K',202004201,'2020-04-20','BF123456XX',11,11,'KRYF21','OC6547','9'),('1234567-K',202004201,'2020-04-20','BVC5678XZ',12,11,'KRYF21','OC6547','9'),('1234567-K',202004201,'2020-04-20','BOB12345X',13,11,'KRYF21','OC6547','9'),('26767898-8',202004202,'2020-04-20','COMP0123456789',5,5,'KRYF23','OC1267','9'),('26767898-8',202004202,'2020-04-20','CORR123456K8',6,5,'KRYF23','OC1267','9'),('66666666-6',202006031,'2020-06-03','BVC5678XZ',5,10,'KRFY67','SC45/2020','9'),('66666666-6',202006031,'2020-06-03','KIT12345Y',10,10,'KRFY67','SC45/2020','9'),('12345667-7',202006061,'2020-06-06','FF324RF11',10,0,'GFNB23','UV456','9'),('12345667-7',202006121,'2020-06-12','KIT12345Y',12,10,'HJYT45','1234','4'),('12345667-7',202006121,'2020-06-12','FF324RF11',1,10,'HJYT45','1234','4');
/*!40000 ALTER TABLE `cotizaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cotizaciones_part`
--

DROP TABLE IF EXISTS `cotizaciones_part`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cotizaciones_part` (
  `rut_cliente` varchar(11) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `correo` varchar(50) COLLATE utf8_spanish_ci DEFAULT 'NULL',
  `num_cotizacion` int(10) NOT NULL,
  `fecha_cotizacion` date NOT NULL,
  `codigo_parte` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `cantidad` int(3) NOT NULL,
  `descuento` int(11) NOT NULL,
  `userid` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cotizaciones_part`
--

LOCK TABLES `cotizaciones_part` WRITE;
/*!40000 ALTER TABLE `cotizaciones_part` DISABLE KEYS */;
INSERT INTO `cotizaciones_part` VALUES ('12567890','PACO PICON','paco@pico.com',201911241,'2019-11-24','XSWQAZ12',11,0,'9'),('12567890','PACO PICON','paco@pico.com',201911241,'2019-11-24','VW12345K789',1,0,'9'),('12765345','OMAR PEREZ','omar@perez.com',201912021,'2019-12-02','MB160948',2,0,'9'),('12765345','OMAR PEREZ','omar@perez.com',201912021,'2019-12-02','KIT12345Y',2,0,'9'),('12765345','OMAR PEREZ','omar@perez.com',201912021,'2019-12-02','OPT4567890',2,0,'9'),('9234567-K','PEDRO PESCADOR','peces@pesca.cl',201912101,'2019-12-10','COMP0123456789',10,0,'9'),('8038624-7','JUAN PEREZ','juan@perez.com',202002071,'2020-02-07','XSWQAZ12',1,0,'9'),('8038624-7','JUAN PEREZ','juan@perez.com',202002071,'2020-02-07','VW12345K789',2,0,'9'),('8038624-7','JUAN PEREZ','juan@perez.com',202002071,'2020-02-07','PTA65348BT',3,0,'9'),('06373611-1','PETER ROCK','peterr@gmail.com',202004041,'2020-04-04','VW12345K789',2,0,'9'),('06373611-1','PETER ROCK','peterr@gmail.com',202004041,'2020-04-04','PTA65348BT',2,0,'9'),('06373611-1','PETER ROCK','peterr@gmail.com',202004041,'2020-04-04','CORR123456K8',11,0,'9'),('23412344-4','SARA','as@a.cl',202004101,'2020-04-10','KIT12345Y',12,0,'9'),('7896652-K','DONALD TROMPETA','a@a.cl',202004201,'2020-04-20','BVC5678XZ',12,0,'9'),('7896652-K','DONALD TROMPETA','a@a.cl',202004201,'2020-04-20','COMP0123456789',13,0,'9'),('7896652-K','DONALD TROMPETA','a@a.cl',202004201,'2020-04-20','BOB12345X',11,0,'9'),('17234567-0','PABLO MARMOL','pablo@marmol.cl',202006061,'2020-06-06','KIT12345Y',20,0,'9'),('25678234-0','JOSE PINERA','jose@mail.cl',202006062,'2020-06-06','BOB12345X',10,0,'9');
/*!40000 ALTER TABLE `cotizaciones_part` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `datos_clientes`
--

DROP TABLE IF EXISTS `datos_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datos_clientes` (
  `id_cliente` int(10) NOT NULL,
  `direccion` varchar(50) CHARACTER SET ascii NOT NULL,
  `comuna` varchar(20) CHARACTER SET ascii NOT NULL,
  `ciudad` varchar(30) CHARACTER SET ascii NOT NULL,
  `email` varchar(30) CHARACTER SET ascii NOT NULL,
  `telefono` varchar(20) CHARACTER SET ascii NOT NULL,
  `contacto` varchar(30) CHARACTER SET ascii NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `datos_clientes`
--

LOCK TABLES `datos_clientes` WRITE;
/*!40000 ALTER TABLE `datos_clientes` DISABLE KEYS */;
INSERT INTO `datos_clientes` VALUES (0,'Alicia Cursi','acursi@gmail.com','123456789','','',''),(0,'Pelayo Perezon','jaavila@cantv.net','+56 976543321','','',''),(0,'Waldo Silva Melendez','francisquiva@gmail.c','999999999','','','');
/*!40000 ALTER TABLE `datos_clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marcas`
--

DROP TABLE IF EXISTS `marcas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marcas` (
  `marca` varchar(20) NOT NULL,
  `sigla` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marcas`
--

LOCK TABLES `marcas` WRITE;
/*!40000 ALTER TABLE `marcas` DISABLE KEYS */;
INSERT INTO `marcas` VALUES ('MITSUBISHI','MM'),('TOYOTA','TY'),('CHEVROLET','CH'),('SSANGYONG','SY'),('PEUEGEOT','PG'),('CITROEN','CT'),('FORD','FD'),('SUBARU','SB'),('AUDI','AU'),('MERCEDES','MB'),('HONDA','HO'),('HYUNDAI','HY'),('KIA','KI'),('VOLVO','VL'),('MAZDA','MZ'),('VOLKSWAGEN','VW'),('SUZUKI','SZ'),('CHRYSLER','CS'),('DODGE','DG'),('FIAT','FT'),('JEEP','JE'),('MAHINDRA','MH'),('MG','MG'),('OPEL','OP'),('RENAULT','RL'),('SAMSUNG','SM');
/*!40000 ALTER TABLE `marcas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partes`
--

DROP TABLE IF EXISTS `partes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partes` (
  `marca` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `modelo` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `tipo_rep` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `cod_tiporep` varchar(3) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `vin` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `codigo` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `costo` decimal(15,2) NOT NULL,
  `valor` decimal(15,2) NOT NULL,
  `valor1` decimal(15,2) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partes`
--

LOCK TABLES `partes` WRITE;
/*!40000 ALTER TABLE `partes` DISABLE KEYS */;
INSERT INTO `partes` VALUES ('RENAULT','DUSTER','BOMBA FRENO','',NULL,'BF123456XX','BOMBA DIRECCION RENAULT',6789.00,12345.00,0.00),('AUDI','BEETLE','ADHESIVO','',NULL,'BOB12345X','BOBINA',1234.00,2000.00,1.00),('MAHINDRA','MX-45','RODAMIENTO HOMOCINET','',NULL,'BVC5678XZ','RODAMIENTO HOMOCINETICO',12345.00,23456.00,0.00),('OPEL','CORSA','COMPRESOR','',NULL,'COMP0123456789','COMPRESOR',25000.00,30000.00,180.00),('FORD','SPARK','CORREA COMPRESOR','',NULL,'CORR123456K8','CORREA COMPRESOR',12300.00,23400.00,0.00),('CHRYSLER','123','CREMALLERA','',NULL,'FF324RF11','CORREA',19000.00,20000.00,7890.00),('TOYOTA','LAND CRUISER','KIT DISTRIBUCION','',NULL,'KIT12345Y','KIT DISTRIBUCION',4567.00,5678.00,1.00),('KIA','RIO','BOCINA','',NULL,'KK2345','BOCINA BOMBA DE AGUA',4500.00,9000.00,85.00),('CHEVROLET','DMAX','PARACHOQUE','047','0123456789','MB160948','PARACHOQUE DELANTERO DMAX RT50 2015',80000.00,120000.00,0.00),('SUZUKI','SWIFT','OPTICO','',NULL,'OPT4567890','OPTICO',65000.00,135000.00,267.00),('VOLKSWAGEN','BEETLE','PUERTA','',NULL,'PTA65348BT','PUERTA DERECHA BEETLE 2000-2010',123780.00,150000.00,0.00),('SUBARU','FORESTER','BALATAS','',NULL,'SUBFORES9423895832X','BALATAS TRASERAS',15000.00,25000.00,0.00),('SUBARU','FORESTER','KIT EMBRAGUE','',NULL,'SUBFORES948975489RY','KIT DE EMBRAGUE',80000.00,110000.00,0.00),('VOLKSWAGEN','BEETLE','TURBO','',NULL,'VW12345K789','TURBO ALIMENTADOR',55670.00,134890.00,0.00),('VOLVO','XX1','CAZOLETA','',NULL,'XSWQAZ12','CAZOLETA1',1234.00,5678.00,12.00);
/*!40000 ALTER TABLE `partes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Basketball','A ball used in the NBA.',49.99,'2015-08-02 12:04:03','2015-08-06 09:59:18'),(3,'Gatorade','This is a very good drink for athletes.',1.99,'2015-08-02 12:14:29','2015-08-06 09:59:18'),(4,'Eye Glasses','It will make you read better.',6,'2015-08-02 12:15:04','2015-08-06 09:59:18'),(5,'Trash Can','It will help you maintain cleanliness.',3.95,'2015-08-02 12:16:08','2015-08-06 09:59:18'),(6,'Mouse','Very useful if you love your computer.',11.35,'2015-08-02 12:17:58','2015-08-06 09:59:18'),(7,'Earphone','You need this one if you love music.',7,'2015-08-02 12:18:21','2015-08-06 09:59:18'),(8,'Pillow','Sleeping well is important.',8.99,'2015-08-02 12:18:56','2015-08-06 09:59:18');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiporepuesto`
--

DROP TABLE IF EXISTS `tiporepuesto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tiporepuesto` (
  `tipo_rep` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `cod_tipo_rep` varchar(3) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cod_tipo_rep`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tiporepuesto`
--

LOCK TABLES `tiporepuesto` WRITE;
/*!40000 ALTER TABLE `tiporepuesto` DISABLE KEYS */;
INSERT INTO `tiporepuesto` VALUES ('ADHESIVO','001'),('AMORTIGUADOR','002'),('ANTENA','003'),('AXIAL','004'),('BALATAS','005'),('BANDEJA','006'),('BIELETA','007'),('BOBINA','008'),('BOCINA','009'),('BOMBA DIRECCION','010'),('BOMBA AGUA','011'),('BOMBA FRENO','012'),('BRAZO DIRECCION','013'),('BUJE','014'),('CAZOLETA','015'),('CILINDRO FRENO','016'),('COMPRESOR','017'),('CORREA ALTERNADOR','018'),('CORREA COMPRESOR','019'),('CREMALLERA','020'),('CULATA','021'),('DEPOSITO','022'),('DISCO','023'),('ESPEJO','024'),('EXTREMO DIRECCION','025'),('EYECTOR','026'),('FILTRO AIRE','027'),('FILTRO ACEITE','028'),('FILTRO CABINA','029'),('FILTRO PETROLEO','030'),('FILTRO DECANTADOR','031'),('FOCO','032'),('GUARDAFANGO','033'),('GUIA PARACHOQUE','034'),('INTERCOOLER','035'),('INYECTOR','036'),('KIT EMBRAGUE','037'),('KIT DISTRIBUCION','038'),('LLANTA','039'),('LLAVE','040'),('MASCARA','041'),('MAZA','042'),('MOLDURA','043'),('NEBLINERO','044'),('OPTICO','045'),('PAQUETE RESORTE','046'),('PARACHOQUE','047'),('PASTILLAS','048'),('PERNO RUEDA','049'),('PIOLA FRENO','050'),('PLUMILLAS','051'),('PUERTA','052'),('PUNTA HOMOCINETICA','053'),('RADIADOR','054'),('REFLECTANTE','055'),('REGULADOR','056'),('RODAMIENTO HOMOCINET','057'),('RODAMIENTO CAZOLETA','058'),('RODAMIENTO EMBRAGUE','059'),('RODAMIENTO MAZA','060'),('ROTULA','061'),('SENSOR ABS','062'),('SET','063'),('SOPORTE CARDAN','064'),('SOPORTE VIDRIO','065'),('TAMBOR','066'),('TAPABARRO','067'),('TECLE','068'),('TENSOR','069'),('TERMINAL','070'),('TUERCA','071'),('TURBO','072'),('VOLANTE','073'),('SENSOR CKP','074'),('REJILLA','075');
/*!40000 ALTER TABLE `tiporepuesto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `created` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (4,'ALDO','$2y$10$cchi6YpM4aAV24EeBPdFmOni0Wl013A8PtUQVZ0/wtQaGbIpdQBzu','Administrador','2019-05-13 16:15:52'),(6,'DIEGO','$2y$10$CQZUjGNlTXrAmhgyMlhrwu6a1SxRKpVRaYTlwJ9Zo82YaN8iMC2hq','Administrador','2019-05-19 21:24:48'),(9,'ROBERTO','$2y$10$ru3K16LS.tSqry/o1igPie99JOmRf2Nm0LTBX7WSQnl2Nw8lcOfuC','Vendedor','2019-09-28 21:48:12'),(10,'GOD','$2y$10$fFOLlsvm78R55t/Q8SpxFumy/ynGw/Cj9ytyIbCsEiGy2rM.IoqXq','Master','2020-04-26 18:42:23');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas` (
  `rut_cliente` varchar(11) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `num_venta` int(10) NOT NULL,
  `fecha_venta` date NOT NULL,
  `codigo_parte` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `cantidad` int(3) NOT NULL,
  `descuento` int(3) NOT NULL,
  `movil` char(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `solcompra` char(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `userid` varchar(4) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
INSERT INTO `ventas` VALUES ('123456789-0',201911161,'2019-11-16','KIT12345Y',11,22,'','','9'),('123456789-0',201911161,'2019-11-16','COMP0123456789',2,22,'','','9'),('123456789-9',201911231,'2019-11-23','KIT12345Y',1,10,'QWER56','ASD123','9'),('123456789-9',201911231,'2019-11-23','FF324RF11',1,10,'QWER56','ASD123','9'),('123456789-9',201911231,'2019-11-23','CORREA12345',1,10,'QWER56','ASD123','9'),('234567890-9',201911232,'2019-11-23','XSWQAZ12',1,15,'HHHW58','ASD123','9'),('234567890-9',201911232,'2019-11-23','VW12345K789',1,15,'HHHW58','ASD123','9'),('234567890-9',201911232,'2019-11-23','OPT4567890',1,15,'HHHW58','ASD123','9'),('23185226-2',201911233,'2019-11-23','BOB12345X',1,10,'SSWH6','ZXC345','9'),('23185226-2',201911233,'2019-11-23','BF123456XX',1,10,'SSWH6','ZXC345','9'),('23185226-2',201911233,'2019-11-23','COMP0123456789',1,10,'SSWH6','ZXC345','9'),('89321678-3',201911234,'2019-11-23','MB160948',11,12,'SDER45','AFG567','9'),('89321678-3',201911234,'2019-11-23','FF324RF11',12,12,'SDER45','AFG567','9'),('455667788-9',201911235,'2019-11-23','KIT12345Y',11,5,'QWER56','HJHJN678','9'),('455667788-9',201911235,'2019-11-23','FF324RF11',12,5,'QWER56','HJHJN678','9'),('12345667-7',201912111,'2019-12-11','FF324RF11',1,10,'KRYF19$%','123,456','9'),('12345667-7',201912111,'2019-12-11','KIT12345Y',12,10,'KRYF19$%','123,456','9'),('12345667-7',201912112,'2019-12-11','FF324RF11',1,10,'KRYF19$%','123,456','9'),('12345667-7',201912112,'2019-12-11','KIT12345Y',12,10,'KRYF19$%','123,456','9'),('123456789-0',202002071,'2020-02-07','BOB12345X',12,5,'ASWE12','AS345-678','9'),('123456789-0',202002071,'2020-02-07','BF123456XX',13,5,'ASWE12','AS345-678','9'),('123456789-0',202002071,'2020-02-07','COMP0123456789',14,5,'ASWE12','AS345-678','9'),('12345667-7',202004193,'2020-04-19','SUBFORES9423895832X',2,11,'KRYF1920','OC212223','9'),('12345667-7',202004193,'2020-04-19','SUBFORES948975489RY',2,11,'KRYF1920','OC212223','9'),('89321678-3',202004194,'2020-04-19','BF123456XX',1,5,'KRYF12','OC123456','9'),('89321678-3',202004194,'2020-04-19','BOB12345X',11,5,'KRYF12','OC123456','9'),('89321678-3',202004194,'2020-04-19','BVC5678XZ',5,5,'KRYF12','OC123456','9'),('123456789-0',202006061,'2020-06-06','BVC5678XZ',10,15,'HJRE45','OC5678','9'),('123456789-0',202006061,'2020-06-06','COMP0123456789',11,15,'HJRE45','OC5678','9'),('12345667-7',202006071,'2020-06-07','FF324RF11',10,0,'GFNB23','UV456','9'),('23185226-2',202006121,'2020-06-12','KK2345',12,5,'BGTR45','45678','4'),('23185226-2',202006121,'2020-06-12','BOB12345X',11,5,'BGTR45','45678','4');
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas_part`
--

DROP TABLE IF EXISTS `ventas_part`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_part` (
  `rut_cliente` varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `correo` varchar(50) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `num_venta` int(10) NOT NULL,
  `fecha_venta` date NOT NULL,
  `codigo_parte` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `cantidad` int(3) NOT NULL,
  `descuento` int(3) NOT NULL,
  `movil` char(20) COLLATE utf8_spanish_ci NOT NULL,
  `solcompra` char(20) COLLATE utf8_spanish_ci NOT NULL,
  `userid` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas_part`
--

LOCK TABLES `ventas_part` WRITE;
/*!40000 ALTER TABLE `ventas_part` DISABLE KEYS */;
INSERT INTO `ventas_part` VALUES ('12972345','JUAN PEREZ','juan@perez.cl',201911231,'2019-11-23','FF324RF11',1,0,'','','9'),('12972345','JUAN PEREZ','juan@perez.cl',201911231,'2019-11-23','CORREA12345',1,0,'','','9'),('12972345','JUAN PEREZ','juan@perez.cl',201911231,'2019-11-23','KIT12345Y',1,0,'','','9'),('23456789','PABLO MARMOL','pablo@marmol.cl',201911232,'2019-11-23','CORR123456K8',1,0,'','','9'),('23456789','PABLO MARMOL','pablo@marmol.cl',201911232,'2019-11-23','COMP0123456789',1,0,'','','9'),('23456789','PABLO MARMOL','pablo@marmol.cl',201911232,'2019-11-23','BVC5678XZ',1,0,'','','9'),('9056123-K','PEPEP PEPON','pepe@pepe.com',202002071,'2020-02-07','FF324RF11',11,0,'','','9'),('9056123-K','PEPEP PEPON','pepe@pepe.com',202002071,'2020-02-07','KIT12345Y',12,0,'','','9'),('12789654-K','PEDRO PABLO','pq@pp.cl',202004111,'2020-04-11','KK2345',1,0,'','','9'),('12789654-K','PEDRO PABLO','pq@pp.cl',202004111,'2020-04-11','KIT12345Y',2,0,'','','9'),('25678234-0','JOSE PINERA','jose@mail.cl',202006071,'2020-06-07','BOB12345X',10,0,'','','9'),('17234567-0','PABLO MARMOL','pablo@marmol.cl',202006072,'2020-06-07','KIT12345Y',20,0,'','','9');
/*!40000 ALTER TABLE `ventas_part` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-06-23 23:05:09
