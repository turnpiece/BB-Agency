CREATE TABLE `bb_agency_job` (
  `JobID` int(10) NOT NULL AUTO_INCREMENT,
  `JobTitle` varchar(127) NOT NULL,
  `JobClient` varchar(127) NOT NULL,
  `JobRate` varchar(63) DEFAULT NULL,
  `JobStatus` tinyint(3) NOT NULL,
  `JobType` tinyint(3) NOT NULL,
  `JobPONumber` int(10) NOT NULL,
  `JobLocation` varchar(255) DEFAULT NULL,
  `JobLocationLatitude` float DEFAULT NULL,
  `JobLocationLongitude` float DEFAULT NULL,
  `JobNotes` text,
  `JobModelBooked` int(10) DEFAULT NULL,
  `JobModelCasted` varchar(255) DEFAULT NULL,
  `JobDate` date NOT NULL,
  `JobDateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `JobDateUpdated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`JobID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bb_agency_job`
--

INSERT INTO `bb_agency_job` (`JobID`, `JobTitle`, `JobClient`, `JobRate`, `JobStatus`, `JobType`, `JobPONumber`, `JobLocation`, `JobLocationLatitude`, `JobLocationLongitude`, `JobNotes`, `JobModelBooked`, `JobModelCasted`, `JobDate`, `JobDateCreated`, `JobDateUpdated`) VALUES
(1, 'test job', 'test client', 'modelling', 0, 0, 234, 'london, se19 1ll', 51.4206, -0.0883423, 'test test test', 252, '238,429,408,227,415', '2014-05-31', '2014-05-26 15:18:01', '2014-05-27 10:42:04');
