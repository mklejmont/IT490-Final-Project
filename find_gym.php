<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Finder</title>
    <!-- <script src="https://maps.googleapis.com/maps/api/place/nearbysearch/json&location=-33.8670522%2C151.1957362&radius=1500&type=gym&key=AIzaSyAsydsXHkmpEuT77PPJfjVb8XSilRTGvYI"></script>
    -->
    <style> 
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>

<body>
    <?php include 'navigation.php';

    function fetchFromAPI()
    {
        $curl = curl_init();
        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?&location={$_GET['lat']}%2c{$_GET['lng']}&radius=2000&type=gym&key=AIzaSyAsydsXHkmpEuT77PPJfjVb8XSilRTGvYI";
        //echo $url; 
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [],
        ]);

        $response = curl_exec($curl);
        $exercises = json_decode($response, true);
        $err = curl_error($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else if (key_exists('error', $exercises)) {
            echo "API Request error: " . $exercises['error'];
            curl_close($curl);
            return [];
        }
        //var_dump($exercises);
        curl_close($curl);
        return $exercises['results'];
    }
    ?>
    <h1>Gym Finder</h1>
    <div id="map"></div>

    <script>
        // Initialize Google Maps Places API
        var map;
        var service;
        var infowindow;

        function initMap() {
            var center = new google.maps.LatLng(<?php echo $_GET['lat'];?>, <?php echo $_GET['lng'];?>);
            map = new google.maps.Map(document.getElementById('map'), {
                center: center,
                zoom: 15
            });
            infowindow = new google.maps.InfoWindow();
            service = new google.maps.places.PlacesService(map);

            // Call function to find nearby gyms
            findNearbyGyms();
        }

        function findNearbyGyms() {
            // Use browser geolocation to get the user's location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    map.setCenter(userLocation);

                    // Perform a nearby search for gyms
                    var request = {
                        location: userLocation,
                        radius: '500',
                        type: 'gym'
                    };

                    service.nearbySearch(request, callback);
                }, function() {
                    handleLocationError(true, infowindow, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infowindow, map.getCenter());
            }
        }

        function callback(results, status) {
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                for (var i = 0; i < results.length; i++) {
                    createMarker(results[i]);
                }
            }
        }

        function createMarker(place) {
            var marker = new google.maps.Marker({
                map: map,
                position: place.geometry.location
            });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent(place.name);
                infowindow.open(map, this);
            });
        }

        function handleLocationError(browserHasGeolocation, infowindow, userLocation) {
            infowindow.setPosition(userLocation);
            infowindow.setContent(browserHasGeolocation ?
                'Error: The Geolocation service failed.' :
                'Error: Your browser doesn\'t support geolocation.');
            infowindow.open(map);
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsydsXHkmpEuT77PPJfjVb8XSilRTGvYI&callback=initMap"></script>
    <?php foreach (fetchFromAPI() as $gym) { ?>
        <h2><?php echo $gym['name']; ?></h2>
        <p>User ratings: <?php echo $gym['user_ratings_total']; ?> <br>
            <?php if ($gym['opening_hours']['open_now']) echo "This gym is currently open";
            else echo "this gym is currently closed";  ?><br>
            <?php echo $gym['vicinity'];?>
        </p>
        <?php //var_dump($gym); ?>
    <?php } ?>
</body>

</html>