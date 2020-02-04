<?php
  require("../../../configVP.php");
  require("functions_main.php");  
  require("functions_user.php");
  $database = "if19_mirjam_pe_1";
  
    //SESSION
  require("classes/Session.class.php");
  //sessioon, mis katkeb, kui brauser suletakse ja on kättesaadav ainult meie domeenis, meie lehele
  SessionManager::sessionStart("vp", 0, "/~pettmir/", "greeny.cs.tlu.ee");

  $mybgcolor = "#FFFFFF";
  $mytxtcolor = "#000000";
  $mydescription = null;
  $oldPasswordError = null;
  $newPasswordError = null;
  $newPasswordError2 = null;
  $notice = null;
  
  //kui pole sisseloginud
  if(!isset($_SESSION["userID"])) {
	  //siis jõuga sisselogimise lehele
	  header("Location: page.php");
	  exit();
  }
  
  //väljalogimie
  if(isset($_GET["Logout"])){
	  session_destroy();
	  header("Location: page.php");
	  exit();
  }
  
  $userName = $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"];
  $userid = $_SESSION["userID"];

	
  if(isset($_POST["submitProfile"])) {
	  if(!empty($_POST["description"])) {
		  $mydescription = test_input($_POST["description"]);
	  }else {
		$myProfileDesc = showMyDesc();
			if(!empty($myProfileDesc)){
				$mydescription = $myProfileDesc;
			}
	  }
	  if(!empty($_POST["bgcolor"])) {
		  $mybgcolor = $_POST["bgcolor"];
	  } 
	  if(!empty($_POST["txtcolor"])) {
		  $mytxtcolor = $_POST["txtcolor"];
	  } 
	  if(!empty($mydescription) and !empty($mybgcolor) and !empty($mytxtcolor)) {
		  $notice = createProfile($userid, $mydescription, $mybgcolor, $mytxtcolor);
	  }
  }

    if(isset($_POST["changePassword"])) {
		if(isset($_POST["oldPassword"]) and !empty($_POST["oldPassword"])) {
			if(($_POST["oldPassword"]) == ($_POST["newPassword"])) {
				$newPasswordError = " Uus salasõna ei saa olla sama, mis vana salasõna!";
			}else{
		    	$oldPassword = test_input($_POST["oldPassword"]);
			}
		}else {
		  $oldPasswordError = " Palun sisestage praegune salasõna!";
		}
		if(isset($_POST["newPassword"]) and !empty($_POST["newPassword"])) {
			if(strlen($_POST["newPassword"]) < 8){
				$newPasswordError = " Salasõna peab olema vähemalt 8 tähemärki!";
			}else {
				$newPassword = test_input($_POST["newPassword"]);
			}
		}else {
		  $newPasswordError = " Palun sisestage uus salasõna!";
		}	
		if(isset($_POST["newPassword2"]) and !empty($_POST["newPassword2"])) {
			if(strlen($_POST["newPassword2"]) < 8){
				$newPasswordError2 = " Salasõna peab olema vähemalt 8 tähemärki!";
			}else {
				$newPassword2 = test_input($_POST["newPassword2"]);
			}
		}else {
		  $newPasswordError2 = " Palun sisestage uus salasõna uuesti!";
		}		    
		if (empty ($newPasswordError2) and (($_POST["newPassword"]) != ($_POST["newPassword2"]))) {
			$newPasswordError2 = " Sisestatud uued salasõnad ei ühti!";
		}else {
			if(empty($oldPasswordError) and empty($newPasswordError) and empty($newPasswordError2)) {
				$notice = changePassword($_SESSION["userID"], $_POST["oldPassword"], $_POST["newPassword"]);
			}else {
				$notice = " Salasõna vahetamine ebaõnnestus!";
			}
		}
	}
  
  //lisame lehe päise
  require("header.php");
 
?>


<body>
  <?php
    echo "<h1>" .$userName ." PHP #6 07.10.2019</h1>";
  ?>
  <p>Antud leht on loodud koolis õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
  <hr>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Minu kirjeldus</label><br>
	  <textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea>
	  <br>
	  <label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
	  <label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $mytxtcolor; ?>"><br>
	  <input name="submitProfile" type="submit" value="Salvesta profiil"><span><?php echo $notice; ?></span>
	</form>
  <hr>
  <p>Muuda salasõna:</p>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Vana salasõna: </label><input name="oldPassword" type="password"><?php echo $oldPasswordError; ?><br>
	  <label>Uus salasõna: </label><input name="newPassword" type="password"><?php echo $newPasswordError; ?><br>
	  <label>Uus salasõna uuesti: </label><input name="newPassword2" type="password"><?php echo $newPasswordError2; ?><br>
	  <input name="changePassword" type="submit" value="Muuda salasõna"><span><?php echo $notice; ?></span>
	</form>
  <hr>
  <p><a href="?Logout=1">Logi välja!</a></p>
  <?php
  //lisame lehe jaluse
  require("footer.php");
  ?>