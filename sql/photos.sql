
--
-- Database: `demo` and php web application user
CREATE DATABASE demo;
GRANT USAGE ON *.* TO 'appuser'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON demo.* TO 'appuser'@'localhost';
FLUSH PRIVILEGES;

USE demo;
--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(10) NOT NULL,
  `text` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `number`, `text`, `date`, `image`) VALUES
(1, 111, 'Ottawa', '2023-04-01', 'ottawa.jpg'),
(2, 222, 'Kingston', '2023-03-01', 'kingston.jpg');

