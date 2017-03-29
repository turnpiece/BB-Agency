UPDATE `bb_agency_customfield_mux` SET `ProfileCustomValue` = FLOOR(`ProfileCustomValue` * 2.54 + 0.5) WHERE `ProfileCustomID` = 5 AND `ProfileCustomValue` REGEXP '^-?[0-9]+$';

UPDATE `bb_agency_customfield_mux` SET `ProfileCustomValue` = REPLACE(`ProfileCustomValue`, 'cm', '') WHERE `ProfileCustomID` = 5 AND `ProfileCustomValue` REGEXP '^-?[0-9]+cm$'