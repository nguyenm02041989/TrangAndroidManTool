
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gcm_users`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `access`
--

CREATE TABLE IF NOT EXISTS `access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `write` tinyint(1) NOT NULL DEFAULT '0',
  `remove` tinyint(1) NOT NULL DEFAULT '0',
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `create_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_edit` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `controller_id` (`controller_id`,`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Gegevens worden uitgevoerd voor tabel `access`
--

INSERT INTO `access` (`id`, `controller_id`, `group_id`, `write`, `remove`, `read`, `create_by`, `modified_by`, `date_create`, `date_edit`) VALUES
(34, 4, 2, 1, 1, 1, 3, 3, '2014-02-09 16:39:46', '2014-02-09 16:39:46'),
(35, 20, 2, 1, 1, 1, 3, 3, '2014-02-09 16:39:55', '2014-02-09 16:39:55'),
(36, 3, 2, 1, 1, 1, 3, 3, '2014-02-09 16:40:03', '2014-02-09 16:40:03'),
(37, 5, 2, 1, 1, 1, 3, 3, '2014-02-09 16:40:09', '2014-02-09 16:40:09'),
(38, 21, 2, 1, 1, 1, 3, 3, '2014-02-09 16:40:16', '2014-02-09 16:40:16'),
(39, 1, 2, 1, 1, 1, 3, 3, '2014-02-09 16:40:29', '2014-02-09 16:40:29'),
(40, 2, 2, 1, 1, 1, 3, 3, '2014-02-09 16:40:47', '2014-02-09 16:40:47');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `access_controllers`
--

CREATE TABLE IF NOT EXISTS `access_controllers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(100) NOT NULL,
  `alias` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `controller` (`controller`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Gegevens worden uitgevoerd voor tabel `access_controllers`
--

INSERT INTO `access_controllers` (`id`, `controller`, `alias`) VALUES
(1, 'UsersController', '{USERS}'),
(2, 'GroupsController', '{GROUPS}'),
(3, 'GcmController', '{GOOGLE}'),
(4, 'AccessController', '{ACCESS}'),
(5, 'PushController', '{PUSH_MESSAGES}'),
(20, 'AppsController', '{APPS}');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gcm_users`
--

CREATE TABLE IF NOT EXISTS `gcm_users` (
  `gcm_id` int(11) NOT NULL AUTO_INCREMENT,
  `gcm_regid` varchar(200) NOT NULL,
  `unique_device_id` text,
  `app_id` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `date_create` datetime NOT NULL,
  `date_edit` datetime NOT NULL,
  PRIMARY KEY (`gcm_id`),
  UNIQUE KEY `gcm_regid` (`gcm_regid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Gegevens worden uitgevoerd voor tabel `gcm_users`
--

INSERT INTO `gcm_users` (`gcm_id`, `gcm_regid`, `unique_device_id`, `app_id`, `name`, `email`, `is_deleted`, `date_create`, `date_edit`) VALUES
(8, 'APA91bEuIuxteC1cWkaQFLHU1aqfoYS6gx8ecKTfty7M4QbfEJKVcZ-UzHSv8OjWMGqHhDtJudAQK_dw0EzR2pQpBC7mthvcR9zqe0wFZr9suRgRGTppcE2SLeGCUDKzHfHjcnqZaYTBuxGMhaxuAYU8xbRSdVLRLw', NULL, '6BVmkq57VF3L8Dy', '', '', 0, '2014-02-05 13:02:24', '2014-02-05 13:02:24'),
(13, '12345', '2222', '68wdd0pa3lY93I1', '', '', 1, '2014-02-09 00:00:00', '2014-02-09 03:50:19'),
(15, 'APA91bFpvpRSohId-opEPRefeIt1t4gIJXaF3KSBpT91HaUAC5V0QaZzi6t8uh40yw-umr6KuZiQ6w9EH39RGCxBpB8Ub7KYCgn4nSQm6W0mao28a8682NHQLicLJoa2tY374qMCptFIoytIInkWYDYsqezv8e3XaA', '', '6BVmkq57VF3L8Dy', '', '', 0, '2014-02-09 15:12:27', '2014-02-09 15:12:27');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `security_level` int(11) DEFAULT '1',
  `create_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `date_edit` datetime NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Gegevens worden uitgevoerd voor tabel `groups`
--

INSERT INTO `groups` (`group_id`, `name`, `security_level`, `create_by`, `modified_by`, `date_create`, `date_edit`) VALUES
(2, 'Admin', 0, 0, 3, '2014-02-07 15:35:54', '2014-02-09 03:34:34'),
(7, 'User', 1, 3, 3, '2014-02-07 19:37:28', '2014-02-07 19:37:28'),
(8, 'Public', 1, 3, 3, '2014-02-07 19:37:41', '2014-02-07 19:37:41'),
(9, 'test', 1, 3, 3, '2014-02-08 19:18:26', '2014-02-08 19:18:26'),
(11, 'Mooie groepnaam', 0, 3, 3, '2014-02-08 21:44:31', '2014-02-08 21:44:31'),
(12, 'Voetbal Ajax', 0, 3, 3, '2014-02-08 21:47:37', '2014-02-08 21:47:37'),
(13, 'Nog eentje', 0, 3, 3, '2014-02-09 03:37:01', '2014-02-09 03:37:01');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `lang_id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_code` varchar(4) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`lang_id`),
  UNIQUE KEY `lang_code` (`lang_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Gegevens worden uitgevoerd voor tabel `languages`
--

INSERT INTO `languages` (`lang_id`, `lang_code`, `description`) VALUES
(1, 'en', '{ENGLISH}'),
(2, 'nl', '{DUTCH}'),
(3, 'fr', '{FRENCH}'),
(4, 'de', '{GERMAN}'),
(5, 'es', '{SPANISH}');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mobile_apps`
--

CREATE TABLE IF NOT EXISTS `mobile_apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL,
  `create_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `date_edit` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_id` (`app_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;


--
-- Tabelstructuur voor tabel `push_messages`
--

CREATE TABLE IF NOT EXISTS `push_messages` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `sento_google` tinyint(1) NOT NULL DEFAULT '0',
  `successfull` int(11) DEFAULT NULL,
  `failed` int(11) DEFAULT NULL,
  `create_by` int(11) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_edit` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `google_response` longtext,
  PRIMARY KEY (`msg_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;
-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `push_messages_sento`
--

CREATE TABLE IF NOT EXISTS `push_messages_sento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) NOT NULL,
  `gcm_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `system_log`
--

CREATE TABLE IF NOT EXISTS `system_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_msg` longtext NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_addr` varchar(25) DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=116 ;

--
-- Tabelstructuur voor tabel `system_users`
--

CREATE TABLE IF NOT EXISTS `system_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT '1',
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `firstname` varchar(40) DEFAULT NULL,
  `middlename` varchar(20) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `lang_id` int(11) DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `date_edit` datetime NOT NULL,
  `system_user` tinyint(1) NOT NULL DEFAULT '0',
  `create_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Gegevens worden uitgevoerd voor tabel `system_users`
--

INSERT INTO `system_users` (`user_id`, `group_id`, `username`, `password`, `email`, `firstname`, `middlename`, `lastname`, `lang_id`, `date_create`, `date_edit`, `system_user`, `create_by`, `modified_by`) VALUES
(3, 2, 'Admin', 'URbtEEpXkJv8Rg+Bqdpfc5WHF7GkuvCR4QmQJNZMzSw=', 'sw0z3iwnGBHMgYL7uiP/SsqXf5R/XkebTxsrtzuqu+I=', 'Admin', '', 'Tool', 1, '2014-02-05 14:53:24', '2014-02-09 16:39:03', 1, 0, 3);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
