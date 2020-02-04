<?php
require("../../../config_vp2019.php");
require("functions_main.php");
require("functions_user.php");
$database = "if19_marten_vp";

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

  







 ?>
