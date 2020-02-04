<?php

  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_news.php");
  $database = "if19_marten_vp";

  //SESSIOON
  require("classes/Session.class.php");
  //sessioon mis katkeb, kui brauser suletakse ja on kättesaadav ainult meie domeenis, meie lehel
  SessionManager::sessionStart("vp", 0, "/~martejur/", "greeny.cs.tlu.ee");
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

    $userName = $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"];
    $newsTitle = "";
    $news = "";
    $notice = null;
    $error = "";
    $expiredate = date("Y-m-d");

    if(isset($_POST["newsBtn"])){
      if(isset($_POST["newsTitle"]) and !empty($_POST["newsTitle"]) and isset($_POST["newsEditor"]) and !empty($_POST["newsEditor"])) {
        $notice = addNews(test_html_input($_POST["newsTitle"]), test_html_input($_POST["newsEditor"]), $_POST["expiredate"]);
      }

    }


    require("header.php");
  ?>

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

<script>
tinymce.init({
		selector:'textarea#newsEditor',
		plugins: "link",
		menubar: 'edit',
});
</script>


  <body>
    <?php
      echo "<h1>" .$userName ."i" ." koolitöö leht</h1>";
    ?>
    <p>See leht on loodud koolis õppetöö raames
    ja ei sisalda tõsiseltvõetavat sisu!</p>
    <hr>
  <p><a href="?logout=1">Logi välja!</a></p>
    <p>Tagasi <a href="home.php">avalehele</a></p>

    <h2>Lisa uudis</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label>Uudise pealkiri:</label><br><input type="text" name="newsTitle" id="newsTitle" style="width: 100%;" value="<?php echo $newsTitle; ?>"><br>
		<label>Uudise sisu:</label><br>
		<textarea name="newsEditor" id="newsEditor"><?php echo $news; ?></textarea>
		<br>
		<label>Uudis nähtav kuni (kaasaarvatud)</label>
		<input type="date" name="expiredate" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php echo $expiredate; ?>">

		<input name="newsBtn" id="newsBtn" type="submit" value="Salvesta uudis!"
		<?php if ($notice == "Uudis salvestatud!"){echo "disabled";} ?>> <span>&nbsp;</span><span><?php echo $error; ?></span>

  </body>
  </html>
