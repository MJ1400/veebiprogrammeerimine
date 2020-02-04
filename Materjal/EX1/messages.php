<?php
  require("../../../configVP.php");
  require("functions_main.php");  
  require("functions_user.php");
  require("functions_message.php");
  $database = "if19_mirjam_pe_1";
  
    //SESSION
  require("classes/Session.class.php");
  //sessioon, mis katkeb, kui brauser suletakse ja on kättesaadav ainult meie domeenis, meie lehele
  SessionManager::sessionStart("vp", 0, "/~pettmir/", "greeny.cs.tlu.ee");
  
  $notice = null;
  $messagesHTML = null;
 
 
  if(isset($_POST["submitMessage"])) {
	if(isset($_POST["message"]) and !empty($_POST["message"])) {
		$notice = storeMessage(test_input($_POST["message"]));
	}
  }
  
  //$messagesHTML = readAllMessages();
  $messagesHTML = readMyMessages();
  
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
  
  //lisame lehe päise
  require("header.php");
 
?>


<body>
  <?php
    echo "<h1>" .$userName ." PHP #7 14.10.2019</h1>";
  ?>
  <p>Antud leht on loodud koolis õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
  <hr>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Minu sõnum</label><br>
	  <textarea rows="10" cols="80" name="message" placeholder="Lisa siia oma sõnum..."></textarea><span></span>
	  <br>
	  <input name="submitMessage" type="submit" value="Salvesta sõnum"><span><?php echo $notice; ?></span>
	</form>
  <hr>
  <h2>Senised sõnumid:</h2>
  <?php
	echo $messagesHTML;
  ?>
  <hr>
  <p><a href="?Logout=1">Logi välja!</a> - Tagasi <a href="home.php">avalehele</a>.</p>
  <?php
  //lisame lehe jaluse
  require("footer.php");
  ?>