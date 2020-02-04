<?php
  require("../../../configVP.php");
  require("functions_main.php");  
  require("functions_party.php");  
  $database = "if19_mirjam_pe_1";
  
  $notice = null;
  $code = null;
  $codeError = null;
  
  $partyInfoHTML = readAllPartyReg();
  
  if(isset($_POST["submitPaidInfo"])) {
	  if(isset($_POST["code"]) and !empty($_POST["code"])) {
		  $code = strtoupper(test_input($_POST["code"]));
	  }else {
		  $codeError = "Palun sisestage üliõpilaskood!";
	  } //koodi kontroll lõpp
	//kui kõik on korras, salvestame
	if(empty($codeError)) {
	  $notice = markAsPaid($code);
	}//kui kõik korras
  }//kui on nuppu vajutatud				
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>Peo administreerimise leht</title>
  </head>
<body>
	<hr>
	<p><a href="party_reg.php">Registreerimise leht</a> | <a href="party_del.php">Registreerimise tühistamise leht</a></p>
	<hr>
  <h1>Kõik peole registreerunud:</h1>
  <?php
    echo $partyInfoHTML;
  ?>
  <h1>Märgi makstud tasu:</h1>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Üliõpilaskood:</label><br>
	  <input type="text" name="code" style="text-transform: uppercase;" value="<?php echo $code; ?>"><span><?php echo $codeError; ?></span><br><br>
	  <input name="submitPaidInfo" type="submit" value="Kinnita makstud"><span><?php echo $notice; ?></span>
	</form>	
  <?php
  ?>		