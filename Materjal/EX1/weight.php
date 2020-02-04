<?php
  require("../../../configVP.php");
  require("functions_main.php");  
  require("functions_user.php");
  $database = "if19_mirjam_pe_1";
  
  //SESSION
  require("classes/Session.class.php");
  //sessioon, mis katkeb, kui brauser suletakse ja on kättesaadav ainult meie domeenis, meie lehele
  SessionManager::sessionStart("vp", 0, "/~pettmir/", "greeny.cs.tlu.ee");
  
  $notice = null;
  //$weightHTML = null;
  $weightHTML = currentWeight();
  
    if(isset($_POST["submitWeight"])) {
	if(isset($_POST["weight"]) and !empty($_POST["weight"])) {
		$notice = addWeight(test_input($_POST["weight"]));
	}
  }
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
  
  <p>Sisesta oma tänane kehakaal!</p>
  <hr>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Minu kehakaal täna:</label><br>
	  <input type="number" name="weight" value="<?php $weightHTML; ?>" min="2" max="500" step=".1"></input><span> kilogrammi</span>
	  <br>
	  <input name="submitWeight" type="submit" value="Salvesta kehakaal"><span><?php echo $notice; ?></span>
	</form>
	<p>Senine keskmine kaal:</p>
	<?php
	echo $weightHTML;
	?>
  <?php
  require("footer.php");
  ?>