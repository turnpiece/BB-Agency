<?php
 header("Content-type: text/xml; charset=utf-8"); 
  echo "<?xml version=\"1.0\"?>";
  echo "<bbagency_version>";
  echo get_option("bb_agency_version");
  echo "</bbagency_version>";
?>