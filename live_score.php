
<?php
error_reporting(0);
$content=file_get_contents("http://cricscore-api.appspot.com/csa");
$array = json_decode($content,true);
$u=$array[0]['id'];
echo '<br/>';
$content=file_get_contents("http://cricscore-api.appspot.com/csa?id=$u");
$array = json_decode($content,true);
echo '<h1><font color="#33ccff">Live Match</font> : ';
echo $array[0]['de'];
echo '</h1>';
$u=$array[0]['de'];
header('Refresh:10;URL=cric.php');
?>
