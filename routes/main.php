<?php
// dashboard
require_once 'dashboard.php';

// send mail
require_once 'send-mail/send-mail.php';

// Auth
require_once 'auth/auth.php';

// profile
require_once 'profile/profile.php';

// settings
require_once 'settings/settings.php';

// users
require_once 'users/users.php';

// user agent
require_once 'user-agent.php';

// cron job
require_once 'cron-job/cron_job.php';


// shipments
require_once 'basic-sections/locations.php';



function uri($reservedUrl, $class, $method, $requestMethod = 'GET')
{
        //current url array
        $currentUrl = explode('?', currentUrl())[0];
        $currentUrl = str_replace(CURRENT_DOMAIN, '', $currentUrl);
        $currentUrl = trim($currentUrl, '/');
        $currentUrlArray = explode('/', $currentUrl);
        $currentUrlArray = array_filter($currentUrlArray);

        //reserved Url array
        $reservedUrl = trim($reservedUrl, '/');
        $reservedUrlArray = explode('/', $reservedUrl);
        $reservedUrlArray = array_filter($reservedUrlArray);

        if (sizeof($currentUrlArray) != sizeof($reservedUrlArray) || methodField() != $requestMethod) {
                return false;
        }

        $parameters = [];
        for ($key = 0; $key < sizeof($currentUrlArray); $key++) {
                if ($reservedUrlArray[$key][0] == "{" && $reservedUrlArray[$key][strlen($reservedUrlArray[$key]) - 1] == "}") {
                        array_push($parameters, $currentUrlArray[$key]);
                } elseif ($currentUrlArray[$key] !== $reservedUrlArray[$key]) {
                        return false;
                }
        }

        if (methodField() == 'POST') {
                $request = isset($_FILES) ? array_merge($_POST, $_FILES) : $_POST;
                $parameters = array_merge([$request], $parameters);
        }

        $object = new $class;
        call_user_func_array(array($object, $method), $parameters);
        exit();
}
