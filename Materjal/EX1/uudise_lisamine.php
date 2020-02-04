<?php
  require("../../../configVP.php");
  require("functions_main.php");  
  require("functions_news.php");
  $database = "if19_mirjam_pe_1";
  
  $notice = null;
  
  //SESSION
  require("classes/Session.class.php");
  //sessioon, mis katkeb, kui brauser suletakse ja on kättesaadav ainult meie domeenis, meie lehele
  SessionManager::sessionStart("vp", 0, "/~pettmir/", "greeny.cs.tlu.ee");
  
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
  
  //cookies (peab olema enne HTML elemente
  //nimi[väärtus, aegumine, path ehk kataloog, domeen, HTTPS, http only - kindlasti üle veebi]
  setcookie("vpname", $_SESSION["userFirstname"] .$_SESSION["userLastname"], time() + (86400 * 31), "/~pettmir/", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]), true);
  
  if(isset($_COOKIE["vpname"])){
	echo "Küpsisest selgus nimi: " .$_COOKIE["vpname"];
  }else{
	echo "Küpsiseid ei leitud!";  
  }
  
  $userName = $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"];
  $userid = $_SESSION["userID"];
  
	$error = "";
	$newsTitle = "";
	$news = "";
	$expiredate = date("Y-m-d");

  
  if(isset($_POST["newsBtn"]) and (empty($_POST["newsTitle"]) or empty($_POST["newsEditor"]) or empty($_POST["expiredate"])) ){
		$ok = false;
		$error = "Palun täitke kõik väljad!";
		if (isset($_POST["newsTitle"])) {
			$newsTitle = htmlentities($_POST["newsTitle"]);
		}
		if (isset($_POST["newsEditor"])) {
			$news = htmlentities($_POST["newsEditor"]);
		}
		if (isset($_POST["expiredate"])) {
			$expiredate = htmlentities($_POST["expiredate"]);
		}
	}else {
		$ok = true;
		//kui on nuppu vajutatud
		if(isset($_POST["newsBtn"])){
			if(!empty($_POST["newsTitle"]) and !empty($_POST["newsEditor"]) and !empty($_POST["expiredate"]) ) {
				$notice = saveNews($userid, test_input($_POST["newsTitle"]), test_input($_POST["newsEditor"]), test_input($_POST["expiredate"]));
			}
		unset($_POST);
	}
  }
  
  	
  //lisame lehe päise
  require("header.php");
 
?>

<!--Javascript osa: -->
<!-- Lisame tekstiredaktory TinyMCE -->
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

<script>
tinymce.init({
		selector:"textarea#newsEditor",
		plugins: "link",
		menubar: "edit",
});
</script>

<h2>Lisa uudis</h2>
	<form accept-charset="UTF-8" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label>Uudise pealkiri:</label><br><input type="text" name="newsTitle" id="newsTitle" style="width: 100%;" value="<?php echo $newsTitle; ?>"><br>
		<label>Uudise sisu:</label><br>
		<textarea name="newsEditor" id="newsEditor"><?php echo $news; ?></textarea>
		<br>
		<label>Uudis nähtav kuni (kaasaarvatud)</label>
		<input type="date" name="expiredate" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php echo $expiredate; ?>">
		
		<input name="newsBtn" id="newsBtn" type="submit" value="Salvesta uudis!"
		<?php if ($notice == "Uudis salvestatud!"){echo "disabled";} ?>> <span>&nbsp;</span><span><?php echo $error; ?></span><span>&nbsp;</span><span><?php echo $notice; ?></span>
	</form>