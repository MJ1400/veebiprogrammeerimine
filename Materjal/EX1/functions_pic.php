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
			$notice = " Pildi andmete salvestamine ebaõnnestus tehnilistel põhjustel! " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function readAllPublicPics($privacy) {
		$picHTML = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy = ? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($fileNameFromDB, $altTextFromDB);
		$stmt->execute();
		while($stmt->fetch()){
			//<img src="thumbs_kataloog/pilt" alt=""> \n
			$picHTML .= '<img src="' .$GLOBALS["pic_upload_dir_thumb"] .$fileNameFromDB .'" alt="' .$altTextFromDB .'">' ."\n";
		}
		if($picHTML == null) {
			$picHTML = "<p>Kahjuks avalikke pilte pole!</p>";
		}
		
		$stmt->close();
		$conn->close();
		return $picHTML;
	}
	
	function readAllPublicPicsPage($privacy, $page, $limit) {
		$picHTML = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT vpphotos.id, vpusers3.firstname, vpusers3.lastname, vpphotos.filename, vpphotos.alttext, AVG(vpphotoratings.rating) as AvgValue FROM vpphotos JOIN vpusers3 ON vpphotos.userid = vpusers3.id LEFT JOIN vpphotoratings ON vpphotoratings.photoid = vpphotos.id WHERE vpphotos.privacy = ? AND deleted IS NULL GROUP BY vpphotos.id DESC LIMIT ?, ?");
		echo $conn->error;
		$skip = ($page - 1) * $limit;
		$stmt->bind_param("iii", $privacy, $skip, $limit);
		$stmt->bind_result($photoIDfromDB, $firstnameFromDB, $lastnamefromDB, $fileNameFromDB, $altTextFromDB, $AvgValuefromDB);
		$stmt->execute();
		while($stmt->fetch()){
			//<img src="thumbs_kataloog/pilt" alt=""> \n
			//<img src="thumbs_kataloog/pilt" alt="" data-fn="failinimi" > \n
			$picHTML .= '<div class="thumbGallery">';
			$picHTML .= '<img src="' .$GLOBALS["pic_upload_dir_thumb"] .$fileNameFromDB .'" alt="' .$altTextFromDB .'" class="thumbs" data-fn="' .$fileNameFromDB .'" data-id="' .$photoIDfromDB .'">' ."\n";
			$picHTML .='<p>' .$firstnameFromDB ." " .$lastnamefromDB .'</p>';
			$picHTML .='<p id="' .$photoIDfromDB .'">';
			if ($AvgValuefromDB == null) {
				$picHTML .= 'Pole hinnatud!</p></div>';
			}else{
				$picHTML .= 'Hinne:' .$AvgValuefromDB .'</p></div>';
			}
		}
		if($picHTML == null) {
			$picHTML = "<p>Kahjuks avalikke pilte pole!</p>";
		}
		
		$stmt->close();
		$conn->close();
		return $picHTML;
	}
	
	function countPublicImages($privacy) {
		$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(id) FROM vpphotos WHERE privacy = ? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($imageCountFromDB);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = $imageCountFromDB;
		}else{
			$notice = 0;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	