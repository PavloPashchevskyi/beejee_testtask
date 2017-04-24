-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Час створення: Квт 24 2017 р., 03:25
-- Версія сервера: 5.7.17-0ubuntu0.16.04.2
-- Версія PHP: 5.6.30-10+deb.sury.org~xenial+2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `beejee_testtask`
--

-- --------------------------------------------------------

--
-- Структура таблиці `tTasks`
--

CREATE TABLE `tTasks` (
  `task_id` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TaskText` longtext,
  `TaskImage` varchar(255) DEFAULT NULL,
  `Done` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп даних таблиці `tTasks`
--

INSERT INTO `tTasks` (`task_id`, `UserID`, `TaskText`, `TaskImage`, `Done`) VALUES
(1, 0, '                                                            task 1\r\nSome description of task 1\r\n                                                    ', '/application/modules/task_manager/views/includes/images/images.jpg', 0),
(2, 0, '                                                            Task 2\r\nSome description of task 2\r\n                                                    ', '/application/modules/task_manager/views/includes/images/images1.jpg', 0),
(3, 0, 'Task 3\r\nSome description of task 3                                            ', '/application/modules/task_manager/views/includes/images/full-hd-images-free-download-4.jpg', 0);

-- --------------------------------------------------------

--
-- Структура таблиці `tUsers`
--

CREATE TABLE `tUsers` (
  `user_id` int(11) NOT NULL,
  `Login` varchar(40) DEFAULT NULL,
  `Passwd` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп даних таблиці `tUsers`
--

INSERT INTO `tUsers` (`user_id`, `Login`, `Passwd`, `Email`) VALUES
(0, NULL, '$2y$10$UyYbGftxUPQDp443UnSvouHGOpnBFCVnZIRH.cJwnBR.v0ZaiARlC', NULL),
(1, 'admin', '$2y$10$XlZydoFXgFfoI3rsza1dSuh2zMGlXjhiAbTt8x0tJ7WQaxxEBa.Z2', 'googalltooth@gmail.com');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `tTasks`
--
ALTER TABLE `tTasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `UserID` (`UserID`);

--
-- Індекси таблиці `tUsers`
--
ALTER TABLE `tUsers`
  ADD PRIMARY KEY (`user_id`);

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `tTasks`
--
ALTER TABLE `tTasks`
  ADD CONSTRAINT `tTasks_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `tUsers` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
