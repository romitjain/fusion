<?php

	$url = "http://cricapi.com/api/cricket";
	//echo $url;
	$matches = file_get_contents($url);
	//echo '<br/>';
	//echo $matches;
	//echo '<br/>';
	$matches = json_decode($matches);
	
	$teams = array("Delhi Daredevils", "Gujarat Lions", "Kings XI Punjab", "Kolkata Knight Riders", "Mumbai Indians", "Rising Pune Supergiants", "Royal Challengers Bangalore", "Sunrisers Hyderabad");

	foreach ($matches->data as $match)
	{
		$total = 0;
		$var = 0;
		foreach ($teams as $team) 
		{
			if(stristr($match->title,$team))
			{
				$total++;
				//echo "Success!";
			}
		}
		if($total == 2)
		{
			$var = $match->unique_id;
			//echo $var;
		}
		if($var == 0)
			continue;
		$liveurl =  "http://cricapi.com/api/cricketScore?unique_id=".$var;
		$livescore = file_get_contents($liveurl);
		//echo $livescore;
		$livescore = json_decode($livescore);
		echo $livescore->score;
		echo '<br/>';
	}
?>