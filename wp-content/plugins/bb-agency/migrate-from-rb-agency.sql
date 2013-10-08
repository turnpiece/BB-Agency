RENAME TABLE `bb_agencyinteract_subscription` TO  `bb_agencyinteract_subscription`;
RENAME TABLE `bb_agencyinteract_subscription_rates` TO `bb_agencyinteract_subscription_rates`;
RENAME TABLE `bb_agency_castingcart` TO `bb_agency_castingcart`;
RENAME TABLE `bb_agency_customfields` TO `bb_agency_customfields`;
RENAME TABLE `bb_agency_customfields_types` TO `bb_agency_customfields_types`;
RENAME TABLE `bb_agency_customfield_mux` TO `bb_agency_customfield_mux`;
RENAME TABLE `bb_agency_data_gender` TO `bb_agency_data_gender`;
RENAME TABLE `bb_agency_data_type` TO `bb_agency_data_type`;
RENAME TABLE `bb_agency_mediacategory` TO `bb_agency_mediacategory`;
RENAME TABLE `bb_agency_profile` TO `bb_agency_profile`;
RENAME TABLE `bb_agency_profile_media` TO `bb_agency_profile_media`;
RENAME TABLE `bb_agency_rel_taxonomy` TO `bb_agency_rel_taxonomy`;
RENAME TABLE `bb_agency_savedfavorite` TO `bb_agency_savedfavorite`;
RENAME TABLE `bb_agency_searchsaved` TO `bb_agency_searchsaved`;
RENAME TABLE `bb_agency_searchsaved_mux` TO `bb_agency_searchsaved_mux`;
UPDATE `orh_options` SET `option_name` = 'bb_agencyinteract_options' WHERE `option_name` = 'bb_agencyinteract_options';
UPDATE `orh_options` SET `option_name` = 'bb_agencyinteract_version' WHERE `option_name` = 'bb_agencyinteract_version';
UPDATE `orh_options` SET `option_name` = 'bb_agency_dummy_options' WHERE `option_name` = 'bb_agency_dummy_options';
UPDATE `orh_options` SET `option_name` = 'bb_agency_options' WHERE `option_name` = 'bb_agency_options';
UPDATE `orh_options` SET `option_name` = 'bb_agency_version' WHERE `option_name` = 'bb_agency_version';
UPDATE `orh_options` SET `option_name` = 'bb_email_content' WHERE `option_name` = 'rb_email_content';
ALTER TABLE `bb_agency_profile` ADD `ProfileDateDue` DATE AFTER `ProfileDateBirth`;