<!DOCTYPE html>
<?php
	require "cricinfo.php";
    function buildBaseString($baseURI, $method, $params) {
        $r = array();
        ksort($params);
        foreach($params as $key=>$value){
            $r[] = "$key=" . rawurlencode($value);
        }
        return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }

    function buildAuthorizationHeader($oauth) {
        $r = 'Authorization: OAuth ';
        $values = array();
        foreach($oauth as $key=>$value)
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        $r .= implode(', ', $values);
        return $r;
    }

    $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";

    $oauth_access_token = "116420445-iMoSZHp4E8SERuqB7hQlxhJr6D2HUID8jARCd2Df";
    $oauth_access_token_secret = "KApNbqiMiY1jKM4nnhFYjrnS5EcHfhPNSycvTT1O3HELu";
    $consumer_key = "EkmtLpx19SqsKEQlU0w57HQyI";
    $consumer_secret = "mWNcN9iCjPrH6pwCum3f0CrIeHVgiRbfTKn6TNlUTW4AmDBSUV";

    $oauth = array( 'screen_name' => 'IPL',
           			'oauth_consumer_key' => $consumer_key,
                    'oauth_nonce' => time(),
                    'oauth_signature_method' => 'HMAC-SHA1',
                    'oauth_token' => $oauth_access_token,
                    'oauth_timestamp' => time(),
                    'oauth_version' => '1.0');

    $base_info = buildBaseString($url, 'GET', $oauth);
    $composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
    $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
    $oauth['oauth_signature'] = $oauth_signature;

    // Make requests
    $header = array(buildAuthorizationHeader($oauth), 'Expect:');
    $options = array( CURLOPT_HTTPHEADER => $header,
                      //CURLOPT_POSTFIELDS => $postfields,
                      CURLOPT_HEADER => false,
                      CURLOPT_URL => $url. '?screen_name=IPL',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_SSL_VERIFYPEER => false);

    $feed = curl_init();
    curl_setopt_array($feed, $options);
    $json = curl_exec($feed);
    curl_close($feed);

    $twitter_data = json_decode($json);
	//print it out
	
	//print_r ($twitter_data[0]);
	//print_r ($twitter_data[1]);

	//print_r($twitter_data[0]->hashtags);

	/*foreach ($twitter_data as $tweet) 
	{
		print_r($tweet->entities->hashtags[0]->text);
		echo "<br/>";
	}*/

	$youtube_key = $twitter_data[0]->entities->hashtags[0]->text;
?>

<html>
<head><title>CRICINFO</title>
<link rel="stylesheet" type="text/css" href="css/material.css">
<link rel="stylesheet" type="text/css" href="css/cstyle.css">
<link href='https://fonts.googleapis.com/css?family=Fjalla+One' rel='stylesheet' type='text/css'>
</head>


<body>

<div classs="container">   <!-- Wraps the whole document -->
		
	<!-- HEADER LIVE SCORES  -->
		<div class="container-fluid">
			<div class="wrapper">
				<div class="row center">
					<div class="content">
						<h1 class="header">LIVE SCORE</h1>
						<h5> <?php echo $livescore->score; ?></h5>
						
					</div>
				</div>
			</div>
		</div>


	<!--	tweets and photos   -->
			
		<div class="container"> <!--    -->  
			<div class="row background">
				<!-- photos -->
				<div class="col m7 s6 photos">
					<?php

$htmlBody = <<<END
<form method="GET" id="you">
  <div>
    <input type="hidden" id="q" name="q" placeholder="Enter Search Term" value = "ipl">
  </div>
  <div>
    <input type="hidden" id="maxResults" name="maxResults" min="1" max="50" step="1" value="1">
  </div>
  <input type="submit" value="Search for Videos">
</form>
END;

// This code will execute if the user entered a search query in the form
// and submitted the form. Otherwise, the page displays the form above.
if ($_GET['q'] && $_GET['maxResults']) {
  // Call set_include_path() as needed to point to your client library.
require_once 'google-api-php-client/src/Google/autoload.php';
require_once 'google-api-php-client/src/Google/Client.php';
require_once 'google-api-php-client/src/Google/Service/YouTube.php';
  /*
   * Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
   * Google Developers Console <https://console.developers.google.com/>
   * Please ensure that you have enabled the YouTube Data API for your project.
   */
  $DEVELOPER_KEY = 'AIzaSyDcKa8aY_rFE_D-Lvsyn_MaQgG3Ro2CA6o';

  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);

  // Define an object that will be used to make all API requests.
  $youtube = new Google_Service_YouTube($client);

  try {
    // Call the search.list method to retrieve results matching the specified
    // query term.
    $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'q' => $_GET['q'],
      'maxResults' => $_GET['maxResults'],
    ));

    $videos = '';
    $channels = '';
    $playlists = '';

    // Add each result to the appropriate list, and then display the lists of
    // matching videos, channels, and playlists.
    foreach ($searchResponse['items'] as $searchResult) {
      switch ($searchResult['id']['kind']) {
        case 'youtube#video':
          $videos .= sprintf('<li>%s (%s)</li>',
              $searchResult['snippet']['title'], $searchResult['id']['videoId']);
          $embedid = $searchResult['id']['videoId'];
          break;
        case 'youtube#channel':
          $channels .= sprintf('<li>%s (%s)</li>',
              $searchResult['snippet']['title'], $searchResult['id']['channelId']);
          $embedid2 = $searchResult['id']['channelId'];
          break;
        case 'youtube#playlist':
          $playlists .= sprintf('<li>%s (%s)</li>',
              $searchResult['snippet']['title'], $searchResult['id']['playlistId']);
          break;
      }
    }

    $videolink = "http://www.youtube.com/embed/".$embedid."?autoplay=1";
    $channellink = "http://www.youtube.com/embed/".$embedid2;
    //echo $channellink;

    $htmlBody .= <<<END
    <h3>Videos</h3>
    <ul>$videos</ul>
    <iframe width="420" height="315"src=$videolink>
    </iframe>
END;
  } catch (Google_Service_Exception $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  }
}
?>

			<?=$htmlBody?>

				</div>
				<!-- TWEETS-->
				<div class="col m5 s6">
					<div class="cards">
						
						<marquee direction="up" scrollamount="3" height="300px"> 
						<div id="1" class="card-panel hoverable">
							<div class="card-content">	
								<span><?php	echo $twitter_data[0]->text;?></span>
							</div>
						</div>
						<div id="2" class="card-panel hoverable">
							<div class="card-content">	
								<span><?php	echo $twitter_data[1]->text;?></span>
							</div>
						</div>
						<div id="2" class="card-panel hoverable">
							<div class="card-content">	
								<span><?php	echo $twitter_data[2]->text;?></span>
							</div>
						</div>
						<div id="2" class="card-panel hoverable">
							<div class="card-content">	
								<span><?php	echo $twitter_data[3]->text;?></span>
							</div>
						</div>
						<div id="2" class="card-panel hoverable">
							<div class="card-content">	
								<span><?php	echo $twitter_data[4]->text;?></span>
							</div>
						</div>
						<div id="2" class="card-panel hoverable">
							<div class="card-content">	
								<span><?php	echo $twitter_data[5]->text;?></span>
							</div>
						</div>
						</marquee>	
					</div>				
				</div>
			</div>
		</div>
	</div>	


<script type="text/javascript" src="js/jquery.js"></script>
</body>
</html>	

