<?php
require_once 'Http/Controllers/basic-sections/locations/Location.php';

// companies routes
uri('locations', 'App\Location', 'locations');
uri('location-store', 'App\Location', 'locationStore', 'POST');
uri('edit-location/{id}', 'App\Location', 'editLocation');
uri('edit-location-store/{id}', 'App\Location', 'editLocationStore', 'POST');
uri('location-details/{id}', 'App\Location', 'locationDetails');
uri('change-status-location/{id}', 'App\Location', 'changeStatusLocation');

