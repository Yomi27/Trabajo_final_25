-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql203.infinityfree.com
-- Tiempo de generación: 26-01-2026 a las 16:39:00
-- Versión del servidor: 11.4.9-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_40851911_libreria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autor`
--

CREATE TABLE `autor` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `autor`
--

INSERT INTO `autor` (`id`, `nombre`, `activo`) VALUES
(1, 'Sarah J. Maas', 1),
(2, 'Brandon Sanderson', 1),
(3, 'H.P. Lovecraft', 1),
(4, 'Uketsu', 1),
(5, 'SenLinYu', 1),
(6, 'Freida McFadden', 1),
(7, 'Arthur Conan Doyle', 1),
(8, 'William Shakespeare', 1),
(9, 'Richard Morgan', 1),
(10, 'Jane Austen', 1),
(11, 'Agatha Christie', 1),
(12, 'Conn Iggulden', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `visible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`, `activo`, `visible`) VALUES
(2, 'Ciencia ficción', 1, 1),
(3, 'Fantasía', 1, 1),
(4, 'Historia', 1, 1),
(6, 'Novela', 1, 0),
(7, 'Novela romántica', 1, 1),
(9, 'Suspenso', 1, 1),
(10, 'Misterio', 1, 1),
(12, 'Aventura', 1, 0),
(13, 'Biografía', 1, 0),
(14, 'Autobiografía', 1, 0),
(15, 'Poesía', 1, 0),
(16, 'Drama', 1, 0),
(17, 'Cómic', 1, 1),
(18, 'Manga', 1, 0),
(19, 'Teatro', 1, 1),
(20, 'Terror', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
  `id` int(11) NOT NULL,
  `dni_usuario` varchar(20) NOT NULL,
  `nombre_destinatario` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direcciones`
--

INSERT INTO `direcciones` (`id`, `dni_usuario`, `nombre_destinatario`, `direccion`, `ciudad`, `provincia`, `codigo_postal`, `pais`, `telefono`, `activo`) VALUES
(1, '77777777L', 'Aroa', 'Calle Prueba 123', 'Murcia', 'Murcia', '30001', 'España', '600000777', 1),
(4, '74378068R', 'Alex', 'Calle falsa 12', 'Elche', 'Comunidad Valenciana', '03205', 'España', '333222111', 1),
(5, '12345678Z', 'Alex', 'Calle falsa 14', 'Elche', 'Comunidad Valenciana', '03205', 'España', '333222111', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro`
--

CREATE TABLE `libro` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `id_categoria` int(11) DEFAULT NULL,
  `sinopsis` text DEFAULT NULL,
  `portada` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libro`
--

INSERT INTO `libro` (`id`, `titulo`, `isbn`, `precio`, `stock`, `id_categoria`, `sinopsis`, `portada`, `activo`) VALUES
(1, 'Palabras radiantes', '123456789', '19.99', 3, 3, 'Palabras Radiantes (The Way of Kings) es la segunda novela de la saga El Archivo de las Tormentas de Brandon Sanderson, que retoma la épica historia de Kaladin, Shallan y Dalinar en el mundo de Roshar, mientras los Caballeros Radiantes deben resurgir para enfrentar una tormenta eterna, explorando sus poderes, juramentos y el destino de sus órdenes, con revelaciones sobre el Cosmere y nuevos desafíos que los llevan a la mítica ciudad de Urithiru.', '1768607337_81AMbKVGC3L._UF1000,1000_QL80_.jpg', 1),
(4, 'Elantris', NULL, '28.00', 11, 3, 'Elantris, la primera novela de Brandon Sanderson, narra la caída de la magnífica ciudad de Elantris, antaño capital mágica de Arelon habitada por inmortales, ahora un lugar de \"muertos en vida\" malditos por una transformación misteriosa llamada Shaod, enfocándose en los esfuerzos del príncipe Raoden, su esposa política la princesa Sarene, y el sacerdote fanático Hrathen por restaurar la ciudad y el reino, en una historia de política, religión y redención.  ', '1768607368_81v-hlxgYyL._AC_UF1000,1000_QL80_.jpg', 1),
(5, 'Trenza del mar Esmeralda: Una novela del Cosmere', NULL, '19.99', 10, 3, 'Trenza del mar Esmeralda narra la historia de Trenza, una joven isleña que ama coleccionar tazas y a su amigo Charlie, pero cuya vida simple se ve alterada cuando Charlie es secuestrado por una hechicera en el peligroso mar de Medianoche; Trenza debe dejar su hogar para embarcarse como polizona en una aventura pirata, enfrentándose a mares peligrosos para rescatar a su amado, en una fantasía juvenil autoconclusiva dentro del Cosmere de Brandon Sanderson, narrada por el personaje Hoid. ', '1769458418_81Sk43aW5aL._UF1000,1000_QL80_.jpg', 1),
(6, 'Juramentada', NULL, '24.50', 5, 3, 'Juramentada (Oathbringer) de Brandon Sanderson es el tercer libro de El Archivo de las Tormentas, donde la humanidad enfrenta una nueva Desolación con los Portadores del Vacío; Dalinar Kholin intenta unir los reinos de Roshar mientras Shallan Davar desvela secretos en Urithiru, y Kaladin cuestiona sus aliados, todo mientras la tormenta eterna arrasa el mundo y los parshmenios descubren ser esclavos milenarios, culminando en un giro épico que revela el oscuro pasado de Dalinar y la verdad sobre los Radiantes.', '1769458404_9788417347000.webp', 1),
(7, 'Stranger Houses', NULL, '22.90', 15, 10, '«Strange Houses» (Casas Extrañas) es un fenómeno de terror japonés escrito por Uketsu, que sigue a un escritor de temas paranormales investigando una casa en Tokio con un plano arquitectónico incomprensible. Al descubrir un espacio oculto y sin acceso, junto a su amigo arquitecto Kurihara, sospechan que la vivienda fue diseñada para cometer crímenes. ', '1769458371_strange-houses.webp', 1),
(8, 'Ciudad Medialuna', NULL, '25.00', 7, 3, 'Ciudad Medialuna (Crescent City) es una serie de fantasía urbana de Sarah J. Maas que sigue a Bryce Quinlan, una mitad humana/mitad hada, en su búsqueda de venganza por el asesinato de sus amigos en la vibrante metrópolis de Ciudad Medialuna, donde conviven fae, demonios, ángeles y otras criaturas, uniéndose con el ángel caído Hunt Athalar para investigar y desentrañar una conspiración que amenaza su mundo, mientras exploran una fuerte atracción mutua y descubren oscuros secretos sobre sus propios poderes y el verdadero poder de la ciudad. ', '1769458301_casa_de_cielo_y_aliento.jpg', 1),
(10, 'El aliento de los dioses', NULL, '12.00', 5, 3, 'El aliento de los dioses o Warbreaker, segunda novela de Brandon Sanderson, es una rara avis en la fantasía épica: una narración completa en un único volumen, con toda la imaginación, la aventura, la magia y los entrañables personajes a los que este autor, destinado a heredar el trono de todo un género, nos tiene acostumbrados.', '1769458279_71w0BArZnwL._UF1000,1000_QL80_.jpg', 1),
(16, 'El imperio final', '9780001', '22.90', 15, 3, 'Una joven descubre su poder en un imperio gobernado por un tirano inmortal.', '1769458254_81MmsmYLIGL._AC_UF1000,1000_QL80_.jpg', 1),
(17, 'El pozo de la ascensión', '9780002', '23.90', 12, 3, 'Tras la revolución, el nuevo orden se tambalea y las amenazas crecen.', '1769458243_3ef2edb9cb148c002e2300f8650ec089.webp', 1),
(18, 'El héroe de las eras', '9780003', '24.90', 10, 3, 'El destino del mundo depende de secretos antiguos y sacrificios.', '1769458227_81c5VPXgDqL.jpg', 1),
(19, 'El camino de los reyes', '9780004', '29.90', 8, 3, 'Una epopeya de guerra, honor y magia en Roshar.', '1769458216_81pzG7oNfHL._UF1000,1000_QL80_.jpg', 1),
(20, 'La Asistenta', '9780005', '22.00', 12, 9, '«La asistenta» (The Housemaid) es un popular thriller psicológico de Freida McFadden que sigue a Millie, una joven desesperada con antecedentes que acepta trabajar para la adinerada familia Winchester. Millie descubre que Nina, la dueña, es inestable y la casa esconde secretos oscuros, convirtiendo su empleo en una peligrosa pesadilla de manipulación. ', '1769458175_71UilMg9WPL._AC_UF1000,1000_QL80_.jpg', 1),
(21, 'Una corte de rosas y espinas', '9780101', '21.90', 20, 3, 'Una cazadora es arrastrada al mundo de las hadas y sus reglas.', '1769458143_818blPXkJ3L._UF1000,1000_QL80_.jpg', 1),
(22, 'Una corte de niebla y furia', '9780102', '22.90', 18, 3, 'Feyre reconstruye su vida y descubre su verdadero poder.', '1769458133_9788408257110.jpg', 1),
(23, 'Una corte de alas y ruina', '9780103', '23.90', 15, 3, 'La guerra por el destino de los reinos está a punto de estallar.', '1769458116_Portada---UNA-CORTE-DE-ALAS-Y-RUINA.webp', 1),
(24, 'Trono de cristal', '9780104', '19.90', 14, 3, 'Una asesina compite por su libertad en un torneo mortal.', '1769458101_cover-108913.jpg', 1),
(25, 'Heredera de fuego', '9780105', '20.90', 12, 3, 'El pasado y el destino chocan mientras ella acepta quién es.', '1769458087_710m0DZVVIL.jpg', 1),
(26, 'La llamada de Cthulhu', '9780201', '14.90', 25, 20, 'Un horror ancestral despierta desde las profundidades.', '1769458076_portada_la-llamada-de-cthulhu_h-p-lovecraft_201904111457.jpg', 1),
(27, 'En las montañas de la locura', '9780202', '16.90', 20, 20, 'Una expedición a la Antártida descubre horrores inimaginables.', '1769458069_81um1OGS5fL._SY342_.jpg', 1),
(28, 'El color que cayó del cielo', '9780203', '13.90', 18, 20, 'Un meteorito trae una presencia extraña y mortal.', '1769457539_763492c9af35f90743cbde51eeeb30c6.webp', 1),
(29, 'La sombra sobre Innsmouth', '9780204', '15.90', 17, 20, 'Un pueblo costero oculta un secreto aterrador.', '1769458055_lasombrasobreinnsmouth.jpg', 1),
(30, 'El horror de Dunwich', '9780205', '14.50', 15, 20, 'Rituales prohibidos desatan una amenaza que no debería existir.', '1769458041_b73a2ccfa144a36bfc9c0f5fd0cacc25.webp', 1),
(31, 'Stranger Pictures', NULL, '19.90', 20, 20, 'Strange Pictures es un fenómeno literario japonés de terror y misterio que se centra en el análisis de ilustraciones perturbadoras, como dibujos de víctimas o escenas domésticas extrañas, para resolver crímenes y descubrir verdades macabras, invitando al lector a actuar como detective en un rompecabezas visual y narrativo que conecta varias historias inquietantes y perturbadoras. ', '1769458476_strange-pictures.webp', 1),
(32, 'Orgullo y prejuicio', NULL, '11.20', 15, 7, 'Orgullo y Prejuicio de Jane Austen narra las desventuras amorosas de las hermanas Bennet en la Inglaterra rural, centrada en la ingeniosa Elizabeth Bennet y su relación conflictiva con el orgulloso y rico Fitzwilliam Darcy; la historia explora el matrimonio, el estatus social y los prejuicios mientras Elizabeth debe superar su propia opinión inicial y Darcy su soberbia para encontrar el amor verdadero, todo ello con humor y crítica a la sociedad de la época. ', '1769458556_71wnBzT9WqL._AC_UF1000,1000_QL80_.jpg', 1),
(33, 'Hamlet', NULL, '13.50', 14, 19, 'Hamlet de William Shakespeare es una tragedia donde el príncipe de Dinamarca busca vengar el asesinato de su padre, el rey, cometido por su tío Claudio, quien usurpó el trono y se casó con la madre de Hamlet, Gertrudis. Marcado por la duda existencial (\"ser o no ser\"), Hamlet finge locura, provocando intrigas y muertes que culminan en un sangriento desenlace. ', '1769458609_portada_hamlet_william-shakespeare_202104201321.jpg', 1),
(34, 'Romeo y Julieta', NULL, '12.00', 16, 19, 'Hamlet de William Shakespeare es una tragedia donde el príncipe de Dinamarca busca vengar el asesinato de su padre, el rey, cometido por su tío Claudio, quien usurpó el trono y se casó con la madre de Hamlet, Gertrudis. Marcado por la duda existencial (\"ser o no ser\"), Hamlet finge locura, provocando intrigas y muertes que culminan en un sangriento desenlace.', '1769458642_71ORVJUYvPL._AC_UF1000,1000_QL80_.jpg', 1),
(35, 'Las aventuras de Sherlock Holmes', NULL, '12.00', 16, 10, 'El detective más famoso de la historia, Sherlock Holmes enfrenta una serie de casos que requieren de todas sus habilidades deductivas y de investigación para desenmascarar a los peores asesinos, prestamistas y embaucadores de la ciudad.', '1769458755_07c8ab4dfef3c9dcd62c9685707492b3.webp', 1),
(36, 'Asesinato en el Orient Express', NULL, '15.50', 11, 10, 'Asesinato en el Orient Express (1934) de Agatha Christie es una célebre novela de misterio donde el detective Hércules Poirot investiga el asesinato de un pasajero a bordo del lujoso tren, detenido por una tormenta de nieve en Yugoslavia. Todos los pasajeros del vagón son sospechosos, obligando a Poirot a resolver el complejo crimen antes de que llegue la policía. ', '1769458838_61e93meepPL.jpg', 1),
(37, 'Nerón', NULL, '21.90', 20, 4, 'DEL MAESTRO DEL GÉNERO HISTÓRICO, UNA APASIONANTE NOVELA SOBRE EL EMPERADOR MÁS TEMIDO DE LA HISTORIA DE ROMA«Increíblemente bueno.» Bernard Cornwell«Magnífico.» The TimesLos tiranos no nacen. Se hacen. Un viaje al corazón de una dinastía empapada en sangre, intrigas y peligros.Un joven emperador se hará con el trono de Roma. Se llama Lucio Domicio Enobarbo. Para todos, Nerón. Su predecesor, Claudio, ha muerto envenenado. Detrás, la sombra de Agripina, la madre de Nerón. El emperador es un hombre complicado, con cientosde caras oscuras, capaz de todo y de lo contrario. Pero ¿qué hay de cierto en la leyenda?«Una lectura apasionante del maestro de la novela histórica.»', '1769458922_9788419834713.webp', 1),
(38, 'Ángelesles Rotos', NULL, '12.00', 4, 2, 'Ángeles Rotos, segunda entrega de la saga de Takeshi Kovacs por Richard Morgan, traslada la acción al planeta Sanción IV, sumido en una brutal guerra corporativa. Kovacs, convertido en mercenario, se ve envuelto en una búsqueda arqueológica de tecnología alienígena marciana, cambiando el género del cyberpunk negro a la ciencia ficción militar y aventuras. ', '1769459001_61B++M+HtgL._AC_UF1000,1000_QL80_.jpg', 1),
(39, 'Carbono modificado', NULL, '14.90', 3, 2, 'Carbono Modificado (Altered Carbon), de Richard Morgan, es una novela ciberpunk/noir donde la conciencia humana se almacena en \"pilas\" y se transfiere a nuevos cuerpos (\"fundas\"), permitiendo la inmortalidad para los ricos. Takeshi Kovacs, un ex-emisario de élite almacenado por sus crímenes, es reanimado para investigar el supuesto suicidio de un millonario.', '1769459070_5c30181739c886c2b42912adeb5964a5.webp', 1),
(40, 'Furias Desatadas', NULL, '15.50', 3, 2, '«Furias desatadas» (Woken Furies), de Richard Morgan, es el cierre de la trilogía de Takeshi Kovacs, donde el protagonista regresa a su planeta natal, Mundo de Harlan, en medio de una inminente revolución. Enfrentándose a sí mismo y a conspiraciones corporativas, Kovacs libera una furia digital y física para sobrevivir en un futuro distópico. ', '1769459122_Ficcion-075-Furias-desatadas-1.0.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_autor`
--

CREATE TABLE `libro_autor` (
  `id_libro` int(11) NOT NULL,
  `id_autor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libro_autor`
--

INSERT INTO `libro_autor` (`id_libro`, `id_autor`) VALUES
(8, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(1, 2),
(4, 2),
(5, 2),
(6, 2),
(10, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(26, 3),
(27, 3),
(28, 3),
(29, 3),
(30, 3),
(7, 4),
(31, 4),
(20, 6),
(35, 7),
(33, 8),
(34, 8),
(38, 9),
(39, 9),
(40, 9),
(32, 10),
(36, 11),
(37, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineas_pedido`
--

CREATE TABLE `lineas_pedido` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_libro` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lineas_pedido`
--

INSERT INTO `lineas_pedido` (`id`, `id_pedido`, `id_libro`, `cantidad`, `precio_unitario`, `activo`) VALUES
(19, 31, 40, 1, '15.50', 1),
(20, 32, 37, 1, '21.90', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `id_usuario` varchar(20) NOT NULL,
  `id_direccion_envio` int(11) NOT NULL,
  `fecha_pedido` datetime DEFAULT current_timestamp(),
  `estado` varchar(50) NOT NULL DEFAULT 'pendiente',
  `total` decimal(10,2) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `stripe_payment_intent` varchar(100) DEFAULT NULL,
  `estado_pago` varchar(20) NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id`, `id_usuario`, `id_direccion_envio`, `fecha_pedido`, `estado`, `total`, `activo`, `stripe_payment_intent`, `estado_pago`) VALUES
(31, '74378068R', 4, '2026-01-26 13:01:47', 'procesando', '15.50', 1, 'pi_3StwuzA5wJL4nNXY1vTefNoL', 'pagado'),
(32, '12345678Z', 5, '2026-01-26 13:15:16', 'pendiente', '21.90', 1, 'pi_3Stx7xA5wJL4nNXY03xq1K86', 'pagado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `dni` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','empleado','usuario') NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`dni`, `nombre`, `email`, `password`, `rol`, `fecha_registro`, `activo`, `reset_token`, `reset_expira`) VALUES
('00000000T', 'Cliente 2', 'cliente002@gmail.com', '$2y$10$u1r9b4aWcO1r3S4j6pY1Qe3H9PzV7qE9m1sYx9k9bq4F4z6RzE7gK', 'usuario', '2026-01-26 02:55:09', 1, NULL, NULL),
('11111111H', 'Cliente 3', 'cliente003@gmail.com', '$2y$10$u1r9b4aWcO1r3S4j6pY1Qe3H9PzV7qE9m1sYx9k9bq4F4z6RzE7gK', 'usuario', '2026-01-26 02:55:09', 1, NULL, NULL),
('12345678Z', 'Cliente 1', 'cliente001@gmail.com', '$2y$10$Vlu1eT7TrocfgebI7TIHEe7Yt5T2dpiKGAaqq4JBuVc5XJ6zzkxLq', 'usuario', '2026-01-26 02:52:07', 1, NULL, NULL),
('22222222J', 'Cliente 4', 'cliente004@gmail.com', '$2y$10$u1r9b4aWcO1r3S4j6pY1Qe3H9PzV7qE9m1sYx9k9bq4F4z6RzE7gK', 'usuario', '2026-01-26 02:55:09', 1, NULL, NULL),
('33333333P', 'Cliente 5', 'cliente005@gmail.com', '$2y$10$u1r9b4aWcO1r3S4j6pY1Qe3H9PzV7qE9m1sYx9k9bq4F4z6RzE7gK', 'usuario', '2026-01-26 02:55:09', 1, NULL, NULL),
('44444444A', 'Cliente 6', 'cliente006@gmail.com', '$2y$10$u1r9b4aWcO1r3S4j6pY1Qe3H9PzV7qE9m1sYx9k9bq4F4z6RzE7gK', 'usuario', '2026-01-26 02:55:09', 1, NULL, NULL),
('55555555K', 'Cliente 7', 'cliente007@gmail.com', '$2y$10$u1r9b4aWcO1r3S4j6pY1Qe3H9PzV7qE9m1sYx9k9bq4F4z6RzE7gK', 'usuario', '2026-01-26 02:55:09', 1, NULL, NULL),
('74378068R', 'Empleado', 'cliente11@gmail.com', '$2y$10$aS/CgyXeIKNg5lxPoPhPeu4Ub72ag1uWa/4RyuMXm45preSGiEiSe', 'empleado', '2026-01-25 21:09:07', 1, NULL, NULL),
('77777777L', 'Aroa', 'cliente1@gmail.com', '$2y$10$ZRwbEmtvbwEGeDl7R7bpge3stwRiH9LZdfNSIEJt9vSumK2r0nZAi', 'admin', '2026-01-14 00:40:36', 1, NULL, NULL),
('87654321X', 'Cliente 8', 'cliente008@gmail.com', '$2y$10$u1r9b4aWcO1r3S4j6pY1Qe3H9PzV7qE9m1sYx9k9bq4F4z6RzE7gK', 'usuario', '2026-01-26 02:55:09', 1, NULL, NULL),
('88888888Y', 'Cliente 10', 'cliente010@gmail.com', '$2y$10$u1r9b4aWcO1r3S4j6pY1Qe3H9PzV7qE9m1sYx9k9bq4F4z6RzE7gK', 'usuario', '2026-01-26 02:55:09', 1, NULL, NULL),
('99999999R', 'Cliente 9', 'cliente009@gmail.com', '$2y$10$u1r9b4aWcO1r3S4j6pY1Qe3H9PzV7qE9m1sYx9k9bq4F4z6RzE7gK', 'usuario', '2026-01-26 02:55:09', 1, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_direccion_usuario` (`dni_usuario`);

--
-- Indices de la tabla `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `fk_libro_categoria` (`id_categoria`);

--
-- Indices de la tabla `libro_autor`
--
ALTER TABLE `libro_autor`
  ADD PRIMARY KEY (`id_libro`,`id_autor`),
  ADD KEY `fk_la_autor` (`id_autor`);

--
-- Indices de la tabla `lineas_pedido`
--
ALTER TABLE `lineas_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_linea_pedido` (`id_pedido`),
  ADD KEY `fk_linea_libro` (`id_libro`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pedido_usuario` (`id_usuario`),
  ADD KEY `fk_pedido_direccion` (`id_direccion_envio`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`dni`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autor`
--
ALTER TABLE `autor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `libro`
--
ALTER TABLE `libro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `lineas_pedido`
--
ALTER TABLE `lineas_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD CONSTRAINT `fk_direccion_usuario` FOREIGN KEY (`dni_usuario`) REFERENCES `usuario` (`dni`) ON DELETE CASCADE;

--
-- Filtros para la tabla `libro`
--
ALTER TABLE `libro`
  ADD CONSTRAINT `fk_libro_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `libro_autor`
--
ALTER TABLE `libro_autor`
  ADD CONSTRAINT `fk_la_autor` FOREIGN KEY (`id_autor`) REFERENCES `autor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_la_libro` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lineas_pedido`
--
ALTER TABLE `lineas_pedido`
  ADD CONSTRAINT `fk_linea_libro` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id`),
  ADD CONSTRAINT `fk_linea_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_direccion` FOREIGN KEY (`id_direccion_envio`) REFERENCES `direcciones` (`id`),
  ADD CONSTRAINT `fk_pedido_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`dni`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
