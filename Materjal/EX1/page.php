<?php
  require("../../../configVP.php");
  require("functions_main.php");  
  require("functions_user.php");
  $database = "if19_mirjam_pe_1";
  
  //SESSION
  require("classes/Session.class.php");
  //sessioon, mis katkeb, kui brauser suletakse ja on kättesaadav ainult meie domeenis, meie lehele
  SessionManager::sessionStart("vp", 0, "/~pettmir/", "greeny.cs.tlu.ee");
  
  $userName = "Anonüümne kasutaja";
  
  $emailError = null;
  $passwordError = null;
  $email = null;
  $notice = null;
  
  $photoDir = "../photos/";
  $picFileTypes = ["image/jpeg", "image/png"];
  $dayNow = date("w");
  $hourNow = date("H");
  $monthNow = date("n");
  //kuu ja nädalapäevade massiivid
  $weekDaysET = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
  $monthsET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  //kuupäev
  $fullTimeNow = "<strong>" .$weekDaysET[$dayNow -1] ." - " .date("d. ") .$monthsET[$monthNow -1] .date(" Y H:i:s") ."</strong>";
  
  //lehe avamise ajal olnud tegevus
  $partOfDay = "hägune aeg";
  if(($hourNow >= 8 && $hourNow < 12) && ($dayNow == 1)){
	$partOfDay = "veebiprogrammeerimise loeng";
  } elseif(($hourNow >= 12 && $hourNow < 16) && ($dayNow == 1)){
	$partOfDay = "interaktsioonidisaini praktikum";
  } elseif(($hourNow >= 8) && ($dayNow == 2)){
	$partOfDay = "programmeerimise aluste loeng";
  } elseif(($hourNow >= 12 && $hourNow < 14) && ($dayNow == 2)){
	$partOfDay = "andmebaaside projekteerimise loeng";
  } elseif(($hourNow >= 14 && $hourNow < 16) && ($dayNow > 1 && $dayNow < 6)){
	$partOfDay = "jaapani keele loeng";
  } else {
	$partOfDay = "loenguvaba aeg";
  }
  
  //info semestri kulgemise kohta
  $semesterStart = new DateTime("2019-9-2");
  $semesterEnd = new DateTime("2019-12-13");
  $semesterDuration = $semesterStart->diff($semesterEnd);
  $today = new DateTime("now");
  $fromSemesterStart = $semesterStart->diff($today);
  //var_dump($fromSemesterStart);
  $semesterInfoHTML = "<p>Siin peaks olema info semestri kulgemise kohta!</p>";
  $elapsedValue = $fromSemesterStart->format("%r%a");
  $durationValue = $semesterDuration->format("%r%a");
  //echo $testValue;
  //<meter min="0" max="155" value="33">Väärtus</meter>
  if($elapsedValue > 0){
	  $semesterInfoHTML = "<p>Semester on täies hoos: ";
	  $semesterInfoHTML .= '<meter min="0" max="' .$durationValue .'" ';
	  $semesterInfoHTML .= 'value="' .$elapsedValue .'">';
	  $semesterInfoHTML .= round( $elapsedValue / $durationValue * 100, 1) ."%";
	  $semesterInfoHTML .= "</meter>";
	  $semesterInfoHTML .= "</p>";
  }
  //foto lisamine lehele
  $allPhotos = [];
  $dirContent = array_slice(scandir($photoDir), 2);
  //var_dump($dirContent);
  foreach($dirContent as $file){
    $fileInfo = getImagesize($photoDir .$file);
	//var_dump($fileInfo);
	if(in_array($fileInfo["mime"], $picFileTypes) == true){
		array_push($allPhotos, $file);
	}
  }
  
  //var_dump($allPhotos);
  $picCount = count($allPhotos);
  $picNum = mt_rand(0, ($picCount - 1));
  //echo $allPhotos[$picNum];
  $photoFile = $photoDir .$allPhotos[$picNum];
  $randomImgHTML = '<img src="' .$photoFile .'" alt="TLÜ Terra õppehoone">';
  $latestPublicPictureHTML = latestPicture(1);
  
    
  if(isset($_POST["submitLogIn"])) {
	  //kui on sisestatud email
	  if(isset($_POST["email"]) and !empty($_POST["email"])) {
		  $email = test_input($_POST["email"]);
	  }else {
		  $emailError = " Palun sisestage email!";
	  } //emaili kontroll lõpp

	  if(isset($_POST["password"]) and !empty($_POST["password"])) {
		  $password = test_input($_POST["password"]);
	  }else {
		  $passwordError = " Palun sisestage salasõna!";
	  }	  
	  
	  if(empty($emailError) and empty($passwordError)) {
		$notice = signIn($email, $_POST["password"]);
	  } else {
		$notice = " Sisse logimine ebaõnnestus!";
	  }
  }
?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Veebiprogrammeerimise harjutused</title>
</head>
<body>
  <?php
    echo "<h1>" .$userName ." PHP #6 07.10.2019</h1>";
  ?>
  <p>Antud leht on loodud koolis õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
  <?php
    echo $semesterInfoHTML;
  ?>
  <hr>
  <p>Lehe avamise hetkel oli aeg: 
  <?php
   echo $fullTimeNow;
  ?>
     .</p>
  <?php 
   echo "<p>Lehe avamise hetkel oli <strong>" .$partOfDay ."</strong>.</p><hr>";
  ?>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>E-mail (kasutajatunnus):</label><br>
	  <input type="email" name="email" value="<?php echo $email; ?>"><span><?php echo $emailError; ?></span><br>
	  <label>Salasõna:</label><br>
	  <input name="password" type="password"><span><?php echo $passwordError; ?></span><br>
	  <input name="submitLogIn" type="submit" value="Logi sisse"><span><?php echo $notice; ?></span>
  </form>
  <h2>Loo kasutaja</h2>
  <p>Loo endale meie lehe <a href="newuser.php">kasutajakonto</a></p>
  <?php
    echo $latestPublicPictureHTML;
    echo $randomImgHTML;
  //lisame lehe jaluse
  require("footer.php");
  ?>