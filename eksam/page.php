<?php

  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");
  $database = "if19_marten_vp";

  //SESSIOON
  require("classes/Session.class.php");
  //sessioon mis katkeb, kui brauser suletakse ja on kättesaadav ainult meie domeenis, meie lehel
  SessionManager::sessionStart("vp", 0, "/~martejur/", "greeny.cs.tlu.ee");

  $notice = "";
  $photoDir = "../photos/";
  $picFileTypes = ["image/jpeg", "image/png"];
  $weekdayNamesET = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
  $monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  $weekdayNow = date("N");
  $dateNow = date("d");
  $monthNow = date("m");
  $yearNow = date("Y");
  $timeNow = date("H:i:s");
  $fullTimeNow = date("d.m.Y H:i:s");
  $hourNow = date("H");
  $partOfDay = "hägune aeg";
  if($hourNow < 8){
	$partOfDay = "varane hommik";
  }
  if($hourNow >= 8 and $hourNow < 16){
		$partOfDay = "Sobiv aeg akadeemiliseks aktiivsuseks";
	}
	if($hourNow >= 16 and $hourNow < 22){
		$partOfDay = "Vaba aeg.";
	}
	if($hourNow > 22){
		$partOfDay = "Uneaeg.";
	}

  //Login

  $loginPasswordError = "";
  $loginEmailError = "";

	//info semestri kulgemise kohta
	$semesterStart = new DateTime("2019-9-2");
	$semesterEnd = new DateTime("2019-12-13");
	$semesterDuration = $semesterStart->diff($semesterEnd);
	$today = new DateTime("now");
	$fromSemesterStart = $semesterStart->diff($today);
	//var_dump($fromSemesterStart);
	//echo "Päevi: " .$fromSemesterStart->days;
	$semesterInfoHTML = "<p>Siin peaks olema info semestri kulgemise kohta!</p>";
	$elapsedValue = $fromSemesterStart->format("%r%a");
	$durationValue = $semesterDuration->format("%r%a");
	//echo $testValue;
	//<meter min="0" max="155" value="33">Väärtus</meter>
	if($elapsedValue >= 0 and $elapsedValue <= $durationValue){
		$semesterInfoHTML = "<p>Semester on täies hoos: ";
		$semesterInfoHTML .= '<meter min="0" max="' .$durationValue .'" ';
		$semesterInfoHTML .= 'value="' .$elapsedValue .'">';
		$semesterInfoHTML .= round($elapsedValue / $durationValue * 100, 1) ."%";
		$semesterInfoHTML .="</meter>";
		$semesterInfoHTML .="</p>";
	}
	if($elapsedValue < 0){
		$semesterInfoHTML = "<p>Semester pole veel alanud.</p>";
	}
	if($elapsedValue > $durationValue){
		$semesterInfoHTML = "<p>Semester on läbi!</p>";
	}

	//foto lisamine lehele
	$allPhotos = [];
	$dirContent = array_slice(scandir($photoDir), 2);
	//var_dump($dirContent);
	foreach ($dirContent as $file){
		//echo $file;
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

	//lisame lehe päise

	/*setlocale(LC_TIME, "et_EE");
	echo strftime("Eesti keeles: %A,");*/
  if(isset($_POST["submitLogin"])){
 		if (isset($_POST["email"]) and !empty($_POST["email"])){
 		  $email = test_input($_POST["email"]);
 		} else {
 		  $loginEmailError = "Palun sisesta kasutajatunnusena e-posti aadress!";
 		}

 		if (!isset($_POST["password"]) or strlen($_POST["password"]) < 8){
 		  $loginPasswordError = "Palun sisesta parool, vähemalt 8 märki!";
 		}

 		if(empty($loginEmailError) and empty($loginPasswordError)){
 		   $notice = signIn($email, $_POST["password"]);
 		} else {
 			$notice = "Ei saa sisse logida!";
 		}
}

?>

<!DOCTYPE html>
 <html>
 <head>
    <meta charset="utf-8">
   	<title>Marten Jürgensi veebiprogrammeerimise leht</title>
 </head>

<body>
  <p>See leht on loodud koolis õppetöö raames
  ja ei sisalda tõsiseltvõetavat sisu!</p>
  <?php
    echo $semesterInfoHTML;
  ?>

  <hr>
  <p>Lehe avamise hetkel oli aeg:
  <?php
    //echo $fullTimeNow;
	echo $weekdayNamesET[$weekdayNow - 1] .", " .$dateNow .". " .$monthNamesET[$monthNow - 1] ." " .$yearNow ." kell " .$timeNow;
  ?>
  .</p>
  <?php
    echo "<p>Lehe avamise hetkel oli " .$partOfDay .".</p>";
  ?>
  <hr>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>E-mail (kasutajatunnus):</label><br>
	  <input type="email" name="email" value=""><span><?php echo $loginEmailError; ?></span><br>
	  <label>Salasõna:</label><br>
	  <input name="password" type="password"><span><?php echo $loginPasswordError; ?></span><br>
    <input name="submitLogin" type="submit" value="Logi sisse"><span><?php echo $notice; ?></span>
	</form>
	<hr>
  <h2>Loo kasutaja</h2>
  <p>Loo endale meie lehe <a href="newuser.php">kasutajakonto</a></p>
  <hr>
  <?php
  echo $latestPublicPictureHTML;
    echo $randomImgHTML;
  ?>
</body>
</html>
