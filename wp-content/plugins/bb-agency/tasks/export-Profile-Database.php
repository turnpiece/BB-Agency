<?php
ob_start();
// Tap into WordPress Database
include_once('../../../../wp-config.php');
include_once('../../../../wp-load.php');
include_once('../../../../wp-includes/wp-db.php');
global $wpdb;

	//$query3 = "SELECT ProfileCustomID, ProfileCustomTitle FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0  ORDER BY ProfileCustomOrder";
	/*
	 * to include all types of views including
	 * private
	 */
	$query3 = "SELECT ProfileCustomID, ProfileCustomTitle FROM ". table_agency_customfields ." ORDER BY ProfileCustomOrder";
	$custom_fields_name = array();
	$custom_fields_id = array();
	$custom_fields = $wpdb->get_results($query3, ARRAY_A);
	foreach ($custom_fields as $key => $value) {
		array_push($custom_fields_name, 'Client'.str_replace(' ', '', $value['ProfileCustomTitle']));
		array_push($custom_fields_id, $value['ProfileCustomID']);
	}
	if(isset($_POST))
	{

		$cols = $wpdb->query("SHOW COLUMNS FROM `bb_agency_profile`", ARRAY_A);
		$i = 0;
		if (count($cols) > 0) {
		  	foreach ($cols as $col) {
		  		if ($i > 0)
					$csv_output .= $col['Field'].", ";

				$i++;
		  	}
		}
		$csv_output .= ", Media\n";
		
	    // get profile type
	    $ProfileType = isset($_POST['ProfileType']) ? $_POST['ProfileType'] : 0;

	    $sql = "SELECT * FROM `bb_agency_profile`";

	    if ($ProfileType > 0)
	        $sql .= " WHERE `ProfileType` = $ProfileType";

		$profiles = $wpdb->get_results($sql, ARRAY_N);

		foreach ($profiles as $profile) {
			for ($j = 1; $j < $i; $j++) {
				$csv_output .= $profile[$j].", ";
			}
			// get images & media attachments
			$media_sql = "SELECT * FROM `bb_agency_profile_media` WHERE `ProfileID` = ".$profile[0];

			$files = $wpdb->get_results( $media_sql, ARRAY_A );

			$media = array()

			foreach ($files as $file) {
				$media[] = $file['ProfileMediaType'] . '=' . $file['ProfileMediaURL'];
			}

		  	$csv_output .= implode('&', $media) . "\n";
		}
		
		$filename = "bb_agency_".date("Y-m-d_H-i",time());
		header("Content-type: application/vnd.ms-excel");
		header("Content-disposition: csv" . date("Y-m-d") . ".csv");
		header( "Content-disposition: filename=".$filename.".csv");
		print $csv_output;
		exit;

		if($_POST['file_type'] == 'csv')
		{ 
			$profile_data = $wpdb->get_results("SELECT ProfileContactDisplay,ProfileContactNameFirst,ProfileContactNameLast,ProfileGender,ProfileDateBirth,ProfileContactEmail,ProfileContactWebsite,ProfileContactPhoneHome,ProfileContactPhoneCell,ProfileContactPhoneWork,ProfileLocationStreet,ProfileLocationCity,ProfileLocationState,ProfileLocationZip,ProfileLocationCountry,ProfileType,ProfileIsActive FROM bb_agency_profile ", ARRAY_A);
			$profile_data_id = $wpdb->get_results("SELECT ProfileID,ProfileContactDisplay,ProfileContactNameFirst  FROM bb_agency_profile", ARRAY_A);
			$csv_output .= "ProfileContactDisplay,ProfileContactNameFirst,ProfileContactNameLast,ProfileGender,ProfileDateBirth,ProfileContactEmail,ProfileContactWebsite,ProfileContactPhoneHome,ProfileContactPhoneCell,ProfileContactPhoneWork,ProfileLocationStreet,ProfileLocationCity,ProfileLocationState,ProfileLocationZip,ProfileLocationCountry,ProfileType,ProfileIsActive,";
			$csv_output .= implode(',', $custom_fields_name);
			$csv_output .= "\n"; 
			foreach ($profile_data as $key => $data_value) { 
                $gender = $wpdb->get_row("SELECT GenderTitle FROM ". table_agency_data_gender ." WHERE GenderID = ".$data_value['ProfileGender'], ARRAY_A);
                $data_value['ProfileGender'] = $gender['GenderTitle'];
                $data_value['ProfileType'] = str_replace(","," | ",$data_value['ProfileType']);  
				$csv_output .= implode(',', $data_value);
				$subresult = $wpdb->get_results("SELECT ProfileCustomID, ProfileCustomValue FROM ". table_agency_customfield_mux ." WHERE ProfileID = ". $profile_data_id[$key]['ProfileID'], ARRAY_A);
                $temp_array = array();
				$c_value_array = array(); 
				foreach ($subresult as $sub_value) {
					$ProfileCustomValue = ""  ;
					if(trim($sub_value['ProfileCustomValue']) != ""){
                    	$ProfileCustomValue = str_replace(',', '/', preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $sub_value['ProfileCustomValue']));
					} else {
					    $ProfileCustomValue = "";
					}
					
					$temp_array[$sub_value['ProfileCustomID']] = $ProfileCustomValue; 
				}
				
				/*
				 * arrange array to right column headings
				 */
				foreach($custom_fields_id as $d){
					$c_value_array[] = $temp_array[$d];
				}
				
				$csv_output .= ','.implode(',', $c_value_array);
				$csv_output .="\n";
				
			}
			$filename = $_SERVER['SERVER_NAME']."_".date("Y-m-d_H-i",time());
			
			header("Content-type: application/vnd.ms-excel");
			header("Content-disposition: csv" . date("Y-m-d") . ".csv");
			header( "Content-disposition: filename=".$filename.".csv");
			
			print $csv_output;
		}	
		elseif($_POST['file_type'] == 'xls')
		{
			require_once WP_CONTENT_DIR.'/plugins/bb-agency/Classes/PHPExcel.php';
			require_once WP_CONTENT_DIR.'/plugins/bb-agency/Classes/PHPExcel/IOFactory.php';
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			$rowNumber = 1;
			/*Getting headers*/
			$headings = array();
            $headings = array('ProfileContactDisplay','ProfileContactNameFirst','ProfileContactNameLast','ProfileGender','ProfileDateBirth','ProfileContactEmail','ProfileContactWebsite','ProfileContactPhoneHome','ProfileContactPhoneCell','ProfileContactPhoneWork','ProfileLocationStreet','ProfileLocationCity','ProfileLocationState','ProfileLocationZip','ProfileLocationCountry','ProfileType','ProfileIsActive');
            $head_count = count($headings);
            foreach ($custom_fields_name as $key => $value) {
            	$headings[$head_count] = $value;
            	$head_count++;
            }
            $objPHPExcel->getActiveSheet()->fromArray(array($headings),NULL,'A'.$rowNumber);
			/*Profile data*/
			$row_data = array();
			$row_data = $wpdb->get_results('SELECT ProfileContactDisplay,ProfileContactNameFirst,ProfileContactNameLast,ProfileGender,ProfileDateBirth,ProfileContactEmail,ProfileContactWebsite,ProfileContactPhoneHome,ProfileContactPhoneCell,ProfileContactPhoneWork,ProfileLocationStreet,ProfileLocationCity,ProfileLocationState,ProfileLocationZip,ProfileLocationCountry,ProfileType,ProfileIsActive FROM bb_agency_profile', ARRAY_A);
			$profile_data_id = $wpdb->get_results("SELECT ProfileID FROM bb_agency_profile", ARRAY_A);

			foreach ($row_data as $key => $data) 
			{
				$rowNumber++;
				$subresult = $wpdb->get_results("SELECT * FROM ". table_agency_customfield_mux ." WHERE ProfileID = ". $profile_data_id[$key]['ProfileID'], ARRAY_A);

				$gender = $wpdb->get_row("SELECT GenderTitle FROM ". table_agency_data_gender ." WHERE GenderID = ".$data['ProfileGender'], ARRAY_A);

				$data['ProfileGender'] =$gender['GenderTitle'];
				$c_value_array = array();
			    $temp_array = array();

				foreach ($subresult as $sub_value) {
					if(trim($sub_value['ProfileCustomValue']) != ""){
                    	$ProfileCustomValue = str_replace(',', '/', preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $sub_value['ProfileCustomValue']));
					} else {
					    $ProfileCustomValue = "";
					}
					
					$temp_array[$sub_value['ProfileCustomID']] = $ProfileCustomValue; 
				}
				
				/*
				 * arrange array to right column headings
				 */
				foreach($custom_fields_id as $d){
					$c_value_array[] = $temp_array[$d];
				}
				
				$data = array_merge($data, $c_value_array);
				
				$objPHPExcel->getActiveSheet()->fromArray(array($data),NULL,'A'.$rowNumber);	
			}
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save(str_replace('.php', '.xls', __FILE__));
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment;filename=".$_SERVER['SERVER_NAME']."_".date("Y-m-d_H-i",time()).".xls"); 
			header("Content-Transfer-Encoding: binary ");
			ob_clean();
			flush();
		    readfile(str_replace('.php', '.xls', __FILE__));
		    unlink(str_replace('.php', '.xls', __FILE__));
		}
	}
	exit;
?>