<?php
  //väljaspool funktsiooni on globaalsed muutujad
  require("../../../config_vp2019.php");
  require("functions_film.php");
  $userName = "Marten Jürgensi";
  $database = "if19_marten_1";

  //var_dump($_POST);
  //kui on nuppu vajutatud
  if(isset($_POST["submitFilm"])) {
    //salvestame, kui vähemalt pealkiri on olemas
    if(!empty($_POST["filmTitle"])) {
      saveFilmInfo($_POST["filmTitle"], $_POST["filmYear"], $_POST["filmDuration"], $_POST["filmGenre"], $_POST["filmCompany"], $_POST["filmDirector"]);
    }

  }


	//lisame lehe päise
	require("header.php");
?>


<body>
  <?php
    echo "<h1>" .$userName ." koolitöö leht</h1>";
  ?>
  <p>See leht on loodud koolis õppetöö raames
  ja ei sisalda tõsiseltvõetavat sisu!</p>
  <hr>
  <h2>Eesti filmid, lisame uue</h2>
  <p>Täida kõik failid ja lisa film andmebaasi: </p>
  <form method="POST">
    <label>Sisesta pealkiri: </label><input type="text" name="filmTitle">
    <br>
    <label>Filmi tootmisaasta: </label><input type="number" min="1912" max="2019" value="2019" name="filmYear">
    <br>
    <label>Filmi kestus (min): </label><input type="number" min="1" max="300" value="80" name="filmDuration">
    <br>
    <label>Filmi Zanr: </label><input type="text" name="filmGenre">
    <br>
    <label>Filmi tootja: </label><input type="text" name="filmCompany">
    <br>
    <label>Filmi lavastaja: </label><input type="text" name="filmDirector">
    <br>
    <input type="submit" value="Salvesta filmi info" name="submitFilm">
  </form>


<?php
  // echo "Server: " .$serverHost .", kasutaja: " .$serverUsername;
  // echo $filmInfoHTML;
  if(isset($_POST["submitFilm"])) {

    if(empty($_POST["filmTitle"])) {
      echo "<p style='color:red;'>" ."Filmi pealkiri ei tohi olla tühi! </p>";
    }
  }
?>

</body>
</html>
