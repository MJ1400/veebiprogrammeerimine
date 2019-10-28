<?php
  function setPicSize($myTempImage, $imageW, $imageH, $newW, $newH) {
    $newImage = imagecreatetruecolor($newW, $newH);
    imagecopyresampled($newImage, $myTempImage, 0, 0, 0, 0, $newW, $newH, $imageW, $imageH);
    return $newImage;
  }

?>
