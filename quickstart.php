<?php
require_once __DIR__ . '/vendor/autoload.php';

define('APPLICATION_NAME', 'Google Calendar API PHP Quickstart');
define('CREDENTIALS_PATH', '~/.credentials/calendar-php-quickstart.json');
define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');
define('SCOPES', implode(' ', array(
  Google_Service_Calendar::CALENDAR_READONLY)
));

date_default_timezone_set('America/New_York'); // Prevent DateTime tz exception
if (php_sapi_name() != 'cli') {
  throw new Exception('This application must be run on the command line.');
}
/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API PHP Quickstart');
    $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
    $client->setAuthConfig('client_secret.json');
    $client->setAccessType('offline');

    // Load previously authorized credentials from a file.
    $credentialsPath = expandHomeDirectory('credentials.json');
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
    } else {
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = "4/AACfUb5Fy47GhZYcKeqUOrfgD2iBPxeFBdRKML5HnhZXNFX9CWSjmlM";

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Store the credentials to disk.
        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        printf("Credentials saved to %s\n", $credentialsPath);
    }
    $client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path)
{
    $homeDirectory = getenv('HOME');
    if (empty($homeDirectory)) {
        $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
    }
    return str_replace('~', realpath($homeDirectory), $path);
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Calendar($client);

// Print the next 10 events on the user's calendar.
$calendarId = 'primary';
$optParams = array(
  'maxResults' => 20,
  'orderBy' => 'startTime',
  'singleEvents' => TRUE,
  'timeMin' => date('c'),
);
$results = $service->events->listEvents($calendarId, $optParams);
$count=0;

if (count($results->getItems()) == 0) {
  print "No upcoming events found.\n";
} else {
  echo "<table style='width: 100%;' cellspacing=15>";
  foreach ($results->getItems() as $event) {
    $start = $event->start->dateTime;
    if (empty($start)) {
      $start = $event->start->date;
    }
    $description = $event->description;
    $myArray = explode('T', $start);
    $det=$event->getSummary();
    $date=date('20y-m-d');
    if($myArray[0]==$date){
      $count++;
      echo "<tr><td>";
      echo "<table style='width:100%;background-color:#fff;'><tr>";
      echo "<td id='title'><b><center>$det ($myArray[0])</center></b></td>";
      echo "</tr><tr>";
      echo "<td>$description</td>";
      echo "</tr></table>";
      echo "</td></tr>";
    }
  }
  if($count==0){
    $calendarId = 'primary';
    $optParams = array(
      'maxResults' => 5,
      'orderBy' => 'startTime',
      'singleEvents' => TRUE,
      'timeMin' => date('c'),
    );
    $results = $service->events->listEvents($calendarId, $optParams);
    foreach ($results->getItems() as $event) {
      $start = $event->start->dateTime;
      if (empty($start)) {
        $start = $event->start->date;
      }
      $description = $event->description;
      $myArray = explode('T', $start);
      $det=$event->getSummary();
      echo "<tr><td>";
      echo "<table style='width:100%;background-color:#fff;'><tr>";
      echo "<td id='title'><b><center>$det ($myArray[0])</center></b></td>";
      echo "</tr><tr>";
      echo "<td><center>$description</center></td>";
      echo "</tr></table>";
      echo "</td></tr>";
    }
  }
    
    echo "</table>";
}

?>
