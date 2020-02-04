<?php
  require("../../../configVP.php");
  require("functions_main.php"); 
  require("functions_party.php");
  $database = "if19_mirjam_pe_1";
   
  $notice = null;
  $code = null;
  
  //muutujad võimalike veateadetega
  $codeError = null;
  
  if(isset($_POST["deletePartyInfo"])) {
	  if(isset($_POST["code"]) and !empty($_POST["code"])) {
		  $code = strtoupper(test_input($_POST["code"]));
	  }else {
		  $codeError = "Palun sisestage üliõpilaskood!";
	  } //koodi kontroll lõpp
	//kui kõik on korras, salvestame
	if(empty($codeError)) {
	  $notice = deleteParty($code);
	}//kui kõik korras
  }//kui on nuppu vajutatud
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>Peo registreerumise tühistamine</title>
  </head>
  <body>
    <h1>Tühista oma registratsioon</h1>
	<hr>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Üliõpilaskood:</label><br>
	  <input type="text" name="code" style="text-transform: uppercase;" value="<?php echo $code; ?>"><span><?php echo $codeError; ?></span><br><br>
	  <input name="deletePartyInfo" type="submit" value="Tühista peole registreerumine"><span><?php echo $notice; ?></span>
	</form>
	<hr>
	<p>Tagasi peole registreerumise lehele saab: <a href="party_reg.php">SIIT</a></p>
	<hr>
  </body>
</html>
<?php
?>