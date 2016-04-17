<!DOCTYPE html>
<?php
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
	
	//print_r ($twitter_data);

	/*foreach ($twitter_data as $tweet) 
	{
		echo $tweet->text;
		echo "<br/>";
	}*/
?>
<html>
<html>
<head><title>CRICINFO</title>
<link rel="stylesheet" type="text/css" href="css/material.css">
<link rel="stylesheet" type="text/css" href="css/cstyle.css">
<link href='https://fonts.googleapis.com/css?family=Fjalla+One' rel='stylesheet' type='text/css'>
</head>


<body>

	<div classs="container">
		
	<!-- HEADER LIVE SCORES  -->
		<div class="container-fluid">
			<div class="wrapper">
				<div class="row center">
					<div class="content">
					<h1 class="header">LIVE SCORE</h1>
						<?php
							error_reporting(0);
							$content=file_get_contents("http://cricscore-api.appspot.com/csa");
							$array = json_decode($content,true);
							$u=$array[0]['id'];
							
							$content=file_get_contents("http://cricscore-api.appspot.com/csa?id=$u");
							$array = json_decode($content,true);
							echo $array[0]['de'];
							// $u=$array[0]['de'];
							
						?>
					</div>
				</div>
			</div>
		</div>


	<!--BODY -->
		<div class="container">
			<div class="row background">
				<!-- photos -->
				<div class="col m7 photos">
						
				</div>
				<!-- TWEETS-->
				<div class="col m5">
					<?php	
						echo $twitter_data[0]->text;
					?>
				</div>
			</div>
		</div>
	</div>	


<script type="text/javascript" src="js/jquery.js"></script>
</body>
</html>	
</html>
