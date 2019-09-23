<?php
  $userName = "Marten Jürgensi";
  $fullTimeNow = date("d.m.Y H:i:s");
  $hourNow = date("H");
  $partOfDay = "hägune aeg";
  if($hourNow < 8) {
    $partOfDay = "varane hommik";
  } elseif($hourNow > 8 and $hourNow < 12) {
      $partofDay = "Hommik";
  } elseif($hourNow > 12 and $hourNow < 16) {
      $partofDay = "Lõuna";
  } elseif($hourNow > 16 and $hourNow < 20) {
      $partofDay = "Õhtu";
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
       lehekülg</title>
</head>
<body>
  <?php
    echo "<h1>" .$userName ." koolitöö leht </h1>";
   ?>
  <p>See leht on loodud koolitöö raames ja ei sisalda tõsiseltvõetavat sisu! </p>
  <hr>
  <p>Lehe avamise hetkel oli aeg:
  <?php
    echo $fullTimeNow;
  ?>
  </p>
  <?php
    echo "<p>Lehe avamise hetkel oli " .$partOfDay ."</p>"
   ?>
   <hr>








</body>
</html>
