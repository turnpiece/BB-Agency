ALTER TABLE `bb_agency_data_type` ADD COLUMN `DataTypePrivacy` INT(4) NOT NULL DEFAULT 0;
UPDATE `bb_agency_data_type` SET `DataTypePrivacy` = '1' WHERE `bb_agency_data_type`.`DataTypeID` = 8; 