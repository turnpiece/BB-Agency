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
        <script src="//maps.googleapis.com/maps/api/js?key=<?php echo self::$key ?>&callback=initMap" async defer></script>
        <script>
            function initMap() {
                var myLatLng = {lat: <?php echo $lat ?>, lng: <?php echo $lng ?>};
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: myLatLng,
                    zoom: 9
                });

                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    title: '<?php echo $name ?>'
                });

                marker.setMap(map);
            }
        </script>
        <div id="map" style="width: 100%; height: 200px;"></div>
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