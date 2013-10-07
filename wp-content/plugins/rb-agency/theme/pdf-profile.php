<?php 
#This page that will generate HTML to feed on domPDF

$rb_agency_options_arr = get_option('rb_agency_options');
$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
$rb_agency_option_agencylogo = $rb_agency_options_arr['rb_agency_option_agencylogo'];

$header='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Print</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Robots" content="noindex, nofollow" />
	<link rel="stylesheet" type="text/css" media="screen, print"  />
	<script language="Javascript1.2">
      <!--
      function printpage() {
       //window.print();
      }
      //-->
    </script>
	'; 

 //where we decide what print format will it be.
 
  if($_POST['print_option']==1){
	  $chrome = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;  //detect if CHROME
	  if($chrome){
		  $widthAndHeight='style="width:365px; height:546px"';
		  $model_info_width="width:365px;";
	  }else{
		  $widthAndHeight='style="width:350px; height:550px"';
		  $model_info_width="width:410px;";		  
	  }
	  
	  $col=2;
      $perPage=2;
	  }
  elseif($_POST['print_option']=="1-1"){
	   	$widthAndHeight='style="width:183px;"';
		$model_info_width="width:182px;"; 
		$col=4;
		$perPage=8;
	  }
  elseif($_POST['print_option']==2){
	   	$widthAndHeight='style="width:183px;"';
		$model_info_width="width:182px;"; 
		$col=4;
		$perPage=8;
	  }
 elseif($_POST['print_option']==3){
		$widthAndHeight='style="width:183px; height:264px"';
		$model_info_width="width:182px;"; 
		$col=4;
		$perPage=8;
		$fileFormat="_medium";
	  }
	  
  elseif($_POST['print_option']=="3-1"){
		$widthAndHeight='style="width:183px; "';
		$model_info_width="width:182px;"; 
		$col=4;
		$perPage=8;
		$fileFormat="_medium";
	  }	  
	  
	  
  elseif($_POST['print_option']==4){
	  $widthAndHeight='style="width:500px"';
	  $ul_css="width:100%;";
	  $model_info_width="width:202px; height:320px;";
	  }
   elseif($_POST['print_option']==5){
	   $widthAndHeight='style="width:100px"';
       $wrapperWidthHeight="width:887px; height:600px;";   
	     $toLandScape='@page{size: landscape;margin: 2cm;}';
	   }


   elseif($_POST['print_option']==11){
		$widthAndHeight='style="width:232px; "';
			$model_info_width="width:158px; border:0px solid #000;"; 
			$col=2;
			$perPage=4;
			$fileFormat="_4perpage";
			$additionalCss="#4, #6, #8, #10, #12, #14, #16, #18 {margin-left:158px;}";
			$paperDef="10x16"; //pdf size
	   }

   elseif($_POST['print_option']==12){

		$widthAndHeight='style="width:520px; "';
			$model_info_width="width:158px; border:0px solid #000;"; 
			$col=1;
			$perPage=1;
			$fileFormat="_4perpage";
			$additionalCss="#1, #2, #3, #4, #5, #6, #7, #8, #9, #10, #11, #12 {margin-left:158px;}";
			$paperDef="10x16"; //pdf size

	   }


  elseif($_POST['print_option']==14){
		$widthAndHeight='style="width:91px; "';
		$model_info_width="width:154px;"; 
		$col=5;
		$perPage=10;
		$fileFormat="_division";
		
		$additionalCss='#profile-results #profile-list .profile-list-layout0 { width: 150px; float: left; margin:  0 10px 10px 10px; position: relative; z-index: 8;  overflow: hidden; height:270px; }
	#profile-results #profile-list .profile-list-layout0 .style { position: absolute; }
	#profile-results #profile-list .profile-list-layout0 .image { width: 148px; height: 228px; overflow: hidden; background: #fff; border: 1px solid #ccc; }
	#profile-results #profile-list .profile-list-layout0 .image img { max-width: 200px; margin: 0; }
	#profile-results #profile-list .profile-list-layout0 .image-broken { text-align: center; position: relative; background: #dadada !important; }
	#profile-results #profile-list .profile-list-layout0 .image-broken a {  margin-top: 90px;  }
    #profile-results #profile-list .profile-list-layout0 .title { top: 230px; left: 0; width: 150px; position: absolute; text-align: center; }
    #profile-results #profile-list .profile-list-layout0 .title .name { width: 150px; height: 30px; line-height: 30px; text-transform:uppercase; }
    #profile-results #profile-list .profile-list-layout0 .title .name a { color:#000; text-transform:uppercase; font-weight:normal;  }
    #profile-results #profile-list .profile-list-layout0 .title .favorite { margin: 9px 0 0 0; font-size: 13px; background: #ccc; padding: 5px 0; }
    #profile-results #profile-list .profile-list-layout0 .details { font-size: 13px; }
    #profile-results #profile-list .profile-list-layout0 .title .favorite a { color: #000; }
    #profile-results #profile-list .profile-list-layout0 .title .favorite .favorite-box { padding: 7px; background: #fff; border: 1px solid #aaa; position: absolute; bottom: 3px; left: 32px; }
    #profile-results #profile-list .profile-list-layout0 .title .favorite .favorite-box:hover { border: 1px solid #666; }
	#profile-results #profile-list .profile-list-layout0 .image img { max-width: 200px; margin: 0; }
	#profile-results #profile-list .profile-list-layout0 .image-broken { margin: 10px 10px 0 10px; text-align: center; width: 180px; height: 220px; }
	#profile-results #profile-list .profile-list-layout0 .image-broken a { margin-top: 90px;  float: left; margin-left: 55px; }	
	/* Profile Standard Layout */
	#profile-results #profile-list .profile-list-layout1 { border: 1px solid #bbacc7; width: 200px; float: left; margin: 0 20px 20px 0; position: relative; z-index: 8;  height: 235px; overflow: hidden; padding: 3px; background:#c8bdd2; }
	#profile-results #profile-list .profile-list-layout1 .image { width: 198px; height: 200px; overflow: hidden; margin: 0px auto; }
	#profile-results #profile-list .profile-list-layout1 .image img { max-width: 185px; }
	#profile-results #profile-list .profile-list-layout1 .image-broken {  }
	#profile-results #profile-list .profile-list-layout1 .image-broken a { display: none; }
	#profile-results #profile-list .profile-list-layout1 .title { }
	#profile-results #profile-list .profile-list-layout1 .title .name { font-size: 16px; margin: 0 0 5px 0; }
	#profile-results #profile-list .profile-list-layout1 .title .name a { color: #000; text-decoration: none; width: 190px; position: absolute; left: -3px; top: 213px; height: 30px; z-index: 10; text-align: center; font-size: 14px; }
	#profile-results #profile-list .profile-list-layout1 .style { background-color:#4D2375; width: 200px; position: absolute; top: 208px; height: 30px; z-index: 7;  }
	#profile-results #profile-list .profile-list-layout1 .favorite { background: #ccc; padding: 5px; margin: 5px 0; }
	#profile-results #profile-list .profile-list-layout1 .favorite:hover  { background: #bbb; }
	#profile-results #profile-list .profile-list-layout1 .details { overflow: hidden; display: block; }
	#profile-results #profile-list .profile-list-layout1 .details div { float: left; }
	#profile-results #profile-list .profile-list-layout1 .details .details-age { width: 50%; text-align: right; }
	#profile-results #profile-list .profile-list-layout1 .details .details-state { width: 50%; text-align: left;  }
	 /* Profile Standard Alternate */
	#profile-results #profile-list .profile-list-layout2 { width: 150px; float: left; margin: 0 20px 20px 0; }
	#profile-results #profile-list .profile-list-layout2 .image { width: 150px; height: 180px; overflow: hidden; }
	#profile-results #profile-list .profile-list-layout2 .image img { max-width: 150px; }
	#profile-results #profile-list .profile-list-layout2 .image-broken { margin: 10px 10px 0 10px; text-align: center; width: 150px; height: 220px; }
	#profile-results #profile-list .profile-list-layout2 .image-broken a { margin-top: 90px;  float: left; margin-left: 45px; }
	#profile-results #profile-list .profile-list-layout2 .title { color: #555; font-size: 13px; padding: 5px 0 0 0; background: #ddd; text-align: center; }
	#profile-results #profile-list .profile-list-layout2 .title .name { font-size: 16px; margin: 0 0 5px 0; }
	#profile-results #profile-list .profile-list-layout2 .title .name a { color: #555; }
	#profile-results #profile-list .profile-list-layout2 .favorite { background: #ccc; padding: 5px; margin: 5px 0; }
	#profile-results #profile-list .profile-list-layout2 .favorite:hover  { background: #bbb; }
	#profile-results #profile-list .profile-list-layout2 .details { overflow: hidden; display: block; }
	#profile-results #profile-list .profile-list-layout2 .details div { float: left; }
	#profile-results #profile-list .profile-list-layout2 .details .details-age { width: 50%; text-align: right; }
	#profile-results #profile-list .profile-list-layout2 .details .details-state { width: 50%; text-align: left;  }
	.pagination{display: none;}';
	
	  }
	  
else{$widthAndHeight='style="width:400px"';
      	$wrapperWidth="887px";
     	$wrapperWidthHeight="887px";
		  $toLandScape='@page{size: landscape;margin: 2cm;}';
		 
	  }

$header.='
<style>
#Experience{display:none;}
'.$toLandScape.'
body{color:#000;}
h1{color: #000; margin-bottom:15px; margin-top:15px;}

tr td span{ float:right; text-align:left; width:200px;}
table {'.$ul_css.'}
tr td{ list-style:none; padding-bottom:1px; padding-top:1px;}
#print_logo{margin-bottom:25px; width:100%; float:left;}
#model_info{border:0px solid #000; float:left;'.$model_info_width.'}
#print_wrapper img.allimages_thumbs{margin-right:5px;}

'.$additionalCss.'
</style>

</head>
<body style="background: #fff;">
<div id="print_wrapper" style=" '.$wrapperWidthHeight.'border:0px solid #000;">
';

if($_POST['print_option']==14){  // print for division
		$table1=do_shortcode('[profile_list gender="2" age_start="18" age_stop="99" type="1" pagingperpage="5"]');
		$table1=strip_tags($table1,'<img><script>');
		$table1=str_replace("&#187;","-->' src",$table1);
		$table1=str_replace('<script type="text/javascript">',"<!--' src",$table1);
		$table1=str_replace("<img src","</td><td width='150'><img style='width:144px;' src",$table1);
		
		$table2.=do_shortcode('[profile_list gender="2" age_start="18" age_stop="99" type="1" pagingperpage="5" paging="2"]');
		$table2=strip_tags($table2,'<img><script>');
		$table2=str_replace("&#187;","-->' src",$table2);
		$table2=str_replace('<script type="text/javascript">',"<!--' src",$table2);
		$table2=str_replace("<img src","</td><td width='150'><img style='width:144px;' src",$table2);

		$table3.=do_shortcode('[profile_list gender="2" age_start="18" age_stop="99" type="1" pagingperpage="5" paging="3"]');
		$table3=strip_tags($table3,'<img><script>');
		$table3=str_replace("&#187;","-->' src",$table3);
		$table3=str_replace('<script type="text/javascript">',"<!--' src",$table3);
		$table3=str_replace("<img src","</td><td width='150'><img style='width:144px;' src",$table3);

		$table4=do_shortcode('[profile_list gender="2" age_start="18" age_stop="99" type="1" pagingperpage="5" paging="4"]');
	
	  // strpos($table2,"No Profiles Found");

	
	  $table= "<table border='0'><tr>".$table1."</tr></table>--></td></tr></table>";
	 // $table.=' <div style="page-break-before:always" /></div><br><br><br>';
	  $table.="</td></tr></table><table border='0'><tr>".$table2."</tr></table>-->";
	  $table.='<div style="page-break-before:always" /></div><br><br><br>';
	  $table.='<img style="width:347px;" src="'.get_bloginfo("url").'/wp-content/plugins/rb-agency/theme/custom-layout6/images/address.jpg">'."</td></tr></table>"."<table border='0'><tr>".$table3."</tr></table>";
	  $table = str_replace("src","",$table);
	  $table = str_replace('<img ="','<img src="',$table);
	  
	  $table = str_replace('="http:','src="http:',$table);
	  $table = str_replace("<br>'","",$table);
	  $table = str_replace(" />"," /><br>",$table);
	  

	
	  
}else{ //this will loop images of simple profile
	
		$modelInfo.='
				<div id="model_info" style="'.$model_info_width.'">
					<h1 style="margin-top:0px; margin-bottom:0px;">'.$ProfileContactDisplay.'</h1>
				
					<ul>
					'.rb_agency_getProfileCustomFieldsCustom($ProfileID, $ProfileGender,"dontecho").'
					</ul>
				</div>'."\n";
				
		//trim more infom
		$modelInfo=str_replace("<li","<tr><td",$modelInfo);
		$modelInfo=str_replace("</li>","</td></tr>",$modelInfo);
		$modelInfo=str_replace("<ul>","<table>",$modelInfo);
		$modelInfo=str_replace("</ul>","</table>",$modelInfo);  
		$modelInfo=str_replace("</label>","</label>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$modelInfo);  
		
			if($_POST['print_type']=="print-polaroids"){$printType="Polaroid";}
			else{$printType="Image";}
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"".$printType."\" ORDER BY $orderBy";
					$resultsImg = mysql_query($queryImg);
					$countImg = mysql_num_rows($resultsImg);
					while ($dataImg = mysql_fetch_array($resultsImg)){$totalCount++;
				
					if($printType!="Polaroid"){ 
						 if($totalCount==1 AND $_POST['print_option']!="3-1" AND $_POST['print_option']!="1-1"){
							 
							 $allImages.="<td>$modelInfo<img $widthAndHeight id='".$dataImg["ProfileMediaID"]."' src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/theme/custom-layout6/images/trans.png\" alt=' ' class='allimages_thumbs'  /></td>";
							$cnt=1;  $cnt2=1; 
						 }
					 }
							 
					 //  if($_POST[$dataImg['ProfileMediaID']]==1){  - for the mean time as the were missed conception
						   $cnt++;
						   $cnt2++;
						   
					// $allImages.="<td><img $widthAndHeight id='".$dataImg["ProfileMediaID"]."' src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt='' class='allimages_thumbs' /></td>\n";
					//style="width:450px; height:650px;"
					$timthumbHW=str_replace('style="width:',"&w=",$widthAndHeight);
					$timthumbHW=str_replace('px; height:',"&h=",$timthumbHW);
					$timthumbHW=str_replace('px;"',"",$timthumbHW);
					
				
			//	$allImages.="<td><img id='".$dataImg["ProfileMediaID"]."' src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/tasks/timthumb.php?src=". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL']  .$timthumbHW."\" alt='' class='allimages_thumbs' /></td>\n";

				copy(get_bloginfo("url")."/wp-content/plugins/rb-agency/tasks/timthumb.php?src=". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL']  .$timthumbHW,"/home/content/99/6048999/html/rbplugin.agency/wp-content/plugins/rb-agency/cache/images/".$totalCount.".jpg");
					
				$allImages.="<td><img $widthAndHeight id='".$dataImg["ProfileMediaID"]."' src='".get_bloginfo("url")."/wp-content/plugins/rb-agency/cache/images/".$totalCount.".jpg' alt='' class='allimages_thumbs' /></td>\n";

					
					 //src=\"".get_bloginfo("url")."/wp-content/plugins/rb-agency/tasks/timthumb.php?src=".rb_agency_UPLOADDIR ."". $dataList["ProfileGallery"] ."/". $dataList["ProfileMediaURL"]."&w=200&q=60\"
						
						 if($cnt==$col){ $allImages.="</tr></table>\n";
						   if($cnt2==$perPage){
							
								  $allImages.='<br><br clear=""><img style="width:347px;" src="'.get_bloginfo("url").'/wp-content/plugins/rb-agency/style/address.jpg">';
								  
								  if($printType=="Polaroid" AND $excape!=1){$allImages.="<td></tr></table>"; $excape=1;}
								  
								  $allImages.='<div style="page-break-before:always" /></div><br>';
						   
						   $cnt2=0;}
							   
						 $allImages.="<table id='$totalCount' border='0'><tr>";
						 $cnt="0";}
						 
						// } for if($_POST[$dataImg['ProfileMediaID']]==1){
					}
			   
			   
		$table="<table border='0'><tr>".$allImages;
		
		$table.="</tr></table>\n";
		
		if($cnt2!=$perPage AND $cnt2!="0"){
		$table.='<br clear="all"><img style="width:347px;" src="'.get_bloginfo("url").'/wp-content/plugins/rb-agency/theme/custom-layout6/images/address.jpg">';
		}
		if($printType=="Polaroid"){
			$modelInfo="<table border='0' width='800'><tr><td width='180'>$modelInfo</td><td width='600'>$table";
			$table=$modelInfo;
		}
}  //end else of if($_POST['print_option']

$footer.='
</div>
</body>
</html>';


$border='
<div style="width:770px; height:760px; border:1px solid #000;">
</div>';

/*
echo $header;
//echo $border;
//echo $modelInfo;
echo $allImages;
echo $footer;

die();*/


$htmlFile=date("ymd").".html"; 
//$pdfFile=str_replace(".html",".pdf",$htmlFile);


//PDF FILE NAMING
$pdfFile=str_replace(" ","_",$ProfileContactDisplay).$fileFormat.".pdf";
if($_POST['print_option']=="1"){$format="-Photos-Large-with-info-";}
elseif($_POST['print_option']=="3"){$format="-Photos-Medium-with-info-";}
elseif($_POST['print_option']=="1-1"){$format="-Photos-Large-no-Info-";}
elseif($_POST['print_option']=="3-1"){$format="-Photos-Medium-no-Info-";}
elseif($_POST['print_option']=="11"){$format="-Polaroids-4perpage-with-Info-";}
elseif($_POST['print_option']=="12"){$format="-Polaroids-1perpage-with-Info-";}
else{$format="";}

$blog_title = strtolower(str_replace(" ","_",get_bloginfo('name')));
$pdfFile="$blog_title-".str_replace(" ","-",$ProfileContactDisplay).$format.date("ymd").".pdf";



$toRedirect=rb_agency_BASEDIR."dompdf/dompdf.php?base_path=htmls/&pper=$paperDef&output_filed=".$pdfFile."&input_file=".$htmlFile;
$path="wp-content/plugins/rb-agency/dompdf/htmls/";

//include("/wp-content/plugins/rb-agency/dompdf/htmls/test.txt");
$fp=fopen($path.$htmlFile,"w");
fwrite($fp,$header);
//fwrite($fp,$border);
//fwrite($fp,$modelInfo);
//fwrite($fp,$allImages);
fwrite($fp,$table);
fwrite($fp,$footer);
fclose($fp);

//die($toRedirect);
header("location:$toRedirect");
