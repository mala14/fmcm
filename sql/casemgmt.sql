-- DROP TABLE IF EXISTS `fmtodo_comment`;
CREATE TABLE IF NOT EXISTS `fmtodo_comment` (
  `id_comment` int(6) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_todo` int(6) NOT NULL,
  `comment` text NOT NULL,
  `id_user` varchar(25) DEFAULT NULL,
  `saved` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- DROP TABLE IF EXISTS `fmtodo_contacts`;
CREATE TABLE IF NOT EXISTS `fmtodo_contacts` (
  `id_contact` int(6) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `con_fname` varchar(25) NOT NULL,
  `con_lname` varchar(35) NOT NULL,
  `con_email` varchar(50) DEFAULT NULL,
  `con_address` varchar(50) DEFAULT NULL,
  `con_office` varchar(50) DEFAULT NULL,
  `con_jtitle` varchar(25) DEFAULT NULL,
  `con_phone` varchar(20) DEFAULT NULL,
  `con_info` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- DROP TABLE IF EXISTS `fmtodo_todo`;
CREATE TABLE IF NOT EXISTS `fmtodo_todo` (
  `id` int(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(50) NOT NULL,
  `title` varchar(55) DEFAULT NULL,
  `issue` text,
  `created` datetime DEFAULT NULL,
  `assigned` varchar(11) DEFAULT NULL,
  `working` int(2) DEFAULT NULL,
  `checked` int(2) DEFAULT NULL,
  `status` varchar(10) NOT NULL,
  `hold` int(1) DEFAULT NULL,
  `closedby` varchar(25) DEFAULT NULL,
  `fixed` datetime DEFAULT NULL,
  `contacts` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- DROP TABLE IF EXISTS `fmtodo_users`;
CREATE TABLE IF NOT EXISTS `fmtodo_users` (
  `id_user` int(6) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(30) NOT NULL,
  `uname` varchar(30) NOT NULL UNIQUE,
  `email` varchar(100) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `type` char(5) DEFAULT NULL,
  `passwd` varchar(255) NOT NULL,
  `regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastlogin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` char(10) DEFAULT NULL,
  `status` int(2) DEFAULT NULL,
  `time` int(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `fmtodo_users` (`id_user`, `fname`, `lname`, `uname`, `email`, `type`, `passwd`, `active`) VALUES
(1, 'admin', 'admin', 'admin', 'admin@fmcm', 'admin', '$2y$12$dW/quMv4KJEQc0NiskDkmegIsMg33UGghpI2JQoFn4QYq6G6.UqwG', 'active');
