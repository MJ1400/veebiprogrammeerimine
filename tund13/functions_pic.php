<?php
  function addPicData($fileName, $altText, $privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpphotos (userid, filename, alttext, privacy) VALUES (?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issi", $_SESSION["userID"], $fileName, $altText, $privacy);
		if($stmt->execute()){
			$notice = " Pildi andmed salvestati andmebaasi!";
		} else {
			$notice = " Pildi andmete salvestamine ebaönnestus tehnilistel põhjustel! " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

  function readAllPublicPics($privacyValue) {
    $picHTML = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy=? and deleted IS NULL");
    echo $conn->error;
    $stmt->bind_param("i", $privacyValue);
    $stmt->bind_result($fileNameFromDb, $altTextFromDb);
    $stmt->execute();
    while($stmt->fetch()) {
      //img src="thumbs_kataloog/pilt" alt=""> \n
      $picHTML .= '<img src="' .$GLOBALS["$pic_upload_dir_thumb"] .$fileNameFromDb .'" alt="' .$altTextFromDb .'">' ."\n";

    }
    if($picHTML == null) {
      $picHTML = "<p>Kahjuks avalikke pilte pole!</p>";
    }

    $stmt->close();
		$conn->close();
    return $picHTML;


  }

  function readAllPublicPicsPage($privacyValue, $page, $limit) {
    $picHTML = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT vpphotos.id, vpusers3.firstname, vpusers3.lastname, vpphotos.filename, vpphotos.alttext, AVG(vpphotoratings.rating) as AvgValue FROM vpphotos JOIN vpusers3 ON vpphotos.userid = vpusers3.id LEFT JOIN vpphotoratings ON vpphotoratings.photoid = vpphotos.id WHERE vpphotos.privacy = ? AND deleted IS NULL GROUP BY vpphotos.id DESC LIMIT ?, ?");
    echo $conn->error;
    $skip = ($page - 1) * $limit;
    $stmt->bind_param("iii", $privacyValue, $skip, $limit);
    $stmt->bind_result($picIdFromDb, $firstNameFromDb, $lastNameFromDb, $fileNameFromDb, $altTextFromDb, $avgRatingFromDb);
    $stmt->execute();
    while($stmt->fetch()) {
      //img src="thumbs_kataloog/pilt" alt=""> \n
      //img src="thumbs_kataloog/pilt" alt="" data-fn="failinimi"> \n
      $picHTML .= '<div class="thumbGallery">';
      $picHTML .= '<img src="' .$GLOBALS["pic_upload_dir_thumb"] .$fileNameFromDb .'" class="thumbs" data-fn="' .$fileNameFromDb .'" data-id="' .$picIdFromDb .'">' ."\n";
      $picHTML .= '<p>' .$firstNameFromDb ." " .$lastNameFromDb .'</p>';
      $picHTML .='<p id="score' .$picIdFromDb .'">';
      if($avgRatingFromDb == null) {
        $picHTML .= 'Pole hinnatud!</p></div>';
      } else {
        $picHTML .= 'Hinne: ' .$avgRatingFromDb .'</p></div>';
      }
    }
    if($picHTML == null) {
      $picHTML = "<p>Kahjuks avalikke pilte pole!</p>";
    }


    $stmt->close();
		$conn->close();
    return $picHTML;


  }

  function countPublicImages($privacyValue) {
    $notice = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT COUNT(*) FROM vpphotos WHERE privacy = ? AND deleted IS NULL");
    echo $conn->error;
    $stmt->bind_param("i", $privacyValue);
    $stmt->bind_result($imageCountFromDb);
    $stmt->execute();
    if($stmt->fetch()) {
      $notice = $imageCountFromDb;
    } else {
      $notice = 0;
    }

    $stmt->close();
		$conn->close();
    return $notice;
  }

  function saveImgRating() {
    $notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpphotoratings (photoid, userid, rating) VALUES (?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("iii", $_SESSION["userID"], $rating);
		if($stmt->execute()){
			$notice = " Pildi andmed salvestati andmebaasi!";
		} else {
			$notice = " Pildi andmete salvestamine ebaönnestus tehnilistel põhjustel! " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;

  }


?>
