<?php

	$url = "http://cricapi.com/api/cricketScore?unique_id=946765";
	echo $url;
	$json = file_get_contents($url);
	echo '<br/>';
	echo $json;
?>

<!DOCTYPE html>
<html>
  <head>
  </head>
    
    <body>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">
    </script>

    <script type="text/javascript">
    alert("Hello");
    </script>
  </body>

</html>