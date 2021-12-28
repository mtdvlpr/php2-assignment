-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 21, 2021 at 09:26 PM
-- Server version: 5.5.31
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s643622_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_information`
--

CREATE TABLE `contact_information` (
  `id` int(11) NOT NULL,
  `email_address` varchar(50) NOT NULL,
  `name` varchar(30) NOT NULL,
  `subject` varchar(20) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact_information`
--

INSERT INTO `contact_information` (`id`, `email_address`, `name`, `subject`, `message`) VALUES
(1, 'example@gmail.com', 'John Doen', 'IMPORTANT STUFF', 'YEASSSSSSSSSSS'),
(2, 'kbj@sdf.com', 'Kn', 'Jnk', 'Kn'),
(3, 'test@test.nl', 'Test', 'Test', 'Test'),
(4, 'jbj@jbjb.ds', 'jb', 'jhb', 'jhb'),
(5, 'test@example.com', 'Test Person', 'testtstst', 'tesfhdfvsdvfjsdb'),
(6, 'test@example.com', 'Test Person', 'testtstst', 'tesfhdfvsdvfjsdb');

-- --------------------------------------------------------

--
-- Table structure for table `donation`
--

CREATE TABLE `donation` (
  `donation_id` int(11) NOT NULL,
  `status` text NOT NULL,
  `amount` float NOT NULL,
  `name` text NOT NULL,
  `donation_date` date NOT NULL,
  `email` text NOT NULL,
  `mollie_id` text NOT NULL,
  `hash` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `donation`
--

INSERT INTO `donation` (`donation_id`, `status`, `amount`, `name`, `donation_date`, `email`, `mollie_id`, `hash`) VALUES
(11, 'created', 1, 'Admin', '2021-06-14', 'admin@example.com', '0', ''),
(12, 'created', 1, 'Admin', '2021-06-14', 'admin@example.com', '0', ''),
(13, 'created', 1, 'Admin', '2021-06-16', 'admin@example.com', '0', ''),
(14, 'created', 1, 'Admin', '2021-06-16', 'admin@example.com', '0', ''),
(15, 'created', 1, 'Admin', '2021-06-16', 'admin@example.com', '0', ''),
(16, 'created', 1, 'Admin', '2021-06-16', 'admin@example.com', '0', ''),
(17, 'created', 1, 'Admin', '2021-06-16', 'admin@example.com', 'ord_w1h0d1', ''),
(18, 'created', 1, 'Admin', '2021-06-16', 'admin@example.com', 'ord_kmmi1', ''),
(19, 'paid', 1, 'Admin', '2021-06-16', 'admin@example.com', 'ord_lgfqcf', ''),
(20, 'paid', 2, 'Admin', '2021-06-16', 'admin@example.com', 'ord_j9t6ft', ''),
(21, 'paid', 1, 'Admin', '2021-06-16', 'admin@example.com', 'ord_jmsuo9', ''),
(22, 'paid', 1, 'Admin', '2021-06-16', 'admin@example.com', 'ord_qcq3af', ''),
(23, 'paid', 1, 'Admin', '2021-06-16', 'admin@example.com', 'ord_2qk18x', ''),
(24, 'paid', 2, 'Admin', '2021-06-16', 'admin@example.com', 'ord_afflb3', ''),
(25, 'paid', 1, 'Manoah', '2021-06-21', '149895ja@gmail.com', 'ord_tzegaz', '291597a100aadd814d197af4f4bab3a7'),
(26, 'paid', 1, 'Manoah', '2021-06-21', '149895ja@gmail.com', 'ord_lmpa0z', 'd82c8d1619ad8176d665453cfb2e55f0'),
(27, 'paid', 1, 'Manoah', '2021-06-21', '149895ja@gmail.com', 'ord_1s6xpt', '46ba9f2a6976570b0353203ec4474217'),
(28, 'paid', 1, 'Manoah', '2021-06-21', '149895ja@gmail.com', 'ord_y2as6n', '82161242827b703e6acf9c726942a1e4'),
(29, 'paid', 12, 'Manoah', '2021-06-21', '149895ja@gmail.com', 'ord_1188f5', 'fb89705ae6d743bf1e848c206e16a1d7'),
(30, 'paid', 1, 'Manoah', '2021-06-21', '149895ja@gmail.com', 'ord_dszv31', '8a0e1141fd37fa5b98d5bb769ba1a7cc'),
(31, 'paid', 12, 'Manoah', '2021-06-21', '149895ja@gmail.com', 'ord_ykj053', '7b13b2203029ed80337f27127a9f1d28'),
(32, 'paid', 1, 'Manoah', '2021-06-21', '149895ja@gmail.com', 'ord_3ox411', 'da8ce53cf0240070ce6c69c48cd588ee');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `release_date` date NOT NULL,
  `director` text NOT NULL,
  `category` text NOT NULL,
  `runtime` int(11) NOT NULL,
  `score` float NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `release_date`, `director`, `category`, `runtime`, `score`, `image`) VALUES
(1, 'The Godfather', '1973-01-18', 'Francis Ford Coppola', 'Crime, Drama', 178, 9.2, 'img/movies/godfather.jpg'),
(2, 'LOTR: The Fellowship Of The Rings', '2001-12-19', 'Peter Jackson', 'Action, Adventure, Drama', 228, 8.8, 'img/movies/fellowship.jpg'),
(3, 'Inception', '2010-07-22', 'Christopher Nolan', 'Action, Adventure, Sci-Fi', 162, 8.7, 'img/movies/inception.jpg'),
(4, 'The Shawshank Redemption', '1995-03-02', 'Frank Darabont', 'Drama', 142, 9.3, 'img/movies/redemption.jpg'),
(5, 'Pulp Fiction', '1994-12-01', 'Quentin Tarantino', 'Crime, Drama', 178, 8.9, 'img/movies/pulp-fiction.jpg'),
(6, 'The Dark Knight', '2008-07-24', 'Christopher Nolan', 'Action, Crime, Drama', 152, 9, 'img/movies/dark-knight.jpg'),
(7, 'LOTR: The Return Of The King', '2003-12-17', 'Peter Jackson', 'Action, Adventure, Drama', 251, 8.9, 'img/movies/king.jpg'),
(8, 'Star Wars: The Empire Strikes Back', '1980-12-18', 'Irvin Kershner', 'Action, Adventure, Fantasy', 127, 8.7, 'img/movies/star-wars.jpg'),
(9, 'LOTR: The Two Towers', '2002-12-18', 'Peter Jackson', 'Action, Adventure, Drama', 223, 8.7, 'img/movies/two-towers.jpg'),
(10, 'Forrest Gump', '1994-09-22', 'Robert Zemeckis', 'Drama, Romance', 142, 8.8, 'img/movies/forrest-gump.jpg'),
(11, 'Tenet', '2020-09-03', 'Christopher Nolan', 'Action, Sci-Fi, Thriller', 150, 7.5, 'img/movies/tenet.jpeg'),
(12, 'The Karate Kid', '1984-06-22', 'John G. Avildsen', 'Action, Drama, Family', 126, 7.3, 'img/movies/karate.jpg'),
(13, 'We Can Be Heroes', '2020-12-25', 'Robert Rodriguez', 'Action, Comedy, Drama', 100, 4.7, 'img/movies/heroes.jpg'),
(14, 'Avengers: Endgame', '2019-04-26', 'Anthony Russo, Joe Russo', 'Action, Adventure, Drama', 181, 8.4, 'img/movies/endgame.jpg'),
(15, 'What Happened to Monday', '2017-08-18', 'Tommy Wirkola', 'Action, Adventure, Crime', 123, 6.9, 'img/movies/monday.jpg'),
(16, 'Interstellar', '2014-11-07', 'Cristopher Nolan', 'Aventure, Drama, Sci-Fi', 169, 8.6, 'img/movies/interstellar.jpg'),
(17, 'Joker', '2019-10-04', 'Todd Phillips', 'Crime, Drama, Thriller', 122, 8.5, 'img/movies/joker.jpg'),
(18, 'Cinderella', '2015-03-13', 'Kenneth Branagh', 'Drama, Family, Fantasy', 105, 6.9, 'img/movies/cinderella.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `birthdate` date DEFAULT NULL,
  `registration_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `role` tinyint(1) NOT NULL DEFAULT '0',
  `address` varchar(100) DEFAULT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT '2',
  `profile_picture` varchar(100) DEFAULT '/img/fillerface.png',
  `query_date` date NOT NULL,
  `hash` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `birthdate`, `registration_date`, `is_active`, `role`, `address`, `gender`, `profile_picture`, `query_date`, `hash`) VALUES
(1, 'Michael Thompson', 'michael.thompson@example.nl', '$6$rounds=7000$fishandchips$sQ7QYGVLCbMGk/C/DLX4bebJP9W5PPXNS57QaafhLuwDeyYzqfXCzime6dyOXLYV1.NEHifgkyqYt2qSujhP6/', '1983-02-09', '2020-11-30', 1, 0, 'dorpslaan 6, 4356JI, Haarlem, Netherlands', 0, '/img/uploads/yeet.jpg', '2021-04-16', ''),
(2, 'Everett Fleming', 'everett.fleming@example.com', '$6$rounds=7000$fishandchips$Ep1QvHLLhc.CyDzKdCcdUBg3aYDq2/ZZvOYk9Yu.4N8FXkd7hRG2eIbfINRXethOsf.1UX05sjeGnvhO4nRVh1', '1984-06-07', '2020-11-19', 1, 0, 'houthakkerstraat 21, 1738SK, Breda, Netherlands', 1, '/img/uploads/user2-GT7Td.jpg', '2021-06-21', ''),
(3, 'Sarah Fields', 'sarah.fields@example.com', '$6$rounds=7000$fishandchips$TUu/F.eW9.5FDaqGBpPoL4E95FKWspVod934ZitvrMEjpcG/d767f8zpZZQaKJbfu4O7QJQ4OUBxRBAsN39ac0', '1986-05-10', '2020-11-01', 1, 0, 'Prunuslaan 15, 4356RT, Alkmaar, Netherlands', 1, '/img/fillerface.png', '2020-12-07', ''),
(4, 'Crystal Byrd', 'crystal.byrd@example.com', '$6$rounds=7000$fishandchips$RZvw1LlNW4I/FYakuXmwwLmuvuJWdL9PoEAraYeWLPT83VGUMJ9OT9opWMGdPbkbvlzczsVkxZz1MgvMFSjJA0', '1963-02-10', '2020-11-11', 1, 0, 'Laanweg 1, 3847HH, Amsterdam, Netherlands', 1, '/img/fillerface.png', '2020-12-07', ''),
(5, 'Brooklyn Hawkins', 'brooklyn.hawkins@example.com', '$6$rounds=7000$fishandchips$pqN3zTvIJdh7V8M9DTDBY3r5NSJPgv1EvzUuWz73G6q4crY6XbXZxNX3ghpR/KmifbE/usoKNjawwKVQIpdTk0', '1974-07-12', '2020-11-14', 0, 0, 'Oranjelaan 533, 9541YA, The Hague, Netherlands', 2, '/img/uploads/Logo.jpg', '2021-04-16', 'hash'),
(6, 'Test Admin', 'test@example.com', '$6$rounds=7000$fishandchips$mk4SMvAOUE3EXHH.uh/0e9bh/byEh80/kkiaI/vHTToJZSUiO6oKpRE8bDHdfIAc4ycA2VGxdzcctTNwqJBM81', NULL, '2020-12-09', 1, 1, NULL, 2, '/img/uploads/Image 120.png', '2020-12-09', ''),
(12, 'Admin', 'admin@example.com', '$6$rounds=7000$fishandchips$mk4SMvAOUE3EXHH.uh/0e9bh/byEh80/kkiaI/vHTToJZSUiO6oKpRE8bDHdfIAc4ycA2VGxdzcctTNwqJBM81', NULL, '2021-01-14', 1, 2, NULL, 2, '/img/fillerface.png', '2021-01-14', ''),
(21, 'Test User', 'user@example.com', '$6$rounds=7000$fishandchips$mk4SMvAOUE3EXHH.uh/0e9bh/byEh80/kkiaI/vHTToJZSUiO6oKpRE8bDHdfIAc4ycA2VGxdzcctTNwqJBM81', NULL, '2021-02-01', 1, 0, NULL, 2, '/img/fillerface.png', '2021-02-01', ''),
(22, 'Manoah', '149895ja@gmail.com', '$6$rounds=7000$fishandchips$.vXm9sG551ePlcc8J8pyQQkDpkW9dLtx6eJ.uhjsE7U.hlKHAR0f2mZ6PKm5GAARYV.90uY9zgGM71wdNf62m0', NULL, '2021-02-01', 1, 0, NULL, 2, '/img/fillerface.png', '2021-02-01', ''),
(30, 'Manoah', 'manoah.tervoort@live.nl', '$6$rounds=7000$fishandchips$Ghgj0hlvHfeQmKObvJKuTWUpiI94fRqYdhLQv9E.b4Xww0xk5rxZKOX8FrGdvjhqhvORONyudwRVNwgbuMQ6S1', NULL, '2021-04-15', 1, 0, NULL, 2, '/img/fillerface.png', '2021-04-15', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_information`
--
ALTER TABLE `contact_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donation`
--
ALTER TABLE `donation`
  ADD PRIMARY KEY (`donation_id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_information`
--
ALTER TABLE `contact_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `donation`
--
ALTER TABLE `donation`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
