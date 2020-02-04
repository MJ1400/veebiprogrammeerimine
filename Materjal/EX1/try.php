<?php
  $userName = "Mirjam Petti";
  $fullTimeNow = date("d.m.Y H:i:s");
  $dayNow = date("w");
  $hourNow = date("H");
//$minuteNow = date("i");
  $partOfDay = "hägune aeg";
  if(($hourNow >= 8) && ($dayNow == 1)){
	$partOfDay = "veebiprogrammeerimise loeng";
  } elseif(($hourNow >= 12 && $hourNow < 16) && ($dayNow == 1)){
	$partOfDay = "interaktsioonidisaini loeng";
  } elseif(($hourNow >= 8) && ($dayNow == 2)){
	$partOfDay = "programmeerimise aluste loeng";
  } elseif(($hourNow >= 12 && $hourNow < 14) && ($dayNow == 2)){
	$partOfDay = "andmebaaside projekteerimise loeng";
  } elseif(($hourNow >= 14 && $hourNow < 16) && ($dayNow > 1 && $dayNow < 6)){
	$partOfDay = "jaapani keele loeng";
  } else {
	$partOfDay = "loenguvaba aeg";
  }
?>
<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title>
  <?php
    echo $userName;
  ?>
  HTML #1 02.09.2019</title>
</head>
<body>
  <?php
    echo "<h1>" .$userName ." HTML #1 02.09.2019</h1>";
  ?>
  <p>Antud leht on loodud koolis õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
  <hr>
  <p>Lehe avamise hetkel oli aeg: 
  <?php
   echo $fullTimeNow;
  ?>
  .</p>
  <?php
    echo "<p>Lehe avamise hetkel oli <strong>" .$partOfDay ."</strong>.</p>";
  ?>
  <hr>
</body>
</html>