<?php
$headerContent = '<!DOCTYPE html><html lang="et"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>' .$userName .' veebiprogrammeerimise harjutused</title>';
$headerContent .= '<style>body{background-color: ' .$_SESSION["mybgcolor"] .'; color: ' .$_SESSION["mytxtcolor"] .'} </style>';
$headerContent .= $toScript;
$headerContent .= '</head>';
  echo $headerContent;
?>