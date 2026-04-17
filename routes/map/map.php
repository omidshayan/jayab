<?php
require_once 'Http/Controllers/map/Map.php';

//  profile routes
uri('show/map', 'App\Map', 'showMap');
uri('mapInfo/store', 'App\Map', 'mapInfoStore', 'POST');

uri('get-places', 'App\Map', 'getPlaces');






