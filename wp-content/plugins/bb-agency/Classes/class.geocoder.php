<?php

// geocode an address

class Geocoder {
    static private $url = "https://maps.googleapis.com/maps/api/geocode/json";
    static private $key = 'AIzaSyAwGmQOA2UBIBMr1t82cYi_K9cidbRBC48';

    static public function getLocation($address)
    {
        $url = self::getUrl($address);
       
        $resp_json = self::curl_file_get_contents($url);
        $resp = json_decode($resp_json, true);

        if ($resp['status'] == 'OK') {
            return $resp['results'][0]['geometry']['location'];
        } else {
            return false;
        }
    }

    static public function getMap($lat, $lng, $name) {
        ?>
        <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
        <script type="text/javascript">
            function initialize() {
                var myLatlng = new google.maps.LatLng(<?php echo $lat ?>, <?php echo $lng ?>);
                var myOptions = {
                    zoom: 9,
                    center: myLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
                           
                new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    title: "<?php echo $name ?>"
                });
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
        <div id="map_canvas" style="width: 100%; height: 200px;"></div>
        <?php
    }

    static private function getUrl($address)
    {
        return self::$url . '?key=' . self::$key .'&sensor=false&address=' . urlencode($address);
    }

    static private function curl_file_get_contents($URL)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents) 
            return $contents;
        else 
            return false;
    }
}