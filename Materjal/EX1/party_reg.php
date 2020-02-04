<?php
  require("../../../configVP.php");
  require("functions_main.php"); 
  require("functions_party.php");
  $database = "if19_mirjam_pe_1";
   
  $notice = null;
  $name = null;
  $surname = null;
  $code = null;
  
  //muutujad võimalike veateadetega
  $nameError = null;
  $surnameError = null;
  $codeError = null;
  
  $regInfoHTML = readSomePartyInfo();
  
  //kui on uue kasutaja loomise nuppu vajutatud
  if(isset($_POST["submitPartyData"])) {
	  //kui on sisestatud nimi
	  if(isset($_POST["firstName"]) and !empty($_POST["firstName"])) {
		  $name = test_input($_POST["firstName"]);
	  }else {
		  $nameError = "Palun sisestage eesnimi!";
	  } //eesnime kontroll lõpp

	  if(isset($_POST["surName"]) and !empty($_POST["surName"])) {
		  $surname = test_input($_POST["surName"]);
	  }else {
		  $surnameError = "Palun sisestage perekonnanimi!";
	  } //perekonnanime kontroll lõpp
	  
	  if(isset($_POST["code"]) and !empty($_POST["code"])) {
		  $code = strtoupper(test_input($_POST["code"]));
	  }else {
		  $codeError = "Palun sisestage üliõpilaskood!";
	  } //koodi kontroll lõpp
	//kui kõik on korras, salvestame
	if(empty($nameError) and empty($surnameError) and empty($codeError)) {
	  $notice = signUpParty($name, $surname, $code);
	}//kui kõik korras
  }//kui on nuppu vajutatud
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>Peole registreerumine</title>
  </head>
  <body>
    <h1>Registreeri ennast peole<img src="partyhat.png" width="50" /></h1>
	<hr>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Eesnimi:</label><br>
	  <input name="firstName" type="text" value="<?php echo $name; ?>"><span><?php echo $nameError; ?></span><br>
      <label>Perekonnanimi:</label><br>
	  <input name="surName" type="text" value="<?php echo $surname; ?>"><span><?php echo $surnameError; ?></span><br>  
	  <label>Üliõpilaskood:</label><br>
	  <input type="text" name="code" style="text-transform: uppercase;" value="<?php echo $code; ?>"><span><?php echo $codeError; ?></span><br><br>
	  <input name="submitPartyData" type="submit" value="Registreeri peole"><span><?php echo $notice; ?></span>
	</form>
	<hr>
	<p>Kui soovid oma registreerumist tühistada, klõpsa sellele lingile: <a href="party_del.php">SIIN</a></p>
	<hr>
	<h1>Hetkel registreerunud:</h1>
	<?php
	echo $regInfoHTML;
	?>
  </body>
</html>
<?php
?>