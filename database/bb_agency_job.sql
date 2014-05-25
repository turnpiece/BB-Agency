CREATE TABLE `bb_agency_job` (
  `JobID` int(10) NOT NULL AUTO_INCREMENT,
  `JobTitle` varchar(127) NOT NULL,
  `JobClient` varchar(127) NOT NULL,
  `JobRate` varchar(63) DEFAULT NULL,
  `JobPONumber` int(10) NOT NULL,
  `JobLocation` varchar(255) DEFAULT NULL,
  `JobNotes` text,
  `JobModelBooked` int(10) DEFAULT NULL,
  `JobModelCasted` varchar(255) DEFAULT NULL,
  `JobDate` date NOT NULL,
  PRIMARY KEY (`JobID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;