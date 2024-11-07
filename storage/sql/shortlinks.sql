-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 06, 2024 at 12:06 PM
-- Server version: 10.6.19-MariaDB-cll-lve
-- PHP Version: 8.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Dumping data for table `shortlinks`
--

INSERT INTO `shortlinks` (`id`, `name`, `apilink`, `views`, `cpm`, `referral`, `demo`, `BMF`, `withdraw`, `status`, `updated`) VALUES
(1, 'cashat.co', 'https://cashat.co/api?api={apikey}&url={url}', '0', 0.00, 'https://cashat.net/ref/ΑvalonɌychmon', 'https://cashat.net/demo', NULL, 'Closed', 'N', '2019-02-07 14:53:16');
