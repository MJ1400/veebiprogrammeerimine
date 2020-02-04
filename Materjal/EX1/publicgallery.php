<?php
  require("../../../configVP.php");
  require("functions_main.php");  
  //require("functions_user.php");
  require("functions_pic.php");
  
  $database = "if19_mirjam_pe_1";
  
    //SESSION
  require("classes/Session.class.php");
  //sessioon, mis katkeb, kui brauser suletakse ja on kättesaadav ainult meie domeenis, meie lehele
  SessionManager::sessionStart("vp", 0, "/~pettmir/", "greeny.cs.tlu.ee");
  
  //kui pole sisseloginud
  if(!isset($_SESSION["userID"])){
	  header("Location: page.php");
	  exit();
  }
  
  //väljalogimine
  if(isset($_GET["logout"])){
	  session_destroy();
	  header("Location: page.php");
	  exit();
  }
  
  $userName = $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"];
  
  $notice = null;
  
  if(isset($_GET["privacy"])) {
    $privacy = $_GET["privacy"];
    }else {
		$privacy = 1;
    }
  
  //piirid galerii lehel näidatava piltide arvu jaoks
  $page = 1;
  $limit = 3;
  $totalPics = countPublicImages($privacy);
  if(!isset($_GET["page"]) or $_GET["page"] < 1){
	  $page = 1;
  }elseif(round($_GET["page"] - 1) * $limit >= $totalPics){
	  $page = round($totalPics / $limit) - 1;
  }else{
	  $page = $_GET["page"];
  }
  $publicThumbsHTML = readAllPublicPicsPage(1, $page, $limit);
  $usersThumbsHTML = readAllPublicPicsPage(2, $page, $limit);
  $privateThumbsHTML = readAllPublicPicsPage(3, $page, $limit);
  
  //<link rel="stylesheet" type="text/css" href="style/modal.css">
  $toScript = '<link rel="stylesheet" type="text/css" href="style/modal.css">' ."\n";
  $toScript .= '<script type="text/javascript" src="javascript/modal.js" defer></script>' ."\n";
  require("header.php");
 
?>


<body>
  <?php
    echo "<h1>" .$userName ." PHP #10 11.11.2019</h1>";
  ?>
  <p>Antud leht on loodud koolis õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
  <hr>
  <form method="GET">
    <input type="radio" name="privacy" value="1" <?php if(isset($_GET["privacy"]) && $_GET["privacy"] == 1) { echo 'checked = "checked"';}else{echo 'checked = "checked"';}?>><label>Avalike piltide galerii</label><br/>
    <input type="radio" name="privacy" value="2" <?php if(isset($_GET["privacy"]) && $_GET["privacy"] == 2) { echo 'checked = "checked"';}?>><label>Kasutajate pildid</label><br/>
    <input type="radio" name="privacy" value="3" <?php if(isset($_GET["privacy"]) && $_GET["privacy"] == 3) { echo 'checked = "checked"';}?>><label>Privaatsed pildid</label><br/>
    <br>
    <input type="submit" name="submitPrivacy" value="Näita pilte">
    </form>
  <?php
  if(isset($_GET["privacy"]) && $_GET["privacy"] == 1) {
	  echo "<h2>Avalike piltide galerii</h2>";
  }elseif(isset($_GET["privacy"]) && $_GET["privacy"] == 2) {
	  echo "<h2>Kasutajate piltide galerii</h2>";
  }else{
	  echo "<h2>Privaatsete piltide galerii</h2>"; 
  }
 ?>
  <!--Teeme modaalakna, W3schools eeskuju-->
  <div id="myModal" class="modal">
	<!--Sulgemisnupp-->
	<span id="close" class="close">&times;</span>
	<!--Pildikoht-->
	<img id="modalImg" class="modal-content">
	<div id="caption"></div>
	<div id="rating" class="modalcaption">
		<label><input id="rate1" name="rating" type="radio" value="1">1</label>
		<label><input id="rate2" name="rating" type="radio" value="2">2</label>
		<label><input id="rate3" name="rating" type="radio" value="3">3</label>
		<label><input id="rate4" name="rating" type="radio" value="4">4</label>
		<label><input id="rate5" name="rating" type="radio" value="5">5</label>
		<input type="button" value="Salvesta hinnang" id="storeRating">
	</div>
  </div>
  <p>
  
  <!--<a href="?page=1">Leht 1</a> - <a href="?page=2">Leht 2</a>-->
  
  <?php
	if($page > 1){
		echo '<a href="?page=' .($page - 1) .'&privacy=' .$privacy .'">Eelmine leht</a> - ' ."\n";
	}else{
		echo "<span>Eelmine leht</span> - \n";
	}
	if($page * $limit < $totalPics) {
		echo '<a href="?page=' .($page + 1) .'&privacy=' .$privacy .'">Järgmine leht</a>' ."\n";
	}else{
		echo "<span>Järgmine leht</span> \n";
	}
  ?>
  
  </p>
  <div id="gallery">
<?php
      if(isset($_GET["privacy"]) && $_GET["privacy"] == 1){
      echo $publicThumbsHTML;
    } elseif(isset($_GET["privacy"]) && $_GET["privacy"] == 2) {
      echo $usersThumbsHTML;
    } elseif(isset($_GET["privacy"]) && $_GET["privacy"] == 3) {
      echo $privateThumbsHTML;
    }
     ?>
  </div>
  <hr>
  <p><a href="?Logout=1">Logi välja!</a> - Tagasi <a href="home.php">avalehele</a>.</p>
  <?php
  //lisame lehe jaluse
  require("footer.php");
  ?>