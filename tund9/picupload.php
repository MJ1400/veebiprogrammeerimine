<?php

  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");
  require("functions_pic.php");
  //võtan kasutusele oma klassi
  //require("classes/Test.classes.php");
  require("classes/Picupload.class.php");
  $database = "if19_marten_vp";
  //kui pole sisseloginud
  if(!isset($_SESSION["userID"])){
  	  //siis jõuga sisselogimise lehele
  	  header("Location: page.php");
  	  exit();
    }

    //väljalogimine
    if(isset($_GET["logout"])){
  	  session_destroy();
  	  header("Location: page.php");
  	  exit();
    }

    //kasutan oma testklassi
    //$myTest = new Test(1337);
    // echo "Salajane: " .$myTest->secretNumber;
    //echo "Avalik: " .$myTest->publicNumber;
    //$myTest->showValues();
    //$myTest->tellSecret;
    //unset($myTest);


    $userName = $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"];

    $notice = null;
    $fileSizeLimit = 2500000;
    $maxPicW = 600;
    $maxPicH = 400;
    $fileNamePrefix = "vp_";
    $waterMarkFile = "../vp_pics/vp_logo_w100_overlay.png";
    $waterMarkLocation = mt_rand(1,4); //1- ülal vasakul, 2 - ülal paremal, 3 - all paremal, 4 - all vasakul, 5 - keskel
    $waterMarkFromEdge = 10;
    $thumbW = 100;
    $thumbH = 100;
  //pic upload algab
	//$target_dir = "uploads/";

  if(isset($_POST["submitPic"])){
// Check if file already exists
		/*if (file_exists($target_file)) {
			echo "Sorry, file already exists.";
			$uploadOk = 0;
		}*/

		//kasutame klassi (saadame kogu info üleslaetava faili kohta ja faili mahu piiri
		$myPic = new Picupload($_FILES["fileToUpload"], $fileSizeLimit);
		if($myPic->error == null){
			//loome failinime
			$myPic->createFileName($fileNamePrefix);
			//teeme pildi väiksemaks
			$myPic->resizeImage($maxPicW, $maxPicH);
			//lisame vesimärgi
			$myPic->addWatermark($waterMarkFile, $waterMarkLocation, $waterMarkFromEdge);
			//kirjutame vähendatud pildi faili
			$notice .= $myPic->savePicFile($pic_upload_dir_w600 .$myPic->fileName);
			//thumbnail
			$myPic->resizeImage($thumbW, $thumbH);
			$myPic->savePicFile($pic_upload_dir_thumb .$myPic->fileName);
			//salvestan originaali
			$notice .= " " .$myPic->saveOriginal($pic_upload_dir_orig .$myPic->fileName);

			//salvestan info andmebaasi
			$notice .= addPicData($myPic->fileName, test_input($_POST["altText"]), $_POST["privacy"]);
		} else {
			//1 - pole pildifail, 2 - liiga suur, pole lubatud tüüp
			if($myPic->error == 1){
				$notice = "Üleslaadimiseks valitud fail pole pilt!";
			}
			if($myPic->error == 2){
				$notice = "Üleslaadimiseks valitud fail on liiga suure failimahuga!";
			}
			if($myPic->error == 3){
				$notice = "Üleslaadimiseks valitud fail pole lubatud tüüpi (lubatakse vaid jpg, png ja gif)!";
			}
		}
		unset($myPic);
	}//kas nuppu klikiti

  //pic upload lõppeb

  require("header.php");
?>

  <body>
    <?php
      echo "<h1>" .$userName ."i" ." koolitöö leht</h1>";
    ?>
    <p>See leht on loodud koolis õppetöö raames
    ja ei sisalda tõsiseltvõetavat sisu!</p>
    <hr>
    <p><a href="?logout=1">Logi välja!</a> | <a href="userprofile.php">Kasutajaprofiil</a> | <a href="messages.php">Sõnumid</a> | <a href="changepassword.php">Muuda salasõna</a> </p>
    <p>Tagasi <a href="home.php">avalehele</a></p>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
	  <label>Vali üleslaetav pilt!</label>
	  <input type="file" name="fileToUpload" id="fileToUpload">
	  <br>
	  <label>Alt tekst: </label><input type="text" name="altText">
	  <br>
	  <label>Privaatsus</label>
	  <br>
	  <input type="radio" name="privacy" value="1"><label>Avalik</label>&nbsp;
	  <input type="radio" name="privacy" value="2"><label>Sisseloginud kasutajatele</label>&nbsp;
	  <input type="radio" name="privacy" value="3" checked><label>Isiklik</label>
      <br>
	  <input name="submitPic" type="submit" value="Lae pilt üles!"><span><?php echo $notice; ?></span>
	</form>
	<hr>
  </body>
  </html>
