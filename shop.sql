-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3307
-- Время создания: Апр 12 2020 г., 16:17
-- Версия сервера: 5.7.23
-- Версия PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `shop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL,
  `name_good` varchar(250) NOT NULL,
  `price` int(11) NOT NULL,
  `info` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `name_good`, `price`, `info`) VALUES
(1, 'black dress', 1258, 'info 1'),
(2, 'blue dress', 5614, 'info 2'),
(3, 'T-shirt', 8156, 'info 3'),
(4, 'green slacks', 9523, 'onfo about green slacks');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_name` varchar(250) NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `price` varchar(250) NOT NULL,
  `tel` varchar(250) DEFAULT NULL,
  `order_data` json DEFAULT NULL,
  `status` varchar(64) DEFAULT '1' COMMENT 'Статус заказа 1-в рассмотрении, 2-доставк, 3-оплачен, 4-получен'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_name`, `address`, `price`, `tel`, `order_data`, `status`) VALUES
(6, 'admin', '', '60694', '', '{\"2\": {\"cost\": 28070, \"name\": \"blue dress\", \"count\": 5, \"price\": \"5614\"}, \"3\": {\"cost\": 32624, \"name\": \"T-shirt\", \"count\": 4, \"price\": \"8156\"}}', '4'),
(7, 'admin', '', '16842', '', '{\"2\": {\"cost\": 16842, \"name\": \"blue dress\", \"count\": 3, \"price\": \"5614\"}}', '3'),
(8, 'admin', '', '13838', '', '{\"1\": {\"cost\": 13838, \"name\": \"black dress\", \"count\": 11, \"price\": \"1258\"}}', '1'),
(9, 'admin', '', '6872', '', '{\"1\": {\"cost\": \"1258\", \"name\": \"black dress\", \"count\": 1, \"price\": \"1258\"}, \"2\": {\"cost\": \"5614\", \"name\": \"blue dress\", \"count\": 1, \"price\": \"5614\"}}', '1'),
(10, 'admin', '', '26984', '', '{\"1\": {\"cost\": 2516, \"name\": \"black dress\", \"count\": 2, \"price\": \"1258\"}, \"3\": {\"cost\": 24468, \"name\": \"T-shirt\", \"count\": 3, \"price\": \"8156\"}}', '1'),
(11, 'admin', '', '3774', '', '{\"1\": {\"cost\": 3774, \"name\": \"black dress\", \"count\": 3, \"price\": \"1258\"}}', '1'),
(12, 'cat', '', '21874', '', '{\"1\": {\"cost\": 5032, \"name\": \"black dress\", \"count\": 4, \"price\": \"1258\"}, \"2\": {\"cost\": 16842, \"name\": \"blue dress\", \"count\": 3, \"price\": \"5614\"}}', '1'),
(13, 'cat', 'london', '16842', '+765412358', '{\"2\": {\"cost\": 16842, \"name\": \"blue dress\", \"count\": 3, \"price\": \"5614\"}}', '2'),
(14, 'admin', 'london', '61874', '+765412358', '{\"1\": {\"cost\": 8806, \"name\": \"black dress\", \"count\": 4, \"price\": \"1258\"}, \"2\": {\"cost\": 44912, \"name\": \"blue dress\", \"count\": 8, \"price\": \"5614\"}, \"3\": {\"cost\": \"8156\", \"name\": \"T-shirt\", \"count\": 1, \"price\": \"8156\"}}', '1'),
(15, 'cat', 'london', '8156', '+765412358', '{\"3\": {\"cost\": \"8156\", \"name\": \"T-shirt\", \"count\": 1, \"price\": \"8156\"}}', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(250) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL COMMENT 'Проверка на админа. 0-обычный пользователь, 1-админ',
  `address` varchar(250) DEFAULT NULL,
  `tel` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `is_admin`, `address`, `tel`) VALUES
(1, 'admin', '$2y$10$F8o694B6u.dkLMFybcwOKeoSAgqKol5P9DkwQQEcSlV37IWMA2.ia', 1, NULL, NULL),
(2, 'ann', '$2y$10$F8o694B6u.dkLMFybcwOKeoSAgqKol5P9DkwQQEcSlV37IWMA2.ia', 0, NULL, NULL),
(3, 'lili', '$2y$10$F8o694B6u.dkLMFybcwOKeoSAgqKol5P9DkwQQEcSlV37IWMA2.ia', 0, '', ''),
(4, 'cat', '$2y$10$h/H8paXz8./dSsnyC4d6NOABjIoQacudFkFR44O.P2p2uG5P/IOTe', 0, 'london', '+765412358'),
(5, 'admi', '123', 1, NULL, NULL),
(6, 'qwerty', '$2y$10$9/yk6uFWIUZ6k9rgzUSapuVIyeF3Eqd8PEpyVOzz0jjUyjNfHlsDe', 0, 'Orel', '+765412358'),
(7, 'qwer', '$2y$10$QKkYcR.OsecWrXgutj8wnOqNoEfq3Wy0yoRm7tVIquAcYMlJLNp66', 0, 'qwerty', '+765412358');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
